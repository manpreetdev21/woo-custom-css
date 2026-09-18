<?php
/**
 * Plugin Name: Woo Custom SASS/CSS
 * Plugin URI:  https://github.com/manpreetdev21/woo-custom-css.git
 * Description: WooCommerce layout & style editor for custom themes. Modern CSS-variable driven styling for shop, product, cart, checkout and account pages.
 * Version:     2.0.0
 * Author:      Manpreet Singh
 * Text Domain: woocustomcss
 * Domain Path: /languages
 * Requires PHP: 7.4
 * WC requires at least: 7.0
 * WC tested up to: 9.4
 **/

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

define( 'WOO_SETTING_VERSION', '2.0.0' );
define( 'WOO_SETTING_TEXT_DOMAIN', 'woocustomcss' );
define( 'WOO_DIR__NAME', dirname( __FILE__ ) );
define( 'WOO_EDITING__URL', plugin_dir_url( __FILE__ ) );
define( 'WOO_EDITING__DIR', plugin_dir_path( __FILE__ ) );
define( 'WOO_SETTING_PLUGIN', __FILE__ );
define( 'WOO_SETTING_PLUGIN_BASENAME', plugin_basename( WOO_SETTING_PLUGIN ) );

/**
 * Declare HPOS / cart-checkout-blocks compatibility.
 */
add_action( 'before_woocommerce_init', function () {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', WOO_SETTING_PLUGIN, true );
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', WOO_SETTING_PLUGIN, true );
	}
} );

/**
 * Boot once WooCommerce is known to be loaded.
 *
 * The old check read the `active_plugins` option directly, which misses
 * network-activated and non-standard installs.
 */
add_action( 'plugins_loaded', function () {
	load_plugin_textdomain( 'woocustomcss', false, dirname( WOO_SETTING_PLUGIN_BASENAME ) . '/languages' );

	if ( class_exists( 'WooCommerce' ) ) {
		require_once WOO_DIR__NAME . '/include/loader.php';
		return;
	}

	add_action( 'admin_notices', function () {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
		printf(
			'<div class="notice notice-error"><p><strong>%s</strong> %s</p></div>',
			esc_html__( 'Woo Custom SASS/CSS:', 'woocustomcss' ),
			esc_html__( 'WooCommerce is not active, so this plugin does nothing.', 'woocustomcss' )
		);
	} );
} );
