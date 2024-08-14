<?php 

if ( ! defined( 'ABSPATH' ) ) {
     die;
}

class WOOSETTINGTAB{
	
	private $setting_page_link = 'woo-custom-css-setting-link';
	private $tab_name = 'Custom CSS';
	
	public function __construct() {

		$this->id = $this->setting_page_link;
		$this->label = __( $this->tab_name, 'woocustomcss' );

		// Add the tab to the tabs array
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'custom_tab_page' ), 99 );
		// Add new section to the page
		add_action( 'woocommerce_sections_'.$this->id, array( $this, 'custom_tab_display_sections' ) );    
		// Add settings
		add_action( 'woocommerce_settings_'.$this->id, array( $this, 'custom_tab_output' ) );    
		// Process/save the settings
		add_action( 'woocommerce_settings_save_'.$this->id, array( $this, 'custom_fields_save' ) );
	}
	
	public function custom_tab_page( $settings_tabs ){
		$settings_tabs[ $this->setting_page_link ] = __( $this->tab_name, 'woocustomcss' );
		return $settings_tabs;
	}
	
	public function create_sections() {
		$sections = array(
			'' 						=> __( 'General', 'woocustomcss' ),
			'theme-hooks-page' 		=> __( 'Theme Hooks', 'woocustomcss' ),
			'product-page-css'		=> __( 'Product/Categories Page', 'woocustomcss' ),
			'cart-page-css' 		=> __( 'Cart Page', 'woocustomcss' ),
			'checkout-page-css' 	=> __( 'Checkout Page', 'woocustomcss' ),
			'extra-settings' 		=> __( 'Extra CSS', 'woocustomcss' ),
		);    
		return apply_filters( 'woocommerce_get_sections_'. $this->id, $sections );
	}
	
	public function custom_tab_display_sections(){
		global $current_section;    
		$sections = $this->create_sections();    
		if ( empty( $sections ) || 1 === sizeof( $sections ) ) {
			return;
		}    
		echo '<ul class="subsubsub">';    
		$array_keys = array_keys( $sections );    
		foreach ( $sections as $id => $label ) {
			echo '<li><a href="' . admin_url( 'admin.php?page=wc-settings&tab=' . $this->id . '&section=' . sanitize_title( $id ) ) . '" class="' . ( $current_section == $id ? 'current' : '' ) . '">' . $label . '</a> ' . ( end( $array_keys ) == $id ? '' : '|' ) . ' </li>';
		}    
		echo '</ul><br class="clear" />';
	}
	
	public function custom_tabs_field_array() {
		global $current_section;    
		$settings = array();
			if ( $current_section == '' ) {
				// General Tab
				include( WOO_EDITING__DIR. '/include/templates/general-setting.php' );
			} elseif ( $current_section == 'theme-hooks-page' ) { 
				// Products and Categoires page Hooks
				include( WOO_EDITING__DIR. '/include/templates/hooks-setting.php' );
			} elseif ( $current_section == 'product-page-css' ) { 
				// Products and Categoires page CSS settings
				include( WOO_EDITING__DIR. '/include/templates/shop-page-setting.php' );
			} elseif ( $current_section == 'cart-page-css' ) { 
				// Cart page CSS settings
				include( WOO_EDITING__DIR. '/include/templates/cart-page-setting.php' );				
			} elseif ( $current_section == 'checkout-page-css' ) { 
				// Checkout page CSS settings
				include( WOO_EDITING__DIR. '/include/templates/checkout-page-setting.php' );
			} elseif ( $current_section == 'extra-settings' ) { 
				// Checkout page CSS settings
				include( WOO_EDITING__DIR. '/include/templates/custom-extra-css.php' );
			}else{
				echo "Noting Found...";
			}    
		return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings );
	}
	
	public function custom_tab_output(){
		$custom_tabs_field_array = $this->custom_tabs_field_array();
        WC_Admin_Settings::output_fields( $custom_tabs_field_array );
	}
	
	public function custom_fields_save() {    
		global $current_section;
		$custom_tabs_field_array = $this->custom_tabs_field_array();
		WC_Admin_Settings::save_fields( $custom_tabs_field_array );
		if ( $current_section ) {
			do_action( 'woocommerce_update_options_' . $this->id . '_' . $current_section );
		}
	}
}