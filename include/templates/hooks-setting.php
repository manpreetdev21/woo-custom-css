<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Theme Hooks section.
 *
 * Every toggle here maps to one WooCommerce action or filter, applied in
 * WOOHOOKSETTING. Nothing is done with CSS display:none — the markup is
 * genuinely not rendered, so it costs nothing and stays out of the DOM for
 * screen readers too.
 */
$settings = array(
	array(
		'title' => __( 'Hooks Options', 'woocustomcss' ),
		'type'  => 'title',
		'desc'  => __( 'WooCommerce hook options for the custom theme.', 'woocustomcss' ),
		'id'    => 'hooks_options',
	),

	/* ---------------------------------------------------------------------
	 * Shop / archive
	 * ------------------------------------------------------------------- */
	array(
		'title' => __( 'Shop &amp; Archive Hooks', 'woocustomcss' ),
		'type'  => 'title',
		'desc'  => __( 'Controls the shop, category and search result pages.', 'woocustomcss' ),
		'id'    => 'woocommerce_shop_page_settings',
	),
	array(
		'title'   => __( 'Woo Breadcrumb', 'woocustomcss' ),
		'desc'    => __( 'Hide WooCommerce Breadcrumb from shop page.', 'woocustomcss' ),
		'id'      => 'woo_breadcrumb',
		'default' => 'no',
		'type'    => 'checkbox',
	),
	array(
		'title'   => __( 'Woo Default Sidebar', 'woocustomcss' ),
		'desc'    => __( 'Hide WooCommerce Sidebar from shop page.', 'woocustomcss' ),
		'id'      => 'woo_sidebar',
		'default' => 'no',
		'type'    => 'checkbox',
	),
	array(
		'title'   => __( 'Woo Related products', 'woocustomcss' ),
		'desc'    => __( 'Hide WooCommerce Related products from product detail page.', 'woocustomcss' ),
		'id'      => 'woo_related_pro',
		'default' => 'no',
		'type'    => 'checkbox',
	),
	array(
		'title'   => __( 'Enable WordPress Gutenberg Editor', 'woocustomcss' ),
		'desc'    => __( 'If you want to use WordPress Gutenberg Editor for the product edit section.', 'woocustomcss' ),
		'id'      => 'post_type_gutenberg_editor',
		'default' => 'no',
		'type'    => 'checkbox',
	),
	array(
		'title'   => __( 'Woo Categories Hide Empty', 'woocustomcss' ),
		'desc'    => __( 'Show Empty Categories on the shop page.', 'woocustomcss' ),
		'id'      => 'woo_categories_hide_empty',
		'default' => 'no',
		'type'    => 'checkbox',
	),
	array(
		'title'   => __( 'Woo Product Image Hover Effect', 'woocustomcss' ),
		'desc'    => __( 'Swap to the first gallery image on hover. Needs at least one gallery image on the product.', 'woocustomcss' ),
		'id'      => 'woo_product_img_hovr',
		'default' => 'no',
		'type'    => 'checkbox',
	),
	array(
		'title'             => __( 'Products Per Page', 'woocustomcss' ),
		'desc'              => __( 'How many products each shop/category page shows before pagination. Leave empty for the theme default.', 'woocustomcss' ),
		'id'                => 'woo_products_per_page',
		'type'              => 'number',
		'css'               => 'width:6em;',
		'default'           => '',
		'custom_attributes' => array(
			'min' => '1',
			'max' => '200',
		),
		'autoload'          => false,
		'desc_tip'          => true,
	),
	array(
		'title'    => __( 'Hide Result Count', 'woocustomcss' ),
		'desc'     => __( 'Hide the "Showing 1&ndash;12 of 40 results" line.', 'woocustomcss' ),
		'id'       => 'woo_hide_result_count',
		'default'  => 'no',
		'type'     => 'checkbox',
		'autoload' => false,
	),
	array(
		'title'    => __( 'Hide Sorting Dropdown', 'woocustomcss' ),
		'desc'     => __( 'Hide the "Default sorting" select on archives.', 'woocustomcss' ),
		'id'       => 'woo_hide_ordering',
		'default'  => 'no',
		'type'     => 'checkbox',
		'autoload' => false,
	),
	array(
		'title'    => __( 'Hide Sale Badge', 'woocustomcss' ),
		'desc'     => __( 'Remove the "Sale!" flash from both the loop and the single product image.', 'woocustomcss' ),
		'id'       => 'woo_hide_sale_flash',
		'default'  => 'no',
		'type'     => 'checkbox',
		'autoload' => false,
	),
	array(
		'title'    => __( 'Hide Star Rating In Loop', 'woocustomcss' ),
		'desc'     => __( 'Remove the star rating from product cards.', 'woocustomcss' ),
		'id'       => 'woo_hide_loop_rating',
		'default'  => 'no',
		'type'     => 'checkbox',
		'autoload' => false,
	),
	array(
		'title'    => __( 'Hide Price In Loop', 'woocustomcss' ),
		'desc'     => __( 'Remove the price from product cards.', 'woocustomcss' ),
		'id'       => 'woo_hide_loop_price',
		'default'  => 'no',
		'type'     => 'checkbox',
		'autoload' => false,
	),
	array(
		'title'    => __( 'Hide Add To Cart In Loop', 'woocustomcss' ),
		'desc'     => __( 'Remove the add-to-cart button from product cards, so the card links through to the product page instead.', 'woocustomcss' ),
		'id'       => 'woo_hide_loop_add_to_cart',
		'default'  => 'no',
		'type'     => 'checkbox',
		'autoload' => false,
	),
	// Read by WOOHOOKSETTING::woo_ms_plugin_wrapper_*.
	array(
		'title'    => __( 'HTML After Header', 'woocustomcss' ),
		'desc'     => __( 'Markup printed at woocommerce_before_main_content — your theme\'s opening wrapper. Requires "Theme Support" to be enabled.', 'woocustomcss' ),
		'id'       => 'html_after_header',
		'type'     => 'textarea',
		'css'      => 'width:100%;height:80px;',
		'default'  => '',
		'autoload' => false,
		'desc_tip' => true,
	),
	array(
		'title'    => __( 'HTML Before Footer', 'woocustomcss' ),
		'desc'     => __( 'Markup printed at woocommerce_after_main_content — the matching closing wrapper. Requires "Theme Support" to be enabled.', 'woocustomcss' ),
		'id'       => 'html_before_footer',
		'type'     => 'textarea',
		'css'      => 'width:100%;height:80px;',
		'default'  => '',
		'autoload' => false,
		'desc_tip' => true,
	),
	array(
		'type' => 'sectionend',
		'id'   => 'woocommerce_shop_page_settings',
	),

	/* ---------------------------------------------------------------------
	 * Single product
	 * ------------------------------------------------------------------- */
	array(
		'title' => __( 'Single Product Hooks', 'woocustomcss' ),
		'type'  => 'title',
		'desc'  => __( 'Controls the individual product page.', 'woocustomcss' ),
		'id'    => 'woocommerce_single_product_settings',
	),
	array(
		'title'    => __( 'Hide SKU', 'woocustomcss' ),
		'desc'     => __( 'Hide the SKU everywhere it is output, including the product meta block.', 'woocustomcss' ),
		'id'       => 'woo_hide_sku',
		'default'  => 'no',
		'type'     => 'checkbox',
		'autoload' => false,
	),
	array(
		'title'    => __( 'Hide Product Meta', 'woocustomcss' ),
		'desc'     => __( 'Remove the categories / tags / SKU block below the add-to-cart form.', 'woocustomcss' ),
		'id'       => 'woo_hide_product_meta',
		'default'  => 'no',
		'type'     => 'checkbox',
		'autoload' => false,
	),
	array(
		'title'    => __( 'Hide Upsells', 'woocustomcss' ),
		'desc'     => __( 'Remove the "You may also like" upsell block.', 'woocustomcss' ),
		'id'       => 'woo_hide_upsells',
		'default'  => 'no',
		'type'     => 'checkbox',
		'autoload' => false,
	),
	array(
		'title'    => __( 'Hide Product Tabs', 'woocustomcss' ),
		'desc'     => __( 'Tabs to remove from the product page. Ctrl/Cmd-click to select more than one.', 'woocustomcss' ),
		'id'       => 'woo_hide_tabs',
		'type'     => 'multiselect',
		'class'    => 'wc-enhanced-select',
		'css'      => 'width:25em;',
		'options'  => array(
			'description'            => __( 'Description', 'woocustomcss' ),
			'additional_information' => __( 'Additional information', 'woocustomcss' ),
			'reviews'                => __( 'Reviews', 'woocustomcss' ),
		),
		'default'  => array(),
		'autoload' => false,
		'desc_tip' => true,
	),
	array(
		'title'             => __( 'Related Products Count', 'woocustomcss' ),
		'desc'              => __( 'How many related products to show. Leave empty for the WooCommerce default (4).', 'woocustomcss' ),
		'id'                => 'woo_related_count',
		'type'              => 'number',
		'css'               => 'width:6em;',
		'default'           => '',
		'custom_attributes' => array(
			'min' => '1',
			'max' => '24',
		),
		'autoload'          => false,
		'desc_tip'          => true,
	),
	array(
		'title'             => __( 'Related Products Columns', 'woocustomcss' ),
		'desc'              => __( 'Columns for the related products grid. Leave empty to follow the Layout section.', 'woocustomcss' ),
		'id'                => 'woo_related_columns',
		'type'              => 'number',
		'css'               => 'width:6em;',
		'default'           => '',
		'custom_attributes' => array(
			'min' => '1',
			'max' => '6',
		),
		'autoload'          => false,
		'desc_tip'          => true,
	),
	array(
		'title'             => __( 'Gallery Thumbnail Columns', 'woocustomcss' ),
		'desc'              => __( 'Columns in the product image gallery thumbnail strip.', 'woocustomcss' ),
		'id'                => 'woo_gallery_columns',
		'type'              => 'number',
		'css'               => 'width:6em;',
		'default'           => '',
		'custom_attributes' => array(
			'min' => '1',
			'max' => '8',
		),
		'autoload'          => false,
		'desc_tip'          => true,
	),
	array(
		'type' => 'sectionend',
		'id'   => 'woocommerce_single_product_settings',
	),

	/* ---------------------------------------------------------------------
	 * Add to cart button text
	 * ------------------------------------------------------------------- */
	array(
		'title' => __( 'Add To Cart Button Text', 'woocustomcss' ),
		'type'  => 'title',
		'desc'  => __( 'Leave a field empty to keep the WooCommerce default.', 'woocustomcss' ),
		'id'    => 'woocommerce_shop_settings',
	),
	array(
		'title'       => __( 'Simple Product button', 'woocustomcss' ),
		'desc'        => __( 'Here you can change the add to cart button text for Simple products. The default text is "Add To Cart"', 'woocustomcss' ),
		'id'          => 'simple_pro_btn',
		'placeholder' => 'Add to cart',
		'default'     => '',
		'type'        => 'text',
		'desc_tip'    => true,
	),
	array(
		'title'       => __( 'Variable Product button', 'woocustomcss' ),
		'desc'        => __( 'Here you can change the add to cart button text for Variable products. The default text is "Select Options"', 'woocustomcss' ),
		'id'          => 'variable_pro_btn',
		'placeholder' => 'Select options',
		'default'     => '',
		'type'        => 'text',
		'desc_tip'    => true,
	),
	array(
		'title'       => __( 'External Product button', 'woocustomcss' ),
		'desc'        => __( 'Here you can change the add to cart button text for External products. The default text is "Buy product"', 'woocustomcss' ),
		'id'          => 'external_pro_btn',
		'placeholder' => 'Buy product',
		'default'     => '',
		'type'        => 'text',
		'desc_tip'    => true,
	),
	array(
		'title'       => __( 'Grouped Product button', 'woocustomcss' ),
		'desc'        => __( 'Here you can change the add to cart button text for Grouped products. The default text is "View products"', 'woocustomcss' ),
		'id'          => 'grouped_pro_btn',
		'placeholder' => 'View products',
		'default'     => '',
		'type'        => 'text',
		'desc_tip'    => true,
	),
	array(
		'title'       => __( 'Related Product Text', 'woocustomcss' ),
		'desc'        => __( 'Here you can change the related products heading text. The default text is "Related Products"', 'woocustomcss' ),
		'id'          => 'related_product_text',
		'placeholder' => 'Related products',
		'default'     => '',
		'type'        => 'text',
		'desc_tip'    => true,
	),
	array(
		'type' => 'sectionend',
		'id'   => 'woocommerce_shop_settings',
	),

	/* ---------------------------------------------------------------------
	 * Cart
	 * ------------------------------------------------------------------- */
	array(
		'title' => __( 'Cart Page Hooks', 'woocustomcss' ),
		'type'  => 'title',
		'desc'  => '',
		'id'    => 'cart_settin_options',
	),
	array(
		'title'    => __( 'Before Table Text', 'woocustomcss' ),
		'desc'     => __( 'Add text before cart table form. Using HTML format.', 'woocustomcss' ),
		'id'       => 'table_before_text',
		'type'     => 'textarea',
		'css'      => 'width:100%;height:80px;',
		'desc_tip' => true,
	),
	array(
		'title'    => __( 'Cart Title Text', 'woocustomcss' ),
		'desc'     => __( 'Heading rendered above the cart table.', 'woocustomcss' ),
		'id'       => 'cart_h_text',
		'type'     => 'text',
		'desc_tip' => true,
	),
	array(
		'title'       => __( 'Cart Empty Return to Shop button text', 'woocustomcss' ),
		'desc'        => __( 'Here you can change the cart page return to shop page button text.', 'woocustomcss' ),
		'id'          => 'cartpagereturn_to_shop_text',
		'placeholder' => 'Return to shop',
		'default'     => '',
		'type'        => 'text',
		'desc_tip'    => true,
	),
	array(
		'title'    => __( 'Hide Cross-Sells', 'woocustomcss' ),
		'desc'     => __( 'Remove the "You may be interested in" block below the cart.', 'woocustomcss' ),
		'id'       => 'woo_hide_cross_sells',
		'default'  => 'no',
		'type'     => 'checkbox',
		'autoload' => false,
	),
	array(
		'title'    => __( 'Disable Coupons', 'woocustomcss' ),
		'desc'     => __( 'Turn off coupons store-wide, which also removes the coupon form from the cart and checkout.', 'woocustomcss' ),
		'id'       => 'woo_disable_coupons',
		'default'  => 'no',
		'type'     => 'checkbox',
		'autoload' => false,
	),
	array(
		'type' => 'sectionend',
		'id'   => 'cart_settin_options',
	),

	/* ---------------------------------------------------------------------
	 * Checkout
	 * ------------------------------------------------------------------- */
	array(
		'title' => __( 'Checkout Page Hooks', 'woocustomcss' ),
		'type'  => 'title',
		'desc'  => '',
		'id'    => 'woocommerce_checkout_page_settings',
	),
	array(
		'title'       => __( 'Message thankyou order received text', 'woocustomcss' ),
		'desc'        => __( 'Here you can change the checkout after payment Thankyou message text.', 'woocustomcss' ),
		'id'          => 'checkbox_message_thankyou_order_received_text',
		'placeholder' => 'Thank you. Your order has been received.',
		'default'     => '',
		'type'        => 'text',
		'desc_tip'    => true,
	),
	array(
		'title'    => __( 'Hide Order Notes', 'woocustomcss' ),
		'desc'     => __( 'Remove the "Order notes" textarea from the checkout form.', 'woocustomcss' ),
		'id'       => 'woo_hide_order_notes',
		'default'  => 'no',
		'type'     => 'checkbox',
		'autoload' => false,
	),
	array(
		'type' => 'sectionend',
		'id'   => 'woocommerce_checkout_page_settings',
	),

	/* ---------------------------------------------------------------------
	 * Theme integration
	 * ------------------------------------------------------------------- */
	array(
		'title' => __( 'Theme Integration', 'woocustomcss' ),
		'type'  => 'title',
		'desc'  => __( 'For themes that style WooCommerce themselves. Test on a staging site first — these change what loads on the front end.', 'woocustomcss' ),
		'id'    => 'woocommerce_theme_integration_settings',
	),
	array(
		'title'    => __( 'WooCommerce Stylesheets', 'woocustomcss' ),
		'desc'     => __( 'Stop WooCommerce loading its own CSS. "Layout only" keeps the grid and responsive rules but drops the colours and component styling, which is usually what a custom theme wants.', 'woocustomcss' ),
		'id'       => 'woo_disable_styles',
		'type'     => 'select',
		'options'  => array(
			''        => __( 'Load all (default)', 'woocustomcss' ),
			'general' => __( 'Layout only — drop woocommerce-general', 'woocustomcss' ),
			'all'     => __( 'Load none', 'woocustomcss' ),
		),
		'css'      => 'width:25em;',
		'default'  => '',
		'autoload' => false,
		'desc_tip' => true,
	),
	array(
		'title'    => __( 'Disable Cart Fragments', 'woocustomcss' ),
		'desc'     => __( 'Stops the wc-cart-fragments AJAX request on every page load. Faster, but a mini-cart counter in your header will no longer update without a page refresh — leave this off if your theme has one.', 'woocustomcss' ),
		'id'       => 'woo_disable_cart_fragments',
		'default'  => 'no',
		'type'     => 'checkbox',
		'autoload' => false,
	),
	array(
		'type' => 'sectionend',
		'id'   => 'woocommerce_theme_integration_settings',
	),

	array(
		'type' => 'sectionend',
		'id'   => 'hooks_options',
	),
);
