<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * General section — theme support, global typography and the page-width
 * container the shop renders inside.
 */
$settings = array(
	array(
		'title' => __( 'Theme Support', 'woocustomcss' ),
		'type'  => 'title',
		'desc'  => __( 'Declares WooCommerce support on behalf of your theme. Leave these off if the theme already declares them itself — doing it twice is harmless, but the theme is the better place for it.', 'woocustomcss' ),
		'id'    => 'woocommerce_general_settings',
	),
	array(
		'title'           => __( 'Theme Support', 'woocustomcss' ),
		'desc'            => __( 'Add Theme Support.', 'woocustomcss' ),
		'id'              => 'theme_support',
		'default'         => 'no',
		'type'            => 'checkbox',
		'checkboxgroup'   => 'start',
		'show_if_checked' => 'option',
	),
	array(
		'desc'            => __( 'Add Product Gallery Zoom.', 'woocustomcss' ),
		'id'              => 'gallery_zoom',
		'default'         => 'no',
		'type'            => 'checkbox',
		'checkboxgroup'   => '',
		'show_if_checked' => 'yes',
		'autoload'        => false,
	),
	array(
		'desc'            => __( 'Add Product Gallery Lightbox.', 'woocustomcss' ),
		'id'              => 'gallery_lightbox',
		'default'         => 'no',
		'type'            => 'checkbox',
		'checkboxgroup'   => '',
		'show_if_checked' => 'yes',
		'autoload'        => false,
	),
	array(
		'desc'            => __( 'Add Product Gallery Slider.', 'woocustomcss' ),
		'id'              => 'gallery_slider',
		'default'         => 'no',
		'type'            => 'checkbox',
		'show_if_checked' => 'yes',
		'checkboxgroup'   => 'end',
		'autoload'        => false,
	),
	array(
		'type' => 'sectionend',
		'id'   => 'woocommerce_general_settings',
	),

	/* ---------------------------------------------------------------------
	 * Global typography
	 * ------------------------------------------------------------------- */
	array(
		'title' => __( 'Global Typography', 'woocustomcss' ),
		'type'  => 'title',
		'desc'  => __( 'Defaults for every WooCommerce page. The per-section settings override these where they are set.', 'woocustomcss' ),
		'id'    => 'woocommerce_global_typography',
	),
	array(
		'title'    => __( 'Base Font Family', 'woocustomcss' ),
		'desc'     => __( 'Font stack for WooCommerce pages, e.g. "Inter", sans-serif. Leave empty to inherit from the theme — which is usually what you want. This does not load the font; add it in your theme or via Extra CSS.', 'woocustomcss' ),
		'id'       => 'global_font_family',
		'type'     => 'text',
		'css'      => 'width:25em;',
		'default'  => '',
		'autoload' => false,
		'desc_tip' => true,
	),
	array(
		'title'    => __( 'Base Font Size', 'woocustomcss' ),
		'desc'     => __( 'Base text size on WooCommerce pages.', 'woocustomcss' ),
		'id'       => 'global_font_size',
		'type'     => 'ms_size',
		'css'      => 'width:4em;',
		'default'  => '',
		'autoload' => false,
		'desc_tip' => true,
	),
	array(
		'title'    => __( 'Base Text Color', 'woocustomcss' ),
		'desc'     => __( 'Default body text colour on WooCommerce pages.', 'woocustomcss' ),
		'id'       => 'global_text_clr',
		'type'     => 'color',
		'css'      => 'width:6em;',
		'default'  => '',
		'autoload' => false,
		'desc_tip' => true,
	),
	array(
		'title'    => __( 'Muted Text Color', 'woocustomcss' ),
		'desc'     => __( 'Used for secondary text — result counts, meta, addresses and placeholder copy. Keep enough contrast against the page background to stay readable.', 'woocustomcss' ),
		'id'       => 'global_muted_clr',
		'type'     => 'color',
		'css'      => 'width:6em;',
		'default'  => '',
		'autoload' => false,
		'desc_tip' => true,
	),
	array(
		'title'    => __( 'Link Color', 'woocustomcss' ),
		'desc'     => __( 'Colour of links inside WooCommerce content.', 'woocustomcss' ),
		'id'       => 'global_link_clr',
		'type'     => 'color',
		'css'      => 'width:6em;',
		'default'  => '',
		'autoload' => false,
		'desc_tip' => true,
	),
	array(
		'title'    => __( 'Link Hover Color', 'woocustomcss' ),
		'desc'     => __( 'Colour of links on hover and focus.', 'woocustomcss' ),
		'id'       => 'global_link_hover_clr',
		'type'     => 'color',
		'css'      => 'width:6em;',
		'default'  => '',
		'autoload' => false,
		'desc_tip' => true,
	),
	array(
		'type' => 'sectionend',
		'id'   => 'woocommerce_global_typography',
	),

	/* ---------------------------------------------------------------------
	 * Global surfaces
	 * ------------------------------------------------------------------- */
	array(
		'title' => __( 'Global Surfaces', 'woocustomcss' ),
		'type'  => 'title',
		'desc'  => __( 'Shared tokens used across every section — panels, borders and corner rounding.', 'woocustomcss' ),
		'id'    => 'woocommerce_global_surfaces',
	),
	array(
		'title'    => __( 'Surface Color', 'woocustomcss' ),
		'desc'     => __( 'Background of panels, cards, inputs and tables. White by default.', 'woocustomcss' ),
		'id'       => 'global_surface_clr',
		'type'     => 'color',
		'css'      => 'width:6em;',
		'default'  => '',
		'autoload' => false,
		'desc_tip' => true,
	),
	array(
		'title'    => __( 'Border Color', 'woocustomcss' ),
		'desc'     => __( 'Default hairline border colour used across panels, tables and widgets.', 'woocustomcss' ),
		'id'       => 'global_line_clr',
		'type'     => 'color',
		'css'      => 'width:6em;',
		'default'  => '',
		'autoload' => false,
		'desc_tip' => true,
	),
	array(
		'title'    => __( 'Global Corner Radius', 'woocustomcss' ),
		'desc'     => __( 'Base corner rounding for panels, tables and widgets. Per-section radius settings override this.', 'woocustomcss' ),
		'id'       => 'global_radius',
		'type'     => 'ms_size',
		'css'      => 'width:4em;',
		'default'  => '',
		'autoload' => false,
		'desc_tip' => true,
	),
	array(
		'title'             => __( 'Transition Speed', 'woocustomcss' ),
		'desc'              => __( 'How long hover and focus transitions take, in milliseconds. Visitors who ask for reduced motion always get none regardless of this.', 'woocustomcss' ),
		'id'                => 'global_speed',
		'type'              => 'number',
		'css'               => 'width:6em;',
		'default'           => '',
		'custom_attributes' => array(
			'min'  => '0',
			'max'  => '1000',
			'step' => '10',
		),
		'autoload'          => false,
		'desc_tip'          => true,
	),
	array(
		'type' => 'sectionend',
		'id'   => 'woocommerce_global_surfaces',
	),

	/* ---------------------------------------------------------------------
	 * Content width
	 * ------------------------------------------------------------------- */
	array(
		'title' => __( 'Content Width', 'woocustomcss' ),
		'type'  => 'title',
		'desc'  => __( 'Constrains the WooCommerce content area. Leave empty to let the theme handle page width, which is the safer default.', 'woocustomcss' ),
		'id'    => 'woocommerce_container_settings',
	),
	array(
		'title'    => __( 'Max Content Width', 'woocustomcss' ),
		'desc'     => __( 'Maximum width of the WooCommerce content wrapper.', 'woocustomcss' ),
		'id'       => 'global_container_width',
		'type'     => 'ms_size',
		'css'      => 'width:5em;',
		'default'  => '',
		'autoload' => false,
		'desc_tip' => true,
	),
	array(
		'title'    => __( 'Side Gutter', 'woocustomcss' ),
		'desc'     => __( 'Horizontal breathing room either side of the content on small screens.', 'woocustomcss' ),
		'id'       => 'global_container_gutter',
		'type'     => 'ms_size',
		'css'      => 'width:5em;',
		'default'  => '',
		'autoload' => false,
		'desc_tip' => true,
	),
	array(
		'type' => 'sectionend',
		'id'   => 'woocommerce_container_settings',
	),
);
