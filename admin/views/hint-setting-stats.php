<?php
global $wpdb;

$size = $wpdb->get_var(
    "SELECT round(data_length + index_length) as 'size_in_bytes' FROM information_schema.TABLES WHERE " .
    "table_schema = '" . DB_NAME . "' AND table_name = '{$wpdb->prefix}mts_locker_stats'");

$count = $wpdb->get_var("SELECT COUNT(*) AS n FROM {$wpdb->prefix}mts_locker_stats");

$human_size = cl_get_human_size( $size );
?>
<div class="cmb-row cmb-inline no-border mt0">
	<div class="cmb-th">&nbsp;</div>
	<div class="cmb-td">
		<p>
			<?php
			if( 0 === $count ):
				esc_html_e( 'The statistical data is empty.', 'content-locker' );
			else:
				printf( wp_kses_post( __( 'The statistical data takes <strong>%s</strong> on your server', 'content-locker' ) ), $human_size );
			endif;
			?>
			<a class="button cl-clear-data" style="margin-left: 5px;" href="#"><?php esc_html_e( 'clear data', 'content-locker' ) ?></a>
	    </p>
	</div>
</div>
