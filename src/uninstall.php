
<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete plugin options
$options = array(
    'pdx_allowed_roles',
    'pdx_post_types',
    'pdx_link_location',
    'pdx_post_status',
    'pdx_redirect',
    'pdx_link_title',
    'pdx_post_prefix',
    'pdx_post_suffix',
);

foreach ($options as $option) {
    delete_option($option);
}