
<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete plugin options
$options = array(
    'postdx_allowed_roles',
    'postdx_post_types',
    'postdx_link_location',
    'postdx_post_status',
    'postdx_redirect',
    'postdx_link_title',
    'postdx_post_prefix',
    'postdx_post_suffix',
);

foreach ($options as $option) {
    delete_option($option);
}