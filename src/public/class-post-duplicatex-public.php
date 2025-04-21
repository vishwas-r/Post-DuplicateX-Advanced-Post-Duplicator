<?php

class Post_DuplicateX51_Public {
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

    public function add_duplicate_post_links() {
        // Check if user has permission
        if (!$this->user_can_duplicate()) {
            return;
        }
    
        $post_types = get_option('pdx_post_types', array('post' => 'post', 'page' => 'page'));
        $link_locations = get_option('pdx_link_location', array('row' => 'row', 'admin_bar' => 'admin_bar', 'classic_editor' => 'classic_editor', 'block_editor' => 'block_editor'));

        // Add admin bar link
        if (isset($link_locations['admin_bar']) && $link_locations['admin_bar'] === 'admin_bar') {
            // Use priority 100 to ensure it's added after WordPress core items
            add_action('admin_bar_menu', array($this, 'add_duplicate_admin_bar_link'), 90);
        }
    }

    public function add_duplicate_admin_bar_link($wp_admin_bar) {
        // Check if user has permission
        if (!$this->user_can_duplicate()) {
            return;
        }
        
        global $post;

        if (!isset($post) || !is_a($post, 'WP_Post')) {
            if (is_singular()) {
                $post = get_post(get_the_ID());
            } else {
                return;
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