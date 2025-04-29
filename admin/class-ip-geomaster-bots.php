<?php
// No direct access allowed
if (!defined('ABSPATH')) exit;
 
class IP_GeoMaster_Bots {


    public static $BOTS;

    public static function init() {
        self::$BOTS = array(
            'banned_bots' => self::load_bots('bad_bots.json'),
            'allowed_bots' => self::load_bots('good_bots.json')
        );
    }

    private static function load_bots($file) {
        $path = plugin_dir_path(__FILE__) . '../web-crawlers/' . $file;

        if (file_exists($path)) {
            $json = file_get_contents($path);
            $data = json_decode($json, true);
            return is_array($data) ? $data : [];
        }

        return [];
    }


    public static function update_bots($bad_bots, $good_bots) {

        
        if ( ! function_exists( 'get_filesystem_method' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
        }

        
        $credentials = request_filesystem_credentials( site_url() );
        if ( ! WP_Filesystem( $credentials ) ) {
            wp_die( 'Unable to initialize filesystem' );
        }

        global $wp_filesystem;

        // Dekodiranje JSON-a
        $decoded_bad_bots_data = json_decode($bad_bots, true);
        $decoded_good_bots_data = json_decode($good_bots, true);
    
       
        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['status' => 'error', 'message' => 'Invalid JSON format'];
        }
    
        
        $bad_bots_file_path = plugin_dir_path(__FILE__) . '../web-crawlers/bad_bots.json';
        $good_bots_file_path = plugin_dir_path(__FILE__) . '../web-crawlers/good_bots.json';
    
       
        if (!file_exists($bad_bots_file_path) || !file_exists($good_bots_file_path)) {
            return ['status' => 'error', 'message' => 'One or both JSON files do not exist'];
        }
    
       
        if ( ! $wp_filesystem->is_writable( $bad_bots_file_path ) || ! $wp_filesystem->is_writable( $good_bots_file_path ) ) {
            return ['status' => 'error', 'message' => 'Permission denied for one or both JSON files. <br><br> <b>Please change the permissions of the ip-geomaster plugin files to 644 or 755.</b>'];

        }
        
        $bad_bots_written = file_put_contents($bad_bots_file_path, json_encode($decoded_bad_bots_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $good_bots_written = file_put_contents($good_bots_file_path, json_encode($decoded_good_bots_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    
        
        if ($bad_bots_written === false || $good_bots_written === false) {
            wp_mail( get_option( 'admin_email' ), 'Failed to write to one or both JSON files' );
            return ['status' => 'error', 'message' => 'Failed to write to one or both JSON files'];
        }
    
        return ['status' => 'success', 'message' => 'Both JSON files updated successfully'];
    }

    /**
     * Retrieves the list of banned bots
     *
     * @return array
     */

    public static function getBadBots() {
        $file_path = plugin_dir_path(__FILE__) . '../web-crawlers/bad_bots.json';
        if (file_exists($file_path)) {
            $json = file_get_contents($file_path);
            return json_decode($json, true);
        }
        return [];
    }

     /**
     * Returns a list of good bots from a JSON file.
     *
     * @return array
     */
    public static function getGoodBots() {
        $file_path = plugin_dir_path(__FILE__) . '../web-crawlers/good_bots.json';
        if (file_exists($file_path)) {
            $json = file_get_contents($file_path);
            return json_decode($json, true);
        }
        return [];
    }
    

}

IP_GeoMaster_Bots::init();