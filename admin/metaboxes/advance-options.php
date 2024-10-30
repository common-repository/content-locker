<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'cmb2_init', 'cl_add_metabox_advance_options' );
/**
 * [cl_add_metabox_advance_options description]
 * @method cl_add_metabox_advance_options
 */
function cl_add_metabox_advance_options() {

	$prefix = '_mts_cl_';

	$cmb = new_cmb2_box( array(
		'id'           => 'cl-advance-options',
		'title'        => esc_html__( 'Advance Options', 'content-locker' ),
		'object_types' => array( 'content-locker' ),
		'context'		=> 'normal',
		'priority'		=> 'default',
		'classes'		=> 'convert-to-tabs'
	));

	$cmb->add_field( array(
		'name' => sprintf(
			'<i class="fa fa-times-circle-o"></i>%s<p class="cmb2-metabox-description">%s</p>',
			esc_html__( 'Close Icon', 'content-locker' ),
			esc_html__( 'Shows Close Icon in the corner.', 'content-locker' )
		),
		'id' => $prefix . 'close',
		'type' => 'radio_inline',
		'options' => array(
			'on' => esc_html__( 'On', 'content-locker' ),
			'off' => esc_html__( 'Off', 'content-locker' )
		),
		'classes' => 'cmb-half cmb-split65',
		'default' => 'off'
	) );

	$extra_desc = '';
	if( ! cl()->is_pro() ) {
		$extra_desc .= wp_kses_post( sprintf( __( 'This Premium feature is available in the <a target="_blank" href="%s">pro version</a>.', 'content-locker' ), cl()->purchase_link() ) );
	}
	$cmb->add_field( array(
		'name' => sprintf(
			'<i class="fa fa-clock-o"></i>%s<p class="cmb2-metabox-description">%s</p>',
			esc_html__( 'Timer Interval', 'content-locker' ),
			esc_html__( 'Unlock the locker after specified time interval.', 'content-locker' ) . $extra_desc
		),
		'id' => $prefix . 'timer',
		'type' => 'text_small',
		'classes' => 'cmb-half cmb-split65 disable-input',
		'attributes'  => array(
			'disabled' => 'disabled'
		)
	) );

	/*$cmb->add_field( array(
		'name' => sprintf(
			'<i class="fa fa-spinner"></i>%s<p class="cmb2-metabox-description">%s</p>',
			esc_html__( 'AJAX', 'content-locker' ),
			esc_html__( 'If On, locked content will be cut from a page source code.', 'content-locker' )
		),
		'id' => $prefix . 'ajax',
		'type' => 'radio_inline',
		'options' => array(
			'on' => esc_html__( 'On', 'content-locker' ),
			'off' => esc_html__( 'Off', 'content-locker' )
		),
		'classes' => 'cmb-split65',
		'default' => 'off'
	) );*/

	$cmb->add_field( array(
		'name' => sprintf(
			'<i class="fa fa-sun-o"></i>%s<p class="cmb2-metabox-description">%s</p>',
			esc_html__( 'Highlight on Unlock', 'content-locker' ),
			esc_html__( 'Enable this option to hightlight the content upon unlock.', 'content-locker' )
		),
		'id' => $prefix . 'highlight',
		'type' => 'radio_inline',
		'options' => array(
			'on' => esc_html__( 'On', 'content-locker' ),
			'off' => esc_html__( 'Off', 'content-locker' )
		),
		'classes' => 'cmb-half cmb-split65',
		'default' => 'on'
	) );
}
