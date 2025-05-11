<?php

class PostDX51_Admin {
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, POSTDX51_PLUGIN_URL . 'admin/css/post-duplicatex-admin.css', array(), $this->version, 'all');
    }

    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, POSTDX51_PLUGIN_URL . 'admin/js/post-duplicatex-admin.js', array('jquery'), $this->version, false);
    }

    public function add_plugin_admin_menu() {
        add_menu_page(
            __('Post DuplicateX', 'post-duplicatex'),
            __('Post DuplicateX', 'post-duplicatex'),
            'manage_options',
            $this->plugin_name,
            array($this, 'display_plugin_admin_page'),
            'dashicons-admin-page',
            26
        );
    }

    public function display_plugin_admin_page() {
        include_once POSTDX51_PLUGIN_DIR . 'admin/partials/post-duplicatex-admin-display.php';
    }

    public function register_plugin_settings() {
        // User Roles
        register_setting(
            'postdx_settings_group',
            'postdx_allowed_roles',
            'sanitize_allowed_roles'
        );

        // Post Types
        register_setting(
            'postdx_settings_group',
            'postdx_post_types',
            'sanitize_post_types'
        );

        // Link Location
        register_setting(
            'postdx_settings_group',
            'postdx_link_location',
            'sanitize_link_location'
        );

        // Post Status
        register_setting(
            'postdx_settings_group',
            'postdx_post_status',
            'sanitize_text_field'
        );

        // Redirection
        register_setting(
            'postdx_settings_group',
            'postdx_redirect',
            'sanitize_text_field'
        );

        // Link Title
        register_setting(
            'postdx_settings_group',
            'postdx_link_title',
            'sanitize_text_field'
        );

        // Post Prefix
        register_setting(
            'postdx_settings_group',
            'postdx_post_prefix',
            'sanitize_text_field'
        );

        // Post Suffix
        register_setting(
            'postdx_settings_group',
            'postdx_post_suffix',
            'sanitize_text_field'
        );
    }

    public function sanitize_allowed_roles($input) {
        if (!is_array($input)) {
            return array();
        }

        $allowed_values = array_keys(wp_roles()->get_names());
        $sanitized = array();
        foreach ($input as $key => $value) {
            $sanitized_value = sanitize_text_field($value);
            if (in_array($sanitized_value, $allowed_values, true)) {
                $sanitized[$sanitized_value] = $sanitized_value;
            }
        }

        return $sanitized;
    }

    public function sanitize_post_types($input) {
        if (!is_array($input)) {
            return array();
        }

        $allowed_values = array_keys(get_post_types(array('public' => true)));
        $sanitized = array();
        foreach ($input as $key => $value) {
            $sanitized_value = sanitize_text_field($value);
            if (in_array($sanitized_value, $allowed_values, true)) {
                $sanitized[$sanitized_value] = $sanitized_value;
            }
        }

        return $sanitized;
    }

    public function sanitize_link_location($input) {
        if (!is_array($input)) {
            return array();
        }

        $allowed_values = array('row', 'admin_bar', 'classic_editor', 'block_editor');
        $sanitized = array();
        foreach ($input as $key => $value) {
            $sanitized_value = sanitize_text_field($value);
            if (in_array($sanitized_value, $allowed_values, true)) {
                $sanitized[$sanitized_value] = $sanitized_value;
            }
        }

        return $sanitized;
    }

    public function add_action_links($links) {
        $settings_link = '<a href="' . admin_url('admin.php?page=' . $this->plugin_name) . '">' . __('Settings', 'post-duplicatex') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    public function add_duplicate_post_links() {
        // Check if user has permission
        if (!$this->user_can_duplicate()) {
            return;
        }
    
        $post_types = get_option('postdx_post_types', array('post' => 'post', 'page' => 'page'));
        $link_locations = get_option('postdx_link_location', array('row' => 'row', 'admin_bar' => 'admin_bar', 'classic_editor' => 'classic_editor', 'block_editor' => 'block_editor'));
        
        // Add row action links
        if (isset($link_locations['row']) && $link_locations['row'] === 'row') {
            foreach ($post_types as $post_type => $value) {
                add_filter('post_row_actions', array($this, 'add_duplicate_row_action'), 10, 2);
                if ($post_type === 'page') {
                    add_filter('page_row_actions', array($this, 'add_duplicate_row_action'), 10, 2);
                }
            }
        }
        
        // Add admin bar link
        if (isset($link_locations['admin_bar']) && $link_locations['admin_bar'] === 'admin_bar') {
            // Use priority 100 to ensure it's added after WordPress core items
            add_action('admin_bar_menu', array($this, 'add_duplicate_admin_bar_link'), 100);
        }
        
        // Add classic editor button
        if (isset($link_locations['classic_editor']) && $link_locations['classic_editor'] === 'classic_editor') {
            add_action('post_submitbox_misc_actions', array($this, 'add_duplicate_classic_editor_button'), 9);
        }
        
        // Add block editor button
        if (isset($link_locations['block_editor']) && $link_locations['block_editor'] === 'block_editor') {
            add_action('enqueue_block_editor_assets', array($this, 'add_duplicate_block_editor_button'), 9);
        }
    }

    public function user_can_duplicate() {
        $allowed_roles = get_option('postdx_allowed_roles', array('administrator' => 'administrator', 'editor' => 'editor'));
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

    public function add_duplicate_row_action($actions, $post) {
        $post_types = get_option('postdx_post_types', array('post' => 'post', 'page' => 'page'));
        
        if (!isset($post_types[$post->post_type])) {
            return $actions;
        }
        
        $link_title = get_option('postdx_link_title', 'Duplicate');
        $duplicate_url = $this->get_duplicate_url($post->ID);
        
        $actions['duplicate'] = '<a href="' . esc_url($duplicate_url) . '" title="' . esc_attr__('Duplicate this item', 'post-duplicatex') . '">' . esc_html($link_title) . '</a>';
        
        return $actions;
    }

    public function add_duplicate_admin_bar_link($wp_admin_bar) {
        // Check if user has permission
        if (!$this->user_can_duplicate()) {
            return;
        }
        
        global $post;

        if (!$post) {
            // First check if post ID is in GET and verify nonce if present
            $post_id = 0;
            if (isset($_GET['post']) && is_numeric($_GET['post'])) {
                // Only process $_GET['post'] if it's a valid integer
                $post_id = intval($_GET['post']);
                
                // Verify this is a legitimate admin page request with proper nonce
                // This is just for safety - not relying solely on nonce for authorization
                if (isset($_GET['action']) && $_GET['action'] === 'edit') {
                    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'update-post_' . $post_id)) {
                        // If there's a nonce but it's invalid, don't proceed
                        if (isset($_GET['_wpnonce'])) {
                            return;
                        }
                        // If no nonce, this might be a direct edit URL which is fine
                    }
                }
                
                // Additional check: Verify current user can edit this post
                $post_obj = get_post($post_id);
                if ($post_obj && !current_user_can('edit_post', $post_id)) {
                    return;
                }
                
                if ($post_id > 0) {
                    $post = get_post($post_id);
                }
            }
        }
        
        if (!$post) {
            return;
        }
        
        $post_id = $post->ID;
        $post_type = $post->post_type;
        $post_types = get_option('postdx_post_types', array('post' => 'post', 'page' => 'page'));
        if (!isset($post_types[$post_type])) {
            return;
        }
        
        $link_title = get_option('postdx_link_title', 'Duplicate');
        $duplicate_url = $this->get_duplicate_url($post_id);
        
        $wp_admin_bar->add_menu(array(
            'id' => 'duplicate-post',
            'title' => $link_title,
            'href' => $duplicate_url,
        ));
    }

    public function add_duplicate_classic_editor_button() {
        global $post;
        
        if (!$post) {
            return;
        }
        
        if (!$this->user_can_duplicate()) {
            return;
        }
        
        $post_types = get_option('postdx_post_types', array('post' => 'post', 'page' => 'page'));
        
        if (!isset($post_types[$post->post_type])) {
            return;
        }
        
        $link_title = get_option('postdx_link_title', 'Duplicate');
        $duplicate_url = $this->get_duplicate_url($post->ID);
        echo '<div class="misc-pub-section">';
        echo '<a class="button postdx-duplicate-button" href="' . esc_url($duplicate_url) . '">' . esc_html($link_title) . '</a>';
        echo '</div>';
    }

    public function add_duplicate_block_editor_button() {
        $post_types = get_option('postdx_post_types', array('post' => 'post', 'page' => 'page'));
        $post_types_array = array_keys($post_types);
        $link_title = get_option('postdx_link_title', 'Duplicate');
        
        wp_enqueue_script(
            'postdx-block-editor',
            POSTDX51_PLUGIN_URL . 'admin/js/post-duplicatex-block-editor.js',
            array('wp-plugins', 'wp-edit-post', 'wp-element', 'wp-components', 'wp-data', 'wp-api-fetch'),
            $this->version,
            true
        );
        wp_localize_script(
            'postdx-block-editor',
            'postdxBlockEditor',
            array(
                'postTypes' => $post_types_array,
                'linkTitle' => $link_title,
                'ajaxURL' => admin_url('admin.php'),
                'nonce' => wp_create_nonce('postdx_duplicate_nonce'),
            )
        );
    }

    public function get_duplicate_url($post_id) {
        return wp_nonce_url(
            admin_url('admin.php?action=postdx_duplicate_post&post=' . $post_id),
            'postdx_duplicate_nonce'
        );
    }

    public function duplicate_post_action() {
        // Check if necessary parameters exist
        if (!isset($_GET['action']) || $_GET['action'] !== 'postdx_duplicate_post') {
            return; // Not our action, exit early
        }
        
        // Check if post parameter exists
        if (!isset($_GET['post'])) {
            wp_die(esc_html__('No post to duplicate has been supplied!', 'post-duplicatex'));
        }
        
        // Validate and sanitize post ID
        $post_id = isset($_GET['post']) ? intval($_GET['post']) : 0;
        if ($post_id <= 0) {
            wp_die(esc_html__('Invalid post ID!', 'post-duplicatex'));
        }
        
        // Verify nonce
        if (!isset($_GET['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'postdx_duplicate_nonce')) {
            wp_die(esc_html__('Security check failed!', 'post-duplicatex'));
        }
        
        // Check user permissions - both role-based and capability-based
        if (!$this->user_can_duplicate()) {
            wp_die(esc_html__('You do not have permission to duplicate posts.', 'post-duplicatex'));
        }
        
        // Also verify the user can edit this specific post
        if (!current_user_can('edit_post', $post_id)) {
            wp_die(esc_html__('You do not have permission to duplicate this specific post.', 'post-duplicatex'));
        }
        
        // Get the post
        $post = get_post($post_id);
        if (!$post) {
            wp_die(esc_html__('Post creation failed, could not find original post.', 'post-duplicatex'));
        }
        
        // Check if post type is enabled for duplication
        $post_types = get_option('postdx_post_types', array('post' => 'post', 'page' => 'page'));
        if (!isset($post_types[$post->post_type])) {
            wp_die(esc_html__('Post type not enabled for duplication.', 'post-duplicatex'));
        }
        
        // Duplicate the post
        $new_post_id = $this->duplicate_post($post_id);
        
        if (is_wp_error($new_post_id)) {
            wp_die(esc_html($new_post_id->get_error_message()));
        }
        
        // Handle redirection
        $redirect = get_option('postdx_redirect', 'to_list');
        
        switch ($redirect) {
            case 'to_list':
                wp_redirect(admin_url('edit.php?post_type=' . $post->post_type));
                break;
            case 'to_edit':
                wp_redirect(admin_url('post.php?action=edit&post=' . (int) $new_post_id));
                break;
            case 'to_new':
                wp_redirect(get_permalink((int) $new_post_id));
                break;
            default:
                wp_redirect(admin_url('edit.php?post_type=' . $post->post_type));
        }
        
        exit;
    }

    public function duplicate_post($post_id) {
        $post = get_post($post_id);
        $prefix = get_option('postdx_post_prefix', 'Copy of ');
        $suffix = get_option('postdx_post_suffix', '');
        $status = get_option('postdx_post_status', 'draft');
        
        $new_post_args = array(
            'post_author'    => $post->post_author,
            'post_content'   => $post->post_content,
            'post_title'     => $prefix . $post->post_title . $suffix,
            'post_excerpt'   => $post->post_excerpt,
            'post_status'    => $status,
            'post_type'      => $post->post_type,
            'post_name'      => '',
            'comment_status' => $post->comment_status,
            'ping_status'    => $post->ping_status,
            'post_parent'    => $post->post_parent,
            'menu_order'     => $post->menu_order,
            'post_password'  => $post->post_password,
            'to_ping'        => $post->to_ping,
            'pinged'         => $post->pinged,
        );
        
        $new_post_id = wp_insert_post($new_post_args);
        
        if (is_wp_error($new_post_id)) {
            return $new_post_id;
        }
        
        // Copy post meta
        $post_meta = get_post_meta($post_id);
        
        if ($post_meta) {
            foreach ($post_meta as $meta_key => $meta_values) {
                foreach ($meta_values as $meta_value) {
                    // Skip some internal WordPress meta that should not be copied
                    if (in_array($meta_key, array('_edit_lock', '_edit_last', '_wp_page_template', '_wp_old_slug'))) {
                        continue;
                    }
                    
                    $meta_value = maybe_unserialize($meta_value);
                    add_post_meta($new_post_id, $meta_key, $meta_value);
                }
            }
        }
        
        // Copy taxonomies/terms
        $taxonomies = get_object_taxonomies($post->post_type);
        
        foreach ($taxonomies as $taxonomy) {
            $terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
            wp_set_object_terms($new_post_id, $terms, $taxonomy);
        }
        
        // Duplicate featured image
        $thumbnail_id = get_post_thumbnail_id($post_id);
        if ($thumbnail_id) {
            set_post_thumbnail($new_post_id, $thumbnail_id);
        }
        
        do_action('postdx_after_post_duplicate', $new_post_id, $post);
        
        return $new_post_id;
    }
}