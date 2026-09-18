<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class WOOHOOKSETTING {

	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'woo_ms_pluign_woocommerce_support' ) );

		// Template hook removals run on init, not after_setup_theme: WooCommerce
		// registers its own template hooks during plugins_loaded, and a theme may
		// re-add them on after_setup_theme. Running later means we always win.
		add_action( 'init', array( $this, 'woo_ms_template_hooks' ), 99 );

		add_action( 'woocommerce_before_main_content', array( $this, 'woo_ms_plugin_wrapper_start' ), 10 );
		add_action( 'woocommerce_after_main_content', array( $this, 'woo_ms_plugin_wrapper_end' ), 10 );
		add_filter( 'use_block_editor_for_post_type', array( $this, 'woo_ms_use_block_editor_for_post_type' ), 10, 2 );
		add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'woo_ms_button_text' ), 10, 2 );
		add_action( 'woocommerce_before_cart_table', array( $this, 'woo_ms_woocommerce_before_cart_table_action' ) );
		add_filter( 'woocommerce_product_related_products_heading', array( $this, 'woo_ms_woocommerce_product_related_products_heading' ) );
		add_filter( 'woocommerce_return_to_shop_text', array( $this, 'woo_ms_woocommerce_return_to_shop_text' ) );
		add_filter( 'woocommerce_thankyou_order_received_text', array( $this, 'woo_ms_order_received_text' ), 10, 2 );
		add_filter( 'woocommerce_product_subcategories_hide_empty', array( $this, 'woo_ms_subcategories_hide_empty' ), 10, 1 );
		add_filter( 'body_class', array( $this, 'woo_ms_hide_body_classes' ) );
		add_filter( 'loop_shop_columns', array( $this, 'woo_ms_loop_columns' ), 20 );
		add_filter( 'loop_shop_per_page', array( $this, 'woo_ms_products_per_page' ), 20 );
		add_filter( 'woocommerce_product_tabs', array( $this, 'woo_ms_product_tabs' ), 98 );
		add_filter( 'woocommerce_output_related_products_args', array( $this, 'woo_ms_related_products_args' ), 20 );
		add_filter( 'woocommerce_product_thumbnails_columns', array( $this, 'woo_ms_gallery_columns' ), 20 );

		// Simple boolean filters, only attached when the merchant asked for them.
		if ( $this->enabled( 'woo_hide_sku' ) ) {
			add_filter( 'wc_product_sku_enabled', '__return_false' );
		}
		if ( $this->enabled( 'woo_disable_coupons' ) ) {
			add_filter( 'woocommerce_coupons_enabled', '__return_false' );
		}
		if ( $this->enabled( 'woo_hide_order_notes' ) ) {
			add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );
		}
		if ( '' !== (string) get_option( 'woo_disable_styles' ) ) {
			add_filter( 'woocommerce_enqueue_styles', array( $this, 'woo_ms_disable_styles' ) );
		}
		if ( $this->enabled( 'woo_disable_cart_fragments' ) ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'woo_ms_disable_cart_fragments' ), 99 );
		}
	}

	/**
	 * Checkbox options are stored as the strings 'yes' / 'no'.
	 */
	private function enabled( $option_name ) {
		return 'yes' === get_option( $option_name );
	}

	/**
	 * Positive integer from an option, or null when unset/invalid.
	 */
	private function positive_int( $option_name, $max ) {
		$value = (int) get_option( $option_name );
		return ( $value >= 1 && $value <= $max ) ? $value : null;
	}

	public function woo_ms_pluign_woocommerce_support() {
		if ( $this->enabled( 'theme_support' ) ) {
			add_theme_support( 'woocommerce' );
		}
		if ( $this->enabled( 'gallery_zoom' ) ) {
			add_theme_support( 'wc-product-gallery-zoom' );
		}
		if ( $this->enabled( 'gallery_lightbox' ) ) {
			add_theme_support( 'wc-product-gallery-lightbox' );
		}
		if ( $this->enabled( 'gallery_slider' ) ) {
			add_theme_support( 'wc-product-gallery-slider' );
		}
	}

	/**
	 * Every "hide X" toggle, applied by unhooking the template function that
	 * renders it. The markup is never generated, so it stays out of the DOM
	 * for screen readers as well — a CSS display:none would not.
	 *
	 * Each entry is: option => [ hook, callback, priority ].
	 */
	public function woo_ms_template_hooks() {
		$removals = array(
			'woo_breadcrumb'            => array( array( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 ) ),
			'woo_sidebar'               => array( array( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 ) ),
			'woo_related_pro'           => array( array( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 ) ),
			'woo_hide_result_count'     => array( array( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 ) ),
			'woo_hide_ordering'         => array( array( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 ) ),
			'woo_hide_loop_rating'      => array( array( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 ) ),
			'woo_hide_loop_price'       => array( array( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 ) ),
			'woo_hide_loop_add_to_cart' => array( array( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 ) ),
			'woo_hide_product_meta'     => array( array( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 ) ),
			'woo_hide_upsells'          => array( array( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 ) ),
			'woo_hide_cross_sells'      => array( array( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display', 10 ) ),
			'woo_hide_sale_flash'       => array(
				array( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 ),
				array( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 ),
			),
		);

		foreach ( $removals as $option_name => $hooks ) {
			if ( ! $this->enabled( $option_name ) ) {
				continue;
			}
			foreach ( $hooks as $hook ) {
				remove_action( $hook[0], $hook[1], $hook[2] );
			}
		}
	}

	/**
	 * Option => body class, for the "hide X" toggles.
	 *
	 * Unhooking the template function is the better mechanism and stays the
	 * primary one, but it only reaches markup that fires the hook. A block
	 * theme renders the catalogue, breadcrumb, result count and sorting as
	 * separate blocks that call none of them, so the toggles silently did
	 * nothing on those shops. The body class lets the stylesheet finish the
	 * job for both markups.
	 *
	 * display:none, not visibility — a display:none subtree is dropped from
	 * the accessibility tree too, so a screen reader does not announce a
	 * control the merchant removed.
	 */
	private function hide_classes() {
		return array(
			'woo_breadcrumb'            => 'woocss-hide-breadcrumb',
			'woo_hide_result_count'     => 'woocss-hide-result-count',
			'woo_hide_ordering'         => 'woocss-hide-ordering',
			'woo_hide_sale_flash'       => 'woocss-hide-sale',
			'woo_hide_loop_rating'      => 'woocss-hide-loop-rating',
			'woo_hide_loop_price'       => 'woocss-hide-loop-price',
			'woo_hide_loop_add_to_cart' => 'woocss-hide-loop-cart',
			'woo_hide_product_meta'     => 'woocss-hide-product-meta',
			'woo_hide_upsells'          => 'woocss-hide-upsells',
			'woo_hide_cross_sells'      => 'woocss-hide-cross-sells',
			'woo_sidebar'               => 'woocss-hide-sidebar',
		);
	}

	public function woo_ms_hide_body_classes( $classes ) {
		foreach ( $this->hide_classes() as $option_name => $class ) {
			if ( $this->enabled( $option_name ) ) {
				$classes[] = $class;
			}
		}
		return $classes;
	}

	public function woo_ms_plugin_wrapper_start() {
		$html = get_option( 'html_after_header' );
		if ( '' !== trim( (string) $html ) && $this->enabled( 'theme_support' ) ) {
			echo wp_kses_post( $html );
		}
	}

	public function woo_ms_plugin_wrapper_end() {
		$html = get_option( 'html_before_footer' );
		if ( '' !== trim( (string) $html ) && $this->enabled( 'theme_support' ) ) {
			echo wp_kses_post( $html );
		}
	}

	public function woo_ms_use_block_editor_for_post_type( $current_status, $post_type ) {
		if ( 'product' === $post_type && $this->enabled( 'post_type_gutenberg_editor' ) ) {
			return true;
		}
		return $current_status;
	}

	/**
	 * The setting reads "show empty categories", so enabling it means
	 * hide_empty = false.
	 *
	 * Previously this returned nothing at all, so the filter received null
	 * and every category was treated as hidden.
	 */
	public function woo_ms_subcategories_hide_empty( $hide_empty ) {
		if ( $this->enabled( 'woo_categories_hide_empty' ) ) {
			return false;
		}
		return $hide_empty;
	}

	/**
	 * Add-to-cart button text per product type.
	 *
	 * Fixes: `$product->product_type` was removed in WooCommerce 3.0, the
	 * global was used instead of the filter's own argument, and the default
	 * branch replaced the text for EVERY other product type (including ones
	 * added by extensions) with "Read more".
	 */
	public function woo_ms_button_text( $text, $product = null ) {
		if ( ! $product instanceof WC_Product ) {
			$product = isset( $GLOBALS['product'] ) ? $GLOBALS['product'] : null;
		}
		if ( ! $product instanceof WC_Product ) {
			return $text;
		}

		$options = array(
			'simple'   => 'simple_pro_btn',
			'variable' => 'variable_pro_btn',
			'external' => 'external_pro_btn',
			'grouped'  => 'grouped_pro_btn',
		);

		$type = $product->get_type();
		if ( ! isset( $options[ $type ] ) ) {
			return $text;
		}

		$custom = get_option( $options[ $type ] );

		return ( '' !== trim( (string) $custom ) ) ? $custom : $text;
	}

	/**
	 * The "Cart Title Text" option was saved but read by nothing at all, so
	 * setting it did nothing. It renders here, above the intro markup.
	 */
	public function woo_ms_woocommerce_before_cart_table_action() {
		$cart_heading = get_option( 'cart_h_text' );
		if ( '' !== trim( (string) $cart_heading ) ) {
			echo '<h2 class="woocss-cart-title">' . esc_html( $cart_heading ) . '</h2>';
		}

		$cart_table_text = get_option( 'table_before_text' );
		if ( '' !== trim( (string) $cart_table_text ) ) {
			echo wp_kses_post( $cart_table_text );
		}
	}

	public function woo_ms_woocommerce_product_related_products_heading( $default_text ) {
		$custom = get_option( 'related_product_text' );
		return ( '' !== trim( (string) $custom ) ) ? $custom : $default_text;
	}

	public function woo_ms_woocommerce_return_to_shop_text( $btn_default_text ) {
		$custom = get_option( 'cartpagereturn_to_shop_text' );
		return ( '' !== trim( (string) $custom ) ) ? $custom : $btn_default_text;
	}

	/**
	 * Fixes an undefined variable ($cartpagereturn_to_shop_text) that made
	 * this a fatal error on PHP 8 whenever a customer reached the thank-you
	 * page.
	 */
	public function woo_ms_order_received_text( $text_html, $order = null ) {
		$custom = get_option( 'checkbox_message_thankyou_order_received_text' );
		return ( '' !== trim( (string) $custom ) ) ? wp_kses_post( $custom ) : $text_html;
	}

	/**
	 * Keeps the PHP loop column count in step with the CSS grid setting, so
	 * pagination and "products per row" agree.
	 */
	public function woo_ms_loop_columns( $columns ) {
		$custom = $this->positive_int( 'woocss_columns', 6 );
		return ( null === $custom ) ? $columns : $custom;
	}

	public function woo_ms_products_per_page( $per_page ) {
		$custom = $this->positive_int( 'woo_products_per_page', 200 );
		return ( null === $custom ) ? $per_page : $custom;
	}

	public function woo_ms_gallery_columns( $columns ) {
		$custom = $this->positive_int( 'woo_gallery_columns', 8 );
		return ( null === $custom ) ? $columns : $custom;
	}

	/**
	 * Removes whichever product tabs the merchant unticked.
	 */
	public function woo_ms_product_tabs( $tabs ) {
		$hidden = get_option( 'woo_hide_tabs' );
		if ( ! is_array( $hidden ) ) {
			return $tabs;
		}

		foreach ( $hidden as $tab_key ) {
			unset( $tabs[ $tab_key ] );
		}

		return $tabs;
	}

	/**
	 * Related products count and columns.
	 *
	 * Columns fall back to the Layout section's "products per row" so the
	 * related grid lines up with the shop grid by default.
	 */
	public function woo_ms_related_products_args( $args ) {
		$count = $this->positive_int( 'woo_related_count', 24 );
		if ( null !== $count ) {
			$args['posts_per_page'] = $count;
		}

		$columns = $this->positive_int( 'woo_related_columns', 6 );
		if ( null === $columns ) {
			$columns = $this->positive_int( 'woocss_columns', 6 );
		}
		if ( null !== $columns ) {
			$args['columns'] = $columns;
		}

		return $args;
	}

	/**
	 * Drops WooCommerce's own stylesheets for themes that style WooCommerce
	 * themselves.
	 *
	 * This plugin's stylesheet deliberately declares no dependency on
	 * woocommerce-general, so it keeps loading either way.
	 */
	public function woo_ms_disable_styles( $styles ) {
		$mode = (string) get_option( 'woo_disable_styles' );

		if ( 'all' === $mode ) {
			return array();
		}

		if ( 'general' === $mode ) {
			unset( $styles['woocommerce-general'] );
		}

		return $styles;
	}

	/**
	 * Cart fragments drive the AJAX mini-cart counter. Dropping the script
	 * removes a request from every page load; a header cart count will then
	 * only update on reload, which the setting description says.
	 */
	public function woo_ms_disable_cart_fragments() {
		wp_dequeue_script( 'wc-cart-fragments' );
	}
}
