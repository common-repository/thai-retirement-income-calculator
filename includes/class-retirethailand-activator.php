<?php
/**
 * Fired during plugin activation
 *
 * @link       http://awcode.com
 * @since      0.0.1
 *
 * @package    Retirethailand
 * @subpackage Retirethailand/includes
 */
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      0.0.1
 * @package    Retirethailand
 * @subpackage Retirethailand/includes
 * @author     AWcode<m@awcode.com>
 */
class Retirethailand_Activator {
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$charset_collate = $wpdb->get_charset_collate();


		$table_name = $wpdb->prefix . "retirethailand_requests"; 
		$sql1 = "CREATE TABLE $table_name (
				  `stat_id` int(11) NOT NULL AUTO_INCREMENT,
				  `stat_ip` varchar(20) NOT NULL,
				  `cur_id` varchar(6) NOT NULL,
				  `cur_rate` decimal(15,5) NOT NULL,
				  `cur_monthly` int(11) NOT NULL,
				  `stat_browser` varchar(255) NOT NULL,
				  `stat_referrer` varchar(255) NOT NULL,
				  `stat_date` datetime NOT NULL, 
				  PRIMARY KEY  stat_id (stat_id) 
				) $charset_collate;";
		
		dbDelta( $sql1 );

		$table_name = $wpdb->prefix . "retirethailand_exchange"; 		
		$sql2 = "CREATE TABLE $table_name (
					  `cur_id` varchar(5) NOT NULL,
					  `cur_sym` varchar(10) NOT NULL,
					  `cur_rate` decimal(15,5) NOT NULL,
					  `cur_update` datetime NOT NULL, 
					  PRIMARY KEY  cur_id (cur_id) 
					) $charset_collate;";
		
		dbDelta( $sql2 );


		$table_name = $wpdb->prefix . "retirethailand_newsletter"; 
		$sql3 = "CREATE TABLE $table_name (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `email` varchar(100) NOT NULL,
			  `name` varchar(100) NOT NULL, 
			  PRIMARY KEY  id (id)
			) $charset_collate;";

		dbDelta( $sql3 );

		if(!get_option('tr-show-link')) update_option( "tr-show-link", "" );
		if(!get_option('tr-css-head')) update_option( "tr-css-head", 24 );
		if(!get_option('tr-css-head-color')) update_option( "tr-css-head-color", "#000" );
		if(!get_option('tr-css-font')) update_option( "tr-css-font", 12 );
		if(!get_option('tr-css-color')) update_option( "tr-css-color", "#000" );
		if(!get_option('tr-css-border-style')) update_option( "tr-css-border-style", "none" );
		if(!get_option('tr-css-border-size')) update_option( "tr-css-border-size", 1 );
		if(!get_option('tr-css-border-color')) update_option( "tr-css-border-color", "#000" );
		if(!get_option('tr-css-bg')) update_option( "tr-css-bg", "" );
		if(!get_option('tr-css-custom')) update_option( "tr-css-custom", "" );
		if(!get_option('tr-newsletter')) update_option( "tr-newsletter", "" );
		if(!get_option('tr-title-newsletter')) update_option( "tr-title-newsletter", "Subscribe" );
		if(!get_option('tr-explain-newsletter')) update_option( "tr-explain-newsletter", "Enter your details below and receive the latest retirement visa news direct to your inbox" );
		if(!get_option('tr-confirm-newsletter')) update_option( "tr-confirm-newsletter", "Signup complete, please check your email to verify your subscription." );
		if(!get_option('tr-button-newsletter')) update_option( "tr-button-newsletter", "Subscribe" );
		if(!get_option('tr-subscriber')) update_option( "tr-subscriber", "" );
		if(!get_option('tr-tracking-adwords')) update_option( "tr-tracking-adwords", "" );
		if(!get_option('tr-tracking-facebook')) update_option( "tr-tracking-facebook", "" );
		if(!get_option('tr-tracking-other')) update_option( "tr-tracking-other", "" );
	}
}
