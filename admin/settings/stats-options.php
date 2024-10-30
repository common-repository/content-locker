<?php
/**
 * The file contains common settings for the plugin.
 */

$cmb->add_field( array(
	'name' => '<i class="fa fa-bar-chart"></i>' . esc_html__( 'Stats Options', 'content-locker' ),
	'id' => 'settings-stats-tab',
	'type' => 'section',
	'render_row_cb' => 'cl_cmb_tab_open_tag'
) );

	$cmb->add_field( array(
		'id' => 'settings-stats-hint',
		'desc' => esc_html__( 'Configure here how the plugin should collect the statistical data.', 'content-locker' ),
		'type' => 'title',
		'render_row_cb' => 'cl_cmb_alert'
	) );

	$cmb->add_field( array(
		'name' => esc_html__( 'Google Analytics', 'content-locker' ),
		'id' => 'google_analytics',
		'type' => 'radio_inline',
		'desc' => wp_kses_post( __( 'If set On, the plugin will generate events for the Google Analytics when the content is unlocked.<br /><strong>Note</strong>: before enabling this feature, please make sure that your website contains the Google Analytics tracker code.', 'content-locker' ) ),
		'options' => array(
			'on' => esc_html__( 'On', 'content-locker' ),
			'off' => esc_html__( 'Off', 'content-locker' ),
		),
		'default' => 'off'
	) );

	$extra_desc = '';
	if( ! cl()->is_pro() ) {
		$extra_desc .= '<br />';
		$extra_desc .= '<span style="color: orange;">'.wp_kses_post( sprintf( __( 'Deatailed Stats Reports is available in the <a target="_blank" href="%s">pro version</a>.', 'content-locker' ), cl()->purchase_link() ) ).'</span>';
	}
	$cmb->add_field( array(
		'name' => esc_html__( 'Collecting Stats', 'content-locker' ),
		'id' => 'tracking',
		'type' => 'radio_inline',
		'desc' => esc_html__( 'Turns on collecting the statistical data for reports.', 'content-locker' ) . $extra_desc,
		'options' => array(
			'on' => esc_html__( 'On', 'content-locker' ),
			'off' => esc_html__( 'Off', 'content-locker' ),
		),
		'default' => 'on',
		'classes' => 'no-border mb0'
	) );

	$cmb->add_field( array(
		'id' => 'stats_warning',
		'type' => 'view',
		'file' => 'hint-setting-stats',
		'render_row_cb' => 'cl_cmb_alert'
	) );

$cmb->add_field( array(
	'id' => 'settings-stats-tab-close',
	'type' => 'section_end',
	'render_row_cb' => 'cl_cmb_tab_close_tag'
) );
