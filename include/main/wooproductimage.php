<?php 

if ( ! defined( 'ABSPATH' ) ) {
     die;
}

class WOOPRODUCTIMAGE {
	
	public function __construct(){
		add_action( 'woocommerce_init' , array( $this, 'woo_ms_product_image_remove_add' ) );
	}

	public function woo_ms_product_image_remove_add(){
		$woo_product_img_hovr = get_option( 'woo_product_img_hovr' );
        if( $woo_product_img_hovr === 'yes' ) {
			//add_action( 'wp_head', array( $this, 'woo_ms_product_image_hover_style' ) );
            remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
            add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'woo_ms_product_loop_thumbnail' ), 10 );
			add_action( 'wp_enqueue_scripts', array( $this, 'woo_ms_product_image_hover_script' ) );
        }
	}
	
	public function woo_ms_product_loop_thumbnail( $size = 'shop_catalog' ){
		global $post;
		
		echo '<div id="product_'.$post->ID.'" class="woo_pro_img pro_'.$post->ID.'">';
		if ( has_post_thumbnail( $post->ID ) ) {   
            echo get_the_post_thumbnail( $post->ID, $size, array( 'class' => 'img-fluid product-img', 'alt' => get_the_title() ) ); 
        } else {
            echo  '<img src="'. woocommerce_placeholder_img_src() .'" class="img-fluid product-img" alt="'.get_the_title().'" />';
		}
        $product = new WC_product( $post->ID );
        $product_gallery_image = isset( $product->get_gallery_image_ids()[0] ) ? $product->get_gallery_image_ids()[0] : '';
        if ( $product_gallery_image ) {
            echo wp_get_attachment_image( $product_gallery_image, $size, '', array( 'class' => 'img-fluid product_img_2', 'alt' => get_the_title() ) ) ;
        } else {
            echo get_the_post_thumbnail( $post->ID, $size, array( 'class' => 'img-fluid product_img_2', 'alt' => get_the_title() ) ); 
        }
		echo '</div>';
	}
	
	public function woo_ms_product_image_hover_script(){
		$file_version = filemtime( WOO_EDITING__DIR . 'assets/frontend/js/app-script.js' );
		wp_enqueue_script( 'app-script_js', WOO_EDITING__URL.'assets/frontend/js/app-script.js', array(), $file_version, false );
	}
	
	public function woo_ms_product_image_hover_style(){
		echo "<style type='text/css'>
		.woo_pro_img {position: relative;}
		.woo_pro_img img{transition: all 0.25s linear;z-index: 0;}
		.woo_pro_img img + img{	position: absolute;	left: 0;top: 0;	opacity: 0;	z-index:1;}
		.woo_pro_img:hover img + img{opacity: 1;}
		</style>";
	}
}