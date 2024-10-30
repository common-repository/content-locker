<?php
/**
 * The Stats
 */

class CL_Stats extends CL_Base {

	private static $current_mysql_date = null;

	/**
	 * [__construct description]
	 * @method __construct
	 */
	public function __construct() {

		// AJAX
		$this->add_action( 'wp_ajax_mts_cl_stats', 'statistics' );
		$this->add_action( 'wp_ajax_nopriv_mts_cl_stats', 'statistics' );
	}

	/**
	 * [statistics description]
	 * @method statistics
	 * @return [type]        [description]
	 */
	public function statistics() {

		if( !$this->verify() ) {
			wp_send_json(array(
				'success' => false,
				'message' => esc_html__( 'Not permitted.', 'content-locker' )
			));
		}

		$stats = isset( $_POST['stats'] ) ? $_POST['stats'] : array();
		$context = isset( $_POST['context'] ) ? $_POST['context'] : array();

		// event
		$event_name = isset( $stats['name'] ) ? $stats['name']: null;
		$event_type = isset( $stats['type'] ) ? $stats['type']: null;

		// context
		$post_id = isset( $context['post_id'] ) ? $context['post_id']: null;
		$locker_id = isset( $context['locker_id'] ) ? $context['locker_id']: null;

		if( empty( $locker_id ) ) {
			wp_send_json(array(
				'success' => false,
				'message' => esc_html__( 'Locker ID is not specified.', 'content-locker' )
			));
		}

		// Process it now.
		$this->process_event( $locker_id, $post_id, $event_name, $event_type );
		wp_send_json(array(
			'success' => true,
			'message' => esc_html__( 'Counted.', 'content-locker' )
		));
	}

	/**
	 * Counts an event (unlock, impress, etc.)
	 * @method process_event
	 * @return [type]        [description]
	 */
	protected function process_event( $locker_id, $post_id, $event_name, $event_type ) {

		if ( 'unlock' == $event_type ) {
            self::count_metric( $locker_id, $post_id, 'unlock' );
            self::count_metric( $locker_id, $post_id, 'unlock-via-' . $event_name );
        } elseif ( 'skip' == $event_type ) {
            self::count_metric( $locker_id, $post_id, 'skip' );
            self::count_metric( $locker_id, $post_id, 'skip-via-' . $event_name );
        } else {
            self::count_metric( $locker_id, $post_id, $event_name );
        }

        // updates the summary stats for the item

        if ( 'unlock' === $event_type ) {

            $unlocks = intval( get_post_meta( $locker_id, '_mts_cl_unlocks', true ) );
            $unlocks++;
            update_post_meta( $locker_id, '_mts_cl_unlocks', $unlocks );

        } elseif ( 'impress' === $event_name ) {

            $imperessions = intval( get_post_meta($locker_id, '_mts_cl_imperessions', true) );
            $imperessions++;
            update_post_meta( $locker_id, '_mts_cl_imperessions', $imperessions );
        }
	}

	public static function count_metric( $locker_id, $post_id, $metric ) {

		global $wpdb;

		if( empty( $locker_id ) || empty( $post_id ) ) {
			return;
		}

		$query = $wpdb->prepare(
			"INSERT INTO {$wpdb->prefix}mts_locker_stats
            (aggregate_date,locker_id,post_id,metric_name,metric_value)
            VALUES (%s,%d,%d,%s,1)
            ON DUPLICATE KEY UPDATE metric_value = metric_value + 1",
			self::get_current_date(), $locker_id, $post_id, $metric
		);

		$wpdb->query( $query );
	}

	/**
	 * A helper method to return a current date in the MySQL format.
	 * @method get_current_date
	 * @return [type]           [description]
	 */
	public static function get_current_date() {

		if ( self::$current_mysql_date ) {
			return self::$current_mysql_date;
		}

        $hrsOffset = get_option( 'gmt_offset' );
        if ( strpos($hrsOffset, '-') !== 0 ) {
			$hrsOffset = '+' . $hrsOffset;
		}

		$hrsOffset .= ' hours';
        $time = strtotime( $hrsOffset, time() );

        self::$current_mysql_date = date( 'Y-m-d', $time );
        return self::$current_mysql_date;
	}

	/**
	 * [verify description]
	 * @method verify
	 * @return [type] [description]
	 */
	public function verify() {
		return check_admin_referer( 'mts_cl_security', 'security', false );
	}
}
new CL_Stats;
