<?php
/*
  Plugin Name: Vetfunder Mailchimp Integration
  Plugin URI: http://oliveconcepts.com
  Description: Vetfunder Mailchimp Integration For Fundamerica.
  Version: 1.0
  Author: Vetfunder
  Author URI: http://vetfunder.com
  Text Domain: vetfunder
*/

/* Define Plugin Constant*/
if ( ! defined( 'VETFUNDER_MAIL_CHIMP_DIR' ) ) { define( 'VETFUNDER_MAIL_CHIMP_DIR', plugin_dir_path( __FILE__ ) ); }
if ( ! defined( 'VETFUNDER_MAIL_CHIMP_URL' ) ) { define( 'VETFUNDER_MAIL_CHIMP_URL', plugins_url( '/', __FILE__ ) ); }
if ( ! defined( 'VETFUNDER_MAIL_CHIMP_PATH' ) ) { define( 'VETFUNDER_MAIL_CHIMP_PATH', plugin_basename( __FILE__ ) ); } 

/* create Menu */

class Vetfunder_Mailchimp {

	public function __construct(){
		add_action( 'init', array( $this, 'mailchimp_loard_file')  );
		add_action( 'admin_menu', array( $this, 'vetfunder_mailchimp_menu')  );
	}

	public function mailchimp_loard_file() {
		require "lib/vendor/autoload.php";
	  	require_once 'common-function.php';
	  	require_once 'admin/calss-champaign-admin.php';
	  	require_once 'public/class-public-subscribe.php';
	}

	public function vetfunder_mailchimp_menu(){
		add_menu_page( "Vetfunder Mailchimp", "Vetfunder Mailchimp", 'manage_options', "vt_mailchimp",array($this,'mailchimp_all_settings'));
	}	
	
	/*get menu and sub menu end*/
	public function mailchimp_all_settings(){
		include "admin/class-admin-settings.php";
	}

	//include	"admin/champaign-mail-list.php";
}	

$Vetfunder_Mailchimp = new Vetfunder_Mailchimp;