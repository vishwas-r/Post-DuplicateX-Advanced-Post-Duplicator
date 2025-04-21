
<?php
/**
 * Plugin Name: Post DuplicateX - Advanced Post Duplicator
 * Plugin URI: https://github.com/vishwas-r/post-duplicatex
 * Description: Duplicate posts, pages, and custom post types easily with a single click and save with your preferred status.
 * Version: 1.0.0
 * Author: Vishwas R
 * Author URI: https://vishwas.me/
 * License: GPL-3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: post-duplicatex
 * Domain Path: /languages
 */

if (!defined('WPINC')) {
    die;
}

define('POST_DUPLICATEX_VERSION', '1.0.0');
define('POST_DUPLICATEX_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('POST_DUPLICATEX_PLUGIN_URL', plugin_dir_url(__FILE__));

register_activation_hook(__FILE__, 'activate_post_duplicatex');
register_deactivation_hook(__FILE__, 'deactivate_post_duplicatex');

function activate_post_duplicatex() {
    require_once POST_DUPLICATEX_PLUGIN_DIR . 'includes/class-post-duplicatex-activator.php';
    Post_DuplicateX_Activator::activate();
}

function deactivate_post_duplicatex() {
    require_once POST_DUPLICATEX_PLUGIN_DIR . 'includes/class-post-duplicatex-deactivator.php';
    Post_DuplicateX_Deactivator::deactivate();
}

require POST_DUPLICATEX_PLUGIN_DIR . 'includes/class-post-duplicatex.php';

function run_post_duplicatex() {
    $plugin = new Post_DuplicateX();
    $plugin->run();
}

run_post_duplicatex();