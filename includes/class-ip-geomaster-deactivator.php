<?php
/**
 * Fired during plugin deactivation
 *
 * @link        https://ipgeomaster.icodes.rocks
 * @since      1.0.0
 *
 * @package    Ip_Geomaster
 * @subpackage Ip_Geomaster/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Ip_Geomaster
 * @subpackage Ip_Geomaster/includes
 * @author     ipgeomaster <ipgeomaster@gmail.com>
 */
class Ip_Geomaster_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */


     public static function deactivate() {
        global $wpdb;
    
        $table_name = $wpdb->prefix . 'ip_geomaster_blocked';
    
        $sql = "DROP TABLE IF EXISTS {$table_name}";
    
        $wpdb->query($sql);

        $wpdb->query("DROP TRIGGER IF EXISTS set_default_mode");
    }
       

}