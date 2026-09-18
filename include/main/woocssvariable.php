<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Turns the saved settings into a :root{} block of CSS custom properties.
 *
 * This replaces the old runtime SCSS compile. The previous version ran
 * scssphp on EVERY request (front end and admin) and wrote two files to
 * disk each time, and it produced nothing at all on a fresh install because
 * the mixins referenced variables that _variables_static.scss never defined.
 *
 * Custom properties do the same substitution natively: every property the
 * merchant has not set is simply omitted, and the fallback baked into
 * woocommerce.css applies instead.
 */
class WOOCSSVARIABLE {

	/**
	 * Option name => custom property name, grouped by how the value is read.
	 */
	private $plain = array(
		'cat_text_ff'            => '--woo-cat-font',
		'cat_title_clr'          => '--woo-cat-title-color',
		'cat_title_fw'           => '--woo-cat-title-weight',
		'cat_box_shadow'         => '--woo-cat-shadow',
		'product_text_ff'        => '--woo-product-font',
		'product_title_text'     => '--woo-product-title-color',
		'product_title_fw'       => '--woo-product-title-weight',
		'product_price_text'     => '--woo-price-color',
		'product_price_fw'       => '--woo-price-weight',
		'product_box_shadow'     => '--woo-card-shadow',
		'btn_text_ff'            => '--woo-btn-font',
		'btn_title_fw'           => '--woo-btn-weight',
		'btn_clr'                => '--woo-btn-bg',
		'btn_txt_clr'            => '--woo-btn-color',
		'btn_hclr'               => '--woo-btn-bg-hover',
		'btn_htxt_clr'           => '--woo-btn-color-hover',
		'pro_detail_text_ff'     => '--woo-single-font',
		'pro_detail_title_text'  => '--woo-single-title-color',
		'pro_detail_title_fw'    => '--woo-single-title-weight',
		'pro_detail_price_text'  => '--woo-single-price-color',
		'pro_detail_price_fw'    => '--woo-single-price-weight',
		'table_bor_clr'          => '--woo-table-border-color',
		'table_hbg_clr'          => '--woo-table-head-bg',
		'table_htext_clr'        => '--woo-table-head-color',
		'table_text_clr'         => '--woo-table-color',
		'table_row_bg'           => '--woo-table-row-bg',
		'cart_remove_bg'         => '--woo-remove-bg',
		'cart_remove_clr'        => '--woo-remove-color',
		'cart_remove_bg_hover'   => '--woo-remove-bg-hover',
		'cart_totals_bg'         => '--woo-totals-bg',
		'cart_title_clr'         => '--woo-cart-title-color',
		'field_bordr_clr'        => '--woo-field-border-color',
		'field_bg_clr'           => '--woo-field-bg',
		'field_text_clr'         => '--woo-field-color',
		'field_focus_clr'        => '--woo-field-focus',
		'field_label_clr'        => '--woo-label-color',
		'field_label_fw'         => '--woo-label-weight',
		'checkout_panel_bg'      => '--woo-panel-bg',
		'product_box_bg'         => '--woo-card-bg',
		'product_box_align'      => '--woo-card-align',
		'cat_box_bg'             => '--woo-cat-bg',
		'cat_box_align'          => '--woo-cat-align',
		'product_img_fit'        => '--woo-img-fit',
		'sale_badge_bg'          => '--woo-sale-bg',
		'sale_badge_clr'         => '--woo-sale-color',
		'star_rating_clr'        => '--woo-star-color',
		'pro_tab_active_clr'     => '--woo-tab-active-color',
		'pro_tab_clr'            => '--woo-tab-color',
		'btn_text_transform'     => '--woo-btn-transform',
		'global_font_family'     => '--woo-font',
		'global_text_clr'        => '--woo-text',
		'global_muted_clr'       => '--woo-muted',
		'global_link_clr'        => '--woo-link',
		'global_link_hover_clr'  => '--woo-link-hover',
		'global_surface_clr'     => '--woo-surface',
		'global_line_clr'        => '--woo-line',
		'woocss_accent'          => '--woo-accent',
		'woocss_img_ratio'       => '--woo-img-ratio',
		'woocss_columns'         => '--woo-cols',
	);

	private $sizes = array(
		'cat_title_size'          => '--woo-cat-title-size',
		'product_title_size'      => '--woo-product-title-size',
		'product_price_size'      => '--woo-price-size',
		'btn_font_size'           => '--woo-btn-size',
		'pro_detail_title_size'   => '--woo-single-title-size',
		'pro_detail_price_size'   => '--woo-single-price-size',
		'table_border_size'       => '--woo-table-border-width',
		'table_head_size'         => '--woo-table-head-size',
		'table_thumb_width'       => '--woo-cart-thumb-width',
		'cart_totals_width'       => '--woo-totals-width',
		'cart_title_size'         => '--woo-cart-title-size',
		'field_height'            => '--woo-field-height',
		'btn_letter_spacing'      => '--woo-btn-tracking',
		'global_font_size'        => '--woo-font-size',
		'global_radius'           => '--woo-radius',
		'global_container_width'  => '--woo-container',
		'global_container_gutter' => '--woo-gutter',
		'woocss_grid_gap'         => '--woo-gap',
	);

	private $dimensions = array(
		'cat_box_pading'        => '--woo-cat-pad',
		'cat_box_bor_radis'     => '--woo-cat-radius',
		'product_box_pading'    => '--woo-card-pad',
		'product_box_bor_radis' => '--woo-card-radius',
		'product_btn_padding'   => '--woo-btn-pad',
		'btn_bor_radius'        => '--woo-btn-radius',
		'table_bor_radius'      => '--woo-table-radius',
		'table_cell_pad'        => '--woo-table-pad',
		'field_bor_radius'      => '--woo-field-radius',
		'checkout_panel_radius' => '--woo-panel-radius',
		'checkout_panel_pad'    => '--woo-panel-pad',
		'pro_gallery_radius'    => '--woo-gallery-radius',
	);

	private $borders = array(
		'cat_box_bor'        => '--woo-cat-border',
		'product_box_bor'    => '--woo-card-border',
		'checkout_panel_bor' => '--woo-panel-border',
		'btn_border'         => '--woo-btn-border',
	);

	/**
	 * Built once per request, reused by both enqueue passes.
	 */
	private $css = null;

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'add_inline_variables' ), 20 );
	}

	/**
	 * Attach the :root block to the plugin stylesheet.
	 */
	public function add_inline_variables() {
		$css = $this->build_css();
		if ( '' !== $css && wp_style_is( 'woocustommincss', 'enqueued' ) ) {
			wp_add_inline_style( 'woocustommincss', $css );
		}
	}

	/**
	 * Public so other classes (and themes) can reuse the generated block.
	 */
	public function build_css() {
		if ( null !== $this->css ) {
			return $this->css;
		}

		$option     = new WOOCUSTOMOPTION();
		$properties = array();

		foreach ( $this->plain as $option_name => $property ) {
			$properties[ $property ] = get_option( $option_name, '' );
		}
		foreach ( $this->sizes as $option_name => $property ) {
			$properties[ $property ] = $option->get_option_size_data( $option_name );
		}
		foreach ( $this->dimensions as $option_name => $property ) {
			$properties[ $property ] = $option->get_option_dimension_data( $option_name );
		}
		foreach ( $this->borders as $option_name => $property ) {
			$properties[ $property ] = $option->get_option_border_data( $option_name );
		}

		// Hover lift is a toggle, but it reads as a distance in the stylesheet.
		$properties['--woo-card-lift'] = ( 'yes' === get_option( 'woocss_card_hover' ) ) ? '-4px' : '0px';

		// Breakpoint column counts. CSS cannot clamp a repeat() count with
		// min(), so the narrower counts are worked out here instead — and
		// they only ever step DOWN from the merchant's choice.
		$columns = (int) get_option( 'woocss_columns' );
		if ( $columns >= 1 ) {
			$properties['--woo-cols-md'] = (string) min( $columns, 3 );
			$properties['--woo-cols-sm'] = (string) min( $columns, 2 );
			$properties['--woo-cols-xs'] = '1';
		}

		// Toggles that read as a CSS keyword rather than a colour or length.
		// Each is only emitted when it differs from the stylesheet default,
		// so an untouched install still emits nothing.
		if ( 'yes' !== get_option( 'table_head_upper', 'yes' ) ) {
			$properties['--woo-table-head-transform'] = 'none';
		}
		if ( 'yes' !== get_option( 'checkout_place_order_full', 'yes' ) ) {
			$properties['--woo-place-order-width'] = 'auto';
		}
		if ( 'right' === get_option( 'sale_badge_pos' ) ) {
			$properties['--woo-sale-left']  = 'auto';
			$properties['--woo-sale-right'] = '0.75rem';
		}

		// Transition speed is entered as a plain number of milliseconds.
		$speed = get_option( 'global_speed' );
		if ( '' !== trim( (string) $speed ) && is_numeric( $speed ) ) {
			$properties['--woo-speed'] = max( 0, (int) $speed ) . 'ms';
		}

		/**
		 * Filter the generated custom properties before they are printed.
		 *
		 * @param array $properties Map of custom property name => CSS value.
		 */
		$properties = apply_filters( 'woocustomcss_css_variables', $properties );

		$declarations = '';
		foreach ( $properties as $property => $value ) {
			$value = $this->sanitize_value( $value );
			if ( '' === $value ) {
				continue;
			}
			$declarations .= sprintf( "\t%s: %s;\n", $property, $value );
		}

		$this->css = ( '' === $declarations ) ? '' : ":root{\n" . $declarations . '}';

		return $this->css;
	}

	/**
	 * These values land inside a <style> block, so anything that could close
	 * the block or open a new rule has to go. url() is dropped too: none of
	 * these properties legitimately need it.
	 */
	private function sanitize_value( $value ) {
		if ( is_array( $value ) || is_object( $value ) || null === $value ) {
			return '';
		}
		$value = (string) $value;
		$value = wp_strip_all_tags( $value );
		$value = str_replace( array( '{', '}', ';', '<', '>', '@' ), '', $value );
		$value = preg_replace( '#(url|expression|javascript)\s*\(#i', '', $value );

		return trim( $value );
	}
}
