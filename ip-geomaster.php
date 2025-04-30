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
 * 
 /**
 * Plugin Name:       IP Geomaster
 * Plugin URI:        https://ipgeomaster.icodes.rocks/
 * Description:       The fastest IP geolocation service for WordPress. Block countries, IPs, and bots to improve security and performance.
 * Version:           1.0.0
 * Author:            srkimafia
 * Author URI:        https://profiles.wordpress.org/srkimafia/
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Requires PHP:      7.0
 * Requires at least: 5.8
 * Tested up to:      6.5
 * Text Domain:       ip-geomaster
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/srkis/ipgeomaster
 *
 * This file is part of IP Geomaster.
 * Copyright (C) 2024 srkimafia <srki.bgd1@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
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