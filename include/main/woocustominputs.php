<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Custom WooCommerce settings field types: ms_size, ms_dimension, ms_border.
 */
class WOOCUSTOMINPUTS {

	public function __construct() {
		add_action( 'woocommerce_admin_field_ms_size', array( $this, 'woo_ms_size_cutsom_type' ) );
		add_action( 'woocommerce_admin_field_ms_dimension', array( $this, 'woo_ms_dimension_cutsom_type' ) );
		add_action( 'woocommerce_admin_field_ms_border', array( $this, 'woo_ms_border_cutsom_type' ) );
	}

	/**
	 * Field definitions do not always declare css/class/default, and reading
	 * them unguarded threw a notice on every settings screen.
	 */
	private function field( $value ) {
		return wp_parse_args(
			(array) $value,
			array(
				'id'      => '',
				'title'   => '',
				'css'     => '',
				'class'   => '',
				'default' => '',
				'type'    => '',
			)
		);
	}

	/**
	 * Renders a select, marking the option that matches this field's own
	 * stored key.
	 *
	 * The old markup used in_array( $key, $option_value ), which matched a
	 * value stored under ANY key - so a border colour of "solid" or a size
	 * unit of "px" could select the wrong entry.
	 */
	private function render_select( $name, $title, $choices, $selected, $width ) {
		printf(
			'<select name="%s" title="%s" style="width:%s;line-height:inherit;">',
			esc_attr( $name ),
			esc_attr( $title ),
			esc_attr( $width )
		);
		foreach ( $choices as $key => $label ) {
			printf(
				'<option value="%s"%s>%s</option>',
				esc_attr( $key ),
				selected( $key, $selected, false ),
				esc_html( $label )
			);
		}
		echo '</select>';
	}

	private function render_number( $value, $key, $current ) {
		printf(
			'<input name="%1$s[%2$s]" id="%1$s_%2$s" type="number" step="any" style="%3$s" value="%4$s" class="%5$s" />',
			esc_attr( $value['id'] ),
			esc_attr( $key ),
			esc_attr( $value['css'] ),
			esc_attr( $current ),
			esc_attr( $value['class'] )
		);
	}

	public function woo_ms_size_cutsom_type( $value ) {
		$value        = $this->field( $value );
		$description  = WC_Admin_Settings::get_field_description( $value );
		$option_value = $this->option_value_array_size( WC_Admin_Settings::get_option( $value['id'], $value['default'] ) );
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $value['id'] ); ?>_size"><?php echo esc_html( $value['title'] ); ?></label>
				<?php echo $description['tooltip_html']; // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</th>
			<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
				<?php
				$this->render_number( $value, 'size', $option_value['size'] );
				$this->render_select( $value['id'] . '[unit]', $value['title'], $this->drodown_units(), $option_value['unit'], '70px' );
				echo $description['description']; // phpcs:ignore WordPress.Security.EscapeOutput
				?>
			</td>
		</tr>
		<?php
	}

	public function option_value_array_size( $raw_value ) {
		$value = wp_parse_args(
			(array) $raw_value,
			array(
				'size' => '',
				'unit' => 'px',
			)
		);

		if ( ! array_key_exists( $value['unit'], $this->drodown_units() ) ) {
			$value['unit'] = 'px';
		}

		return $value;
	}

	public function woo_ms_dimension_cutsom_type( $value ) {
		$value        = $this->field( $value );
		$description  = WC_Admin_Settings::get_field_description( $value );
		$option_value = $this->option_value_array_dimension( WC_Admin_Settings::get_option( $value['id'], $value['default'] ) );
		$sides        = array(
			'top'    => __( 'Top', 'woocustomcss' ),
			'right'  => __( 'Right', 'woocustomcss' ),
			'bottom' => __( 'Bottom', 'woocustomcss' ),
			'left'   => __( 'Left', 'woocustomcss' ),
		);
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $value['id'] ); ?>_top"><?php echo esc_html( $value['title'] ); ?></label>
				<?php echo $description['tooltip_html']; // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</th>
			<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
				<?php
				foreach ( $sides as $side => $label ) {
					$this->render_number( $value, $side, $option_value[ $side ] );
				}
				$this->render_select( $value['id'] . '[unit]', $value['title'], $this->drodown_units(), $option_value['unit'], '70px' );
				echo $description['description']; // phpcs:ignore WordPress.Security.EscapeOutput
				?>
				<br/>
				<span class="description">
					<?php foreach ( $sides as $label ) : ?>
						<span style="display:inline-block;width:60px;"><?php echo esc_html( $label ); ?></span>
					<?php endforeach; ?>
				</span>
			</td>
		</tr>
		<?php
	}

	public function option_value_array_dimension( $raw_value ) {
		$value = wp_parse_args(
			(array) $raw_value,
			array(
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
				'unit'   => 'px',
			)
		);

		if ( ! array_key_exists( $value['unit'], $this->drodown_units() ) ) {
			$value['unit'] = 'px';
		}

		return $value;
	}

	public function woo_ms_border_cutsom_type( $value ) {
		$value        = $this->field( $value );
		$description  = WC_Admin_Settings::get_field_description( $value );
		$option_value = $this->option_value_array_border( WC_Admin_Settings::get_option( $value['id'], $value['default'] ) );
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $value['id'] ); ?>_size"><?php echo esc_html( $value['title'] ); ?></label>
				<?php echo $description['tooltip_html']; // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</th>
			<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
				<?php
				$this->render_select( $value['id'] . '[bordr]', $value['title'], $this->drodown_border_type(), $option_value['bordr'], '110px' );
				$this->render_number( $value, 'size', $option_value['size'] );
				$this->render_select( $value['id'] . '[unit]', $value['title'], $this->drodown_units(), $option_value['unit'], '70px' );
				?>
				<span class="colorpickpreview" style="background:<?php echo esc_attr( $option_value['colorpick'] ); ?>">&nbsp;</span>
				<input
					name="<?php echo esc_attr( $value['id'] ); ?>[colorpick]"
					id="<?php echo esc_attr( $value['id'] ); ?>_colorpick"
					type="text"
					dir="ltr"
					style="width:7em;"
					class="<?php echo esc_attr( trim( $value['class'] . ' colorpick' ) ); ?>"
					value="<?php echo esc_attr( $option_value['colorpick'] ); ?>"
				/>
				<div id="colorPickerDiv_<?php echo esc_attr( $value['id'] ); ?>" class="colorpickdiv" style="z-index:100;background:#eee;border:1px solid #ccc;position:absolute;display:none;"></div>
				<?php echo $description['description']; // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</td>
		</tr>
		<?php
	}

	public function option_value_array_border( $raw_value ) {
		$value = wp_parse_args(
			(array) $raw_value,
			array(
				'bordr'     => 'solid',
				'size'      => '',
				'colorpick' => '',
				'unit'      => 'px',
			)
		);

		// This was validated against drodown_units(), where no border style is
		// ever a key - so every saved style silently reverted to "solid".
		if ( ! array_key_exists( $value['bordr'], $this->drodown_border_type() ) ) {
			$value['bordr'] = 'solid';
		}
		if ( ! array_key_exists( $value['unit'], $this->drodown_units() ) ) {
			$value['unit'] = 'px';
		}

		$value['colorpick'] = $value['colorpick'] ? (string) sanitize_hex_color( $value['colorpick'] ) : '';

		return $value;
	}

	/* Units Dropdown */
	public function drodown_units() {
		$custom_unites = array(
			'px'  => __( 'px', 'woocustomcss' ),
			'em'  => __( 'em', 'woocustomcss' ),
			'rem' => __( 'rem', 'woocustomcss' ),
			'%'   => __( '%', 'woocustomcss' ),
			'vw'  => __( 'vw', 'woocustomcss' ),
			'vh'  => __( 'vh', 'woocustomcss' ),
		);

		$units = apply_filters( 'ms_size_dimension_units', $custom_unites );

		return is_array( $units ) && ! empty( $units ) ? $units : $custom_unites;
	}

	/* Border Type Dropdown */
	public function drodown_border_type() {
		$custom_border_type = array(
			'solid'  => __( 'solid', 'woocustomcss' ),
			'none'   => __( 'none', 'woocustomcss' ),
			'hidden' => __( 'hidden', 'woocustomcss' ),
			'dotted' => __( 'dotted', 'woocustomcss' ),
			'dashed' => __( 'dashed', 'woocustomcss' ),
			'double' => __( 'double', 'woocustomcss' ),
			'groove' => __( 'groove', 'woocustomcss' ),
			'ridge'  => __( 'ridge', 'woocustomcss' ),
			'inset'  => __( 'inset', 'woocustomcss' ),
			'outset' => __( 'outset', 'woocustomcss' ),
		);

		$border_type = apply_filters( 'ms_border_type', $custom_border_type );

		return is_array( $border_type ) && ! empty( $border_type ) ? $border_type : $custom_border_type;
	}
}
