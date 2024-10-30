<?php
/**
 * The Lead Exporter
 */

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists( 'CL_Lead_Export' ) ) :

/**
 * @class CL_Lead_Export
 */
class CL_Lead_Export extends CL_Base {

	/**
	 * [__construct description]
	 * @method __construct
	 */
	public function __construct() {

		$this->add_action( 'init', 'init', 1 );
		$this->add_action( 'admin_footer', 'add_export_link' );
	}

	/**
	 * [init description]
	 * @method init
	 * @return [type] [description]
	 */
	public function init() {

		new CL_Admin_Page( 'cl-export', esc_html__('Export Leads','content-locker'), array(
			'position' => 10,
			'parent' => 'edit.php?post_type=content-locker',
			'onrender' => array( $this, 'display_page' )
		));

		$this->export();
	}

	/**
	 * [display_page description]
	 * @method display_page
	 * @return [type]       [description]
	 */
	public function display_page() {


		$cmb = new_cmb2_box( array(
		    'id'           => 'front-end-post-form',
		    'object_types' => array( 'cl-lead' ),
		    'hookup'       => false,
		    'save_fields'  => false,
		));

		$cmb->add_field( array(
		    'name' => esc_html__( 'Format', 'content-locker' ),
			'id' => 'format',
		    'type' => 'radio_inline',
			'desc' => esc_html__( 'Only the CSV format is available currently.', 'content-locker' ),
			'options' => array(
				'csv' => esc_html__( 'CSV File', 'content-locker' )
			),
			'classes' => 'no-border',
			'default' => 'csv'
		));

		$cmb->add_field( array(
		    'name' => esc_html__( 'Delimiter', 'content-locker' ),
			'id' => 'delimiter',
		    'type' => 'radio_inline',
			'desc' => esc_html__( 'Choose a delimiter for a CSV document.', 'content-locker' ),
			'options' => array(
				',' => esc_html__( 'Comma', 'content-locker' ),
				';' => esc_html__( 'Semicolon', 'content-locker' )
			),
			'default' => ','
		));

		$cmb->add_field( array(
		    'name' => esc_html__( 'Channels', 'content-locker' ),
			'id' => 'lockers',
		    'type' => 'multicheck',
			'desc' => esc_html__( 'Mark lockers which attracted leads you wish to export.', 'content-locker' ),
			'options' => cl_get_lockers( '', 'cmb' ),
			'default' => ','
		));

		$cmb->add_field( array(
		    'name' => esc_html__( 'Email Status', 'content-locker' ),
			'id' => 'status',
		    'type' => 'radio_inline',
			'desc' => esc_html__( 'Choose the email status of leads to export.', 'content-locker' ),
			'options' => array(
				'all' => esc_html__( 'All', 'content-locker' ),
				'confirmed' => esc_html__( 'Only Confirmed Emails', 'content-locker' ),
				'not-confirmed' => esc_html__( 'Only Not Confirmed', 'content-locker' )
			),
			'default' => 'all'
		));

		$cmb->add_field( array(
		    'name' => esc_html__( 'Fields To Export', 'content-locker' ),
			'id' => 'fields',
		    'type' => 'multicheck',
			'options' => array(
				'lead_email' => esc_html__( 'Email', 'content-locker' ),
				'lead_display_name' => esc_html__( 'Display Name', 'content-locker' ),
				'lead_name' => esc_html__( 'Firstname', 'content-locker' ),
				'lead_family' => esc_html__( 'Lastname', 'content-locker' ),
				'lead_date' => esc_html__( 'Date Added', 'content-locker' ),
				'lead_email_status' => esc_html__( 'Email Status', 'content-locker' ),
				'lead_ip' => esc_html__( 'IP', 'content-locker' )
			),
			//'select_all_button' => false,
			'default' => 'all'
		));

		include_once 'views/page-export.php';
	}

	/**
	 * [add_export_link description]
	 * @method add_export_link
	 */
	public function add_export_link() {
		$screen = get_current_screen();

		// checks
		if ( empty( $screen ) ) {
			return;
		}
	    if ( 'edit' !== $screen->base || 'cl-lead' !== $screen->post_type ) {
			return;
		}
		?>
		<script>
		(function($){

			var btn = '';
			btn += '<a href="<?php echo cl_get_admin_url('export') ?>" class="page-title-action" style="display:inline-block">export</a>';
			$('.page-title-action').after(btn);

		})(jQuery);
		</script>
		<?php
	}

	/**
	 * [export description]
	 * @method export
	 * @return [type] [description]
	 */
	public function export() {

		if( !isset( $_POST['object_id'] ) || 'cl-export-plz' !== $_POST['object_id'] ) {
			return;
		}

		// default values
        $status = 'all';
        $fields = array('lead_email', 'lead_name', 'lead_family');
        $delimiter = ',';

		// - delimiter
        $delimiter = isset( $_POST['delimiter'] ) ? $_POST['delimiter'] : ',';
        if ( !in_array( $status, array(',', ';') ) ) {
			$status = ',';
		}

        // - channels
		$ids = array();
        $lockers = isset( $_POST['lockers'] ) ? $_POST['lockers'] : array();
        foreach( $lockers as $id ) {
            $ids[] = intval( $id );
        }

        // - status
        $status = isset( $_POST['status'] ) ? $_POST['status'] : 'all';
        if ( !in_array( $status, array('all', 'confirmed', 'not-confirmed') ) ) {
			$status = 'all';
		}

        // - fields
		$fields = array();
		$raw_fields = isset( $_POST['fields'] ) ? $_POST['fields'] : array();
        foreach( $raw_fields as $field ) {

			if ( !in_array( $field, array( 'lead_email', 'lead_display_name', 'lead_name', 'lead_family', 'lead_ip', 'lead_date', 'lead_email_status' ) ) ) {
				continue;
			}

            $fields[] = $field;
        }

		if( empty( $ids ) || empty( $fields ) ) {
			$this->message = '<div class="error inline"><p>'. esc_html__( 'Please make sure that you selected at least one channel and field.', 'content-locker' ) .'</p></div>';
			return;
		}

		global $wpdb;

		$select_hash = array(
			'lead_email' => 'posts.post_excerpt as email',
			'lead_display_name' => 'posts.post_title as display_name',
			'lead_date' => 'posts.post_date as date_added',
			'lead_email_status' => 'posts.post_status as email_status',
			'lead_name' => 'm1.meta_value as first_name',
			'lead_family' => 'm2.meta_value as last_name',
			'lead_ip' => 'm3.meta_value as ip',
		);

		$join_hash = array(
			'lead_name' => "LEFT JOIN mts_postmeta as m1 ON ( posts.ID = m1.post_id AND m1.meta_key = '_mts_cl_lead_name' )",
			'lead_family' => "LEFT JOIN mts_postmeta as m2 ON ( posts.ID = m2.post_id AND m2.meta_key = '_mts_cl_lead_family' )",
			'lead_ip' => "LEFT JOIN mts_postmeta as m3 ON ( posts.ID = m3.post_id AND m3.meta_key = '_mts_cl_lead_ip' )",
		);

		$select = $joins = array();
		foreach( $fields as $fid ) {

			if( isset( $select_hash[$fid] ) ) {
				$select[] = $select_hash[$fid];
			}

			if( isset( $join_hash[$fid] ) ) {
				$joins[] = $join_hash[$fid];
			}
		}

		$ids = join( "', '", $ids );
		$joins = join( ' ', $joins );
		$select = join( ', ', $select );

		if( 'all' === $status ) {
			$status = '';
		}
		else {
			$status = " AND p.post_status = '{$status}'";
		}

		$subquery = "SELECT p.ID FROM {$wpdb->posts} as p INNER JOIN {$wpdb->postmeta} as m ON ( p.ID = m.post_id ) WHERE 1=1 AND ( ( m.meta_key = '_mts_cl_lead_locker_id' AND m.meta_value IN ('{$ids}') ) ) AND p.post_type = 'cl-lead' {$status} GROUP BY p.ID ORDER BY p.post_date DESC";
		$sql = "SELECT posts.ID as id, {$select} FROM {$wpdb->posts} as posts {$joins} WHERE posts.ID IN ({$subquery}) GROUP BY posts.ID";

		$leads = $wpdb->get_results( $sql, ARRAY_A );

		if( empty( $leads ) ) {
			$this->message = '<div class="error inline"><p>'. esc_html__( 'No leads found. Please try to change the settings of exporting.', 'content-locker' ) .'</p></div>';
			return;
		}

		$filename = 'content-locker-leads-' . date('Y-m-d-H-i-s') . '.csv';

        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = fopen("php://output", "w");
		fputcsv( $output, array_keys( $leads['0'] ), $delimiter );
        foreach( $leads as $row ) {
            fputcsv( $output, $row, $delimiter );
        }
        fclose($output);

        exit;
	}
}

endif;
new CL_Lead_Export;
