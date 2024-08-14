<?php 
$settings = array(
	array(
		'title' 	=> __( 'Custom CSS', 'woocustomcss' ),
		'type'  	=> 'title',
		'desc'  	=> '',
		'id'    	=> 'checkout_css_options',
	),				
	array(
		'title'   	=> __( 'CSS Style Sheet', 'woocustomcss' ),
		'desc'     	=> __( 'You can add your custom CSS code.', 'woocustomcss' ),
		'id'       	=> 'custom_css',
		'type'     	=> 'textarea',
		'css'      	=> 'width:100%',
		'class'		=> 'codemirror_text',
		'default'  	=> '',
		'autoload' 	=> false,
		'desc_tip' 	=> true,
	),
	array(
		'type' 		=> 'sectionend',
		'id'   		=> 'checkout_css_options',
	),
);