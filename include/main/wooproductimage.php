<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Product image hover swap.
 *
 * Catalogue cards come in at least three shapes in the wild:
 *
 *  1. WooCommerce's classic content-product.php  (ul.products > li.product)
 *  2. A theme's own override of it               (Bravis: div.card-product)
 *  3. The Product Collection block               (li.wc-block-product)
 *
 * The plugin used to swap the thumbnail server side, by unhooking
 * woocommerce_template_loop_product_thumbnail and printing its own markup.
 * That only ever worked for shape 1: shape 2 never fires the hook at all, and
 * on shape 3 the block still renders its own product image, so the card ended
 * up showing the picture twice.
 *
 * So the image is no longer re-rendered. The gallery URL is handed to a small
 * script that overlays a second image on whatever the markup already
 * produced, which behaves the same in all three. Without JavaScript the card
 * is simply left alone — the swap is decoration, so that degrades cleanly.
 *
 * the_post is the collection anchor because every one of the three runs
 * through it: the classic loop, a theme override, and WooCommerce's own
 * ProductTemplate block, which calls $query->the_post() per product.
 */
class WOOPRODUCTIMAGE {

	/**
	 * product id => secondary image URL, collected during the loop.
	 */
	private $gallery = array();

	public function __construct() {
		add_action( 'woocommerce_init', array( $this, 'woo_ms_product_image_remove_add' ) );
	}

	private function enabled() {
		return 'yes' === get_option( 'woo_product_img_hovr' );
	}

	public function woo_ms_product_image_remove_add() {
		if ( ! $this->enabled() ) {
			return;
		}

		add_action( 'the_post', array( $this, 'collect_gallery_image' ) );
		add_action( 'wp_footer', array( $this, 'print_gallery_data' ), 5 );
	}

	/**
	 * First gallery image for a product, or '' when it has none.
	 */
	private function secondary_image_url( $product, $size = 'woocommerce_thumbnail' ) {
		if ( ! $product instanceof WC_Product ) {
			return '';
		}

		$gallery_ids = $product->get_gallery_image_ids();
		if ( empty( $gallery_ids ) ) {
			return '';
		}

		$url = wp_get_attachment_image_url( reset( $gallery_ids ), $size );

		return $url ? $url : '';
	}

	/**
	 * Records the product currently being set up by a loop.
	 *
	 * @param WP_Post|null $post The post the_post just set up.
	 */
	public function collect_gallery_image( $post = null ) {
		// A single product page is not a catalogue card; its own gallery
		// already handles alternate images.
		if ( is_singular( 'product' ) ) {
			return;
		}

		$post_id = ( $post instanceof WP_Post ) ? $post->ID : get_the_ID();
		if ( ! $post_id || 'product' !== get_post_type( $post_id ) ) {
			return;
		}

		if ( isset( $this->gallery[ $post_id ] ) ) {
			return;
		}

		$url = $this->secondary_image_url( wc_get_product( $post_id ) );
		if ( '' !== $url ) {
			$this->gallery[ $post_id ] = $url;
		}
	}

	/**
	 * Hands the collected URLs to app-script.js.
	 *
	 * Runs at wp_footer:5, ahead of wp_print_footer_scripts at 20, so the
	 * data is in place before the script that reads it.
	 */
	public function print_gallery_data() {
		if ( empty( $this->gallery ) ) {
			return;
		}

		if ( ! wp_script_is( 'woocustomcss-app', 'enqueued' ) ) {
			wp_enqueue_script(
				'woocustomcss-app',
				WOO_EDITING__URL . 'assets/frontend/js/app-script.js',
				array(),
				WOO_SETTING_VERSION,
				true
			);
		}

		wp_add_inline_script(
			'woocustomcss-app',
			'var wooCustomCssGallery = ' . wp_json_encode( $this->gallery ) . ';',
			'before'
		);
	}
}
