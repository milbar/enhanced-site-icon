<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             0.0.1
 * @package           Enhanced_Site_Icon
 *
 * @wordpress-plugin
 * Plugin Name:       MilBar Enhanced Site Icon
 * Plugin URI:        https://milbar.eu/
 * Description:       A simple WordPress plugin to add Dark Site Icon (aka: favicon) support
 * Version:           0.0.1
 * Author:            Milan Bartalovics
 * Plugin URI:        https://milbar.eu/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       enhanced-site-icon
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 0.0.1 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('PLUGIN_NAME_VERSION', '0.0.1');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-enhanced-site-icon-activator.php
 */
function activate_enhanced_site_icon()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-enhanced-site-icon-activator.php';
    Enhanced_Site_Icon_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-enhanced-site-icon-deactivator.php
 */
function deactivate_enhanced_site_icon()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-enhanced-site-icon-deactivator.php';
    Enhanced_Site_Icon_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_enhanced_site_icon');
register_deactivation_hook(__FILE__, 'deactivate_enhanced_site_icon');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-enhanced-site-icon.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.0.1
 */
function run_enhanced_site_icon()
{

    $plugin = new Enhanced_Site_Icon();
    $plugin->run();

}

run_enhanced_site_icon();
