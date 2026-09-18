<?php
/**
 * Self-check for the option -> CSS custom property pipeline.
 * Run: php tests/test-css-vars.php
 */

define( 'ABSPATH', __DIR__ );
define( 'WOO_SETTING_VERSION', 'test' );
define( 'WOO_EDITING__URL', 'https://example.test/wp-content/plugins/woo-custom-css/' );
define( 'WOO_EDITING__DIR', dirname( __DIR__ ) . '/' );
define( 'WOO_SETTING_PLUGIN_BASENAME', 'woo-custom-css/woo-custom-css.php' );

$GLOBALS['options'] = array();
$GLOBALS['actions'] = array();

function get_option( $name, $default = false ) {
	return array_key_exists( $name, $GLOBALS['options'] ) ? $GLOBALS['options'][ $name ] : $default;
}
function add_action( ...$a ) { $GLOBALS['actions'][] = $a[0]; }
function add_filter( ...$a ) {}
function apply_filters( $tag, $value ) { return $value; }
function wp_strip_all_tags( $s ) { return strip_tags( (string) $s ); }
function sanitize_hex_color( $c ) {
	return preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', (string) $c ) ? $c : '';
}
function wp_parse_args( $args, $defaults ) { return array_merge( $defaults, (array) $args ); }

$base = dirname( __DIR__ );

require_once $base . '/include/main/woocustomoption.php';
require_once $base . '/include/main/woocssvariable.php';

$pass = 0;
$fail = 0;
function check( $label, $actual, $expected ) {
	global $pass, $fail;
	if ( $actual === $expected ) {
		$pass++;
		echo "  ok   $label\n";
	} else {
		$fail++;
		echo "  FAIL $label\n       expected: " . var_export( $expected, true ) . "\n       actual:   " . var_export( $actual, true ) . "\n";
	}
}

$o = new WOOCUSTOMOPTION();

echo "\n== WOOCUSTOMOPTION: unset options must yield '' so CSS fallbacks win ==\n";
check( 'size unset',      $o->get_option_size_data( 'nope' ), '' );
check( 'dimension unset', $o->get_option_dimension_data( 'nope' ), '' );
check( 'border unset',    $o->get_option_border_data( 'nope' ), '' );

// This is the exact regression the old code had: an absent option still
// produced "0px 0px 0px 0px", which would override every stylesheet default.
$GLOBALS['options']['empty_dim'] = array( 'unit' => 'px' );
check( 'dimension with only a unit stored', $o->get_option_dimension_data( 'empty_dim' ), '' );

echo "\n== WOOCUSTOMOPTION: set values ==\n";
$GLOBALS['options']['s'] = array( 'size' => '18', 'unit' => 'px' );
check( 'size', $o->get_option_size_data( 's' ), '18px' );

$GLOBALS['options']['s2'] = array( 'size' => '1.5', 'unit' => 'rem' );
check( 'fractional size', $o->get_option_size_data( 's2' ), '1.5rem' );

$GLOBALS['options']['s3'] = array( 'size' => '2.50', 'unit' => 'em' );
check( 'trailing zeros trimmed', $o->get_option_size_data( 's3' ), '2.5em' );

$GLOBALS['options']['bad_unit'] = array( 'size' => '4', 'unit' => 'furlongs' );
check( 'bogus unit falls back to px', $o->get_option_size_data( 'bad_unit' ), '4px' );

$GLOBALS['options']['d'] = array( 'top' => '10', 'right' => '20', 'bottom' => '', 'left' => '20', 'unit' => 'px' );
check( 'dimension, blank side becomes 0', $o->get_option_dimension_data( 'd' ), '10px 20px 0px 20px' );

$GLOBALS['options']['b'] = array( 'bordr' => 'dashed', 'size' => '2', 'colorpick' => '#ff0000', 'unit' => 'px' );
check( 'border', $o->get_option_border_data( 'b' ), 'dashed 2px #ff0000' );

$GLOBALS['options']['b2'] = array( 'bordr' => 'none', 'size' => '2', 'colorpick' => '#ff0000', 'unit' => 'px' );
check( 'border none collapses', $o->get_option_border_data( 'b2' ), 'none' );

$GLOBALS['options']['b3'] = array( 'bordr' => 'solid', 'size' => '1', 'colorpick' => 'red; } body{display:none', 'unit' => 'px' );
check( 'non-hex colour rejected', $o->get_option_border_data( 'b3' ), 'solid 1px' );

echo "\n== WOOCSSVARIABLE ==\n";
$GLOBALS['options'] = array();
$v = new WOOCSSVARIABLE();
$css = $v->build_css();
check( 'no options set -> no :root block at all (stylesheet fallbacks apply)', strpos( $css, '--woo-btn-bg' ), false );

$GLOBALS['options'] = array(
	'btn_clr'         => '#0f172a',
	'product_title_size' => array( 'size' => '20', 'unit' => 'px' ),
	'woocss_columns'  => '5',
	'woocss_card_hover' => 'yes',
);
$v2  = new WOOCSSVARIABLE();
$css = $v2->build_css();

check( 'button colour emitted',   (bool) strpos( $css, '--woo-btn-bg: #0f172a;' ), true );
check( 'size emitted',            (bool) strpos( $css, '--woo-product-title-size: 20px;' ), true );
check( 'desktop columns',         (bool) strpos( $css, '--woo-cols: 5;' ), true );
check( 'tablet columns clamp to 3', (bool) strpos( $css, '--woo-cols-md: 3;' ), true );
check( 'mobile columns clamp to 2', (bool) strpos( $css, '--woo-cols-sm: 2;' ), true );
check( 'hover lift on',           (bool) strpos( $css, '--woo-card-lift: -4px;' ), true );
check( 'unset property omitted',  strpos( $css, '--woo-cat-shadow' ), false );

// Columns must only ever step DOWN, never up.
$GLOBALS['options'] = array( 'woocss_columns' => '2' );
$v3  = new WOOCSSVARIABLE();
$css = $v3->build_css();
check( '2 columns stays 2 on tablet', (bool) strpos( $css, '--woo-cols-md: 2;' ), true );
check( '2 columns stays 2 on mobile', (bool) strpos( $css, '--woo-cols-sm: 2;' ), true );

echo "\n== sanitiser: values land inside <style>, must not escape it ==\n";
$GLOBALS['options'] = array( 'btn_clr' => 'red; } body { display: none; } .x {' );
$v4  = new WOOCSSVARIABLE();
$css = $v4->build_css();
check( 'braces stripped',         strpos( $css, '{ body' ), false );
check( 'only one rule opened',    substr_count( $css, '{' ), 1 );
check( 'only one rule closed',    substr_count( $css, '}' ), 1 );
// The payload must stay inside its own declaration: one property, one ';'.
preg_match( '/--woo-btn-bg:[^\n]*/', $css, $m );
check( 'payload confined to one declaration', substr_count( $m[0], ';' ), 1 );

$GLOBALS['options'] = array( 'cat_box_shadow' => 'url(javascript:alert(1))' );
$v5  = new WOOCSSVARIABLE();
$css = $v5->build_css();
check( 'url() stripped', strpos( $css, 'url(' ), false );

$GLOBALS['options'] = array( 'product_text_ff' => '</style><script>alert(1)</script>' );
$v6  = new WOOCSSVARIABLE();
$css = $v6->build_css();
check( 'tags stripped', strpos( $css, '<' ), false );

echo "\n== new option groups (cart / checkout / product / general) ==\n";
$GLOBALS['options'] = array(
	'table_row_bg'       => '#fafafa',
	'cart_remove_bg'     => '#eeeeee',
	'field_bg_clr'       => '#ffffff',
	'field_label_fw'     => '700',
	'product_box_bg'     => '#f5f5f5',
	'product_box_align'  => 'center',
	'sale_badge_bg'      => '#000000',
	'star_rating_clr'    => '#f59e0b',
	'btn_text_transform' => 'uppercase',
	'global_link_clr'    => '#0000ee',
	'checkout_panel_pad' => array( 'top' => '24', 'right' => '24', 'bottom' => '24', 'left' => '24', 'unit' => 'px' ),
	'btn_border'         => array( 'bordr' => 'solid', 'size' => '2', 'colorpick' => '#111111', 'unit' => 'px' ),
	'field_height'       => array( 'size' => '52', 'unit' => 'px' ),
);
$vn  = new WOOCSSVARIABLE();
$css = $vn->build_css();
check( 'cart row bg',        (bool) strpos( $css, '--woo-table-row-bg: #fafafa;' ), true );
check( 'remove button bg',   (bool) strpos( $css, '--woo-remove-bg: #eeeeee;' ), true );
check( 'field label weight', (bool) strpos( $css, '--woo-label-weight: 700;' ), true );
check( 'card align',         (bool) strpos( $css, '--woo-card-align: center;' ), true );
check( 'sale badge bg',      (bool) strpos( $css, '--woo-sale-bg: #000000;' ), true );
check( 'button transform',   (bool) strpos( $css, '--woo-btn-transform: uppercase;' ), true );
check( 'link colour',        (bool) strpos( $css, '--woo-link: #0000ee;' ), true );
check( 'panel padding',      (bool) strpos( $css, '--woo-panel-pad: 24px 24px 24px 24px;' ), true );
check( 'button border',      (bool) strpos( $css, '--woo-btn-border: solid 2px #111111;' ), true );
check( 'field height',       (bool) strpos( $css, '--woo-field-height: 52px;' ), true );

echo "\n== keyword toggles only emit when they differ from the default ==\n";
$GLOBALS['options'] = array();
$vd  = new WOOCSSVARIABLE();
$css = $vd->build_css();
check( 'uppercase headings on by default -> nothing emitted', strpos( $css, '--woo-table-head-transform' ), false );
check( 'full-width place order on by default -> nothing emitted', strpos( $css, '--woo-place-order-width' ), false );
check( 'badge left by default -> nothing emitted', strpos( $css, '--woo-sale-left' ), false );

$GLOBALS['options'] = array(
	'table_head_upper'          => 'no',
	'checkout_place_order_full' => 'no',
	'sale_badge_pos'            => 'right',
	'global_speed'              => '350',
);
$vt  = new WOOCSSVARIABLE();
$css = $vt->build_css();
check( 'uppercase off',      (bool) strpos( $css, '--woo-table-head-transform: none;' ), true );
check( 'place order auto',   (bool) strpos( $css, '--woo-place-order-width: auto;' ), true );
check( 'badge moves right',  (bool) strpos( $css, '--woo-sale-right: 0.75rem;' ), true );
check( 'badge left cleared', (bool) strpos( $css, '--woo-sale-left: auto;' ), true );
check( 'speed gets ms unit', (bool) strpos( $css, '--woo-speed: 350ms;' ), true );

$GLOBALS['options'] = array( 'global_speed' => 'fast' );
$vs  = new WOOCSSVARIABLE();
check( 'non-numeric speed ignored', strpos( $vs->build_css(), '--woo-speed' ), false );

echo "\n== WOOMAIN::build_custom_css ==\n";
require_once $base . '/include/main/woomain.php';

$GLOBALS['options'] = array();
$m = new WOOMAIN();
check( 'nothing set -> empty', $m->build_custom_css(), '' );

$GLOBALS['options'] = array( 'custom_css' => '.a{color:red}' );
check( 'global css passes through', $m->build_custom_css(), '.a{color:red}' );

$GLOBALS['options'] = array( 'custom_css_tablet' => '.a{color:red}' );
check(
	'tablet css is wrapped',
	$m->build_custom_css(),
	"@media (max-width: 991.98px) {\n.a{color:red}\n}"
);

$GLOBALS['options'] = array( 'custom_css_mobile' => '.a{color:red}' );
check(
	'mobile css is wrapped',
	$m->build_custom_css(),
	"@media (max-width: 575.98px) {\n.a{color:red}\n}"
);

// Narrowest last, so it wins on equal specificity.
$GLOBALS['options'] = array(
	'custom_css'        => '.g{}',
	'custom_css_tablet' => '.t{}',
	'custom_css_mobile' => '.m{}',
);
$out = $m->build_custom_css();
check( 'order: global before tablet', strpos( $out, '.g{}' ) < strpos( $out, '.t{}' ), true );
check( 'order: tablet before mobile', strpos( $out, '.t{}' ) < strpos( $out, '.m{}' ), true );

// An unbalanced brace would swallow every rule printed after it.
$GLOBALS['options'] = array( 'custom_css' => '.a{color:red' );
check( 'unbalanced css dropped, not shipped broken', $m->build_custom_css(), '' );

$GLOBALS['options'] = array( 'custom_css' => '.a{color:red}', 'custom_css_tablet' => '.b{' );
check( 'one bad block does not lose the good one', $m->build_custom_css(), '.a{color:red}' );

$GLOBALS['options'] = array( 'custom_css' => '<script>alert(1)</script>.a{color:red}' );
check( 'tags stripped from custom css', strpos( $m->build_custom_css(), '<' ), false );

// Wrapping must stay balanced or it breaks the whole inline style block.
$GLOBALS['options'] = array( 'custom_css_tablet' => '.a{b:c}', 'custom_css_mobile' => '.d{e:f}' );
$out = $m->build_custom_css();
check( 'assembled output is brace-balanced', substr_count( $out, '{' ), substr_count( $out, '}' ) );

echo "\n$pass passed, $fail failed\n";
exit( $fail > 0 ? 1 : 0 );
