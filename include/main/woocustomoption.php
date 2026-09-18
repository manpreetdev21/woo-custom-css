<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Reads the compound option types (size / dimension / border) back out as
 * CSS value strings.
 *
 * Every getter returns an EMPTY string when the merchant never set the
 * option. That matters: an empty value is skipped when the custom property
 * block is printed, so the fallback baked into the stylesheet wins instead
 * of a meaningless "0px 0px 0px 0px".
 */
class WOOCUSTOMOPTION {

	/**
	 * Allowed CSS units. Anything else is dropped.
	 */
	private const UNITS = array( 'px', 'em', 'rem', '%', 'vw', 'vh' );

	/**
	 * Allowed border styles.
	 */
	private const BORDER_STYLES = array( 'solid', 'none', 'hidden', 'dotted', 'dashed', 'double', 'groove', 'ridge', 'inset', 'outset' );

	/**
	 * A stored value counts as "set" only if it is an array with at least one
	 * non-empty member other than the unit.
	 */
	private function has_value( $option_value, array $keys ) {
		if ( ! is_array( $option_value ) ) {
			return false;
		}
		foreach ( $keys as $key ) {
			if ( isset( $option_value[ $key ] ) && '' !== trim( (string) $option_value[ $key ] ) ) {
				return true;
			}
		}
		return false;
	}

	private function unit( $option_value ) {
		$unit = isset( $option_value['unit'] ) ? $option_value['unit'] : 'px';
		return in_array( $unit, self::UNITS, true ) ? $unit : 'px';
	}

	/**
	 * Numeric part of a compound value, cast safely. Returns null if absent.
	 */
	private function number( $option_value, $key ) {
		if ( ! isset( $option_value[ $key ] ) || '' === trim( (string) $option_value[ $key ] ) ) {
			return null;
		}
		return (float) $option_value[ $key ];
	}

	/**
	 * e.g. "16px". Empty string when unset.
	 */
	public function get_option_size_data( $option_name ) {
		$option_value = get_option( $option_name );
		if ( ! $this->has_value( $option_value, array( 'size' ) ) ) {
			return '';
		}
		$unit = $this->unit( $option_value );
		$size = $this->number( $option_value, 'size' );

		return ( null === $size ? '0' : $this->trim_float( $size ) ) . $unit;
	}

	/**
	 * e.g. "10px 20px 10px 20px". Empty string when unset.
	 */
	public function get_option_dimension_data( $option_name ) {
		$option_value = get_option( $option_name );
		$sides        = array( 'top', 'right', 'bottom', 'left' );

		if ( ! $this->has_value( $option_value, $sides ) ) {
			return '';
		}

		$unit  = $this->unit( $option_value );
		$parts = array();
		foreach ( $sides as $side ) {
			$number  = $this->number( $option_value, $side );
			$parts[] = ( null === $number ? '0' : $this->trim_float( $number ) ) . $unit;
		}

		return implode( ' ', $parts );
	}

	/**
	 * e.g. "solid 1px #e5e7eb". Empty string when unset.
	 */
	public function get_option_border_data( $option_name ) {
		$option_value = get_option( $option_name );
		if ( ! $this->has_value( $option_value, array( 'size', 'colorpick' ) ) ) {
			return '';
		}

		$style = isset( $option_value['bordr'] ) ? $option_value['bordr'] : 'solid';
		if ( ! in_array( $style, self::BORDER_STYLES, true ) ) {
			$style = 'solid';
		}

		if ( 'none' === $style || 'hidden' === $style ) {
			return $style;
		}

		$unit  = $this->unit( $option_value );
		$size  = $this->number( $option_value, 'size' );
		$size  = ( null === $size ? '0' : $this->trim_float( $size ) ) . $unit;
		$color = isset( $option_value['colorpick'] ) ? sanitize_hex_color( $option_value['colorpick'] ) : '';

		return trim( $style . ' ' . $size . ' ' . $color );
	}

	/**
	 * 1.50 -> "1.5", 12.0 -> "12".
	 */
	private function trim_float( $number ) {
		return rtrim( rtrim( number_format( (float) $number, 4, '.', '' ), '0' ), '.' ) ?: '0';
	}
}
