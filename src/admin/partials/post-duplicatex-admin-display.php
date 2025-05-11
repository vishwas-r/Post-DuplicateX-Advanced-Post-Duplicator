<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$allowed_roles = get_option('postdx_allowed_roles', array('administrator' => 'administrator', 'editor' => 'editor'));
$post_types = get_option('postdx_post_types', array('post' => 'post', 'page' => 'page'));
$link_location = get_option('postdx_link_location', array('row' => 'row', 'admin_bar' => 'admin_bar', 'classic_editor' => 'classic_editor', 'block_editor' => 'block_editor'));
$post_status = get_option('postdx_post_status', 'draft');
$redirect = get_option('postdx_redirect', 'to_edit');
$link_title = get_option('postdx_link_title', 'Duplicate');
$post_prefix = get_option('postdx_post_prefix', 'Copy of ');
$post_suffix = get_option('postdx_post_suffix', '');
?>
<div class="wrap postdx-admin-wrapper">
    <div class="postdx-header">
        <div class="postdx-header-content">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <p class="postdx-header-description"><?php esc_html_e('Configure how Post DuplicateX behaves when cloning your content.', 'post-duplicatex'); ?></p>
        </div>
        <div class="postdx-header-badge">
            <span class="postdx-version"><?php echo esc_html('v' . POSTDX51_VERSION); ?></span>
        </div>
    </div>
    
    <div class="postdx-admin-container">
        <form method="post" action="options.php">
            <?php settings_fields('postdx_settings_group'); ?>
            
            <div class="postdx-admin-grid">                
                <div class="postdx-admin-content">
                    <div class="postdx-admin-card">
                        <div class="postdx-card-header">
                            <div class="postdx-card-icon"><span class="dashicons dashicons-admin-users"></span></div>
                            <h2><?php esc_html_e('General Settings', 'post-duplicatex'); ?></h2>
                        </div>
                        
                        <div class="postdx-card-content">
                            <div class="postdx-form-group">
                                <label class="postdx-form-label"><?php esc_html_e('User Roles', 'post-duplicatex'); ?></label>
                                <div class="postdx-form-control postdx-checkbox-group">
                                    <?php 
                                    $roles = wp_roles()->get_names();
                                    foreach ($roles as $role_value => $role_name) : 
                                    ?>
                                        <div class="postdx-checkbox-item">
                                            <input type="checkbox" id="postdx_allowed_roles_<?php echo esc_attr($role_value); ?>" name="postdx_allowed_roles[<?php echo esc_attr($role_value); ?>]" value="<?php echo esc_attr($role_value); ?>" <?php checked(isset($allowed_roles[$role_value]), true); ?>>
                                            <label for="postdx_allowed_roles_<?php echo esc_attr($role_value); ?>"><?php echo esc_html($role_name); ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                    <p class="postdx-form-help"><?php esc_html_e('Select which user roles can duplicate content.', 'post-duplicatex'); ?></p>
                                </div>
                            </div>
                            
                            <div class="postdx-form-group">
                                <label class="postdx-form-label"><?php esc_html_e('Post Types', 'post-duplicatex'); ?></label>
                                <div class="postdx-form-control postdx-checkbox-group">
                                    <?php 
                                    $available_post_types = get_post_types(array('public' => true), 'objects');
                                    foreach ($available_post_types as $post_type) : 
                                    ?>
                                        <div class="postdx-checkbox-item">
                                            <input type="checkbox" id="postdx_post_types_<?php echo esc_attr($post_type->name); ?>" name="postdx_post_types[<?php echo esc_attr($post_type->name); ?>]" value="<?php echo esc_attr($post_type->name); ?>" <?php checked(isset($post_types[$post_type->name]), true); ?>>
                                            <label for="postdx_post_types_<?php echo esc_attr($post_type->name); ?>"><?php echo esc_html($post_type->labels->singular_name); ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                    <p class="postdx-form-help"><?php esc_html_e('Select which post types can be duplicated.', 'post-duplicatex'); ?></p>
                                </div>
                            </div>
                            
                            <div class="postdx-form-group">
                                <label class="postdx-form-label"><?php esc_html_e('Clone Link Location', 'post-duplicatex'); ?></label>
                                <div class="postdx-form-control postdx-checkbox-group">
                                    <div class="postdx-checkbox-item">
                                        <input type="checkbox" id="postdx_link_location_row" name="postdx_link_location[row]" value="row" <?php checked(isset($link_location['row']), true); ?>>
                                        <label for="postdx_link_location_row"><?php esc_html_e('Posts list (row actions)', 'post-duplicatex'); ?></label>
                                    </div>
                                    
                                    <div class="postdx-checkbox-item">
                                        <input type="checkbox" id="postdx_link_location_admin_bar" name="postdx_link_location[admin_bar]" value="admin_bar" <?php checked(isset($link_location['admin_bar']), true); ?>>
                                        <label for="postdx_link_location_admin_bar"><?php esc_html_e('Admin bar', 'post-duplicatex'); ?></label>
                                    </div>
                                    
                                    <div class="postdx-checkbox-item">
                                        <input type="checkbox" id="postdx_link_location_classic_editor" name="postdx_link_location[classic_editor]" value="classic_editor" <?php checked(isset($link_location['classic_editor']), true); ?>>
                                        <label for="postdx_link_location_classic_editor"><?php esc_html_e('Classic editor', 'post-duplicatex'); ?></label>
                                    </div>
                                    
                                    <div class="postdx-checkbox-item">
                                        <input type="checkbox" id="postdx_link_location_block_editor" name="postdx_link_location[block_editor]" value="block_editor" <?php checked(isset($link_location['block_editor']), true); ?>>
                                        <label for="postdx_link_location_block_editor"><?php esc_html_e('Block editor (Gutenberg)', 'post-duplicatex'); ?></label>
                                    </div>
                                    <p class="postdx-form-help"><?php esc_html_e('Choose where to show the duplicate link.', 'post-duplicatex'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="postdx-admin-card">
                        <div class="postdx-card-header">
                            <div class="postdx-card-icon"><span class="dashicons dashicons-admin-settings"></span></div>
                            <h2><?php esc_html_e('Duplicate Options', 'post-duplicatex'); ?></h2>
                        </div>
                        
                        <div class="postdx-card-content">
                            <div class="postdx-form-group">
                                <label class="postdx-form-label" for="postdx_post_status"><?php esc_html_e('Duplicate Post Status', 'post-duplicatex'); ?></label>
                                <div class="postdx-form-control">
                                    <select name="postdx_post_status" id="postdx_post_status" class="postdx-select">
                                        <option value="draft" <?php selected($post_status, 'draft'); ?>><?php esc_html_e('Draft', 'post-duplicatex'); ?></option>
                                        <option value="publish" <?php selected($post_status, 'publish'); ?>><?php esc_html_e('Published', 'post-duplicatex'); ?></option>
                                        <option value="private" <?php selected($post_status, 'private'); ?>><?php esc_html_e('Private', 'post-duplicatex'); ?></option>
                                        <option value="pending" <?php selected($post_status, 'pending'); ?>><?php esc_html_e('Pending', 'post-duplicatex'); ?></option>
                                    </select>
                                    <p class="postdx-form-help"><?php esc_html_e('What status should the duplicated post have?', 'post-duplicatex'); ?></p>
                                </div>
                            </div>
                            
                            <div class="postdx-form-group">
                                <label class="postdx-form-label" for="postdx_redirect"><?php esc_html_e('Redirection', 'post-duplicatex'); ?></label>
                                <div class="postdx-form-control">
                                    <select name="postdx_redirect" id="postdx_redirect" class="postdx-select">
                                        <option value="to_edit" <?php selected($redirect, 'to_edit'); ?>><?php esc_html_e('To edit screen', 'post-duplicatex'); ?></option>
                                        <option value="to_list" <?php selected($redirect, 'to_list'); ?>><?php esc_html_e('To posts list', 'post-duplicatex'); ?></option>
                                        <option value="to_new" <?php selected($redirect, 'to_new'); ?>><?php esc_html_e('To new post', 'post-duplicatex'); ?></option>
                                    </select>
                                    <p class="postdx-form-help"><?php esc_html_e('Where to redirect after clicking the duplicate link.', 'post-duplicatex'); ?></p>
                                </div>
                            </div>
                            
                            <div class="postdx-form-group">
                                <label class="postdx-form-label" for="postdx_link_title"><?php esc_html_e('Duplicate Link Title', 'post-duplicatex'); ?></label>
                                <div class="postdx-form-control">
                                    <input type="text" name="postdx_link_title" id="postdx_link_title" value="<?php echo esc_attr($link_title); ?>" class="regular-text postdx-text">
                                    <p class="postdx-form-help"><?php esc_html_e('Text for the duplicate link.', 'post-duplicatex'); ?></p>
                                </div>
                            </div>
                            
                            <div class="postdx-form-row">
                                <div class="postdx-form-col">
                                    <div class="postdx-form-group">
                                        <label class="postdx-form-label" for="postdx_post_prefix"><?php esc_html_e('Post Prefix', 'post-duplicatex'); ?></label>
                                        <div class="postdx-form-control">
                                            <input type="text" name="postdx_post_prefix" id="postdx_post_prefix" value="<?php echo esc_attr($post_prefix); ?>" class="regular-text postdx-text">
                                            <p class="postdx-form-help"><?php esc_html_e('Add this text before the original title.', 'post-duplicatex'); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="postdx-form-col">
                                    <div class="postdx-form-group">
                                        <label class="postdx-form-label" for="postdx_post_suffix"><?php esc_html_e('Post Suffix', 'post-duplicatex'); ?></label>
                                        <div class="postdx-form-control">
                                            <input type="text" name="postdx_post_suffix" id="postdx_post_suffix" value="<?php echo esc_attr($post_suffix); ?>" class="regular-text postdx-text">
                                            <p class="postdx-form-help"><?php esc_html_e('Add this text after the original title.', 'post-duplicatex'); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="postdx-submit-container">
                        <?php submit_button(esc_html__('Save Settings', 'post-duplicatex'), 'primary postdx-submit-button', 'submit', false); ?>
                    </div>
                </div>
                
                <div class="postdx-admin-sidebar">
                    <div class="postdx-admin-sidebar-widget">
                        <div class="postdx-widget-header">
                            <div class="postdx-widget-icon"><span class="dashicons dashicons-info"></span></div>
                            <h3><?php esc_html_e('About Post DuplicateX', 'post-duplicatex'); ?></h3>
                        </div>
                        <div class="postdx-widget-content">
                            <p><?php esc_html_e('Post DuplicateX allows you to duplicate any post, page or custom post type with a single click.', 'post-duplicatex'); ?></p>
                            <div class="postdx-features">
                                <div class="postdx-feature">
                                    <span class="dashicons dashicons-yes-alt"></span>
                                    <span><?php esc_html_e('Duplicate any post type', 'post-duplicatex'); ?></span>
                                </div>
                                <div class="postdx-feature">
                                    <span class="dashicons dashicons-yes-alt"></span>
                                    <span><?php esc_html_e('Copy all content & metadata', 'post-duplicatex'); ?></span>
                                </div>
                                <div class="postdx-feature">
                                    <span class="dashicons dashicons-yes-alt"></span>
                                    <span><?php esc_html_e('Role-based permissions', 'post-duplicatex'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="postdx-admin-sidebar-widget">
                        <div class="postdx-widget-header">
                            <div class="postdx-widget-icon"><span class="dashicons dashicons-star-filled"></span></div>
                            <h3><?php esc_html_e('Rate Us', 'post-duplicatex'); ?></h3>
                        </div>
                        <div class="postdx-widget-content">
                            <p><?php esc_html_e('If you find this plugin useful, please consider rating it.', 'post-duplicatex'); ?></p>
                            <div class="postdx-rating">
                                <span class="dashicons dashicons-star-filled"></span>
                                <span class="dashicons dashicons-star-filled"></span>
                                <span class="dashicons dashicons-star-filled"></span>
                                <span class="dashicons dashicons-star-filled"></span>
                                <span class="dashicons dashicons-star-filled"></span>
                            </div>
                            <a href="https://wordpress.org/support/plugin/post-duplicatex/reviews/#new-post" target="_blank" class="button postdx-button postdx-button-full"><?php esc_html_e('Rate on WordPress.org', 'post-duplicatex'); ?></a>
                        </div>
                    </div>
                    
                    <div class="postdx-admin-sidebar-widget">
                        <div class="postdx-widget-header">
                            <div class="postdx-widget-icon"><span class="dashicons dashicons-sos"></span></div>
                            <h3><?php esc_html_e('Need Support?', 'post-duplicatex'); ?></h3>
                        </div>
                        <div class="postdx-widget-content">
                            <p><?php esc_html_e('If you need help with the plugin, please visit our support forum.', 'post-duplicatex'); ?></p>
                            <div class="postdx-button-group">
                                <a href="https://wordpress.org/support/plugin/post-duplicatex/" target="_blank" class="button postdx-button"><?php esc_html_e('WordPress Forum', 'post-duplicatex'); ?></a>
                                <a href="https://github.com/vishwas-r/Post-DuplicateX-Advanced-Post-Duplicator/issues/" target="_blank" class="button postdx-button postdx-button-outline"><?php esc_html_e('GitHub Issues', 'post-duplicatex'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <div class="postdx-footer">
        <div class="postdx-footer-content">
            <?php // Translators: 1: Plugin version. 2: Developer name with link. ?>
            <p><?php printf(esc_html__('Post DuplicateX v%1$s | Developed by %2$s', 'post-duplicatex'), esc_html(POSTDX51_VERSION), '<a href="https://vishwas.me/" target="_blank">Vishwas R</a>'); ?></p>
        </div>
        <div class="postdx-footer-links">
            <a href="https://wordpress.org/plugins/post-duplicatex/" target="_blank"><?php esc_html_e('Plugin Page', 'post-duplicatex'); ?></a>
            <a href="https://github.com/vishwas-r/Post-DuplicateX-Advanced-Post-Duplicator" target="_blank"><?php esc_html_e('GitHub', 'post-duplicatex'); ?></a>
            <a href="https://wordpress.org/support/plugin/post-duplicatex/" target="_blank"><?php esc_html_e('Support', 'post-duplicatex'); ?></a>
        </div>
    </div>
</div>