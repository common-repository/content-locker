<?php
/**
 * The file contains common settings for the plugin.
 */

$cmb->add_field( array(
	'name' => '<i class="fa fa-lock"></i>' . esc_html__( 'Lock Options', 'content-locker' ),
	'id' => 'settings-lock-tab',
	'type' => 'section',
	'render_row_cb' => 'cl_cmb_tab_open_tag'
) );

	$cmb->add_field( array(
		'id' => 'settings-lock-hint',
		'desc' => esc_html__( 'Options linked with the locking feature. Don\'t change the options here if you are not sure that you do.', 'content-locker' ),
		'type' => 'title',
		'render_row_cb' => 'cl_cmb_alert'
	) );

	$cmb->add_field( array(
		'id' => 'settings-lock-hint-img',
		'type' => 'view',
		'file' => 'hint-lock-options',
		'render_row_cb' => 'cl_cmb_alert'
	) );

$cmb->add_field( array(
	'id' => 'settings-lock-tab-close',
	'type' => 'section_end',
	'render_row_cb' => 'cl_cmb_tab_close_tag'
) );
