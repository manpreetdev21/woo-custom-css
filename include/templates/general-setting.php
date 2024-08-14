<?php 
$settings = array(                  
	array(
		'title' 	=> __( 'General Settings', 'woocustomcss' ),
		'type' 		=> 'title',
		'desc' 		=>  __( 'General Shop page and theme options...', 'woocustomcss' ),
		'id' 		=> 'woocommerce_general_settings'
	),
	array(
		'title' 	=> __( 'Theme Support', 'woocustomcss' ),
		'desc' 		=> __( 'Add Theme Support.', 'woocustomcss' ),
		'id' 		=> 'theme_support',
		'default'  	=> 'no',
		'type' 		=> 'checkbox',                   
		//'desc_tip' => false,
		'checkboxgroup'   => 'start',
		'show_if_checked' => 'option',
	),
	array(
		'desc' 		=> __( 'Add Product Gallery Zoom.', 'woocustomcss' ),
		'id' 		=> 'gallery_zoom',
		'default'  	=> 'no',
		'type' 		=> 'checkbox',                   
		//'desc_tip' => false,
		'checkboxgroup'   => '',
		'show_if_checked' => 'yes',
		'autoload'        => false,
	),
	array(
		'desc' 		=> __( 'Add Product Gallery Lightbox.', 'woocustomcss' ),
		'id' 		=> 'gallery_lightbox',
		'default'  	=> 'no',
		'type' 		=> 'checkbox',                   
		//'desc_tip' => false,
		'checkboxgroup'   => '',
		'show_if_checked' => 'yes',
		'autoload'        => false,
	),
	array(
		'desc' 		=> __( 'Add Product Gallery Slider.', 'woocustomcss' ),
		'id' 		=> 'gallery_slider',
		'default'  	=> 'no',
		'type' 		=> 'checkbox',                   
		//'desc_tip' => false,
		'show_if_checked' => 'yes',
		'checkboxgroup'   => 'end',
		'autoload'        => false,
	),	
	array(
		'type' 		=> 'sectionend',
		'id' 		=> 'woocommerce_general_settings'
	),
);