<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'cmb2_init', 'cl_add_metabox_visibility_options' );
/**
 * [cl_add_metabox_visibility_options description]
 * @method cl_add_metabox_visibility_options
 */
function cl_add_metabox_visibility_options() {

	$prefix = '_mts_cl_';

	$cmb = new_cmb2_box( array(
		'id'           => 'cl-visibility-options',
		'title'        => esc_html__( 'Visibility Options', 'content-locker' ),
		'object_types' => array( 'content-locker' ),
		'context'		=> 'normal',
		'priority'		=> 'default',
		'classes'		=> 'convert-to-tabs'
	));

	$cmb->add_field( array(
		'name' => sprintf(
			'<i class="fa fa-users"></i>%s<p class="cmb2-metabox-description">%s</p>',
			esc_html__( 'Hide For Members', 'content-locker' ),
			esc_html__( 'Use this option to hide Lockers for logged in users.', 'content-locker' )
		),
		'id' => $prefix . 'hide_for_member',
		'type' => 'radio_inline',
		'options' => array(
			'on' => esc_html__( 'On', 'content-locker' ),
			'off' => esc_html__( 'Off', 'content-locker' )
		),
		'default' => 'off',
		'classes' => 'cmb-half cmb-split65'
	) );

	$cmb->add_field( array(
		'name' => sprintf(
			'<i class="fa fa-umbrella"></i>%s<p class="cmb2-metabox-description">%s</p>',
			esc_html__( 'Show Always', 'content-locker' ),
			esc_html__( 'Even if locker was unlocked before, it will appear again after page reload.', 'content-locker' )
		),
		'id' => $prefix . 'always',
		'type' => 'radio_inline',
		'options' => array(
			'on' => esc_html__( 'On', 'content-locker' ),
			'off' => esc_html__( 'Off', 'content-locker' )
		),
		'default' => 'off',
		'classes' => 'cmb-half cmb-split65'
	) );

	$extra_desc = '';
	if( ! cl()->is_pro() ) {
		$extra_desc .= '<br />';
		$extra_desc .= wp_kses_post( sprintf( __( 'This Premium feature is available in the <a target="_blank" href="%s">pro version</a>.', 'content-locker' ), cl()->purchase_link() ) );
	}
	$cmb->add_field( array(
		'name' => sprintf(
			'<i class="fa fa-lock"></i>%s<p class="cmb2-metabox-description">%s</p>',
			esc_html__( 'Lock After', 'content-locker' ),
			esc_html__( 'If on, the locker will appear after a specified interval of post creation.', 'content-locker' ) . $extra_desc
		),
		'id' => $prefix . 'post_lock',
		'type' => 'radio_inline',
		'options' => array(
			'on' => esc_html__( 'On', 'content-locker' ),
			'off' => esc_html__( 'Off', 'content-locker' )
		),
		'default' => 'off',
		'attributes'  => array(
			'data-for' => 'section-post_lock',
			'disabled' => 'disabled'
		),
		'classes' => 'cmb-half cmb-split65 service-checker move-to-parent disable-input'
	) );

	$cmb->add_field( array(
		'id' => 'post_lock',
		'type' => 'dependency',
		'render_row_cb' => 'cl_cmb_tab_open_tag'
	) );

		$cmb->add_field( array(
			'id' => $prefix . 'post_lock_interval',
			'name' => esc_html__( 'The locker will appear after:', 'content-locker' ),
			'type' => 'text',
			'classes' => 'cmb-split65 cmb-60 no-border-top no-border-left'
		) );

		$cmb->add_field( array(
			'id' => $prefix . 'post_lock_interval_units',
			'type' => 'select',
			'options' => array(
				'days' => esc_html__( 'day(s)', 'content-locker' ),
				'hours' => esc_html__( 'hour(s)', 'content-locker' ),
				'minutes' => esc_html__( 'minute(s)', 'content-locker' ),
			),
			'classes' => 'cmb-40 no-border-top'
		) );

	$cmb->add_field( array(
		'id' => 'post_lock_close',
		'type' => 'tab_end',
		'render_row_cb' => 'cl_cmb_tab_close_tag'
	) );

	$cmb->add_field( array(
		'name' => sprintf(
			'<i class="fa fa-refresh"></i>%s<p class="cmb2-metabox-description">%s</p>',
			esc_html__( 'Relock', 'content-locker' ),
			esc_html__( 'If on, unlocked locker will appear again after a specified interval.', 'content-locker' ) . $extra_desc
		),
		'id' => $prefix . 'relock',
		'type' => 'radio_inline',
		'options' => array(
			'on' => esc_html__( 'On', 'content-locker' ),
			'off' => esc_html__( 'Off', 'content-locker' )
		),
		'default' => 'off',
		'attributes'  => array(
			'data-for' => 'section-relock',
			'disabled' => 'disabled'
		),
		'classes' => 'cmb-half cmb-split65 service-checker move-to-parent disable-input'
	) );

	$cmb->add_field( array(
		'id' => 'relock',
		'type' => 'dependency',
		'render_row_cb' => 'cl_cmb_tab_open_tag'
	) );

			$cmb->add_field( array(
				'id' => $prefix . 'relock_interval',
				'name' => sprintf(
					'%s<br><span class="cmb2-metabox-description">%s</span>',
					esc_html__( 'The locker will reappear after:', 'content-locker' ),
					esc_html__( 'Any changes will apply only for new users.', 'content-locker' )
				),
				'type' => 'text',
				'classes' => 'cmb-split65 cmb-60 no-border-top no-border-left'
			) );

			$cmb->add_field( array(
				'id' => $prefix . 'relock_interval_units',
				'type' => 'select',
				'options' => array(
					'days' => esc_html__( 'day(s)', 'content-locker' ),
					'hours' => esc_html__( 'hour(s)', 'content-locker' ),
					'minutes' => esc_html__( 'minute(s)', 'content-locker' ),
				),
				'classes' => 'cmb-40 no-border-top'
			) );

	$cmb->add_field( array(
		'id' => 'relock-close',
		'type' => 'tab_end',
		'render_row_cb' => 'cl_cmb_tab_close_tag'
	) );

	$cmb->add_field( array(
		'name' => sprintf(
			'<i class="fa fa-mobile"></i>%s<p class="cmb2-metabox-description">%s</p>',
			esc_html__( 'Mobile', 'content-locker' ),
			esc_html__( 'If on, the locker will appear on mobile devices.', 'content-locker' )
		),
		'id' => $prefix . 'mobile',
		'type' => 'radio_inline',
		'options' => array(
			'on' => esc_html__( 'On', 'content-locker' ),
			'off' => esc_html__( 'Off', 'content-locker' )
		),
		'default' => 'on',
		'classes' => 'cmb-half cmb-split65'
	) );
}
