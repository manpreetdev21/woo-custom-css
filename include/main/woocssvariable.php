<?php 


if ( ! defined( 'ABSPATH' ) ) {
     die;
}

class WOOCSSVARIABLE{
	
	public function __construct(){
		$this->woo_ms_sass_css_compiler();
	}
	
	public function woo_ms_sass_css_compiler() {
		
		// Custom get_option() object.
		$woo_custom_option = new WOOCUSTOMOPTION();
		
		// Plugin custom Varibales
		$plugin_css_variables = array(
			'$cat_text_ff'				=> get_option( 'cat_text_ff' ),
			'$cat_title_clr'			=> get_option( 'cat_title_clr' ),
			'$cat_title_size'			=> $woo_custom_option->get_option_size_data( 'cat_title_size' ),
			'$cat_title_fw'				=> get_option( 'cat_title_fw' ),
			'$cat_box_pading'			=> $woo_custom_option->get_option_dimension_data( 'cat_box_pading' ),
			'$cat_box_bor'				=> $woo_custom_option->get_option_border_data( 'cat_box_bor' ),
			'$cat_box_bor_radis'		=> $woo_custom_option->get_option_dimension_data( 'cat_box_bor_radis' ),
			'$cat_box_shadow'			=> get_option( 'cat_box_shadow' ),
			'$product_text_ff'			=> get_option( 'product_text_ff' ),
			'$product_title_text' 		=> get_option( 'product_title_text' ),
			'$product_title_size' 		=> $woo_custom_option->get_option_size_data( 'product_title_size' ),
			'$product_title_fw' 		=> get_option( 'product_title_fw' ),
			'$product_price_text' 		=> get_option( 'product_price_text' ),
			'$product_price_size' 		=> $woo_custom_option->get_option_size_data( 'product_price_size' ),
			'$product_price_fw' 		=> get_option( 'product_price_fw' ),
			'$product_box_pading' 		=> $woo_custom_option->get_option_dimension_data( 'product_box_pading' ),
			'$product_box_bor' 			=> $woo_custom_option->get_option_border_data( 'product_box_bor' ),
			'$product_box_bor_radis' 	=> $woo_custom_option->get_option_dimension_data( 'product_box_bor_radis' ),
			'$product_box_shadow' 		=> get_option( 'product_box_shadow' ),
			'$btn_text_ff' 				=> get_option( 'btn_text_ff' ),
			'$btn_font_size' 			=> $woo_custom_option->get_option_size_data( 'btn_font_size' ),
			'$btn_title_fw' 			=> get_option( 'btn_title_fw' ),
			'$btn_clr' 					=> get_option( 'btn_clr' ),
			'$btn_txt_clr' 				=> get_option( 'btn_txt_clr' ),
			'$btn_hclr' 				=> get_option( 'btn_hclr' ),
			'$btn_htxt_clr' 			=> get_option( 'btn_htxt_clr' ),
			'$product_btn_padding' 		=> $woo_custom_option->get_option_dimension_data( 'product_btn_padding' ),
			'$btn_bor_radius' 			=> $woo_custom_option->get_option_dimension_data( 'btn_bor_radius' ),
			'$pro_detail_text_ff' 		=> get_option( 'pro_detail_text_ff' ),
			'$pro_detail_title_text' 	=> get_option( 'pro_detail_title_text' ),
			'$pro_detail_title_size' 	=> $woo_custom_option->get_option_size_data( 'pro_detail_title_size' ),
			'$pro_detail_title_fw' 		=> get_option( 'pro_detail_title_fw' ),
			'$pro_detail_price_text' 	=> get_option( 'pro_detail_price_text' ),
			'$pro_detail_price_size' 	=> $woo_custom_option->get_option_size_data( 'pro_detail_price_size' ),
			'$pro_detail_price_fw' 		=> get_option( 'pro_detail_price_fw' ),
			'$table_border_size' 		=> $woo_custom_option->get_option_size_data( 'table_border_size'),
			'$table_bor_clr' 			=> get_option( 'table_bor_clr' ),
			'$table_hbg_clr' 			=> get_option( 'table_hbg_clr' ),
			'$table_htext_clr' 			=> get_option( 'table_htext_clr' ),
			'$table_text_clr' 			=> get_option( 'table_text_clr' ),
			'$field_bordr_clr' 			=> get_option( 'field_bordr_clr' ),
		);
		
		$content_var = "";
		foreach( $plugin_css_variables as $k => $v ) {
			if( $v != "" ) $content_var .= $k . " : " . $v . ";" . chr(13);
		}
		
		file_put_contents( WOO_EDITING__DIR.'assets/frontend/sass/_variables_dynamic.scss', $content_var );
		
		// ScssPhp Compiler
		require_once WOO_EDITING__DIR.'include/composer/sass/vendor/scssphp/scssphp/scss.inc.php';
		
		$compiler = new ScssPhp\ScssPhp\Compiler();
		
		$source_scss = WOO_EDITING__DIR.'assets/frontend/sass/woocommerce.scss';
		$scss_contents = file_get_contents( $source_scss );
		$import_path = WOO_EDITING__DIR.'assets/frontend/sass';
		$compiler->addImportPath( $import_path );
		$target_css = WOO_EDITING__DIR.'assets/frontend/sass/woocommerce.min.css';
		$compiler->setOutputStyle(\ScssPhp\ScssPhp\OutputStyle::COMPRESSED);
		$compiler->setVariables( $plugin_css_variables );
		$css = $compiler->compile( $scss_contents );
		
		if( !empty( $css ) && is_string( $css ) ) {
			file_put_contents( $target_css, $css );
		}
	}
}