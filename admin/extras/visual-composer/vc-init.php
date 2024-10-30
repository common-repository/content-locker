<?php
/**
 * The Visual Composer Map
 */

add_action( 'vc_before_init', function() {

	// check
	if ( !class_exists( 'WPBakeryShortCodesContainer' ) ) {
		return;
	}

	// Init VC Containers for Shortcodes
	class WPBakeryShortCode_sociallocker extends WPBakeryShortCodesContainer {}
    class WPBakeryShortCode_signinlocker extends WPBakeryShortCodesContainer {}

	// Social Locker Map
	$social = array(
		'name' => esc_html__( 'Social Locker', 'content-locker' ),
		'base' => 'sociallocker',
		'description' => esc_html__( 'Adds one of existing Social Lockers.', 'content-locker' ),
		'category' => esc_html__( 'MyThemeShop', 'content-locker' ),
		'content_element' => true,
        'show_settings_on_create' => true,
        'as_parent' => array( 'except' => 'nothing_or_something' ),
		'js_view' => 'VcColumnView',
		'save_always' => true,
		'params' => array(
			array(
				'type' => 'dropdown',
				'param_name' => 'id',
				'heading' => esc_html__('Select locker to insert', 'content-locker'),
				'value' => cl_get_lockers( 'social-locker', 'vc' )
			)
		)
	);
	vc_map( $social );

	// Signing Locker Map
	$signin = array(
		'name' => esc_html__( 'Sign-In Locker', 'content-locker' ),
		'base' => 'signinlocker',
		'description' => esc_html__( 'Adds one of existing Sign-In Lockers.', 'content-locker' ),
		'category' => esc_html__( 'MyThemeShop', 'content-locker' ),
		'content_element' => true,
        'show_settings_on_create' => true,
        'as_parent' => array( 'except' => 'nothing_or_something' ),
		'js_view' => 'VcColumnView',
		'save_always' => true,
		'params' => array(
			array(
				'type' => 'dropdown',
				'param_name' => 'id',
				'heading' => esc_html__('Select locker to insert', 'content-locker'),
				'value' => cl_get_lockers( 'signin-locker', 'vc' )
			)
		)
	);
	vc_map( $signin );
});
