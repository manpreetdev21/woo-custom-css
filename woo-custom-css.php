<?php
/**
* Plugin Name: Woo Custom SASS/CSS
* Plugin URI: #
* Description: WooCommerce plugin custom CSS option for the themes.
* Version: 1.0.0
* Author: Manpreet Singh
* Text Domain: woocustomcss
**/

define( 'WOO_SETTING_VERSION', '1.0.0' );
define( 'WOO_SETTING_TEXT_DOMAIN', 'woocustomcss' );
define( 'WOO_DIR__NAME', dirname( __FILE__ ) );
define( 'WOO_EDITING__URL', plugin_dir_url( __FILE__ ) );
define( 'WOO_EDITING__DIR', plugin_dir_path( __FILE__ ) );
define( 'WOO_SETTING_PLUGIN', __FILE__ );
define( 'WOO_SETTING_PLUGIN_BASENAME', plugin_basename( WOO_SETTING_PLUGIN ) );

if ( ! defined( 'ABSPATH' ) ) {
     die;
}

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	include WOO_DIR__NAME. '/include/loader.php';
} else {
	add_action( 'admin_notices', 'woo_admin_notice_warning' );
	function woo_admin_notice_warning() {
		echo '<div class="notice notice-error"><p><strong>Error:</strong> Please note, the WooCommerce plugin is not active.</p></div>'; 
	}	
}