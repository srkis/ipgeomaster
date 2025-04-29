<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once plugin_dir_path( __FILE__ ) . 'partials/ip-geomaster-admin-display.php';
require_once plugin_dir_path( __FILE__ ) . 'class-ip-geomaster-countries.php';
require_once plugin_dir_path( __FILE__ ) . 'class-ip-geomaster-bots.php';
require_once plugin_dir_path( __FILE__ ) . 'partials/ip-geomaster-admin-bots.php';
require_once plugin_dir_path( __FILE__ ) . 'partials/ip-geomaster-admin-ban-ips.php';
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://ipgeomaster.icodes.rocks
 * @since      1.0.0
 *
 * @package    Ip_Geomaster
 * @subpackage Ip_Geomaster/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ip_Geomaster
 * @subpackage PluIp_Geomastergin_Name/admin
 * @author     ipgeomaster <ipgeomaster@gmail.com>
 */
class Ip_Geomaster_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->init_ip_geomaster_admin_page();

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles($hook) {

		if (strpos($hook, 'ip-geomaster') === false) {
			return;
		}

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ip-geomaster-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'ip-geomaster-jquery-toast', plugin_dir_url( __FILE__ ) . 'css/jquery.toast.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'ip-geomaster-bootstrap4', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), '4.7.0', 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts($hook) {

		if (strpos($hook, 'ip-geomaster') === false) {
			return;
		}
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ip-geomaster-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'ip-geomaster-jquery-toast', plugin_dir_url( __FILE__ ) . 'js/jquery.toast.min.js', array( 'jquery' ), $this->version, true );
		
		wp_enqueue_script( 'ip-geomaster-popper', plugin_dir_url( __FILE__ ) . 'js/popper.min.js', array( 'jquery' ), '1.14.7', true );
		
		wp_enqueue_script( 'ip-geomaster-bootstrap5js',  plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array( 'jquery' ), $this->version, true );
	
		 // Sending admin-ajax URL and nonce to the script
		 wp_localize_script( $this->plugin_name, 'ip_geomaster_ajax', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'ip-geomaster-ajax-nonce' ),
		));


		if (isset($_GET['page']) && $_GET['page'] === 'ip-geomaster-ban-ip') {
			wp_enqueue_script(
				'ip-geomaster-ban-ips-js',
				plugin_dir_url(__FILE__) . 'js/ip-geomaster-ban-ips-js',
				array('jquery'),
				$this->version,
				true
			);
		}

		if (isset($_GET['page']) && $_GET['page'] === 'ip-geomaster-bots') {
			wp_enqueue_script(
				'ip-geomaster-bots-js',
				plugin_dir_url(__FILE__) . 'js/ip-geomaster-bots-js',
				array('jquery'),
				$this->version,
				true
			);
		}



	
	}


	public function init_ip_geomaster_admin_page() {

		add_action('admin_menu', array($this, 'ip_geomaster_admin_page' ) );

    }

	public function ip_geomaster_admin_page() {
				
		$icon_base64 = 'PD94bWwgdmVyc2lvbj0iMS4wIiBzdGFuZGFsb25lPSJubyI/Pgo8IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDIwMDEwOTA0Ly9FTiIKICJodHRwOi8vd3d3LnczLm9yZy9UUi8yMDAxL1JFQy1TVkctMjAwMTA5MDQvRFREL3N2ZzEwLmR0ZCI+CjxzdmcgdmVyc2lvbj0iMS4wIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciCiB3aWR0aD0iNTEyLjAwMDAwMHB0IiBoZWlnaHQ9IjUxMi4wMDAwMDBwdCIgdmlld0JveD0iMCAwIDUxMi4wMDAwMDAgNTEyLjAwMDAwMCIKIHByZXNlcnZlQXNwZWN0UmF0aW89InhNaWRZTWlkIG1lZXQiPgoKPGcgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMC4wMDAwMDAsNTEyLjAwMDAwMCkgc2NhbGUoMC4xMDAwMDAsLTAuMTAwMDAwKSIKZmlsbD0iIzAwMDAwMCIgc3Ryb2tlPSJub25lIj4KPHBhdGggZD0iTTI0NDUgNDUxMyBjLTE0NCAtMjMgLTMyNSAtOTYgLTQzOSAtMTc3IC0xNDYgLTEwNCAtMjg2IC0yODggLTM0NgotNDU0IC00OCAtMTMzIC02MyAtMjE0IC02NCAtMzUyIC0xIC0xMTQgMiAtMTM1IDMyIC0yMzkgNjYgLTIyNCAyMTQgLTU4NiA0MDcKLTk5MSA1OCAtMTI0IDEwOSAtMjMxIDExMiAtMjM3IDQgLTEwIC05MSAtMTMgLTQ0MiAtMTUgbC00NDcgLTMgLTMzOCAtNzIwCi0zMzkgLTcyMCAxOTcxIC0zIGMxMDg0IC0xIDE5NzMgMCAxOTc2IDIgMiAzIC0xNDggMzI5IC0zMzUgNzI1IGwtMzM4IDcyMQotNDI3IDAgYy0yMzYgMCAtNDI4IDIgLTQyOCAzIDAgMiAzOCA4MiA4NCAxNzggMjg2IDU5NSA0NTkgMTA1MSA0NzMgMTI0NSAxMwoxODggLTUxIDQyNSAtMTYwIDU5MyAtNjAgOTEgLTE2OSAyMDcgLTI1MyAyNjcgLTg0IDYwIC0yMzEgMTI4IC0zMzQgMTU1IC04NAoyMSAtMjkxIDM0IC0zNjUgMjJ6IG0yOTggLTY2OCBjNzEgLTM1IDE1MCAtMTE3IDE4NSAtMTkyIDIzIC01MCAyNyAtNzEgMjcKLTE1MyAwIC04MiAtNCAtMTAzIC0yNyAtMTUzIC0zNSAtNzUgLTExMyAtMTU2IC0xODUgLTE5MyAtNTQgLTI3IC02NiAtMjkKLTE2OCAtMjkgLTEwNCAwIC0xMTMgMiAtMTcxIDMyIC0yMjggMTE5IC0yODIgNDE0IC0xMTEgNjAwIDg2IDk0IDE2MiAxMjQgMjk3CjEyMCA4MSAtMyAxMDQgLTcgMTUzIC0zMnogbS03OTMgLTIwMjcgbDAgLTgzIC0xMjIgMCBjLTEwOSAwIC0xMzIgLTMgLTE5NwotMjcgLTQxIC0xNSAtMTI3IC01OSAtMTkxIC05OCAtMTAyIC02MiAtMjU3IC0xMzYgLTI2NiAtMTI3IC0yIDEgMzEgNzcgNzIKMTY3IDQxIDkxIDg0IDE4NCA5NCAyMDggbDE5IDQyIDI5NSAwIDI5NiAwIDAgLTgyeiBtNDMzIC0yMjggYzg3IC0xNzEgMTU0Ci0zMTAgMTUwIC0zMTAgLTUgMCAtNTEgNTAgLTEwMyAxMTEgLTEzMSAxNTQgLTIwNCAyMTggLTMxMSAyNzMgLTQ5IDI1IC05NyA0NgotMTA1IDQ2IC0xMSAwIC0xNCAxOSAtMTQgOTUgbDAgOTUgMTEzIDAgMTEyIDAgMTU4IC0zMTB6IG03MjMgMjkzIGM3NSAtMjMwCjI5MiAtMzQ4IDY2NyAtMzYyIGwxNTkgLTYgODUgLTE3OSBjNjUgLTEzOCA4MSAtMTgxIDcxIC0xODggLTMyIC0yMCAtMjA4Ci0xMDcgLTI1OCAtMTI2IC0zODEgLTE0OSAtODgzIC04MCAtMTE5NSAxNjUgbC01MyA0MiA3OSAxNTMgYzQ0IDg0IDEyMSAyMzUKMTcyIDMzNiBsOTIgMTgyIDg4IDAgYzc1IDAgODggLTMgOTMgLTE3eiBtNzE0IC0xMzUgYzM5IC04MCA3MCAtMTUwIDcwIC0xNTQKMCAtNCAtNjQgLTQgLTE0MiAtMSAtMjAwIDcgLTM0MCA0NyAtNDUxIDEyNyAtNDYgMzMgLTEwMiAxMDkgLTExMyAxNTMgbC02IDI3CjI4NiAtMiAyODYgLTMgNzAgLTE0N3ogbS0xOTA3IC04NCBsMzcgLTcgMCAtNDU4IDAgLTQ1OSAtNTU1IDAgYy0zMDUgMCAtNTU1CjMgLTU1NSA4IDAgNCA2NSAxNTEgMTQ1IDMyOCBsMTQ2IDMyMCA3MiAyMyBjODAgMjYgMTgwIDc0IDI2MCAxMjUgNzggNTAgMTcxCjk1IDIzMCAxMTEgNTUgMTUgMTU4IDE5IDIyMCA5eiBtMTg4IC02NiBjNzcgLTQwIDE1NSAtMTA4IDI0MCAtMjA4IDE5MSAtMjI2CjMyNiAtMzMzIDUyNiAtNDE2IDE4NCAtNzYgNDM0IC0xMTMgNjM2IC05NSAxOTYgMTkgMzIzIDU3IDUxNyAxNTcgNjEgMzEgMTEyCjU1IDExNCA1MyAyIC0yIDE0IC0yOSAyOCAtNTkgMTQgLTMwIDUxIC0xMDggODEgLTE3MiBsNTYgLTExOCAtMTE0OSAwIC0xMTUwCjAgMCA0NTEgMCA0NTAgMjMgLTYgYzEyIC00IDQ3IC0yMCA3OCAtMzd6Ii8+CjwvZz4KPC9zdmc+Cg==';
		$icon_data_uri = 'data:image/svg+xml;base64,' . $icon_base64;

		add_menu_page(
		'IP Geomaster', 
		'IP Geomaster', 
		'manage_options',
		'ip-geomaster',
		 function() { ip_geomaster_admin_display(1, 'ip-geomaster'); },
		 $icon_data_uri
	);

	add_submenu_page(
		'ip-geomaster', 
		'IP Geomaster Bots', 
		'IP Geomaster Bots', 
		'manage_options', 
		'ip-geomaster-bots', 
		function() { ip_geomaster_bots(1, 'ip-geomaster-bots'); } 
	);

	add_submenu_page(
        'ip-geomaster', 
        'Ban IP Settings', 
        'Ban IP Settings ', 
        'manage_options', 
        'ip-geomaster-ban-ip', 
        function() { ip_geomaster_ban_ip_settings_page(1, 'ip-geomaster-ban-ip'); } 
    );

}

}
