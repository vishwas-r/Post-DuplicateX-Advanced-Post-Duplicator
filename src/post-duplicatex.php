
<?php
/**
 * Plugin Name: Post DuplicateX - Advanced Post Duplicator
 * Plugin URI: https://github.com/vishwas-r/Post-DuplicateX-Advanced-Post-Duplicator
 * Description: Duplicate posts, pages, and custom post types easily with a single click and save with your preferred status.
 * Version: 1.0.0
 * Author: Vishwas R
 * Author URI: https://vishwas.me/
 * License: GPL-3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: post-duplicatex
 */

if (!defined('WPINC')) {
    die;
}

define('POSTDX51_VERSION', '1.0.0');
define('POSTDX51_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('POSTDX51_PLUGIN_URL', plugin_dir_url(__FILE__));

register_activation_hook(__FILE__, 'postdx51_activate');
register_deactivation_hook(__FILE__, 'postdx51_deactivate');

function postdx51_activate() {
    require_once POSTDX51_PLUGIN_DIR . 'includes/class-post-duplicatex-activator.php';
    PostDX51_Activator::activate();
}

function postdx51_deactivate() {
    require_once POSTDX51_PLUGIN_DIR . 'includes/class-post-duplicatex-deactivator.php';
    PostDX51_Deactivator::deactivate();
}

require POSTDX51_PLUGIN_DIR . 'includes/class-post-duplicatex.php';

function postdx51_run() {
    $plugin = new PostDX51();
    $plugin->run();
}

postdx51_run();