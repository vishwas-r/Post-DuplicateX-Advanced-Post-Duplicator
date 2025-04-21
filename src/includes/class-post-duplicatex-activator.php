
<?php

class Post_DuplicateX_Activator {
    public static function activate() {
        // Default settings
        $default_options = array(
            'pdx_allowed_roles' => array('administrator' => 'administrator', 'editor' => 'editor'),
            'pdx_post_types' => array('post' => 'post', 'page' => 'page'),
            'pdx_link_location' => array('row' => 'row', 'admin_bar' => 'admin_bar', 'classic_editor' => 'classic_editor', 'block_editor' => 'block_editor'),
            'pdx_post_status' => 'draft',
            'pdx_redirect' => 'to_list',
            'pdx_link_title' => 'Duplicate',
            'pdx_post_prefix' => 'Copy of ',
            'pdx_post_suffix' => '',
        );
        
        foreach ($default_options as $option_name => $option_value) {
            if (get_option($option_name) === false) {
                add_option($option_name, $option_value);
            }
        }
    }
}