
<?php

class Post_DuplicateX {
    protected $loader;
    protected $plugin_name;
    protected $version;

    public function __construct() {
        $this->version = POST_DUPLICATEX_VERSION;
        $this->plugin_name = 'post-duplicatex';
        
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    private function load_dependencies() {
        require_once POST_DUPLICATEX_PLUGIN_DIR . 'includes/class-post-duplicatex-loader.php';
        require_once POST_DUPLICATEX_PLUGIN_DIR . 'admin/class-post-duplicatex-admin.php';
        require_once POST_DUPLICATEX_PLUGIN_DIR . 'public/class-post-duplicatex-public.php';

        $this->loader = new Post_DuplicateX_Loader();
    }

    private function set_locale() {
        add_action('plugins_loaded', function() {
            load_plugin_textdomain(
                'post-duplicatex',
                false,
                dirname(plugin_basename(POST_DUPLICATEX_PLUGIN_DIR)) . '/languages/'
            );
        });
    }

    private function define_admin_hooks() {
        $plugin_admin = new Post_DuplicateX_Admin($this->get_plugin_name(), $this->get_version());
        
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_plugin_admin_menu');
        $this->loader->add_action('admin_init', $plugin_admin, 'register_plugin_settings');
        
        // Add duplicate links based on settings
        $this->loader->add_action('admin_init', $plugin_admin, 'add_duplicate_post_links');
        
        // Handle duplicate post action
        $this->loader->add_action('admin_action_pdx_duplicate_post', $plugin_admin, 'duplicate_post_action');
        
        // Add links to plugins page
        $this->loader->add_filter('plugin_action_links_' . plugin_basename(POST_DUPLICATEX_PLUGIN_DIR . 'post-duplicatex.php'), $plugin_admin, 'add_action_links');
    }

    private function define_public_hooks() {
         $plugin_public = new Post_DuplicateX_Public($this->get_plugin_name(), $this->get_version());
        
         $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
         $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

         $this->loader->add_action('admin_bar_menu', $plugin_public, 'add_duplicate_admin_bar_link', 90);
    }

    public function run() {
        $this->loader->run();
    }

    public function get_plugin_name() {
        return $this->plugin_name;
    }

    public function get_loader() {
        return $this->loader;
    }

    public function get_version() {
        return $this->version;
    }
}