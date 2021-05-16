<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://milbar.eu
 * @since      0.0.1
 *
 * @package    Enhanced_Site_Icon
 * @subpackage Enhanced_Site_Icon/admin/includes
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Enhanced_Site_Icon
 * @subpackage Enhanced_Site_Icon/admin/includes
 * @author     Milan Bartalovics <develop@milbar.eu>
 */
class Enhanced_Site_Icon_Settings
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
     * The main page title of the plugin.
     *
     * @since    0.0.1
     * @access   protected
     * @var      string $main_page_title The main page title of the plugin.
     */
    private $main_page_title;

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
     * @param string $main_page_title The main page title of the plugin.
     * @since    0.0.1
     */
    public function __construct($plugin_name, $version, $main_page_title, $esi_plugin_option)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->main_page_title = $main_page_title;
        $this->esi_plugin_option = $esi_plugin_option;
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

    public function add_theme_page()
    {
        $page = add_theme_page(
            $this->main_page_title,
            __('Site Icon'),
            'manage_options',
            'esi-settings',
            array($this, 'esi_settings_page')
        );
    }

    public function mb_esi_settings_init()
    {
        register_setting('mb_esi_plugin', $this->esi_plugin_option);

        add_settings_section(
            'mb_esi_main_section',
            __('Light Color Scheme', 'enhanced-site-icon'),
            '',
            'mb_esi_plugin'
        );
        add_settings_section(
            'mb_esi_dark_section',
            __('Dark Color Scheme', 'enhanced-site-icon'),
            '',
            'mb_esi_plugin'
        );

        add_settings_field(
            "site_icon",
            __('Site Icon'),
            array($this, 'site_icon_render'),
            'mb_esi_plugin',
            'mb_esi_main_section',
            array('name' => 'site_icon')
        );

        add_settings_field(
            "site_icon_dark",
            sprintf('%s %s', __('Dark Theme', 'enhanced-site-icon'), __('Site Icon')),
            array($this, 'site_icon_render'),
            'mb_esi_plugin',
            'mb_esi_dark_section',
            array('name' => 'site_icon_dark')
        );

        add_settings_field(
            "site_color",
            __('Color Scheme', 'enhanced-site-icon'),
            array($this, 'color_picker_render'),
            'mb_esi_plugin',
            'mb_esi_main_section',
            array('name' => 'site_color')
        );

        add_settings_field(
            "site_color_dark",
            sprintf('%s %s', __('Dark Theme', 'enhanced-site-icon'), __('Color Scheme', 'enhanced-site-icon')),
            array($this, 'color_picker_render'),
            'mb_esi_plugin',
            'mb_esi_dark_section',
            array('name' => 'site_color_dark')
        );
    }

    public function esi_settings_page()
    {
        ?>

        <div class="wrap">
            <h2><?php echo $this->main_page_title; ?></h2>
            <?php settings_errors(); ?>
            <form action='options.php' method='post'>
                <?php
                settings_fields('mb_esi_plugin');
                do_settings_sections('mb_esi_plugin');
                submit_button();
                ?>

            </form>
        </div>

        <?php
    }

    function site_icon_render($args)
    {
        wp_enqueue_media();

        $name = $args['name'];
        $show_delete = false;
        $name_arg = sprintf('%s[%s]', $this->esi_plugin_option, $name);

        $site_icon = $this->get_esi_option($name, 0);
        if ($site_icon) {
            $show_delete = true;
        }

        ?>
        <img class="upload_preview" <?php echo empty($site_icon) ? 'style="display:none;"' : ''; ?>
             src="<?php echo wp_get_attachment_thumb_url($site_icon); ?>"/>
        <input type="hidden" class="upload_field" name="<?php echo $name_arg ?>"
               id="<?php echo $name ?>"
               value="<?php echo $site_icon; ?>"/>
        <input id="<?php echo $name ?>_button_upload" type="button" class="button upload_button"
               value="<?php _e('Upload'); ?>"/>
        <input id="<?php echo $name ?>_button_clear" type="button"
               class="button button-danger upload_clear <?php echo !$show_delete ? 'disabled' : ''; ?>"
               value="<?php _e('Delete'); ?>"/>
    <?php }

    function color_picker_render($args)
    {
        $name = $args['name'];
        $value = $this->get_esi_option($name);
        $name_arg = sprintf('%s[%s]', $this->esi_plugin_option, $name);
        ?>

        <input class="esi-color-picker" type='text' name='<?php echo $name_arg ?>'
               value='<?php echo $value; ?>'>
        <?php
    }

    /**
     * Add custom meta tags to head
     */
    function yco_site_icon_meta_tags($meta_tags)
    {
        $site_icon = get_option('site_icon');

        if ($site_icon and function_exists('fly_get_attachment_image_src')) {
            $site_icon_mime_type = get_post_mime_type($site_icon);

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
            ];
            $apple_format = '<link rel="apple-touch-icon" href="%s" sizes="%s" />';

            foreach ($icon_sizes_apple as $icon_size) {
                $meta_tags[] = sprintf($apple_format,
                    esc_url(fly_get_attachment_image_src($site_icon, $icon_size, true)['src']),
                    $icon_size[0] . 'x' . $icon_size[1]
                );
            }

            // ICONS
            $icon_sizes = [
                [16, 16],
                [32, 32],
                [96, 96],
                [128, 128],
                [196, 196],
            ];
            $icons_format = '<link rel="icon" type="%s" href="%s" sizes="%s" />';

            foreach ($icon_sizes as $icon_size) {
                $meta_tags[] = sprintf($icons_format,
                    $site_icon_mime_type,
                    esc_url(fly_get_attachment_image_src($site_icon, $icon_size, true)['src']),
                    $icon_size[0] . 'x' . $icon_size[1]
                );
            }

            // MS APPLICATION META
            $ms_icons = [
                [144, 144, 'TileImage'],
                [70, 70, 'square70x70logo'],
                [150, 150, 'square150x150logo'],
                [310, 310, 'square310x310logo'],
                [310, 150, 'wide310x150logo']
            ];
            $ms_format = '<meta name="%s" content="%s" />';

            $meta_tags[] = sprintf('<meta name="application-name" content="%s" />', get_bloginfo());
            $meta_tags[] = sprintf('<meta name="msapplication-TileColor" content="%s" />', '#FFFFFF');
            foreach ($ms_icons as $ms_icon) {
                $meta_tags[] = sprintf($ms_format,
                    'msapplication-' . $ms_icon[2],
                    esc_url(fly_get_attachment_image_src($site_icon, [
                        $ms_icon[0],
                        $ms_icon[1]
                    ], true)['src']));
            }
        }

        return $meta_tags;
    }

}
