<?php 

if ( ! defined( 'ABSPATH' ) ) {
     die;
}

class WOOMAIN{
	
	private $plugin_name = 'woo-custom-css';
	private $setting_page = 'Woo CSS Settings';
	private $setting_page_link = 'woo-custom-css-setting-link';
	private $setting_btn_name = 'Custom Settings';	
	
	public function __construct(){
		add_action( 'wp_enqueue_scripts', array( $this, 'woo_main_min_css' ) );
		add_filter( 'plugin_action_links', array( $this, 'woo_custom_add_action_plugin' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'woo_ms_admin_enqueue_admin_style_script' ) );
		add_action(	'admin_footer', array( $this, 'woo_ms_admin_script_footer' ));
		add_action( 'wp_enqueue_scripts', array( $this, 'woo_ms_custom_style_css' ) );
	}
	
	public function woo_main_min_css(){
		$version = filemtime( WOO_EDITING__DIR . 'assets/frontend/sass/woocommerce.min.css' );
		wp_register_style('woocustommincss', WOO_EDITING__URL.'assets/frontend/sass/woocommerce.min.css', array( 'woocommerce-general' ) , $version, 'all' );
		wp_enqueue_style('woocustommincss');
	}

	public function woo_ms_custom_style_css(){
		$custom_css = get_option( 'custom_css' );
		if( !empty( $custom_css ) ){
			file_put_contents( WOO_EDITING__DIR.'assets/frontend/css/custom-style.css', $custom_css );
			$version = filemtime( WOO_EDITING__DIR . 'assets/frontend/css/custom-style.css' );
			wp_register_style('custom-style', WOO_EDITING__URL.'assets/frontend/css/custom-style.css', array() , $version, 'all' );
			wp_enqueue_style('custom-style');
		}			
	}
	
	public function woo_ms_admin_enqueue_admin_style_script() {
		global $current_section;
		if( $current_section == 'extra-settings' ){
			$file_version = '6.65.7';
			wp_enqueue_style( 'codemirror_css', WOO_EDITING__URL.'assets/admin/css/codemirror.min.css', array(), $file_version, false );
			wp_enqueue_script( 'codemirror_js', WOO_EDITING__URL.'assets/admin/js/codemirror.min.js', array(), $file_version, false );	
		}
	}

	public function woo_ms_admin_script_footer(){		
		wp_enqueue_script( 'admin-script', WOO_EDITING__URL.'assets/admin/js/admin-script.js', array(), '1.0.1', false );
	}

	public function woo_custom_add_action_plugin( $plugin_link, $plugin_file ){
		if ( $plugin_file != WOO_SETTING_PLUGIN_BASENAME ) {
			return $plugin_link;
		}			
		$settings_link = sprintf( __( '<a href="%s" target="_blank">'.$this->setting_btn_name.'</a>', 'woocustomcss' ), esc_url( admin_url( "admin.php?page=wc-settings&tab=$this->setting_page_link" ) ) );;
		
		array_unshift( $plugin_link, $settings_link );
		return $plugin_link;
	}	
}