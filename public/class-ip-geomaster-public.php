<?php
require_once IP_GEOMASTER_ROOT_PATH . '/vendor/maxmind-db/src/MaxMind/Db/Reader.php';
require_once IP_GEOMASTER_ROOT_PATH . '/vendor/maxmind-db/src/MaxMind/Db/Reader/Decoder.php';
require_once IP_GEOMASTER_ROOT_PATH . '/vendor/maxmind-db/src/MaxMind/Db/Reader/InvalidDatabaseException.php';
require_once IP_GEOMASTER_ROOT_PATH . '/vendor/maxmind-db/src/MaxMind/Db/Reader/Util.php';
require_once IP_GEOMASTER_ROOT_PATH . '/vendor/maxmind-db/src/MaxMind/Db/Reader/Metadata.php';

/**
 * The public-facing functionality of the plugin.
 *
 * @link        https://ipgeomaster.icodes.rocks
 * @since      1.0.0
 *
 * @package    Ip_Geomaster
 * @subpackage Ip_Geomaster/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ip_Geomaster
 * @subpackage Ip_Geomaster/public
 * @author     ipgeomaster <ipgeomaster@gmail.com>
 */
class Ip_Geomaster_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ip-geomaster-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ip-geomaster-public.js', array( 'jquery' ), $this->version, false );
	}


	public function init() {

		if (!is_user_logged_in()) {
			$this->_checkBots();
			$this->_checkGeoLocation();
			$this->_checkIpAddress();
		 }

	}


	public function ip_geomaster_fetch_countries() {
		// Get the list of countries
		$countries = Ip_Geomaster_Country_List::COUNTRIES;
	
		// Get the banned countries
		
		global $wpdb;

		$table_name = esc_sql( $wpdb->prefix . 'ip_geomaster_blocked' );
		

		$cache_key = 'ip_geomaster_blocked_countries';

		$cached_row = wp_cache_get( $cache_key, 'ip_geomaster' );

		if ( false === $cached_row ) {

			$row = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT blocked_data, country_msg FROM `$table_name` WHERE type = %s LIMIT 1",
					'countries'
				)
			);

			if ( $row ) {
				wp_cache_set( $cache_key, $row, 'ip_geomaster', 3600 ); // Keširaj na 1 sat (3600 sekundi)
			}
		} else {
			
			$row = $cached_row;
		}

		$banned_countries = [];
		if ($row && !empty($row->blocked_data)) {
			$data = json_decode($row->blocked_data, true);
			$banned_codes = $data['countries']; // Niz oznaka banovanih država
	
			// Mapiraj oznake država u puna imena
			foreach ($banned_codes as $code) {
				if (isset($countries[$code])) {
					$banned_countries[$code] = $countries[$code]; // Dodaj puno ime države
				}
			}
		}
	
		// split the countries into available and banned
		$available_countries = [];
		foreach ($countries as $code => $name) {
			if (!isset($banned_countries[$code])) {
				$available_countries[$code] = $name; // Dodaj u available countries
			}
		}
	
		// Prepare the response
		$response = [
			'available_countries' => $available_countries,
			'banned_countries' => $banned_countries,
			'country_msg' => $row ? $row->country_msg : 'Content is not available in your country.'
		];
	
		// Send the response
		echo json_encode($response);
		wp_die();
	}


	public function ip_geomaster_fetch_bots() {

		global $wpdb;
		$table_name = esc_sql( $wpdb->prefix . 'ip_geomaster_blocked' );
		
		$row = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM `$table_name` WHERE type = %s LIMIT 1",
				'bots'
			)
		);

		// Prepare the response
		$response = [
			'banned_bots' => IP_GeoMaster_Bots::$BOTS['banned_bots'],
			'allowed_bots' => IP_GeoMaster_Bots::$BOTS['allowed_bots'],
			'bots_mode' => $row->mode,
			'bots_msg' => $row->bots_msg,
		];
	
		// Send the response
		echo json_encode($response);
		wp_die();
	}


	public function ip_geomaster_ban_bots() {

		global $wpdb;
		$table_name = esc_sql( $wpdb->prefix . 'ip_geomaster_blocked' );
		$user_id = get_current_user_id(); // ID of the current user

		$row = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM `$table_name` WHERE type = %s LIMIT 1",
				'bots'
			)
		);

		if (
			!isset($_POST['ip_geomaster_nonce_field']) || 
			!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ip_geomaster_nonce_field'])), 'ip-geomaster-ajax-nonce') // Trebalo bi da koristiš isti nonce kao u JavaScript-u
		) {
			wp_send_json_error('Invalid nonce.');
		}
		
		// Get data from the request
		
		$banned_bots = isset($_POST['banned_bots']) ? array_map('sanitize_text_field', wp_unslash($_POST['banned_bots'])) : [];
		$allowed_bots = isset($_POST['allowed_bots']) ? array_map('sanitize_text_field', wp_unslash($_POST['allowed_bots'])) : [];
		$bots_mode = isset($_POST['bots_mode']) ? sanitize_text_field(wp_unslash($_POST['bots_mode'])) : 'off';
		$bots_msg = isset($_POST['bots_msg']) ? sanitize_text_field(wp_unslash($_POST['bots_msg'])) : '403 Forbidden. Bots are not allowed to access this content.';

		$wpdb->update(
			$table_name,
			[
				'user_id' => $user_id,
				'bots_msg' => $bots_msg,
				'mode' => $bots_mode 
			],
			['id' => $row->id] 
		);
	

		// Prepare data for saving
		$bad_bots = json_encode($banned_bots);
		$good_bots = json_encode($allowed_bots);

		$msg_status = IP_GeoMaster_Bots::update_bots($bad_bots, $good_bots);

		if($msg_status['status'] == 'error') {
			wp_send_json_error($msg_status);
			
		}else{
			wp_send_json_success($msg_status);
		}

	}


	public function ip_geomaster_ban_countries() {

		if (
			!isset($_POST['ip_geomaster_nonce_field']) || 
			!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ip_geomaster_nonce_field'])), 'ip-geomaster-ajax-nonce') // Trebalo bi da koristiš isti nonce kao u JavaScript-u
		) {
			wp_send_json_error('Invalid nonce.');
		}
		
		// Get data from the request
		$user_id = get_current_user_id(); // ID of the current user

		$country_msg = isset($_POST['country_msg']) ? sanitize_text_field(wp_unslash($_POST['country_msg'])) : 'Content is not available in your country.';
		$countries = isset($_POST['banned_countries']) ? array_map('sanitize_text_field', wp_unslash($_POST['banned_countries'])) : []; // Niz banovanih država

		// Prepare data for saving
		$data = json_encode(['countries' => $countries]);
	
		global $wpdb;

		$table_name = esc_sql( $wpdb->prefix . 'ip_geomaster_blocked' );

		$row = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM `$table_name` WHERE type = %s LIMIT 1",
				'countries'
			)
		);
	
		if ($row) {
			// If the row exists, update the row
			$wpdb->update(
				$table_name,
				[
					'user_id' => $user_id,
					'country_msg' => $country_msg,
					'blocked_data' => $data 
				],
				['id' => $row->id] 
			);
		} else {
			//If the row does not exist, insert a new row
			$wpdb->insert(
				$table_name,
				[
					'user_id' => $user_id,
					'type' => 'countries',
					'country_msg' => $country_msg,
					'blocked_data' => $data // Json object with key "countries"
				]
			);
		}
	
		// return success response
		wp_send_json_success('Countries saved successfully.');
	}



	public function ip_geomaster_get_banned_countries() {
		global $wpdb;
		$table_name = esc_sql( $wpdb->prefix . 'ip_geomaster_blocked' );

		$row = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM `$table_name` WHERE type = %s LIMIT 1",
				'countries'
			)
		);
	
		if ($row) {
			$data = json_decode($row->blocked_data, true);
			echo json_encode($data['countries']);
		} else {
			echo json_encode([]);
		}
	
		wp_die();
	}



	private function _checkGeoLocation() {

		$databaseFile = IP_GEOMASTER_ROOT_PATH . '/GeoLite2-Country.mmdb'; // Path to the database
  		$reader = new MaxMind\Db\Reader($databaseFile);
		$ip = $this->_getUserIpAddr();
		global $wpdb;


    try {
        $record = $reader->get($ip);

        if (isset($record['country']['iso_code'])) {
            $country_code = $record['country']['iso_code']; // ISO code of the country

		
			$table_name = esc_sql( $wpdb->prefix . 'ip_geomaster_blocked' );

			$row = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM `$table_name` WHERE type = %s LIMIT 1",
					'countries'
				)
			);

			if ($row) {
                $data = json_decode($row->blocked_data, true);

				if(!$data) {
					return null; 
				}

                $banned_countries = $data['countries']; // Array of banned countries
                $country_msg = $row->country_msg;

                //Check if the country is banned
                if (in_array($country_code, $banned_countries)) {
					header('HTTP/1.1 403 Forbidden');
                    wp_die( esc_html( $country_msg ), 'Access Denied', array('response' => 403) );
					
                }
            }
	 }
    } catch (Exception $e) {
		// Send an email to the admin if an error occurs
	   wp_mail( get_option( 'admin_email' ), 'GeoIP Error', $e->getMessage() );
    } finally {
        $reader->close();
    }

    return null; // If the country is not found
	}


	private function _checkBots() {

		global $wpdb;

		$table_name = esc_sql( $wpdb->prefix . 'ip_geomaster_blocked' );

		$result = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT mode, bots_msg FROM `$table_name` WHERE type = %s LIMIT 1",
				'bots'
			)
		);

		$bots_msg = $result->bots_msg;

		if ($result->mode == 'off') {
			return;
		}
		
		// Get the user agent data
		$userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'])) : '';
		$ipAddress = $this->_getUserIpAddr(); // Proveri ako je IP već sanitarizovan unutar te funkcije
		$currentUrl = isset($_SERVER['REQUEST_URI']) ? esc_url(sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI']))) : '/';
		$referer = isset($_SERVER['HTTP_REFERER']) ? esc_url(sanitize_text_field(wp_unslash($_SERVER['HTTP_REFERER']))) : '';

	
		// Retrieve the list of good and bad bots
		$goodBots = IP_Geomaster_Bots::getGoodBots();
		$badBots = IP_Geomaster_Bots::getBadBots();
	
		// Checking if the user agent is a bad bot
		$isBadBot = $this->_isBotInList($userAgent, $badBots);
	
		// If the user agent is not a bad bot, check if it is a good bot
		if (!$isBadBot) {
			$isGoodBot = $this->_isBotInList($userAgent, $goodBots);
		}
	
		// Block the bad bot
		if ($isBadBot) {
			$this->_blockBot($bots_msg);
		}
	}

	private function _checkIpAddress() {

		global $wpdb;

		$table_name = esc_sql( $wpdb->prefix . 'ip_geomaster_blocked' );
		$user_ip = $this->_getUserIpAddr();

		$result = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT mode, ips_msg, blocked_data FROM `$table_name` WHERE type = %s LIMIT 1",
				'ips'
			)
		);


		if (!$result) {
			return; 
		}

		$ips_msg = $result->ips_msg;

		if ($result->mode == 'off') {
			return;
		}

		$blocked_data = json_decode($result->blocked_data, true);

		if (!is_array($blocked_data) || !isset($blocked_data['banned_ips'])) {
			return;
		}

		foreach ($blocked_data['banned_ips'] as $banned) {
			if ($banned['ip'] === $user_ip) {
				wp_die( esc_html( $ips_msg ), 'Access Denied', array('response' => 403) );
			}
		}


	}

	public function ip_geomaster_ban_ip() {

		// Check if nonce field are sent
		if (
			!isset($_POST['ip_geomaster_nonce_field']) || 
			!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ip_geomaster_nonce_field'])), 'ip-geomaster-ajax-nonce') // Trebalo bi da koristiš isti nonce kao u JavaScript-u
		) {
			wp_send_json_error('Invalid nonce.');
		}
		
		// Get data from the request
		$user_id = get_current_user_id(); // ID of the current user

		global $wpdb;
		$table_name = esc_sql( $wpdb->prefix . 'ip_geomaster_blocked' );

		$row = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$table_name} WHERE type = %s LIMIT 1", 
				'ips'
			)
		);

		$banned_ips_msg = isset($_POST['banned_ips_msg']) ? sanitize_text_field(wp_unslash($_POST['banned_ips_msg'])) : 'Your IP address is not allowed to access this content..';
		$banned_ip = isset($_POST['banned_ip']) ? sanitize_text_field(wp_unslash($_POST['banned_ip'])) : '';
		$banned_ip_note = isset($_POST['banned_ip_note']) ? sanitize_text_field(wp_unslash($_POST['banned_ip_note'])) : '';
		
		$ips_mode = isset($_POST['ips_mode']) ? sanitize_text_field(wp_unslash($_POST['ips_mode'])) : 'off';

		if(empty($banned_ip) && empty($banned_ip_note) && !empty($ips_mode)) {
				//update mode and msg
			if ($row) {
				$wpdb->update(
					$table_name,
					[
						'user_id' => $user_id,
						'ips_msg' => $banned_ips_msg,
						'mode' => $ips_mode
					],
					['id' => $row->id],
					['%d', '%s', '%s'], // Data types for security
					['%d']
				);
			}else {
				$wpdb->insert(
					$table_name,
					[
						'type' => 'ips',
						'user_id' => $user_id,
						'ips_msg' => $banned_ips_msg,
						'mode' => $ips_mode
					],
					['%s', '%d', '%s', '%s']
				);
			}

			wp_send_json_success('Data updated successfully.');
			exit;

		}

		
		if (!filter_var($banned_ip, FILTER_VALIDATE_IP)) {

			wp_send_json_error( array(
				'msg' => 'IP address is not valid',
				'invalid_ip' => $banned_ip
			));

		}

		$data = [];
		if ($row && !empty($row->blocked_data)) {
			$data = json_decode($row->blocked_data, true);
		}

		if (!is_array($data) || !isset($data['banned_ips'])) {
			$data = ['banned_ips' => []];
		}

		$new_ip = [
			'ip' => $banned_ip,
			'date' => gmdate('Y-m-d'), 
			'time' => gmdate('H:i:s'), 
			'notes' => $banned_ip_note
		];

		
		array_unshift($data['banned_ips'], $new_ip);

		$new_json = json_encode($data, JSON_UNESCAPED_UNICODE);

		if ($row) {
			$wpdb->update(
				$table_name,
				[
					'user_id' => $user_id,
					'blocked_data' => $new_json,
				],
				['id' => $row->id],
				['%d', '%s', '%s'], 
				['%d']
			);
		} else {
			
			$wpdb->insert(
				$table_name,
				[
					'type' => 'ips',
					'user_id' => $user_id,
					'blocked_data' => $new_json
				],
				['%s', '%d', '%s', '%s']
			);
		}

		
		wp_send_json_success('IP addresses banned successfully.');
		exit;
		
	}


	public function ip_geomaster_ban_many_ips(){
		

		if (
			!isset($_POST['ip_geomaster_nonce_field']) || 
			!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ip_geomaster_nonce_field'])), 'ip-geomaster-ajax-nonce') // Trebalo bi da koristiš isti nonce kao u JavaScript-u
		) {
			wp_send_json_error('Invalid nonce.');
		}

		// Get data from the request
		$user_id = get_current_user_id(); // ID of the current user

		global $wpdb;
		$table_name = esc_sql( $wpdb->prefix . 'ip_geomaster_blocked' );

		$row = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$table_name} WHERE type = %s LIMIT 1", 
				'ips'
			)
		);

		$banned_ips = isset($_POST['banned_ips']) ? array_map('sanitize_text_field', wp_unslash($_POST['banned_ips'])) : []; 

		$new_ip = [];

			$data = [];
			if ($row && !empty($row->blocked_data)) {
				$data = json_decode($row->blocked_data, true);
			}
	
			if (!is_array($data) || !isset($data['banned_ips'])) {
				$data = ['banned_ips' => []];
			}

		foreach ($banned_ips as $entry) {
			
			$parts = explode('#', $entry, 2);
			$ip = trim($parts[0]); // IP adresa
			$note = isset($parts[1]) ? trim($parts[1]) : ''; // Note ako postoji

			 // Proveravamo da li je validna IP adresa
			 if (!filter_var($ip, FILTER_VALIDATE_IP)) {
				wp_send_json_error([
					'msg' => 'Some IPs are not valid',
					'invalid_ip' => $ip
				]);
				exit;
			}

			array_unshift($data['banned_ips'], [
				'ip' => $ip,
				'date' => gmdate('Y-m-d'),
				'time' => gmdate('H:i:s'),
				'notes' => $note
			]);
		
		}

		$new_json = json_encode($data, JSON_UNESCAPED_UNICODE);

			
			if ($row) {
				$wpdb->update(
					$table_name,
					[
						'user_id' => $user_id,
						'blocked_data' => $new_json,
					],
					['id' => $row->id],
					['%d', '%s', '%s'], 
					['%d']
				);
			} else {
				
				$wpdb->insert(
					$table_name,
					[
						'type' => 'ips',
						'user_id' => $user_id,
						'blocked_data' => $new_json
					],
					['%s', '%d', '%s', '%s']
				);
			}
	
			wp_send_json_success('IP addresses banned successfully.');
			exit;
}


	public function ip_geomaster_get_banned_ips() {

		
		global $wpdb;
		$table_name = esc_sql( $wpdb->prefix . 'ip_geomaster_blocked' );

		$row = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM `$table_name` WHERE type = %s LIMIT 1",
				'ips'
			)
		);
	
		if ($row) {
			
		   $data = json_decode($row->blocked_data, true);
		   $data = is_array($data) ? $data : [];

		   $response = [
			'banned_ips' => isset($data['banned_ips']) ? $data['banned_ips'] : [],
			'ips_mode' => $row->mode,
			'ips_msg' => $row->ips_msg,
		];
			
			echo json_encode($response);
			wp_die();
	
		} else {
			
			echo json_encode([]);
		}
	
		wp_die();
	}


	public function ip_geomaster_remove_ban_ip() {

		if (
			!isset($_POST['ip_geomaster_nonce_field']) || 
			!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ip_geomaster_nonce_field'])), 'ip-geomaster-ajax-nonce') // Trebalo bi da koristiš isti nonce kao u JavaScript-u
		) {
			wp_send_json_error('Invalid nonce.');
		}
		
		$user_id = get_current_user_id(); // ID trenutnog korisnika

		global $wpdb;
		$table_name = $wpdb->prefix . 'ip_geomaster_blocked';
		

		$row = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$table_name} WHERE type = %s LIMIT 1", 
				'ips'
			)
		);

		$banned_ip = isset($_POST['banned_ip']) ? sanitize_text_field(wp_unslash($_POST['banned_ip'])) : '';

		if ($row && !empty($banned_ip)) {
			$data = json_decode($row->blocked_data, true);

			if (!empty($data['banned_ips'])) {
				// Filtriramo niz da uklonimo IP adresu
				$data['banned_ips'] = array_values(array_filter($data['banned_ips'], function($entry) use ($banned_ip) {
					return $entry['ip'] !== $banned_ip;
				}));

				$new_json = json_encode($data, JSON_UNESCAPED_UNICODE);

				$wpdb->update(
					$table_name,
					[
						'user_id' => $user_id,
						'blocked_data' => $new_json,
					],
					['id' => $row->id],
					['%d', '%s', '%s'], 
					['%d']
				);

					wp_send_json_success([
						'message' => 'IP removed successfully.',
						'count' => count($data['banned_ips']) 
					]);

				} else {
					wp_send_json_error(['message' => 'No banned IPs found.']);
				}
			} else {
				wp_send_json_error(['message' => 'Invalid request or no data found.']);
			}
		}


	/**
	 * Checking if the user agent is a bot
	 *
	 * @param string $userAgent
	 * @param array $botList
	 * @return bool
	 */
	
	private function _isBotInList($userAgent, $botList) {
		foreach ($botList as $bot) {
			if (strpos($userAgent, $bot) !== false) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Block the bot by sending the 403 Forbidden header
	 */
	private function _blockBot($bots_msg) {
		header('HTTP/1.1 403 Forbidden');
		wp_die( esc_html( $bots_msg ), 'Access Denied', array('response' => 403) );
	}


	private function _getUserIpAddr() {
		$ip = null;
	
		// Provera i sanitizacija za HTTP_CLIENT_IP
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = sanitize_text_field(wp_unslash($_SERVER['HTTP_CLIENT_IP']));
		}
	
		// Provera i sanitizacija za HTTP_X_FORWARDED_FOR
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$forwardedFor = sanitize_text_field(wp_unslash($_SERVER['HTTP_X_FORWARDED_FOR']));
			if (strpos($forwardedFor, ",")) {
				$explode = explode(",", $forwardedFor);
				$ip = sanitize_text_field(end($explode));  // Sanitizuj poslednji element
			} else {
				$ip = $forwardedFor;
			}
		}
	
		// Provera i sanitizacija za REMOTE_ADDR
		elseif (!empty($_SERVER['REMOTE_ADDR'])) {
			$ip = sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR']));
		}
	
		if ($ip) {
			return sanitize_text_field(trim($ip));  
		}
		
		return null;  
	}


}


$ip_geomaster_public = new Ip_Geomaster_Public(IP_GEOMASTER_PLUGIN_NAME,IP_GEOMASTER_VERSION);
add_action('init', array($ip_geomaster_public, 'init'));
