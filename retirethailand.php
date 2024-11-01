<?php
/*
 * Plugin Name: Retirethailand
 * Version: 1.1.4
 * Plugin URI: http://retirethailand.info/
 * Description: Our retirement income calculator allows visitors to easily calculate if their income is sufficient for a Thai retirement visa.
 * Author: thaiexpatservicecenter,awcode
 * Author URI: http://retirethailand.info/
 * Requires at least: 4.0
 * Tested up to: 4.9.8
 *
 */
 
 // If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function ritl_activate_retirethailand() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-retirethailand-activator.php';
	Retirethailand_Activator::activate();
}


function ritl_deactivate_retirethailand() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-retirethailand-deactivator.php';
	Retirethailand_Deactivator::deactivate();
}

function ritl_retirethailand_register_widgets() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-retirethailand-widget.php';
	register_widget( 'ritl_Retirethailand_Widget' );
}

register_activation_hook( __FILE__, 'ritl_activate_retirethailand' );
register_deactivation_hook( __FILE__, 'ritl_deactivate_retirethailand' );

require_once plugin_dir_path( __FILE__ ) . 'includes/class-retirethailand-includes.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-retirethailand-shortcode.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-retirethailand-ajax-callback.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-retirethailand-setting.php';

if( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
require_once plugin_dir_path( __FILE__ ) . 'includes/class-retirethailand-phrase-table.php';

add_action( 'widgets_init', 'ritl_retirethailand_register_widgets' );
add_shortcode( 'retirethailand', 'ritl_shortcode_func' );
add_action( 'wp_enqueue_scripts','ritl_enqueue_styles' );
add_action( 'wp_enqueue_scripts','ritl_enqueue_scripts' );
add_action( 'wp_ajax_retirethailand', 'ritl_retirethailand_callback' );
add_action( 'wp_ajax_nopriv_retirethailand', 'ritl_retirethailand_callback' );
add_action('admin_menu', 'retirethailand_control_menu');

add_action( 'admin_enqueue_scripts', 'retirethailand_mw_enqueue_color_picker' );
add_action( 'init', 'retirethailand_process_post' );

function retirethailand_process_post() {
	global $wpdb;
     if( isset( $_REQUEST['newsletter-confirm'] ) ) {
     	$wpdb->update( 
					$wpdb->prefix . "retirethailand_newsletter", 
					array( 
						'confirm_date' => date("Y-m-d")
					), 
					array( 'email' => $_REQUEST['retirethailand-confirm'] )
				);
     	die();
     }else if( isset( $_REQUEST['retirethailand-unsubscribe'] ) ) {
     	$wpdb->update( 
					$wpdb->prefix . "retirethailand_newsletter", 
					array( 
						'unsubscribe_date' => date("Y-m-d")
					), 
					array( 'email' => $_REQUEST['retirethailand-unsubscribe'] )
				);
     	die();
     }

}

function retirethailand_mw_enqueue_color_picker( $hook_suffix ) {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker' );
}

?>
