<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Layout section — the grid/geometry controls a theme actually needs.
 */
$settings = array(
	array(
		'title' => __( 'Shop Grid Layout', 'woocustomcss' ),
		'type'  => 'title',
		'desc'  => __( 'Controls the product grid on shop, category and search pages. The column count also drives WooCommerce\'s own loop_shop_columns, so pagination stays in step.', 'woocustomcss' ),
		'id'    => 'woocss_layout_options',
	),
	array(
		'title'    => __( 'Products Per Row', 'woocustomcss' ),
		'desc'     => __( 'Columns on desktop. Tablet and mobile step down automatically and never exceed this value.', 'woocustomcss' ),
		'id'       => 'woocss_columns',
		'type'     => 'select',
		'options'  => array(
			''  => __( 'Theme default (4)', 'woocustomcss' ),
			'1' => __( '1', 'woocustomcss' ),
			'2' => __( '2', 'woocustomcss' ),
			'3' => __( '3', 'woocustomcss' ),
			'4' => __( '4', 'woocustomcss' ),
			'5' => __( '5', 'woocustomcss' ),
			'6' => __( '6', 'woocustomcss' ),
		),
		'css'      => 'width:10em;',
		'default'  => '',
		'autoload' => false,
		'desc_tip' => true,
	),
	array(
		'title'    => __( 'Grid Gap', 'woocustomcss' ),
		'desc'     => __( 'Space between product cards, horizontally and vertically.', 'woocustomcss' ),
		'id'       => 'woocss_grid_gap',
		'type'     => 'ms_size',
		'css'      => 'width:4em;',
		'default'  => '',
		'autoload' => false,
		'desc_tip' => true,
	),
	array(
		'title'    => __( 'Product Image Ratio', 'woocustomcss' ),
		'desc'     => __( 'Locks every product image to one shape so the grid never jumps while images load.', 'woocustomcss' ),
		'id'       => 'woocss_img_ratio',
		'type'     => 'select',
		'options'  => array(
			''       => __( 'Square (1:1) — default', 'woocustomcss' ),
			'4 / 3'  => __( 'Landscape (4:3)', 'woocustomcss' ),
			'3 / 4'  => __( 'Portrait (3:4)', 'woocustomcss' ),
			'2 / 3'  => __( 'Tall (2:3)', 'woocustomcss' ),
			'16 / 9' => __( 'Wide (16:9)', 'woocustomcss' ),
			'auto'   => __( 'Original image ratio', 'woocustomcss' ),
		),
		'css'      => 'width:14em;',
		'default'  => '',
		'autoload' => false,
		'desc_tip' => true,
	),
	array(
		'title'    => __( 'Card Hover Lift', 'woocustomcss' ),
		'desc'     => __( 'Raise the product card slightly on hover. Automatically disabled for visitors who ask for reduced motion.', 'woocustomcss' ),
		'id'       => 'woocss_card_hover',
		'default'  => 'no',
		'type'     => 'checkbox',
		'autoload' => false,
	),
	array(
		'title'    => __( 'Accent Color', 'woocustomcss' ),
		'desc'     => __( 'Used for focus rings, active tabs, pagination and any element with no explicit colour of its own.', 'woocustomcss' ),
		'id'       => 'woocss_accent',
		'type'     => 'color',
		'css'      => 'width:6em;',
		'default'  => '',
		'autoload' => false,
		'desc_tip' => true,
	),
	array(
		'type' => 'sectionend',
		'id'   => 'woocss_layout_options',
	),
);
