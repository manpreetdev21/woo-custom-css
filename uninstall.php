<?php
/**
 * Removes every option this plugin creates.
 *
 * Runs only when the plugin is deleted from the Plugins screen, never on
 * deactivation.
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

$woocss_options = array(
	// General
	'theme_support',
	'gallery_zoom',
	'gallery_lightbox',
	'gallery_slider',

	// General — global tokens
	'global_font_family',
	'global_font_size',
	'global_text_clr',
	'global_muted_clr',
	'global_link_clr',
	'global_link_hover_clr',
	'global_surface_clr',
	'global_line_clr',
	'global_radius',
	'global_speed',
	'global_container_width',
	'global_container_gutter',

	// Theme hooks
	'woo_breadcrumb',
	'woo_sidebar',
	'woo_related_pro',
	'post_type_gutenberg_editor',
	'woo_categories_hide_empty',
	'woo_product_img_hovr',
	'html_after_header',
	'html_before_footer',
	'woo_products_per_page',
	'woo_hide_result_count',
	'woo_hide_ordering',
	'woo_hide_sale_flash',
	'woo_hide_loop_rating',
	'woo_hide_loop_price',
	'woo_hide_loop_add_to_cart',
	'woo_hide_sku',
	'woo_hide_product_meta',
	'woo_hide_upsells',
	'woo_hide_tabs',
	'woo_related_count',
	'woo_related_columns',
	'woo_gallery_columns',
	'woo_hide_cross_sells',
	'woo_disable_coupons',
	'woo_hide_order_notes',
	'woo_disable_styles',
	'woo_disable_cart_fragments',
	'simple_pro_btn',
	'variable_pro_btn',
	'external_pro_btn',
	'grouped_pro_btn',
	'related_product_text',
	'table_before_text',
	'cart_h_text',
	'cartpagereturn_to_shop_text',
	'checkbox_message_thankyou_order_received_text',

	// Category loop
	'cat_text_ff',
	'cat_title_clr',
	'cat_title_size',
	'cat_title_fw',
	'cat_box_pading',
	'cat_box_bor',
	'cat_box_bor_radis',
	'cat_box_shadow',
	'cat_box_bg',
	'cat_box_align',

	// Product loop
	'product_text_ff',
	'product_title_text',
	'product_title_size',
	'product_title_fw',
	'product_price_text',
	'product_price_size',
	'product_price_fw',
	'product_box_pading',
	'product_box_bor',
	'product_box_bor_radis',
	'product_box_shadow',
	'product_box_bg',
	'product_box_align',
	'product_img_fit',
	'sale_badge_bg',
	'sale_badge_clr',
	'sale_badge_pos',
	'star_rating_clr',

	// Buttons
	'btn_text_ff',
	'btn_font_size',
	'btn_title_fw',
	'btn_clr',
	'btn_txt_clr',
	'btn_hclr',
	'btn_htxt_clr',
	'product_btn_padding',
	'btn_bor_radius',
	'btn_text_transform',
	'btn_letter_spacing',
	'btn_border',

	// Single product
	'pro_detail_text_ff',
	'pro_detail_title_text',
	'pro_detail_title_size',
	'pro_detail_title_fw',
	'pro_detail_price_text',
	'pro_detail_price_size',
	'pro_detail_price_fw',
	'pro_tab_active_clr',
	'pro_tab_clr',
	'pro_gallery_radius',

	// Cart
	'table_border_size',
	'table_bor_clr',
	'table_bor_radius',
	'table_hbg_clr',
	'table_htext_clr',
	'table_head_size',
	'table_head_upper',
	'table_text_clr',
	'table_row_bg',
	'table_cell_pad',
	'table_thumb_width',
	'cart_remove_bg',
	'cart_remove_clr',
	'cart_remove_bg_hover',
	'cart_totals_bg',
	'cart_totals_width',
	'cart_title_clr',
	'cart_title_size',

	// Checkout
	'field_bordr_clr',
	'field_bg_clr',
	'field_text_clr',
	'field_focus_clr',
	'field_bor_radius',
	'field_height',
	'field_label_clr',
	'field_label_fw',
	'checkout_panel_bg',
	'checkout_panel_bor',
	'checkout_panel_radius',
	'checkout_panel_pad',
	'checkout_place_order_full',

	// Layout
	'woocss_columns',
	'woocss_grid_gap',
	'woocss_img_ratio',
	'woocss_card_hover',
	'woocss_accent',

	// Extra CSS
	'custom_css',
	'custom_css_tablet',
	'custom_css_mobile',
);

foreach ( $woocss_options as $woocss_option ) {
	delete_option( $woocss_option );
}
