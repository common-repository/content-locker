<?php
/**
 * The Installer
 * Installation related functions and actions.
 */

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CL_Install {

	public static function init() {
		add_action( 'admin_init', array( __CLASS__, 'welcome' ) );
	}

	public static function install() {

		// Add activation code
		add_option( 'content_locker_do_activation_redirect', true );

		// Create DB Tables
		self::create_tables();
	}

	public static function welcome() {
		if ( get_option( 'content_locker_do_activation_redirect', false ) ) {
        	delete_option( 'content_locker_do_activation_redirect' );

			if( ! isset( $_GET['activate-multi'] ) ) {
				wp_redirect( 'edit.php?post_type=content-locker&page=cl-help' );
			}
		}
	}

	private static function create_tables() {

		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		// stats
        $cl_stats = "
		CREATE TABLE {$wpdb->prefix}mts_locker_stats (
			ID bigint(20) NOT NULL AUTO_INCREMENT,
			aggregate_date date NOT NULL,
			post_id bigint(20) NOT NULL,
			locker_id int(11) NOT NULL,
			metric_name varchar(50) NOT NULL,
			metric_value int(11) NOT NULL DEFAULT 0,
			PRIMARY KEY  (ID),
			UNIQUE KEY UK_mts_cl_stats (aggregate_date,locker_id,post_id,metric_name)
		) {$charset_collate};";

		if ( ! function_exists('dbDelta') ) {
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        }

		dbDelta( $cl_stats );
	}
}
CL_Install::init();
