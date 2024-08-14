<?php 

if ( ! defined( 'ABSPATH' ) ) {
     die;
}

class WOOCUSTOMOPTION{
	
	public function get_option_size_data( $option_value ) {
		$option_value = get_option( $option_value );
		$unite_value = isset( $option_value[ 'unit' ] ) ? $option_value[ 'unit' ] : '';
		$size_value = isset( $option_value[ 'size' ] ) ? $option_value[ 'size' ] : '0' . $unite_value;
		return $size_value.$unite_value;
	}
	
	public function get_option_dimension_data( $option_value ) {
		$option_value = get_option( $option_value );
		$unite_value = isset( $option_value[ 'unit' ] ) ? $option_value[ 'unit' ] : 'px';
		$top_value = isset( $option_value[ 'top' ] ) ? $option_value[ 'top' ] . $unite_value : '0' . $unite_value;
		$right_value = isset( $option_value[ 'right' ] ) ? $option_value[ 'right' ] . $unite_value : '0' . $unite_value;
		$bottom_value = isset( $option_value[ 'bottom' ] ) ? $option_value[ 'bottom' ] . $unite_value : '0' . $unite_value;
		$left_value = isset( $option_value[ 'left' ] ) ? $option_value[ 'left' ] . $unite_value : '0' . $unite_value;
		return $top_value . ' ' . $right_value . ' ' . $bottom_value . ' ' . $left_value;
	}
	
	public function get_option_border_data( $option_value ) {
		$option_value = get_option( $option_value );
		$unite_value = isset( $option_value[ 'unit' ] ) ? $option_value[ 'unit' ] : 'px';
		$bordr_value = isset( $option_value[ 'bordr' ] ) ? $option_value[ 'bordr' ] : 'none';
		$size_value = isset( $option_value[ 'size' ] ) ? $option_value[ 'size' ] . $unite_value : '0' . $unite_value;
		$colorpick_value = isset( $option_value[ 'colorpick' ] ) ? $option_value[ 'colorpick' ] : '#000000';
		return $bordr_value . ' ' . $size_value . ' ' . $colorpick_value;
	}		
}