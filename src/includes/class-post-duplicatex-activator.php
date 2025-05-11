
<?php

class PostDX51_Activator {
    public static function activate() {
        // Default settings
        $default_options = array(
            'postdx_allowed_roles' => array('administrator' => 'administrator', 'editor' => 'editor'),
            'postdx_post_types' => array('post' => 'post', 'page' => 'page'),
            'postdx_link_location' => array('row' => 'row', 'admin_bar' => 'admin_bar', 'classic_editor' => 'classic_editor', 'block_editor' => 'block_editor'),
            'postdx_post_status' => 'draft',
            'postdx_redirect' => 'to_list',
            'postdx_link_title' => 'Duplicate',
            'postdx_post_prefix' => 'Copy of ',
            'postdx_post_suffix' => '',
        );
        
        foreach ($default_options as $option_name => $option_value) {
            if (get_option($option_name) === false) {
                add_option($option_name, $option_value);
            }
        }
    }
}