<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Helper classes are instantiated on demand, not booted.
 */
$woo_css_helpers = array( 'WOOCUSTOMOPTION' );

$woo_css_files = glob( WOO_EDITING__DIR . 'include/main/*.php' );
sort( $woo_css_files );

foreach ( $woo_css_files as $woo_css_file ) {
	require_once $woo_css_file;
}

foreach ( $woo_css_files as $woo_css_file ) {
	$woo_css_class = strtoupper( basename( $woo_css_file, '.php' ) );
	if ( class_exists( $woo_css_class ) && ! in_array( $woo_css_class, $woo_css_helpers, true ) ) {
		new $woo_css_class();
	}
}
