<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://ipgeomaster.icodes.rocks
 * @since             1.0.0
 * @package           Ip_Geomaster
 *
 * @wordpress-plugin
 * Plugin Name:       IP Geomaster
 * Plugin URI:        https://ipgeomaster.icodes.rocks/
 * Description:       The Fastest IP Geolocation Service on the Web. Find out where your website’s visitors are coming from. Detect potential security threats. Easily optimize for user experience.
 * Version:           1.0.0
 * Author:            IP Geomaster
 * Author URI:        https://github.com/srkis
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Requires PHP: 7.0
 * Requires at least: 5.8
 * Tested up to: 6.5
 * Text Domain:       ip-geomaster
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'IP_GEOMASTER_VERSION', '1.0.0' );

//Define constant for the path to the root folder of the plugin
if (!defined('IP_GEOMASTER_ROOT_PATH')) {
    define('IP_GEOMASTER_ROOT_PATH', plugin_dir_path(__FILE__));
}

// Define constant for the URL to the root folder of the plugin
if (!defined('IP_GEOMASTER_ROOT_URL')) {
    define('IP_GEOMASTER_ROOT_URL', plugin_dir_url(__FILE__));
}

if (!defined('IP_GEOMASTER_PLUGIN_NAME')) {
    define('IP_GEOMASTER_PLUGIN_NAME', 'ip-geomaster');
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */

function Ip_Geomaster_activate_plugin() {

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ip-geomaster-activator.php';
	Ip_Geomaster_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function Ip_Geomaster_deactivate_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ip-geomaster-deactivator.php';
	Ip_Geomaster_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'Ip_Geomaster_activate_plugin' );
register_deactivation_hook( __FILE__, 'Ip_Geomaster_deactivate_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ip-geomaster.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function Ip_Geomaster_run_plugin() {

	$plugin = new Ip_Geomaster();
	$plugin->run();

}
Ip_Geomaster_run_plugin();