<?php
$allowed_roles = get_option('pdx_allowed_roles', array('administrator' => 'administrator', 'editor' => 'editor'));
$post_types = get_option('pdx_post_types', array('post' => 'post', 'page' => 'page'));
$link_location = get_option('pdx_link_location', array('row' => 'row', 'admin_bar' => 'admin_bar', 'classic_editor' => 'classic_editor', 'block_editor' => 'block_editor'));
$post_status = get_option('pdx_post_status', 'draft');
$redirect = get_option('pdx_redirect', 'to_edit');
$link_title = get_option('pdx_link_title', 'Duplicate');
$post_prefix = get_option('pdx_post_prefix', 'Copy of ');
$post_suffix = get_option('pdx_post_suffix', '');
?>
<div class="wrap pdx-admin-wrapper">
    <div class="pdx-header">
        <div class="pdx-header-content">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <p class="pdx-header-description"><?php esc_html_e('Configure how Post DuplicateX behaves when cloning your content.', 'post-duplicatex'); ?></p>
        </div>
        <div class="pdx-header-badge">
            <span class="pdx-version"><?php echo esc_html('v' . POST_DUPLICATEX_VERSION); ?></span>
        </div>
    </div>
    
    <div class="pdx-admin-container">
        <form method="post" action="options.php">
            <?php settings_fields('pdx_settings_group'); ?>
            
            <div class="pdx-admin-grid">                
                <div class="pdx-admin-content">
                    <div class="pdx-admin-card">
                        <div class="pdx-card-header">
                            <div class="pdx-card-icon"><span class="dashicons dashicons-admin-users"></span></div>
                            <h2><?php esc_html_e('General Settings', 'post-duplicatex'); ?></h2>
                        </div>
                        
                        <div class="pdx-card-content">
                            <div class="pdx-form-group">
                                <label class="pdx-form-label"><?php esc_html_e('User Roles', 'post-duplicatex'); ?></label>
                                <div class="pdx-form-control pdx-checkbox-group">
                                    <?php 
                                    $roles = wp_roles()->get_names();
                                    foreach ($roles as $role_value => $role_name) : 
                                    ?>
                                        <div class="pdx-checkbox-item">
                                            <input type="checkbox" id="pdx_allowed_roles_<?php echo esc_attr($role_value); ?>" name="pdx_allowed_roles[<?php echo esc_attr($role_value); ?>]" value="<?php echo esc_attr($role_value); ?>" <?php checked(isset($allowed_roles[$role_value]), true); ?>>
                                            <label for="pdx_allowed_roles_<?php echo esc_attr($role_value); ?>"><?php echo esc_html($role_name); ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                    <p class="pdx-form-help"><?php esc_html_e('Select which user roles can duplicate content.', 'post-duplicatex'); ?></p>
                                </div>
                            </div>
                            
                            <div class="pdx-form-group">
                                <label class="pdx-form-label"><?php esc_html_e('Post Types', 'post-duplicatex'); ?></label>
                                <div class="pdx-form-control pdx-checkbox-group">
                                    <?php 
                                    $available_post_types = get_post_types(array('public' => true), 'objects');
                                    foreach ($available_post_types as $post_type) : 
                                    ?>
                                        <div class="pdx-checkbox-item">
                                            <input type="checkbox" id="pdx_post_types_<?php echo esc_attr($post_type->name); ?>" name="pdx_post_types[<?php echo esc_attr($post_type->name); ?>]" value="<?php echo esc_attr($post_type->name); ?>" <?php checked(isset($post_types[$post_type->name]), true); ?>>
                                            <label for="pdx_post_types_<?php echo esc_attr($post_type->name); ?>"><?php echo esc_html($post_type->labels->singular_name); ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                    <p class="pdx-form-help"><?php esc_html_e('Select which post types can be duplicated.', 'post-duplicatex'); ?></p>
                                </div>
                            </div>
                            
                            <div class="pdx-form-group">
                                <label class="pdx-form-label"><?php esc_html_e('Clone Link Location', 'post-duplicatex'); ?></label>
                                <div class="pdx-form-control pdx-checkbox-group">
                                    <div class="pdx-checkbox-item">
                                        <input type="checkbox" id="pdx_link_location_row" name="pdx_link_location[row]" value="row" <?php checked(isset($link_location['row']), true); ?>>
                                        <label for="pdx_link_location_row"><?php esc_html_e('Posts list (row actions)', 'post-duplicatex'); ?></label>
                                    </div>
                                    
                                    <div class="pdx-checkbox-item">
                                        <input type="checkbox" id="pdx_link_location_admin_bar" name="pdx_link_location[admin_bar]" value="admin_bar" <?php checked(isset($link_location['admin_bar']), true); ?>>
                                        <label for="pdx_link_location_admin_bar"><?php esc_html_e('Admin bar', 'post-duplicatex'); ?></label>
                                    </div>
                                    
                                    <div class="pdx-checkbox-item">
                                        <input type="checkbox" id="pdx_link_location_classic_editor" name="pdx_link_location[classic_editor]" value="classic_editor" <?php checked(isset($link_location['classic_editor']), true); ?>>
                                        <label for="pdx_link_location_classic_editor"><?php esc_html_e('Classic editor', 'post-duplicatex'); ?></label>
                                    </div>
                                    
                                    <div class="pdx-checkbox-item">
                                        <input type="checkbox" id="pdx_link_location_block_editor" name="pdx_link_location[block_editor]" value="block_editor" <?php checked(isset($link_location['block_editor']), true); ?>>
                                        <label for="pdx_link_location_block_editor"><?php esc_html_e('Block editor (Gutenberg)', 'post-duplicatex'); ?></label>
                                    </div>
                                    <p class="pdx-form-help"><?php esc_html_e('Choose where to show the duplicate link.', 'post-duplicatex'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pdx-admin-card">
                        <div class="pdx-card-header">
                            <div class="pdx-card-icon"><span class="dashicons dashicons-admin-settings"></span></div>
                            <h2><?php esc_html_e('Duplicate Options', 'post-duplicatex'); ?></h2>
                        </div>
                        
                        <div class="pdx-card-content">
                            <div class="pdx-form-group">
                                <label class="pdx-form-label" for="pdx_post_status"><?php esc_html_e('Duplicate Post Status', 'post-duplicatex'); ?></label>
                                <div class="pdx-form-control">
                                    <select name="pdx_post_status" id="pdx_post_status" class="pdx-select">
                                        <option value="draft" <?php selected($post_status, 'draft'); ?>><?php esc_html_e('Draft', 'post-duplicatex'); ?></option>
                                        <option value="publish" <?php selected($post_status, 'publish'); ?>><?php esc_html_e('Published', 'post-duplicatex'); ?></option>
                                        <option value="private" <?php selected($post_status, 'private'); ?>><?php esc_html_e('Private', 'post-duplicatex'); ?></option>
                                        <option value="pending" <?php selected($post_status, 'pending'); ?>><?php esc_html_e('Pending', 'post-duplicatex'); ?></option>
                                    </select>
                                    <p class="pdx-form-help"><?php esc_html_e('What status should the duplicated post have?', 'post-duplicatex'); ?></p>
                                </div>
                            </div>
                            
                            <div class="pdx-form-group">
                                <label class="pdx-form-label" for="pdx_redirect"><?php esc_html_e('Redirection', 'post-duplicatex'); ?></label>
                                <div class="pdx-form-control">
                                    <select name="pdx_redirect" id="pdx_redirect" class="pdx-select">
                                        <option value="to_edit" <?php selected($redirect, 'to_edit'); ?>><?php esc_html_e('To edit screen', 'post-duplicatex'); ?></option>
                                        <option value="to_list" <?php selected($redirect, 'to_list'); ?>><?php esc_html_e('To posts list', 'post-duplicatex'); ?></option>
                                        <option value="to_new" <?php selected($redirect, 'to_new'); ?>><?php esc_html_e('To new post', 'post-duplicatex'); ?></option>
                                    </select>
                                    <p class="pdx-form-help"><?php esc_html_e('Where to redirect after clicking the duplicate link.', 'post-duplicatex'); ?></p>
                                </div>
                            </div>
                            
                            <div class="pdx-form-group">
                                <label class="pdx-form-label" for="pdx_link_title"><?php esc_html_e('Duplicate Link Title', 'post-duplicatex'); ?></label>
                                <div class="pdx-form-control">
                                    <input type="text" name="pdx_link_title" id="pdx_link_title" value="<?php echo esc_attr($link_title); ?>" class="regular-text pdx-text">
                                    <p class="pdx-form-help"><?php esc_html_e('Text for the duplicate link.', 'post-duplicatex'); ?></p>
                                </div>
                            </div>
                            
                            <div class="pdx-form-row">
                                <div class="pdx-form-col">
                                    <div class="pdx-form-group">
                                        <label class="pdx-form-label" for="pdx_post_prefix"><?php esc_html_e('Post Prefix', 'post-duplicatex'); ?></label>
                                        <div class="pdx-form-control">
                                            <input type="text" name="pdx_post_prefix" id="pdx_post_prefix" value="<?php echo esc_attr($post_prefix); ?>" class="regular-text pdx-text">
                                            <p class="pdx-form-help"><?php esc_html_e('Add this text before the original title.', 'post-duplicatex'); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="pdx-form-col">
                                    <div class="pdx-form-group">
                                        <label class="pdx-form-label" for="pdx_post_suffix"><?php esc_html_e('Post Suffix', 'post-duplicatex'); ?></label>
                                        <div class="pdx-form-control">
                                            <input type="text" name="pdx_post_suffix" id="pdx_post_suffix" value="<?php echo esc_attr($post_suffix); ?>" class="regular-text pdx-text">
                                            <p class="pdx-form-help"><?php esc_html_e('Add this text after the original title.', 'post-duplicatex'); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pdx-submit-container">
                        <?php submit_button(esc_html__('Save Settings', 'post-duplicatex'), 'primary pdx-submit-button', 'submit', false); ?>
                    </div>
                </div>
                
                <div class="pdx-admin-sidebar">
                    <div class="pdx-admin-sidebar-widget">
                        <div class="pdx-widget-header">
                            <div class="pdx-widget-icon"><span class="dashicons dashicons-info"></span></div>
                            <h3><?php esc_html_e('About Post DuplicateX', 'post-duplicatex'); ?></h3>
                        </div>
                        <div class="pdx-widget-content">
                            <p><?php esc_html_e('Post DuplicateX allows you to duplicate any post, page or custom post type with a single click.', 'post-duplicatex'); ?></p>
                            <div class="pdx-features">
                                <div class="pdx-feature">
                                    <span class="dashicons dashicons-yes-alt"></span>
                                    <span><?php esc_html_e('Duplicate any post type', 'post-duplicatex'); ?></span>
                                </div>
                                <div class="pdx-feature">
                                    <span class="dashicons dashicons-yes-alt"></span>
                                    <span><?php esc_html_e('Copy all content & metadata', 'post-duplicatex'); ?></span>
                                </div>
                                <div class="pdx-feature">
                                    <span class="dashicons dashicons-yes-alt"></span>
                                    <span><?php esc_html_e('Role-based permissions', 'post-duplicatex'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pdx-admin-sidebar-widget">
                        <div class="pdx-widget-header">
                            <div class="pdx-widget-icon"><span class="dashicons dashicons-star-filled"></span></div>
                            <h3><?php esc_html_e('Rate Us', 'post-duplicatex'); ?></h3>
                        </div>
                        <div class="pdx-widget-content">
                            <p><?php esc_html_e('If you find this plugin useful, please consider rating it.', 'post-duplicatex'); ?></p>
                            <div class="pdx-rating">
                                <span class="dashicons dashicons-star-filled"></span>
                                <span class="dashicons dashicons-star-filled"></span>
                                <span class="dashicons dashicons-star-filled"></span>
                                <span class="dashicons dashicons-star-filled"></span>
                                <span class="dashicons dashicons-star-filled"></span>
                            </div>
                            <a href="https://wordpress.org/support/plugin/post-duplicatex/reviews/#new-post" target="_blank" class="button pdx-button pdx-button-full"><?php esc_html_e('Rate on WordPress.org', 'post-duplicatex'); ?></a>
                        </div>
                    </div>
                    
                    <div class="pdx-admin-sidebar-widget">
                        <div class="pdx-widget-header">
                            <div class="pdx-widget-icon"><span class="dashicons dashicons-sos"></span></div>
                            <h3><?php esc_html_e('Need Support?', 'post-duplicatex'); ?></h3>
                        </div>
                        <div class="pdx-widget-content">
                            <p><?php esc_html_e('If you need help with the plugin, please visit our support forum.', 'post-duplicatex'); ?></p>
                            <div class="pdx-button-group">
                                <a href="https://wordpress.org/support/plugin/post-duplicatex/" target="_blank" class="button pdx-button"><?php esc_html_e('WordPress Forum', 'post-duplicatex'); ?></a>
                                <a href="https://github.com/vishwas-r/Post-DuplicateX-Advanced-Post-Duplicator/issues/" target="_blank" class="button pdx-button pdx-button-outline"><?php esc_html_e('GitHub Issues', 'post-duplicatex'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <div class="pdx-footer">
        <div class="pdx-footer-content">
            <?php // Translators: 1: Plugin version. 2: Developer name with link. ?>
            <p><?php printf(esc_html__('Post DuplicateX v%1$s | Developed by %2$s', 'post-duplicatex'), esc_html(POST_DUPLICATEX_VERSION), '<a href="https://vishwas.me/" target="_blank">Vishwas R</a>'); ?></p>
        </div>
        <div class="pdx-footer-links">
            <a href="https://wordpress.org/plugins/post-duplicatex/" target="_blank"><?php esc_html_e('Plugin Page', 'post-duplicatex'); ?></a>
            <a href="https://github.com/vishwas-r/Post-DuplicateX-Advanced-Post-Duplicator" target="_blank"><?php esc_html_e('GitHub', 'post-duplicatex'); ?></a>
            <a href="https://wordpress.org/support/plugin/post-duplicatex/" target="_blank"><?php esc_html_e('Support', 'post-duplicatex'); ?></a>
        </div>
    </div>
</div>