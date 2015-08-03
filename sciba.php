<?php
/**
 * Plugin Name: Slider comparison image before and after
 * Plugin URI: https://wordpress.org/plugins/slider-comparison-image-before-and-after
 * Description: Comparison image before and after using the JS slider.
 * Author: Artem Bovkun
 * Author URI: http://art-develop.tk/
 * License: GPLv2
 * Text Domain: sciba
 * Domain Path: /languages/
 * Version: 0.7
*/

if ( ! defined( 'SCIBA_PLUGIN_DIR' ) )
	define( 'SCIBA_PLUGIN_DIR', untrailingslashit( plugin_dir_url( __FILE__ ) ) );

class SCIBA {

	static $shortcode_show;

	static function initialize() {
		self::$shortcode_show = false;
		
		self::sciba_load_plugin_textdomain();

		add_shortcode('sciba', array(__CLASS__, 'sciba_shortcode'));
			
		add_action('admin_head', array(__CLASS__, 'sciba_add_mce_button'));
		add_action( 'admin_head', array(__CLASS__, 'sciba_add_admin_css') );
		add_action('init', array(__CLASS__, 'init'));
		add_action('wp_footer', array(__CLASS__, 'wp_footer'));
	}
	
	static function sciba_load_plugin_textdomain() {
		load_plugin_textdomain( 'sciba', false, SCIBA_PLUGIN_DIR . '/languages' );
	}

	static function sciba_shortcode( $atts ) {
		self::$shortcode_show = true;

		wp_enqueue_script('juxtapose');

		extract( shortcode_atts( array(
			'startingposition'  => 50,
			'showlabels'        => true,
			'showcredits'       => true,
			'animate'           => true,
			'mode'              => 'horizontal',
			'leftsrc'           => '',
			'leftlabel'         => '',
			'leftcredit'        => '',
			'rightsrc'          => '',
			'rightlabel'        => '',
			'rightcredit'       => '',
		), $atts ) );

		$out = <<<EOT
		<div class="juxtapose" data-startingposition="{$startingposition}" data-showlabels="{$showlabels}" data-showcredits="{$showcredits}" data-animate="{$animate}" data-mode="{$mode}">
		  <img src="{$leftsrc}" data-label="{$leftlabel}" data-credit="{$leftcredit}">
		  <img src="{$rightsrc}" data-label="{$rightlabel}" data-credit="{$rightcredit}">
		</div>
EOT;

		return $out;

	}
	
	static function sciba_add_mce_button() {
		if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
			return;
		}
		
		if ( 'true' == get_user_option( 'rich_editing' ) ) {
			add_filter( 'mce_external_plugins', array(__CLASS__, 'sciba_add_tinymce_script') );
			add_filter( 'mce_buttons', array(__CLASS__, 'sciba_register_mce_button') );
		}
	}
	
	static function sciba_add_admin_css() {
		echo '<style type="text/css">i.mce-i-sciba {background: url("' . SCIBA_PLUGIN_DIR . '/images/sciba-icon.png");}</style>';
	}

	static function sciba_add_tinymce_script( $plugin_array ) {
		$plugin_array['sciba_mce_button'] = SCIBA_PLUGIN_DIR .'/js/sciba-button.js';
		
		return $plugin_array;
	}
	
	static function sciba_register_mce_button( $buttons ) {
		array_push( $buttons, 'sciba_mce_button' );
		
		return $buttons;
	}

	static function init() {
		wp_register_script( 'juxtapose',  "//s3.amazonaws.com/cdn.knightlab.com/libs/juxtapose/latest/js/juxtapose.js", array( 'jquery' ), '', true );
		
	}

	static function wp_footer() {
		if(self::$shortcode_show){
			wp_enqueue_style( 'juxtapose', "//s3.amazonaws.com/cdn.knightlab.com/libs/juxtapose/latest/css/juxtapose.css" );
		}    
	}

}

SCIBA::initialize();