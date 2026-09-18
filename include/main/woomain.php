<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class WOOMAIN {

	private $setting_page_link = 'woo-custom-css-setting-link';
	private $setting_btn_name  = 'Custom Settings';

	/**
	 * Breakpoints the device-scoped editors are wrapped in. These match the
	 * media queries in assets/frontend/css/woocommerce.css — change both or
	 * neither.
	 */
	private $custom_css_breakpoints = array(
		'custom_css_tablet' => '991.98px',
		'custom_css_mobile' => '575.98px',
	);

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'woo_main_min_css' ), 15 );
		add_action( 'wp_enqueue_scripts', array( $this, 'woo_ms_custom_style_css' ), 25 );
		add_action( 'admin_enqueue_scripts', array( $this, 'woo_ms_admin_enqueue_admin_style_script' ) );
		add_filter( 'plugin_action_links_' . WOO_SETTING_PLUGIN_BASENAME, array( $this, 'woo_custom_add_action_plugin' ) );
		add_filter( 'body_class', array( $this, 'woo_ms_body_class' ) );
	}

	/**
	 * Main stylesheet. Plain CSS now — the custom properties that theme it
	 * are injected by WOOCSSVARIABLE at priority 20.
	 */
	public function woo_main_min_css() {
		$path = WOO_EDITING__DIR . 'assets/frontend/css/woocommerce.css';
		if ( ! file_exists( $path ) ) {
			return;
		}

		wp_enqueue_style(
			'woocustommincss',
			WOO_EDITING__URL . 'assets/frontend/css/woocommerce.css',
			array(),
			filemtime( $path )
		);
	}

	/**
	 * Merchant's own CSS, printed inline.
	 *
	 * The old version wrote assets/frontend/css/custom-style.css to disk on
	 * every single front-end request, which needed a writable plugin
	 * directory and broke silently when it was not.
	 */
	public function woo_ms_custom_style_css() {
		$custom_css = $this->build_custom_css();
		if ( '' === $custom_css || ! wp_style_is( 'woocustommincss', 'enqueued' ) ) {
			return;
		}

		wp_add_inline_style( 'woocustommincss', $custom_css );
	}

	/**
	 * Assembles the global editor plus the device-scoped ones, narrowest
	 * last so it wins on equal specificity.
	 *
	 * Public so it can be exercised by tests/test-css-vars.php.
	 */
	public function build_custom_css() {
		$blocks = array();

		$global = $this->clean_css( get_option( 'custom_css' ) );
		if ( '' !== $global ) {
			$blocks[] = $global;
		}

		foreach ( $this->custom_css_breakpoints as $option_name => $max_width ) {
			$css = $this->clean_css( get_option( $option_name ) );
			if ( '' === $css ) {
				continue;
			}
			$blocks[] = sprintf( "@media (max-width: %s) {\n%s\n}", $max_width, $css );
		}

		return implode( "\n", $blocks );
	}

	/**
	 * The value is printed inside a <style> block. Tags have to go, and an
	 * unbalanced brace would swallow every rule that follows it, so a block
	 * that does not balance is dropped rather than shipped broken.
	 */
	private function clean_css( $css ) {
		if ( ! is_string( $css ) || '' === trim( $css ) ) {
			return '';
		}

		$css = wp_strip_all_tags( $css );

		if ( substr_count( $css, '{' ) !== substr_count( $css, '}' ) ) {
			return '';
		}

		return trim( $css );
	}

	/**
	 * Lets the stylesheet react to layout toggles without extra properties.
	 */
	public function woo_ms_body_class( $classes ) {
		if ( 'yes' === get_option( 'woocss_card_hover' ) ) {
			$classes[] = 'woocss-card-hover';
		}
		if ( 'yes' === get_option( 'woo_product_img_hovr' ) ) {
			$classes[] = 'woocss-img-hover';
		}

		// The container rule only exists under this class, so a theme's own
		// page width is untouched unless one of these is actually set.
		$width  = get_option( 'global_container_width' );
		$gutter = get_option( 'global_container_gutter' );
		if ( ! empty( $width['size'] ) || ! empty( $gutter['size'] ) ) {
			$classes[] = 'woocss-container';
		}

		// The Product Collection block ships its own columns-N grid. The
		// plugin only takes that over when a column count or gap was actually
		// chosen, so a block shop keeps its own layout by default.
		$gap = get_option( 'woocss_grid_gap' );
		if ( '' !== (string) get_option( 'woocss_columns' ) || ! empty( $gap['size'] ) ) {
			$classes[] = 'woocss-grid';
		}

		return $classes;
	}

	/**
	 * Admin assets, only on this plugin's settings screens.
	 *
	 * Uses the CodeMirror bundled with WordPress since 4.9 — the plugin's own
	 * codemirror.min.js / .css were both zero-byte files, so the CSS editor
	 * had silently been a plain textarea.
	 */
	public function woo_ms_admin_enqueue_admin_style_script( $hook ) {
		if ( 'woocommerce_page_wc-settings' !== $hook ) {
			return;
		}

		$tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : '';
		if ( $this->setting_page_link !== $tab ) {
			return;
		}

		wp_enqueue_script(
			'woocustomcss-admin',
			WOO_EDITING__URL . 'assets/admin/js/admin-script.js',
			array( 'jquery' ),
			WOO_SETTING_VERSION,
			true
		);

		$section = isset( $_GET['section'] ) ? sanitize_text_field( wp_unslash( $_GET['section'] ) ) : '';
		if ( 'extra-settings' !== $section || ! function_exists( 'wp_enqueue_code_editor' ) ) {
			return;
		}

		$editor = wp_enqueue_code_editor( array( 'type' => 'text/css' ) );
		if ( false === $editor ) {
			// Merchant turned syntax highlighting off in their profile.
			return;
		}

		// Not wp_localize_script: that casts every value to a string, which
		// mangles CodeMirror's numeric and boolean settings.
		wp_add_inline_script(
			'woocustomcss-admin',
			'var wooCustomCssEditor = ' . wp_json_encode( $editor ) . ';',
			'before'
		);
	}

	public function woo_custom_add_action_plugin( $plugin_link ) {
		$settings_link = sprintf(
			'<a href="%s">%s</a>',
			esc_url( admin_url( 'admin.php?page=wc-settings&tab=' . $this->setting_page_link ) ),
			esc_html__( 'Custom Settings', 'woocustomcss' )
		);

		array_unshift( $plugin_link, $settings_link );
		return $plugin_link;
	}
}
