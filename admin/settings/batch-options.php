<?php
/**
 * The file contains common settings for the plugin.
 */
$cmb->add_field( array(
	'name' => '<i class="fa fa-braille"></i>' . esc_html__( 'Batch Locking', 'content-locker' ),
	'id' => 'settings-batch-tab',
	'type' => 'section',
	'render_row_cb' => 'cl_cmb_tab_open_tag'
) );

	$cmb->add_field( array(
		'id' => 'settings-batch-hint',
		'desc' => esc_html__( 'Batch Locking allows to apply the single locker to the posts automatically.', 'content-locker' ),
		'type' => 'title',
		'render_row_cb' => 'cl_cmb_alert'
	) );

	$cmb->add_field( array(
		'id' => 'settings-batch-hint-img',
		'type' => 'view',
		'file' => 'hint-batch-locking',
		'render_row_cb' => 'cl_cmb_alert'
	) );


$cmb->add_field( array(
	'id' => 'settings-batch-tab-close',
	'type' => 'section_end',
	'render_row_cb' => 'cl_cmb_tab_close_tag'
) );
