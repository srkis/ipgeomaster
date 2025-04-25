<?php
/**
 * Fired during plugin activation
 *
 * @link       https://ipgeomaster.icodes.rocks
 * @since      1.0.0
 *
 * @package    Ip_Geomaster
 * @subpackage Ip_Geomaster/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Ip_Geomaster
 * @subpackage Ip_Geomaster/includes
 * @author     ipgeomaster <ipgeomaster@gmail.com>
 */
class Ip_Geomaster_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
    public static function activate() {
        global $wpdb;
    
        $table_name = $wpdb->prefix . 'ip_geomaster_blocked';
        
        $sql = "CREATE TABLE $table_name (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id BIGINT(20) UNSIGNED NOT NULL,
            type ENUM('countries', 'bots', 'ips') NOT NULL,
            country_msg TEXT NOT NULL DEFAULT 'Content is not available in your country.',
            ips_msg TEXT NOT NULL DEFAULT 'Your IP address is not allowed to access this content.',
            blocked_data TEXT NOT NULL,
            bots_msg TEXT NOT NULL DEFAULT '403 Forbidden. Bots are not allowed to access this content.',
            mode ENUM('on', 'off') NOT NULL,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    
        // Get dbDelta function
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    
        // Create table with dbDelta
        dbDelta($sql);
    
        // check if table was created
        if ($wpdb->last_error) {
            wp_die( 'Error creating table: ' . esc_html( $wpdb->last_error ) );
        }
    
        // Check if there are any records in the table (if not, insert default data)
        $exists = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE type = 'countries' OR type = 'bots'" );
    
        if ( $exists == 0 ) {
            // Inserting default data
            $wpdb->insert(
                $table_name,
                array(
                    'user_id' => 0, // 0 = default
                    'type' => 'countries',
                    'blocked_data' => '',
                    'mode' => 'on'
                )
            );
    
            $wpdb->insert(
                $table_name,
                array(
                    'user_id' => 0,
                    'type' => 'bots',
                    'blocked_data' => '',
                    'mode' => 'off'
                )
            );

            $wpdb->insert(
                $table_name,
                array(
                    'user_id' => 0,
                    'type' => 'ips',
                    'blocked_data' => '',
                    'mode' => 'off'
                )
            );
        }
    }
    

}