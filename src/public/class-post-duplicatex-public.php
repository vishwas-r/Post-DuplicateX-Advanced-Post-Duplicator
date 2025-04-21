<?php

class Post_DuplicateX_Public {
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function enqueue_styles() {
        // Add frontend styles if needed
    }

    public function enqueue_scripts() {
        // Add frontend scripts if needed
    }

    public function add_duplicate_admin_bar_link($wp_admin_bar) {
        // Check if user has permission
        if (!$this->user_can_duplicate()) {
            return;
        }
        
        global $post;

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce verified in duplicate_post_action(); $_GET['post'] only used to generate nonce-protected URL.
        if (!$post) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce verified in duplicate_post_action(); $_GET['post'] sanitized and used for URL generation only.
            $post_id = isset($_GET['post']) ? intval($_GET['post']) : 0;
            if ($post_id > 0) {
                $post = get_post($post_id);
            }
        }
        
        if (!$post) {
            return;
        }
        
        $post_id = $post->ID;
        $post_type = $post->post_type;
        $post_types = get_option('pdx_post_types', array('post' => 'post', 'page' => 'page'));
        if (!isset($post_types[$post_type])) {
            return;
        }
        
        $link_title = get_option('pdx_link_title', 'Duplicate');
        $duplicate_url = $this->get_duplicate_url($post_id);
        
        $wp_admin_bar->add_menu(array(
            'id' => 'duplicate-post',
            'title' => $link_title,
            'href' => $duplicate_url,
        ));
    }

    public function user_can_duplicate() {
        $allowed_roles = get_option('pdx_allowed_roles', array('administrator' => 'administrator', 'editor' => 'editor'));
        $user = wp_get_current_user();
        
        if (!$user || !$user->roles) {
            return false;
        }
        
        foreach ($user->roles as $role) {
            if (isset($allowed_roles[$role])) {
                return true;
            }
        }
        
        return false;
    }

    public function get_duplicate_url($post_id) {
        return wp_nonce_url(
            admin_url('admin.php?action=pdx_duplicate_post&post=' . $post_id),
            'pdx_duplicate_nonce'
        );
    }
}