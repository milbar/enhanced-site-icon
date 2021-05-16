<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://milbar.eu
 * @since      0.0.1
 *
 * @package    Enhanced_Site_Icon
 * @subpackage Enhanced_Site_Icon/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Enhanced_Site_Icon
 * @subpackage Enhanced_Site_Icon/admin
 * @author     Milan Bartalovics <develop@milbar.eu>
 */
class Enhanced_Site_Icon_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    0.0.1
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    0.0.1
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Options Group slug of the plugin.
     *
     * @since    0.0.1
     * @access   protected
     * @var      string $esi_plugin_option Options Group slug of the plugin.
     */
    private $esi_plugin_option;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    0.0.1
     */
    public function __construct($plugin_name, $version, $esi_plugin_option)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->esi_plugin_option = $esi_plugin_option;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    0.0.1
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Enhanced_Site_Icon_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Enhanced_Site_Icon_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/enhanced-site-icon.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    0.0.1
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Enhanced_Site_Icon_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Enhanced_Site_Icon_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/enhanced-site-icon.js', array('jquery', 'wp-color-picker'), $this->version, false);

    }

    /**
     * Update WordPress site_icon option with plugin default
     */
    public function esi_options_save($old_value, $new_value)
    {
        if (isset($new_value['site_icon'])) {
            update_option('site_icon', $new_value['site_icon']);
        }
    }

    /**
     * Update Plugin site_icon when customizer save
     */
    public function esi_update_site_icon()
    {
        $site_icon = get_option('site_icon');
        $plugin_options = get_option($this->esi_plugin_option);
        $plugin_options['site_icon'] = $site_icon;

        update_option($this->esi_plugin_option, $plugin_options);
    }
}
