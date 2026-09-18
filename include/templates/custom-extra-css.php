<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Extra CSS section.
 *
 * The three editors are concatenated in order by WOOMAIN::build_custom_css(),
 * with the tablet and mobile blocks wrapped in media queries that match the
 * breakpoints used by assets/frontend/css/woocommerce.css.
 */
$settings = array(
	array(
		'title' => __( 'Custom CSS', 'woocustomcss' ),
		'type'  => 'title',
		'desc'  => __( 'Printed inline after the plugin stylesheet, so anything here wins. You can also override the design tokens directly — for example <code>:root{ --woo-accent:#7c3aed; --woo-radius:4px; }</code>. Available tokens include <code>--woo-accent</code>, <code>--woo-radius</code>, <code>--woo-surface</code>, <code>--woo-line</code>, <code>--woo-muted</code>, <code>--woo-card-bg</code>, <code>--woo-card-align</code>, <code>--woo-cat-bg</code>, <code>--woo-sale-bg</code>, <code>--woo-sale-color</code>, <code>--woo-star-color</code>, <code>--woo-img-fit</code> and <code>--woo-btn-transform</code>.', 'woocustomcss' ),
		'id'    => 'custom_css_options',
	),
	array(
		'title'    => __( 'CSS Style Sheet', 'woocustomcss' ),
		'desc'     => __( 'Applies at every screen width.', 'woocustomcss' ),
		'id'       => 'custom_css',
		'type'     => 'textarea',
		'css'      => 'width:100%;height:300px;',
		'class'    => 'codemirror_text',
		'default'  => '',
		'autoload' => false,
		'desc_tip' => true,
	),
	array(
		'title'    => __( 'Tablet CSS', 'woocustomcss' ),
		'desc'     => __( 'Wrapped in @media (max-width: 991.98px) for you — write plain rules, no media query needed.', 'woocustomcss' ),
		'id'       => 'custom_css_tablet',
		'type'     => 'textarea',
		'css'      => 'width:100%;height:200px;',
		'class'    => 'codemirror_text',
		'default'  => '',
		'autoload' => false,
		'desc_tip' => true,
	),
	array(
		'title'    => __( 'Mobile CSS', 'woocustomcss' ),
		'desc'     => __( 'Wrapped in @media (max-width: 575.98px) for you — write plain rules, no media query needed.', 'woocustomcss' ),
		'id'       => 'custom_css_mobile',
		'type'     => 'textarea',
		'css'      => 'width:100%;height:200px;',
		'class'    => 'codemirror_text',
		'default'  => '',
		'autoload' => false,
		'desc_tip' => true,
	),
	array(
		'type' => 'sectionend',
		'id'   => 'custom_css_options',
	),
);
