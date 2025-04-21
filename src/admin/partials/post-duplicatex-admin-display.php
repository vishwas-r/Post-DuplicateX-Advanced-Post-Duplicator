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

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <div class="pdx-admin-container">
        <form method="post" action="options.php">
            <?php settings_fields('pdx_settings_group'); ?>
            
            <div class="pdx-admin-grid">                
                <div class="pdx-admin-content">
                    <div class="pdx-admin-card">
                        <h2><?php esc_html_e('General Settings', 'post-duplicatex'); ?></h2>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php esc_html_e('User Roles', 'post-duplicatex'); ?></th>
                                <td>
                                    <?php 
                                    $roles = wp_roles()->get_names();
                                    foreach ($roles as $role_value => $role_name) : 
                                    ?>
                                        <label for="pdx_allowed_roles_<?php echo esc_attr($role_value); ?>">
                                            <input type="checkbox" id="pdx_allowed_roles_<?php echo esc_attr($role_value); ?>" name="pdx_allowed_roles[<?php echo esc_attr($role_value); ?>]" value="<?php echo esc_attr($role_value); ?>" <?php checked(isset($allowed_roles[$role_value]), true); ?>>
                                            <?php echo esc_html($role_name); ?>
                                        </label><br>
                                    <?php endforeach; ?>
                                    <p class="description"><?php esc_html_e('Select which user roles can duplicate content.', 'post-duplicatex'); ?></p>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php esc_html_e('Post Types', 'post-duplicatex'); ?></th>
                                <td>
                                    <?php 
                                    $available_post_types = get_post_types(array('public' => true), 'objects');
                                    foreach ($available_post_types as $post_type) : 
                                    ?>
                                        <label for="pdx_post_types_<?php echo esc_attr($post_type->name); ?>">
                                            <input type="checkbox" id="pdx_post_types_<?php echo esc_attr($post_type->name); ?>" name="pdx_post_types[<?php echo esc_attr($post_type->name); ?>]" value="<?php echo esc_attr($post_type->name); ?>" <?php checked(isset($post_types[$post_type->name]), true); ?>>
                                            <?php echo esc_html($post_type->labels->singular_name); ?>
                                        </label><br>
                                    <?php endforeach; ?>
                                    <p class="description"><?php esc_html_e('Select which post types can be duplicated.', 'post-duplicatex'); ?></p>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php esc_html_e('Clone Link Location', 'post-duplicatex'); ?></th>
                                <td>
                                    <label for="pdx_link_location_row">
                                        <input type="checkbox" id="pdx_link_location_row" name="pdx_link_location[row]" value="row" <?php checked(isset($link_location['row']), true); ?>>
                                        <?php esc_html_e('Posts list (row actions)', 'post-duplicatex'); ?>
                                    </label><br>
                                    
                                    <label for="pdx_link_location_admin_bar">
                                        <input type="checkbox" id="pdx_link_location_admin_bar" name="pdx_link_location[admin_bar]" value="admin_bar" <?php checked(isset($link_location['admin_bar']), true); ?>>
                                        <?php esc_html_e('Admin bar', 'post-duplicatex'); ?>
                                    </label><br>
                                    
                                    <label for="pdx_link_location_classic_editor">
                                        <input type="checkbox" id="pdx_link_location_classic_editor" name="pdx_link_location[classic_editor]" value="classic_editor" <?php checked(isset($link_location['classic_editor']), true); ?>>
                                        <?php esc_html_e('Classic editor', 'post-duplicatex'); ?>
                                    </label><br>
                                    
                                    <label for="pdx_link_location_block_editor">
                                        <input type="checkbox" id="pdx_link_location_block_editor" name="pdx_link_location[block_editor]" value="block_editor" <?php checked(isset($link_location['block_editor']), true); ?>>
                                        <?php esc_html_e('Block editor (Gutenberg)', 'post-duplicatex'); ?>
                                    </label>
                                    <p class="description"><?php esc_html_e('Choose where to show the duplicate link.', 'post-duplicatex'); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="pdx-admin-card">
                        <h2><?php esc_html_e('Duplicate Options', 'post-duplicatex'); ?></h2>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php esc_html_e('Duplicate Post Status', 'post-duplicatex'); ?></th>
                                <td>
                                    <select name="pdx_post_status" id="pdx_post_status">
                                        <option value="draft" <?php selected($post_status, 'draft'); ?>><?php esc_html_e('Draft', 'post-duplicatex'); ?></option>
                                        <option value="publish" <?php selected($post_status, 'publish'); ?>><?php esc_html_e('Published', 'post-duplicatex'); ?></option>
                                        <option value="private" <?php selected($post_status, 'private'); ?>><?php esc_html_e('Private', 'post-duplicatex'); ?></option>
                                        <option value="pending" <?php selected($post_status, 'pending'); ?>><?php esc_html_e('Pending', 'post-duplicatex'); ?></option>
                                    </select>
                                    <p class="description"><?php esc_html_e('What status should the duplicated post have?', 'post-duplicatex'); ?></p>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php esc_html_e('Redirection', 'post-duplicatex'); ?></th>
                                <td>
                                    <select name="pdx_redirect" id="pdx_redirect">
                                        <option value="to_edit" <?php selected($redirect, 'to_edit'); ?>><?php esc_html_e('To edit screen', 'post-duplicatex'); ?></option>
                                        <option value="to_list" <?php selected($redirect, 'to_list'); ?>><?php esc_html_e('To posts list', 'post-duplicatex'); ?></option>
                                        <option value="to_new" <?php selected($redirect, 'to_new'); ?>><?php esc_html_e('To new post', 'post-duplicatex'); ?></option>
                                    </select>
                                    <p class="description"><?php esc_html_e('Where to redirect after clicking the duplicate link.', 'post-duplicatex'); ?></p>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php esc_html_e('Duplicate Link Title', 'post-duplicatex'); ?></th>
                                <td>
                                    <input type="text" name="pdx_link_title" id="pdx_link_title" value="<?php echo esc_attr($link_title); ?>" class="regular-text">
                                    <p class="description"><?php esc_html_e('Text for the duplicate link.', 'post-duplicatex'); ?></p>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php esc_html_e('Post Prefix', 'post-duplicatex'); ?></th>
                                <td>
                                    <input type="text" name="pdx_post_prefix" id="pdx_post_prefix" value="<?php echo esc_attr($post_prefix); ?>" class="regular-text">
                                    <p class="description"><?php esc_html_e('Add this text before the original title.', 'post-duplicatex'); ?></p>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php esc_html_e('Post Suffix', 'post-duplicatex'); ?></th>
                                <td>
                                    <input type="text" name="pdx_post_suffix" id="pdx_post_suffix" value="<?php echo esc_attr($post_suffix); ?>" class="regular-text">
                                    <p class="description"><?php esc_html_e('Add this text after the original title.', 'post-duplicatex'); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <?php submit_button(esc_html__('Save Settings', 'post-duplicatex'), 'primary', 'submit', true); ?>
                </div>

                <div class="pdx-admin-sidebar">
                    <div class="pdx-admin-sidebar-widget">
                        <h3><?php esc_html_e('About Post DuplicateX', 'post-duplicatex'); ?></h3>
                        <p><?php esc_html_e('Post DuplicateX allows you to duplicate any post, page or custom post type with a single click.', 'post-duplicatex'); ?></p>
                        <p><strong><?php esc_html_e('Version:', 'post-duplicatex'); ?></strong> <?php echo esc_html(POST_DUPLICATEX_VERSION); ?></p>
                    </div>
                    
                    <div class="pdx-admin-sidebar-widget">
                        <h3><?php esc_html_e('Rate Us', 'post-duplicatex'); ?></h3>
                        <p><?php esc_html_e('If you find this plugin useful, please consider rating it.', 'post-duplicatex'); ?></p>
                        <a href="https://wordpress.org/support/plugin/post-duplicatex/reviews/#new-post" target="_blank" class="button button-primary"><?php esc_html_e('Rate on WordPress.org', 'post-duplicatex'); ?></a>
                    </div>
                    
                    <div class="pdx-admin-sidebar-widget">
                        <h3><?php esc_html_e('Need Support?', 'post-duplicatex'); ?></h3>
                        <p><?php esc_html_e('If you need help with the plugin, please visit our support forum.', 'post-duplicatex'); ?></p>
                        <a href="https://wordpress.org/support/plugin/post-duplicatex/" target="_blank" class="button"><?php esc_html_e('Get Support', 'post-duplicatex'); ?></a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>