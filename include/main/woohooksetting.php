<?php 

if ( ! defined( 'ABSPATH' ) ) {
     die;
}

class WOOHOOKSETTING{
	
	public function __construct(){
		add_action( 'after_setup_theme', array( $this, 'woo_ms_pluign_woocommerce_support' ) );
		add_action( 'woocommerce_before_main_content', array( $this, 'woo_ms_plugin_wrapper_start' ), 10);
		add_action( 'woocommerce_after_main_content', array( $this, 'woo_ms_plugin_wrapper_end' ), 10);
		add_filter( 'use_block_editor_for_post_type', array( $this, 'woo_ms_use_block_editor_for_post_type' ), 10, 2 );
		add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'woo_ms_button_text' ) );
		add_action( 'woocommerce_before_cart_table', array( $this, 'woo_ms_woocommerce_before_cart_table_action' ) );
		add_filter( 'woocommerce_product_related_products_heading', array( $this, 'woo_ms_woocommerce_product_related_products_heading' ) );
		add_filter( 'woocommerce_return_to_shop_text', array( $this, 'woo_ms_woocommerce_return_to_shop_text' ) );
		add_filter( 'woocommerce_thankyou_order_received_text', array( $this, 'woo_ms_order_received_text' ), 10, 2 );
		add_filter( 'woocommerce_product_subcategories_hide_empty', array( $this, 'woo_ms_subcategories_hide_empty' ), 10, 1 );
	}
	
	public function woo_ms_pluign_woocommerce_support(){
		$theme_support = get_option( 'theme_support' );
		$gallery_zoom = get_option( 'gallery_zoom' );
		$gallery_lightbox = get_option( 'gallery_lightbox' );
		$gallery_slider = get_option( 'gallery_slider' );
		$woo_breadcrumb = get_option( 'woo_breadcrumb' );
		$woo_sidebar = get_option( 'woo_sidebar' );
		$woo_related_pro = get_option( 'woo_related_pro' );

		if( !empty( $theme_support == 'yes' ) ){
			add_theme_support( 'woocommerce' );
		}
		if( !empty( $gallery_zoom == 'yes' ) ){
			add_theme_support( 'wc-product-gallery-zoom' );
		}
		if( !empty( $gallery_lightbox == 'yes' ) ){
			add_theme_support( 'wc-product-gallery-lightbox' );
		}
		if( !empty( $gallery_slider == 'yes' ) ){
			add_theme_support( 'wc-product-gallery-slider' );
		}
		if( !empty( $woo_breadcrumb == 'yes' ) ){
			remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0 );
		}
		if( !empty( $woo_sidebar == 'yes' ) ){
			remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
		}
		if( !empty( $woo_related_pro == 'yes' ) ){
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
		}
	}

	public function woo_ms_plugin_wrapper_start(){
		$wrapper_start_textarea = get_option( 'html_after_header' );
		$theme_support = get_option( 'theme_support' );
		if( ( $wrapper_start_textarea != '' ) && ( !empty( $theme_support == 'yes' ) ) ){
			echo $wrapper_start_textarea;
		}
	}

	public function woo_ms_plugin_wrapper_end(){
		$wrapper_end_textarea = get_option( 'html_before_footer' );
		$theme_support = get_option( 'theme_support' );
		if( ( $wrapper_end_textarea != '' ) && ( !empty( $theme_support == 'yes' ) ) ){
			echo $wrapper_end_textarea;
		}
	}

	public function woo_ms_use_block_editor_for_post_type( $current_status, $post_type ){
		$enable_editor = get_option( 'post_type_gutenberg_editor' );            
		if( $enable_editor == 'yes' ){
			if ($post_type === 'product'){					
				$current_status = true;					
			}       
		}
		return $current_status;
	}            
	
	public function woo_ms_subcategories_hide_empty( $hide_empty ) {
		$woo_categories_hide_empty = get_option( 'woo_categories_hide_empty' );
		if( $woo_categories_hide_empty == 'yes' ){
			$hide_empty = FALSE;
		}            
	}
	public function woo_ms_button_text(){
		global $product;
		$product_type = $product->product_type;
		
		$simple_btn = get_option( 'simple_pro_btn' );
		$variable_btn = get_option( 'variable_pro_btn' );
		$external_btn = get_option( 'external_pro_btn' );
		$grouped_btn = get_option( 'grouped_pro_btn' );

		switch ( $product_type ) {
			case 'simple':
				if( $simple_btn != '' ){
					return __( $simple_btn , 'woocommerce' );
				} else{
					return __( 'Add to cart', 'woocommerce' );
				}				
				break;				
			case 'variable':
				if( $variable_btn != '' ){
					return __( $variable_btn , 'woocommerce' );
				} else{
					return __( 'Select options', 'woocommerce' );
				}
				break;				
			case 'external':
				if( $external_btn != '' ){
					return __( $external_btn , 'woocommerce' );
				} else{
					return __( 'Buy product', 'woocommerce' );
				}
				break;				
			case 'grouped':
				if( $grouped_btn != '' ){
					return __( $grouped_btn , 'woocommerce' );
				} else{
					return __( 'View products', 'woocommerce' );
				}
				break;
			default:
				return __( 'Read more', 'woocommerce' );

		}
	}

	public function woo_ms_woocommerce_before_cart_table_action(){
		$cart_table_text = get_option( 'table_before_text' );
		if( !empty( $cart_table_text ) ){
			echo $cart_table_text;
		}
	}
	
	public function woo_ms_woocommerce_product_related_products_heading($default_text){
		$related_product_text = get_option( 'related_product_text' );
		if( $related_product_text != '' ){
			$default_text === $related_product_text;
			return $related_product_text;
		}else{
			return $default_text;
		}
	}
	
	public function woo_ms_woocommerce_return_to_shop_text( $btn_default_text ){
		$cartpagereturn_to_shop_text = get_option( 'cartpagereturn_to_shop_text' );
		if( $cartpagereturn_to_shop_text != '' ){
			$btn_default_text === $cartpagereturn_to_shop_text;
			return $cartpagereturn_to_shop_text;
		}else{
			return $btn_default_text;
		}
	}
	
	public function woo_ms_order_received_text( $text_html, $order ){
		$checkbox_message_thankyou = get_option( 'checkbox_message_thankyou_order_received_text' );
		if( $cartpagereturn_to_shop_text != '' ){
			$text_html === $checkbox_message_thankyou;
			return $checkbox_message_thankyou;
		} else {
			return $text_html;
		}
	}
}