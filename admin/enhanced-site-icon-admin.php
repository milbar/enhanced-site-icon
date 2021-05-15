<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
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
 * @author     Your Name <email@example.com>
 */
class Enhanced_Site_Icon_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    0.0.1
     * @access   private
     * @var      string $enhanced_site_icon The ID of this plugin.
     */
    private $enhanced_site_icon;

    /**
     * The version of this plugin.
     *
     * @since    0.0.1
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $enhanced_site_icon The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    0.0.1
     */
    public function __construct($enhanced_site_icon, $version)
    {

        $this->enhanced_site_icon = $enhanced_site_icon;
        $this->version = $version;

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

        wp_enqueue_style($this->enhanced_site_icon, plugin_dir_url(__FILE__) . 'css/enhanced-site-icon.css', array(), $this->version, 'all');

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

        wp_enqueue_script($this->enhanced_site_icon, plugin_dir_url(__FILE__) . 'js/enhanced-site-icon.js', array('jquery'), $this->version, false);

    }

}
