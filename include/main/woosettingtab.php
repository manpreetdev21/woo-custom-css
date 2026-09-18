<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class WOOSETTINGTAB {

	/**
	 * Declared explicitly. They used to be assigned in the constructor only,
	 * which is a deprecation on PHP 8.2 and a fatal on 9.0.
	 */
	public $id;
	public $label;

	private $setting_page_link = 'woo-custom-css-setting-link';

	/**
	 * Section slug => template file (relative to include/templates/).
	 *
	 * Ordered the way a theme is actually built: global foundations, then
	 * structure, then per-page styling in the order a customer walks the
	 * store, then behaviour, then the raw-CSS escape hatch last. The empty
	 * slug must stay first — WooCommerce treats it as the default section.
	 */
	private $sections = array(
		''                  => 'general-setting.php',
		'layout-settings'   => 'layout-setting.php',
		'product-page-css'  => 'shop-page-setting.php',
		'cart-page-css'     => 'cart-page-setting.php',
		'checkout-page-css' => 'checkout-page-setting.php',
		'theme-hooks-page'  => 'hooks-setting.php',
		'extra-settings'    => 'custom-extra-css.php',
	);

	public function __construct() {
		$this->id    = $this->setting_page_link;
		$this->label = __( 'Custom CSS', 'woocustomcss' );

		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'custom_tab_page' ), 99 );
		add_action( 'woocommerce_sections_' . $this->id, array( $this, 'custom_tab_display_sections' ) );
		add_action( 'woocommerce_settings_' . $this->id, array( $this, 'custom_tab_output' ) );
		add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'custom_fields_save' ) );
	}

	public function custom_tab_page( $settings_tabs ) {
		$settings_tabs[ $this->setting_page_link ] = $this->label;
		return $settings_tabs;
	}

	public function create_sections() {
		$sections = array(
			''                  => __( 'General', 'woocustomcss' ),
			'layout-settings'   => __( 'Layout', 'woocustomcss' ),
			'product-page-css'  => __( 'Product/Categories Page', 'woocustomcss' ),
			'cart-page-css'     => __( 'Cart Page', 'woocustomcss' ),
			'checkout-page-css' => __( 'Checkout Page', 'woocustomcss' ),
			'theme-hooks-page'  => __( 'Theme Hooks', 'woocustomcss' ),
			'extra-settings'    => __( 'Extra CSS', 'woocustomcss' ),
		);

		return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
	}

	public function custom_tab_display_sections() {
		global $current_section;

		$sections = $this->create_sections();
		if ( empty( $sections ) || 1 === count( $sections ) ) {
			return;
		}

		$array_keys = array_keys( $sections );

		echo '<ul class="subsubsub">';
		foreach ( $sections as $id => $label ) {
			$url = admin_url( 'admin.php?page=wc-settings&tab=' . $this->id . '&section=' . sanitize_title( $id ) );
			printf(
				'<li><a href="%s" class="%s">%s</a> %s</li>',
				esc_url( $url ),
				esc_attr( $current_section === $id ? 'current' : '' ),
				esc_html( $label ),
				end( $array_keys ) === $id ? '' : '|'
			);
		}
		echo '</ul><br class="clear" />';
	}

	/**
	 * Loads the field definitions for the section being viewed.
	 *
	 * The old version was an if/elseif chain that printed "Noting Found..."
	 * mid-form for any unrecognised section.
	 */
	public function custom_tabs_field_array() {
		global $current_section;

		$settings = array();
		$section  = (string) $current_section;

		if ( isset( $this->sections[ $section ] ) ) {
			$template = WOO_EDITING__DIR . 'include/templates/' . $this->sections[ $section ];
			if ( file_exists( $template ) ) {
				include $template;
			}
		}

		return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings );
	}

	public function custom_tab_output() {
		WC_Admin_Settings::output_fields( $this->custom_tabs_field_array() );
	}

	public function custom_fields_save() {
		global $current_section;

		WC_Admin_Settings::save_fields( $this->custom_tabs_field_array() );

		if ( $current_section ) {
			do_action( 'woocommerce_update_options_' . $this->id . '_' . $current_section );
		}
	}
}
