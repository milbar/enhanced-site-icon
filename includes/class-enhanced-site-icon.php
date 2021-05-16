<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://milbar.eu
 * @since      0.0.1
 *
 * @package    Enhanced_Site_Icon
 * @subpackage Enhanced_Site_Icon/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      0.0.1
 * @package    Enhanced_Site_Icon
 * @subpackage Enhanced_Site_Icon/includes
 * @author     Milan Bartalovics <develop@milbar.eu>
 */
class Enhanced_Site_Icon
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    0.0.1
     * @access   protected
     * @var      Enhanced_Site_Icon_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    0.0.1
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    0.0.1
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * The main page title of the plugin.
     *
     * @since    0.0.1
     * @access   protected
     * @var      string $main_page_title The main page title of the plugin.
     */
    protected $main_page_title;

    /**
     * Options Group slug of the plugin.
     *
     * @since    0.0.1
     * @access   protected
     * @var      string $esi_plugin_option Options Group slug of the plugin.
     */
    protected $esi_plugin_option;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    0.0.1
     */
    public function __construct()
    {
        if (defined('PLUGIN_NAME_VERSION')) {
            $this->version = PLUGIN_NAME_VERSION;
        } else {
            $this->version = '0.0.1';
        }
        $this->plugin_name = 'enhanced-site-icon';
        $this->main_page_title = sprintf('%s - %s', __('Enhanced Site Icon', 'enhanced-site-icon'), __('Settings'));
        $this->esi_plugin_option = 'mb_esi_plugin_options';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Enhanced_Site_Icon_Loader. Orchestrates the hooks of the plugin.
     * - Enhanced_Site_Icon_i18n. Defines internationalization functionality.
     * - Enhanced_Site_Icon_Admin. Defines all hooks for the admin area.
     * - Enhanced_Site_Icon_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    0.0.1
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-enhanced-site-icon-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-enhanced-site-icon-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-enhanced-site-icon-admin.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/includes/class-enhanced-site-icon-settings.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-enhanced-site-icon-public.php';

        $this->loader = new Enhanced_Site_Icon_Loader();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Enhanced_Site_Icon_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    0.0.1
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new Enhanced_Site_Icon_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    0.0.1
     * @access   private
     */
    private function define_admin_hooks()
    {

        $plugin_admin = new Enhanced_Site_Icon_Admin($this->get_plugin_name(), $this->get_version(), $this->get_option_slug());
        $plugin_settings = new Enhanced_Site_Icon_Settings($this->get_plugin_name(), $this->get_version(), $this->get_main_page_title(), $this->get_option_slug());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('update_option_' . $this->esi_plugin_option . '', $plugin_admin, 'esi_options_save', 10, 2);
        $this->loader->add_action('customize_save_after', $plugin_admin, 'esi_update_site_icon');
        $this->loader->add_action('admin_menu', $plugin_settings, 'add_theme_page');
        $this->loader->add_action('admin_init', $plugin_settings, 'mb_esi_settings_init');

    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    0.0.1
     * @access   private
     */
    private function define_public_hooks()
    {

        $plugin_public = new Enhanced_Site_Icon_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('wp_head', $plugin_public, 'esi_print_scripts');

    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    0.0.1
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @return    string    The name of the plugin.
     * @since     0.0.1
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return    Enhanced_Site_Icon_Loader    Orchestrates the hooks of the plugin.
     * @since     0.0.1
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return    string    The version number of the plugin.
     * @since     0.0.1
     */
    public function get_version()
    {
        return $this->version;
    }

    /**
     * Retrieve the main page title of the plugin.
     *
     * @return    string    The version number of the plugin.
     * @since     0.0.1
     */
    public function get_main_page_title()
    {
        return $this->main_page_title;
    }

    /**
     * Retrieve the option group slug of the plugin.
     *
     * @return    string    The version number of the plugin.
     * @since     0.0.1
     */
    public function get_option_slug()
    {
        return $this->esi_plugin_option;
    }

    /**
     * Retrieve the the given option from the plugin options group.
     *
     * @param string $option_name
     * @param string|int $default
     * @return mixed|string|int
     */
    public function get_esi_option($option_name = '', $default = '')
    {

        if (empty($option_name)) {
            return $default;
        }

        $option = get_option($this->get_option_slug());

        if (!isset($option[$option_name]) || empty($option[$option_name])) {
            return $default;
        }

        return $option[$option_name];
    }

}
