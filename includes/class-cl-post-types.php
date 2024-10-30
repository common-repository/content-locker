<?php
/**
 * Post Types
 *
 * Registers post types and taxonomies.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Post_types Class.
 */
class CL_Post_types extends CL_Base {

	/**
	 * [__construct description]
	 * @method __construct
	 */
	public function __construct() {

		$this->add_action( 'init', 'register_post_types', 5 );
		$this->add_action( 'init', 'register_post_status', 9 );
	}

	/**
	 * [register_post_types description]
	 * @method register_post_types
	 * @return [type]              [description]
	 */
	public function register_post_types() {

		if ( post_type_exists('content-locker') ) {
			return;
		}

		cl_action( 'register_post_type' );

		$labels = array(
			'name'                  => __( 'Lockers', 'content-locker' ),
			'singular_name'         => __( 'Locker', 'content-locker' ),
			'all_items'				=> __( 'All Lockers', 'content-locker' ),
			'menu_name'             => _x( 'Content Locker', 'Admin menu name', 'content-locker' ),
			'add_new'               => __( '+ New Locker', 'content-locker' ),
			'add_new_item'          => __( 'Add New Locker', 'content-locker' ),
			'edit'                  => __( 'Edit', 'content-locker' ),
			'edit_item'             => __( 'Edit Item', 'content-locker' ),
			'new_item'              => __( 'New Item', 'content-locker' ),
			'view'                  => __( 'View', 'content-locker' ),
			'view_item'             => __( 'View Item', 'content-locker' ),
			'search_items'          => __( 'Search Items', 'content-locker' ),
			'not_found'             => __( 'No Items found', 'content-locker' ),
			'not_found_in_trash'    => __( 'No Items found in trash', 'content-locker' ),
			'parent'                => __( 'Parent Item', 'content-locker' )
		);

		register_post_type( 'content-locker',
			cl_filter( 'register_post_type_locker',
				array(
					'label'              => __( 'Locker', 'content-locker' ),
					'description'         => __( 'This is where you can add new lockers.', 'content-locker' ),
					'labels'              => $labels,
					'supports'            => array( 'title' ),
					'hierarchical'        => false, // Hierarchical causes memory issues - WP loads all records!
					'public'              => false,
					'show_ui'             => true,
					'show_in_menu'        => true,
					'show_in_admin_bar'     => false,
					'show_in_nav_menus'   => false,
					'can_export'            => true,
					'has_archive'         => false,
					'exclude_from_search' => true,
					'menu_icon'				=> 'dashicons-lock',
					//'map_meta_cap'        => true,
					'publicly_queryable'  => false,
					'rewrite'             => false,
					'capability_type'     => 'post'
				)
			)
		);

		$labels = array(
			'name'                  => __( 'Leads', 'content-locker' ),
			'singular_name'         => __( 'Lead', 'content-locker' ),
			'all_items'				=> __( 'All Leads', 'content-locker' ),
			'menu_name'             => _x( 'Content Lead', 'Admin menu name', 'content-locker' ),
			'add_new'               => __( '+ New Lead', 'content-locker' ),
			'add_new_item'          => __( 'Add New Locker', 'content-locker' ),
			'edit'                  => __( 'Edit', 'content-locker' ),
			'edit_item'             => __( 'Edit Item', 'content-locker' ),
			'new_item'              => __( 'New Item', 'content-locker' ),
			'view'                  => __( 'View', 'content-locker' ),
			'view_item'             => __( 'View Item', 'content-locker' ),
			'search_items'          => __( 'Search Items', 'content-locker' ),
			'not_found'             => __( 'No Items found', 'content-locker' ),
			'not_found_in_trash'    => __( 'No Items found in trash', 'content-locker' ),
			'parent'                => __( 'Parent Item', 'content-locker' )
		);

		register_post_type( 'cl-lead',
			cl_filter( 'register_post_type_lead',
				array(
					'label'              => __( 'Lead', 'content-locker' ),
					'description'         => __( 'This is where you can add new lockers.', 'content-locker' ),
					'labels'              => $labels,
					'supports'            => array( 'title' ),
					'hierarchical'        => false, // Hierarchical causes memory issues - WP loads all records!
					'public'              => false,
					'show_ui'             => true,
					'show_in_menu'        => false,
					'show_in_admin_bar'     => false,
					'show_in_nav_menus'   => false,
					'can_export'            => true,
					'has_archive'         => false,
					'exclude_from_search' => true,
					//'map_meta_cap'        => true,
					'publicly_queryable'  => false,
					'rewrite'             => false,
					'capability_type'     => 'post'
				)
			)
		);
	}

	/**
	 * [register_post_status description]
	 * @method register_post_status
	 * @return [type]               [description]
	 */
	public function register_post_status() {

		$lead_statuses = array(
			'confirmed' => array(
				'label'                     => _x( 'Confirmed', 'Status General Name', 'content-locker' ),
				'label_count'               => _n_noop( 'Confirmed (%s)',  'Confirmed (%s)', 'content-locker' ),
				'public'                    => true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true
			),

			'not-confirmed' => array(
				'label'                     => _x( 'Not Confirmed', 'Status General Name', 'content-locker' ),
				'label_count'               => _n_noop( 'Not Confirmed (%s)',  'Not Confirmed (%s)', 'content-locker' ),
				'public'                    => true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true
			)
		);

		$lead_statuses = cl_filter( 'register_lead_post_status', $lead_statuses );

		foreach ( $lead_statuses as $status => $values ) {
			register_post_status( $status, $values );
		}
	}
}
new CL_Post_types;
