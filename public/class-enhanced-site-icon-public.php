<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      0.0.1
 *
 * @package    Enhanced_Site_Icon
 * @subpackage Enhanced_Site_Icon/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Enhanced_Site_Icon
 * @subpackage Enhanced_Site_Icon/public
 * @author     Your Name <email@example.com>
 */
class Enhanced_Site_Icon_Public
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
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     * @since    0.0.1
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
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

        wp_enqueue_script('favicon-switcher', plugin_dir_url(__FILE__) . 'src/favicon-switcher.js', array('jquery'), '1.2.2', false);
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/enhanced-site-icon.js', array('jquery', 'favicon-switcher'), $this->version, false);
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
        $plugin = new Enhanced_Site_Icon();
        return $plugin->get_esi_option($option_name, $default);
    }

    /**
     * Add custom meta tags to head
     */
    public function extend_meta_tags($meta_tags)
    {
        $site_icon = get_option('site_icon');
        $site_icon_dark = $this->get_esi_option('site_icon_dark', 0);
        $site_color = $this->get_esi_option('site_color', '#FFFFFF');

        if ($site_icon && function_exists('fly_get_attachment_image_src')) {
            $site_icon_mime_type = get_post_mime_type($site_icon);

            // MS APPLICATION META
            $ms_icons = [
                [144, 144, 'TileImage'],
            ];
            $ms_format = '<meta name="%s" content="%s" />';
            foreach ($ms_icons as $ms_icon) {
                $meta_tags[] = sprintf($ms_format,
                    'msapplication-' . $ms_icon[2],
                    esc_url(fly_get_attachment_image_src($site_icon, [
                        $ms_icon[0],
                        $ms_icon[1]
                    ], false)['src']));
            }
            $meta_tags[] = sprintf('<meta name="application-name" content="%s" />', get_bloginfo('name'));
            $meta_tags[] = sprintf('<meta name="msapplication-TileColor" content="%s" />', '#FFFFFF');

            // APPLE ICONS
            $icon_sizes_apple = [
                [57, 57],
                [60, 60],
                [72, 72],
                [76, 76],
                [114, 114],
                [120, 120],
                [144, 144],
                [152, 152],
                [180, 180],
            ];
            $apple_format = '<link media="%s" rel="apple-touch-icon" href="%s" sizes="%s" />';

            foreach ($icon_sizes_apple as $icon_size) {
                if (!empty($site_icon_dark)) {
                    $meta_tags[] = sprintf($apple_format, '(prefers-color-scheme:dark)',
                        esc_url(fly_get_attachment_image_src($site_icon_dark, $icon_size, false)['src']),
                        $icon_size[0] . 'x' . $icon_size[1]
                    );
                }

                $meta_tags[] = sprintf($apple_format, '(prefers-color-scheme:light)',
                    esc_url(fly_get_attachment_image_src($site_icon, $icon_size, false)['src']),
                    $icon_size[0] . 'x' . $icon_size[1]
                );
            }

            // ICONS
            $icon_sizes = [
                [192, 192],
                [32, 32],
                [96, 96],
                [16, 16],
            ];
            $icons_format = '<link media="%s" rel="icon" type="%s" href="%s" sizes="%s" />';

            foreach ($icon_sizes as $icon_size) {
                if (!empty($site_icon_dark)) {
                    $meta_tags[] = sprintf($icons_format, '(prefers-color-scheme:dark)',
                        $site_icon_mime_type,
                        esc_url(fly_get_attachment_image_src($site_icon_dark, $icon_size, false)['src']),
                        $icon_size[0] . 'x' . $icon_size[1]
                    );
                }

                $meta_tags[] = sprintf($icons_format, '(prefers-color-scheme:light)',
                    $site_icon_mime_type,
                    esc_url(fly_get_attachment_image_src($site_icon, $icon_size, false)['src']),
                    $icon_size[0] . 'x' . $icon_size[1]
                );
            }
            $meta_tags[] = sprintf('<meta name="theme-color" content="%s" />', $site_color);
        }

        return $meta_tags;
    }
}
