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
     * The main page title of the plugin.
     *
     * @since    0.0.1
     * @access   protected
     * @var      string $main_page_title The main page title of the plugin.
     */
    private $main_page_title;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $enhanced_site_icon The name of this plugin.
     * @param string $version The version of this plugin.
     * @param string $main_page_title The main page title of the plugin.
     * @since    0.0.1
     */
    public function __construct($enhanced_site_icon, $version, $main_page_title)
    {

        $this->enhanced_site_icon = $enhanced_site_icon;
        $this->version = $version;
        $this->main_page_title = $main_page_title;

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

        add_action('admin_print_scripts-' . $page, array($this, 'esi_settings_page_enqueue_assets'));
    }

    public function mb_esi_settings_init()
    {
        register_setting('mb_esi_plugin', 'mb_esi_plugin_options');

        add_settings_section(
            'mb_esi_main_section',
            '',
            '',
            'mb_esi_plugin'
        );

        add_settings_field(
            "site_icon",
            __('Site Icon'),
            array($this, 'site_icon_render'),
            'mb_esi_plugin',
            'mb_esi_main_section'
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

    // enqueue needed assets (for tinymce actualy)
    function esi_settings_page_enqueue_assets()
    {
        $options = get_option('mb_esi_plugin_options');
        $site_icon = $options['site_icon'];

        ?>
        <script type='text/javascript'>
            window.MilBar = window.MilBar || {};
            window.MilBar.currentImgID = <?php echo $site_icon; ?>;

        </script><?php

    }

    function site_icon_render()
    {
        wp_enqueue_media();

        $options = get_option('mb_esi_plugin_options');
        $site_icon = $options['site_icon'];
        ?>
        <img class="upload_preview" <?php echo empty($site_icon) ? 'style="display:none;"' : ''; ?>
             src="<?php echo wp_get_attachment_thumb_url($site_icon); ?>"/>
        <input type="hidden" class="upload_field" name="mb_esi_plugin_options[site_icon]" id="site_icon"
               value="<?php echo $site_icon; ?>"/>
        <input id="site_icon_button_upload" type="button" class="button upload_button" value="<?php _e('Select'); ?>"/>
        <input id="site_icon_button_clear" type="button" class="button upload_clear" value="<?php _e('Delete'); ?>"/>
    <?php }
}
