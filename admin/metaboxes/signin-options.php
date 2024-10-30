<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'cmb2_init', 'cl_add_metabox_signin_options' );
/**
 * [cl_add_metabox_signin_options description]
 * @method cl_add_metabox_signin_options
 */
function cl_add_metabox_signin_options() {

	$prefix = '_mts_cl_';

	$cmb = new_cmb2_box( array(
		'id'           => 'cl-signin-options',
		'title'        => esc_html__( 'Connect Options', 'content-locker' ),
		'object_types' => array( 'content-locker' ),
		'context'      => 'normal',
		'priority'     => 'default',
		'classes'		=> 'convert-to-tabs',
		'show_on_cb'   => function() {
			return 'signin-locker' == cl_get_item_type() ? true : false;
		}
	) );

	$cmb->add_field( array(
		'id' => $prefix . 'warning',
		'type' => 'view',
		'file' => 'hint-signing-connect',
		'render_row_cb' => 'cl_cmb_alert'
	) );

	$cmb->add_field( array(
		'id' => 'signing-options-tabs',
		'type' => 'tabs',
		'render_row_cb' => 'cl_cmb_tabs_open_tag'
	) );

		$order = array(
			'facebook',
			'twitter',
			'google',
			'linkedin',
			'email'
		);

		foreach( $order as $func ) {
			$func = "cl_signing_option_{$func}";
			$func( $cmb );
		}

	$cmb->add_field( array(
		'id' => 'signing-options-tabs-close',
		'type' => 'tabs',
		'render_row_cb' => 'cl_cmb_tabs_close_tag'
	));
}

/**
 * [cl_signing_option_facebook description]
 * @method cl_signing_option_facebook
 * @param  [type]                     $cmb [description]
 * @return [type]                          [description]
 */
function cl_signing_option_facebook( $cmb ) {

	$prefix = '_mts_cl_facebook_';

	$cmb->add_field( array(
		'name' => '<i class="fa fa-facebook-square"></i>' . esc_html__( 'Facebook', 'content-locker' ),
		'id' => 'facebook-connect',
		'type' => 'section',
		'render_row_cb' => 'cl_cmb_tab_open_tag',
		'classes_cb' => function() {
			return cl_cmb_facebook_errors() ? 'cl-signin-section-has-errors' : '';
		}
	) );

		if( $errors = cl_cmb_facebook_errors() ) {
			$cmb->add_field( array(
				'id' => $prefix . 'error',
				'type' => 'danger',
				'desc' => $errors,
				'render_row_cb' => 'cl_cmb_alert',
				'classes' => 'mt0'
			) );
		}

		$cmb->add_field( array(
			'id' => $prefix . 'active',
			'type' => 'radio_inline',
			'name' => esc_html__( 'Activate the Facebook button.', 'content-locker' ),
			'attributes'  => array(
				'data-for' => 'facebook-connect'
			),
			'options' => array(
				'on' => esc_html__( 'On', 'content-locker' ),
				'off' => esc_html__( 'Off', 'content-locker' ),
			),
			'default' => 'on',
			'classes' => 'no-border cmb-split50 cmb-th-left cmb-td-right pr30 service-checker'
		) );

		// Save Email
		$cmb->add_field( array(
			'name' => esc_html__( 'Save Email', 'content-locker' ),
			'id' => $prefix . 'lead',
			'type' => 'radio_inline',
			'desc' => esc_html__( 'This action retrieves an email and some other personal data of the user and saves it in the database.', 'content-locker' ),
			'options' => array(
				'on' => esc_html__( 'Enable', 'content-locker' ),
				'off' => esc_html__( 'Disable', 'content-locker' ),
			),
			'default' => 'on',
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Default List', 'content-locker' ),
			'id' => $prefix . 'mailing',
			'type' => 'select',
			'options' => cl_get_mailing_lists( 'list' ),
			'desc' => wp_kses_post( sprintf( __( 'Select a default list where you want to save the subscriber details. You can integrate your email service <a href="%s">from here</a>.', 'content-locker' ), cl_get_admin_url('settings#mailing_repeat') ) ),
		));

		$cmb->add_field( array(
			'id' => $prefix . 'warning-email',
			'type' => 'view',
			'file' => 'hint-action-email',
			'render_row_cb' => 'cl_cmb_alert'
		) );

		// Create Account
		$cmb->add_field( array(
			'name' => esc_html__( 'Create Account', 'content-locker' ),
			'id' => $prefix . 'signup',
			'type' => 'radio_inline',
			'desc' => esc_html__( 'This action registers the user on your website (password will be generated automatically).', 'content-locker' ),
			'options' => array(
				'on' => esc_html__( 'Enable', 'content-locker' ),
				'off' => esc_html__( 'Disable', 'content-locker' ),
			),
			'default' => 'off',
		) );

		$cmb->add_field( array(
			'id' => $prefix . 'warning',
			'type' => 'view',
			'file' => 'hint-action-signup',
			'render_row_cb' => 'cl_cmb_alert'
		) );

	$cmb->add_field( array(
		'id' => 'facebook-connect-close',
		'type' => 'tab_end',
		'render_row_cb' => 'cl_cmb_tab_close_tag'
	) );
}

/**
 * [cl_signing_option_twitter description]
 * @method cl_signing_option_twitter
 * @param  [type]                    $cmb [description]
 * @return [type]                         [description]
 */
function cl_signing_option_twitter( $cmb ) {
	$prefix = '_mts_cl_twitter_';

	$cmb->add_field( array(
		'name' => '<i class="fa fa-twitter-square"></i>' . esc_html__( 'Twitter', 'content-locker' ),
		'id' => 'twitter-connect',
		'type' => 'section',
		'render_row_cb' => 'cl_cmb_tab_open_tag',
		'classes_cb' => function() {
			return cl_cmb_twitter_errors() ? 'cl-signin-section-has-errors' : '';
		}
	) );

		if( $errors = cl_cmb_twitter_errors() ) {
			$cmb->add_field( array(
				'id' => $prefix . 'error',
				'type' => 'danger',
				'desc' => $errors,
				'render_row_cb' => 'cl_cmb_alert',
				'classes' => 'mt0'
			) );
		}

		$cmb->add_field( array(
			'id' => $prefix . 'active',
			'type' => 'radio_inline',
			'name' => esc_html__( 'Activate the Twiiter button.', 'content-locker' ),
			'attributes'  => array(
				'data-for' => 'twitter-connect'
			),
			'options' => array(
				'on' => esc_html__( 'On', 'content-locker' ),
				'off' => esc_html__( 'Off', 'content-locker' ),
			),
			'default' => 'on',
			'classes' => 'no-border cmb-split50 cmb-th-left cmb-td-right pr30 service-checker'
		) );

		// Save Email
		$cmb->add_field( array(
			'name' => esc_html__( 'Save Email', 'content-locker' ),
			'id' => $prefix . 'lead',
			'type' => 'radio_inline',
			'desc' => esc_html__( 'This action retrieves an email and some other personal data of the user and saves it in the database.', 'content-locker' ),
			'options' => array(
				'on' => esc_html__( 'Enable', 'content-locker' ),
				'off' => esc_html__( 'Disable', 'content-locker' ),
			),
			'default' => 'on',
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Default List', 'content-locker' ),
			'id' => $prefix . 'mailing',
			'type' => 'select',
			'options' => cl_get_mailing_lists( 'list' ),
			'desc' => wp_kses_post( sprintf( __( 'Select a default list where you want to save the subscriber details. You can integrate your email service <a href="%s">from here</a>.', 'content-locker' ), cl_get_admin_url('settings#mailing_repeat') ) ),
		));

		$cmb->add_field( array(
			'id' => $prefix . 'warning-email',
			'type' => 'view',
			'file' => 'hint-action-email',
			'render_row_cb' => 'cl_cmb_alert'
		) );

		// Create Account
		$cmb->add_field( array(
			'name' => esc_html__( 'Create Account', 'content-locker' ),
			'id' => $prefix . 'signup',
			'type' => 'radio_inline',
			'desc' => esc_html__( 'This action registers the user on your website (password will be generated automatically).', 'content-locker' ),
			'options' => array(
				'on' => esc_html__( 'Enable', 'content-locker' ),
				'off' => esc_html__( 'Disable', 'content-locker' ),
			),
			'default' => 'off',
		) );

		$cmb->add_field( array(
			'id' => $prefix . 'warning',
			'type' => 'view',
			'file' => 'hint-action-signup',
			'render_row_cb' => 'cl_cmb_alert'
		) );

		// Extra
		$cmb->add_field( array(
			'name' => esc_html__( 'Show more options (5)', 'content-locker' ),
			'id' => 'fbs_show_extra',
			'type' => 'more',
			'render_row_cb' => 'cl_cmb_more_open_tag'
		) );

			// Follow
			$cmb->add_field( array(
				'name' => esc_html__( 'Follow', 'content-locker' ),
				'id' => $prefix . 'follow',
				'type' => 'radio_inline',
				'desc' => esc_html__( 'If enabled, user will follow you on Twitter after clicking the Sign In button.', 'content-locker' ),
				'options' => array(
					'on' => esc_html__( 'Enable', 'content-locker' ),
					'off' => esc_html__( 'Disable', 'content-locker' ),
				),
				'default' => 'off'
			) );

			$cmb->add_field( array(
				'name' => esc_html__( 'User to follow', 'content-locker' ),
				'id' => $prefix . 'follow_user',
				'type' => 'text',
				'desc' => esc_html__( 'Set a Twitter user name to follow ( without @, for example, mythemeshopteam)', 'content-locker' )
			) );

			$cmb->add_field( array(
				'name' => esc_html__( 'Notifications', 'content-locker' ),
				'id' => $prefix . 'notifications',
				'type' => 'radio_inline',
				'desc' => esc_html__( 'If On, follower will get notifications about new tweets (usually via sms).', 'content-locker' ),
				'options' => array(
					'on' => esc_html__( 'On', 'content-locker' ),
					'off' => esc_html__( 'Off', 'content-locker' ),
				),
				'default' => 'off'
			) );

			// Tweet
			$cmb->add_field( array(
				'name' => esc_html__( 'Tweet', 'content-locker' ),
				'id' => $prefix . 'tweet',
				'type' => 'radio_inline',
				'desc' => esc_html__( 'Sends below specified tweet on behalf of the user after signing in.', 'content-locker' ),
				'options' => array(
					'on' => esc_html__( 'Enable', 'content-locker' ),
					'off' => esc_html__( 'Disable', 'content-locker' ),
				),
				'default' => 'off'
			) );

			$cmb->add_field( array(
				'name' => esc_html__( 'Tweet', 'content-locker' ),
				'id' => $prefix . 'tweet_message',
				'type' => 'textarea_small',
				'desc' => esc_html__( 'Type a tweet. It may include any URL.', 'content-locker' ),
			) );

		$cmb->add_field( array(
			'id' => 'fbs_show_extra_close',
			'type' => 'tab_end',
			'render_row_cb' => 'cl_cmb_more_close_tag'
		) );

	$cmb->add_field( array(
		'id' => 'twitter-connect-close',
		'type' => 'tab_end',
		'render_row_cb' => 'cl_cmb_tab_close_tag'
	) );
}

/**
 * [cl_signing_option_google description]
 * @method cl_signing_option_google
 * @param  [type]                   $cmb [description]
 * @return [type]                        [description]
 */
function cl_signing_option_google( $cmb ) {
	$prefix = '_mts_cl_google_';

	$cmb->add_field( array(
		'name' => '<i class="fa fa-google-plus-square"></i>' . esc_html__( 'Google', 'content-locker' ),
		'id' => 'google-connect',
		'type' => 'section',
		'render_row_cb' => 'cl_cmb_tab_open_tag',
		'classes_cb' => function() {
			return cl_cmb_google_errors() ? 'cl-signin-section-has-errors' : '';
		}
	) );

		if( $errors = cl_cmb_google_errors() ) {
			$cmb->add_field( array(
				'id' => $prefix . 'error',
				'type' => 'danger',
				'desc' => $errors,
				'render_row_cb' => 'cl_cmb_alert',
				'classes' => 'mt0'
			) );
		}

		$cmb->add_field( array(
			'id' => $prefix . 'active',
			'type' => 'radio_inline',
			'name' => esc_html__( 'Activate the Google+ button.', 'content-locker' ),
			'attributes'  => array(
				'data-for' => 'google-connect'
			),
			'options' => array(
				'on' => esc_html__( 'On', 'content-locker' ),
				'off' => esc_html__( 'Off', 'content-locker' ),
			),
			'default' => 'on',
			'classes' => 'no-border cmb-split50 cmb-th-left cmb-td-right pr30 service-checker'
		) );

		// Save Email
		$cmb->add_field( array(
			'name' => esc_html__( 'Save Email', 'content-locker' ),
			'id' => $prefix . 'lead',
			'type' => 'radio_inline',
			'desc' => esc_html__( 'This action retrieves an email and some other personal data of the user and saves it in the database.', 'content-locker' ),
			'options' => array(
				'on' => esc_html__( 'Enable', 'content-locker' ),
				'off' => esc_html__( 'Disable', 'content-locker' ),
			),
			'default' => 'on',
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Default List', 'content-locker' ),
			'id' => $prefix . 'mailing',
			'type' => 'select',
			'options' => cl_get_mailing_lists( 'list' ),
			'desc' => wp_kses_post( sprintf( __( 'Select a default list where you want to save the subscriber details. You can integrate your email service <a href="%s">from here</a>.', 'content-locker' ), cl_get_admin_url('settings#mailing_repeat') ) ),
		));

		$cmb->add_field( array(
			'id' => $prefix . 'warning-email',
			'type' => 'view',
			'file' => 'hint-action-email',
			'render_row_cb' => 'cl_cmb_alert'
		) );

		// Create Account
		$cmb->add_field( array(
			'name' => esc_html__( 'Create Account', 'content-locker' ),
			'id' => $prefix . 'signup',
			'type' => 'radio_inline',
			'desc' => esc_html__( 'This action registers the user on your website (password will be generated automatically).', 'content-locker' ),
			'options' => array(
				'on' => esc_html__( 'Enable', 'content-locker' ),
				'off' => esc_html__( 'Disable', 'content-locker' ),
			),
			'default' => 'off',
		) );

		$cmb->add_field( array(
			'id' => $prefix . 'warning',
			'type' => 'view',
			'file' => 'hint-action-signup',
			'render_row_cb' => 'cl_cmb_alert'
		) );

		// Subscribe
		$cmb->add_field( array(
			'id' => $prefix . 'hint-youtube',
			'type' => 'info',
			'name' => esc_html__( 'Youtube Channel', 'content-locker' ),
			'desc' => esc_html__( 'This action subscribers the user to the specified Youtube channel.', 'content-locker' ),
			'render_row_cb' => 'cl_cmb_alert'
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Activate', 'content-locker' ),
			'id' => $prefix . 'youtube_subscribe',
			'type' => 'radio_inline',
			'options' => array(
				'on' => esc_html__( 'Enable', 'content-locker' ),
				'off' => esc_html__( 'Disable', 'content-locker' ),
			),
			'default' => 'off'
		) );

		$url = 'https://www.youtube.com/account_advanced';
		$cmb->add_field( array(
			'name' => esc_html__( 'Youtube Channel ID', 'content-locker' ),
			'id' => $prefix . 'youtube_channel_id',
			'type' => 'text',
			'desc' => wp_kses_post( sprintf( __( 'Set a channel ID to subscribe, to get your channel ID <a href="%s" target="_blank">click here</a>.', 'content-locker' ), $url ) ),
		) );

	$cmb->add_field( array(
		'id' => 'google-connect-close',
		'type' => 'tab_end',
		'render_row_cb' => 'cl_cmb_tab_close_tag'
	) );
}

/**
 * [cl_signing_option_linkedin description]
 * @method cl_signing_option_linkedin
 * @param  [type]                     $cmb [description]
 * @return [type]                          [description]
 */
function cl_signing_option_linkedin( $cmb ) {
	$prefix = '_mts_cl_linkedin_';

	$cmb->add_field( array(
		'name' => '<i class="fa fa-linkedin-square"></i>' . esc_html__( 'Linkedin', 'content-locker' ),
		'id' => 'linkedin-connect',
		'type' => 'section',
		'render_row_cb' => 'cl_cmb_tab_open_tag',
		'classes_cb' => function() {
			return cl_cmb_linkedin_errors() ? 'cl-signin-section-has-errors' : '';
		}
	) );

		if( $errors = cl_cmb_linkedin_errors() ) {
			$cmb->add_field( array(
				'id' => $prefix . 'error',
				'type' => 'danger',
				'desc' => $errors,
				'render_row_cb' => 'cl_cmb_alert',
				'classes' => 'mt0'
			) );
		}

		$cmb->add_field( array(
			'id' => $prefix . 'active',
			'type' => 'radio_inline',
			'name' => esc_html__( 'Activate the Linkedin button.', 'content-locker' ),
			'attributes'  => array(
				'data-for' => 'linkedin-connect'
			),
			'options' => array(
				'on' => esc_html__( 'On', 'content-locker' ),
				'off' => esc_html__( 'Off', 'content-locker' ),
			),
			'default' => 'on',
			'classes' => 'no-border cmb-split50 cmb-th-left cmb-td-right pr30 service-checker'
		) );

		// Save Email
		$cmb->add_field( array(
			'name' => esc_html__( 'Save Email', 'content-locker' ),
			'id' => $prefix . 'lead',
			'type' => 'radio_inline',
			'desc' => esc_html__( 'This action retrieves an email and some other personal data of the user and saves it in the database.', 'content-locker' ),
			'options' => array(
				'on' => esc_html__( 'Enable', 'content-locker' ),
				'off' => esc_html__( 'Disable', 'content-locker' ),
			),
			'default' => 'on',
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Default List', 'content-locker' ),
			'id' => $prefix . 'mailing',
			'type' => 'select',
			'options' => cl_get_mailing_lists( 'list' ),
			'desc' => wp_kses_post( sprintf( __( 'Select a default list where you want to save the subscriber details. You can integrate your email service <a href="%s">from here</a>.', 'content-locker' ), cl_get_admin_url('settings#mailing_repeat') ) ),
		));

		$cmb->add_field( array(
			'id' => $prefix . 'warning-email',
			'type' => 'view',
			'file' => 'hint-action-email',
			'render_row_cb' => 'cl_cmb_alert'
		) );

		// Create Account
		$cmb->add_field( array(
			'name' => esc_html__( 'Create Account', 'content-locker' ),
			'id' => $prefix . 'signup',
			'type' => 'radio_inline',
			'desc' => esc_html__( 'This action registers the user on your website (password will be generated automatically).', 'content-locker' ),
			'options' => array(
				'on' => esc_html__( 'Enable', 'content-locker' ),
				'off' => esc_html__( 'Disable', 'content-locker' ),
			),
			'default' => 'off',
		) );

		$cmb->add_field( array(
			'id' => $prefix . 'warning',
			'type' => 'view',
			'file' => 'hint-action-signup',
			'render_row_cb' => 'cl_cmb_alert'
		) );

	$cmb->add_field( array(
		'id' => 'linkedin-connect-close',
		'type' => 'tab_end',
		'render_row_cb' => 'cl_cmb_tab_close_tag'
	) );
}

/**
 * [cl_signing_option_email description]
 * @method cl_signing_option_email
 * @param  [type]                  $cmb [description]
 * @return [type]                       [description]
 */
function cl_signing_option_email( $cmb ) {
	$prefix = '_mts_cl_email_';

	$cmb->add_field( array(
		'name' => '<i class="fa fa-envelope"></i>' . esc_html__( 'Email', 'content-locker' ),
		'id' => 'email-connect',
		'type' => 'section',
		'render_row_cb' => 'cl_cmb_tab_open_tag'
	) );

		$cmb->add_field( array(
			'id' => $prefix . 'active',
			'type' => 'radio_inline',
			'name' => esc_html__( 'Activate the Email button.', 'content-locker' ),
			'attributes'  => array(
				'data-for' => 'email-connect'
			),
			'options' => array(
				'on' => esc_html__( 'On', 'content-locker' ),
				'off' => esc_html__( 'Off', 'content-locker' ),
			),
			'default' => 'on',
			'classes' => 'no-border cmb-split50 cmb-th-left cmb-td-right pr30 service-checker'
		) );

		// Save Email
		$cmb->add_field( array(
			'name' => esc_html__( 'Save Email', 'content-locker' ),
			'id' => $prefix . 'lead',
			'type' => 'radio_inline',
			'desc' => esc_html__( 'This action retrieves an email and some other personal data of the user and saves it in the database.', 'content-locker' ),
			'options' => array(
				'on' => esc_html__( 'Enable', 'content-locker' ),
				'off' => esc_html__( 'Disable', 'content-locker' ),
			),
			'default' => 'on'
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Default List', 'content-locker' ),
			'id' => $prefix . 'mailing',
			'type' => 'select',
			'options' => cl_get_mailing_lists( 'list' ),
			'desc' => wp_kses_post( sprintf( __( 'Select a default list where you want to save the subscriber details. You can integrate your email service <a href="%s">from here</a>.', 'content-locker' ), cl_get_admin_url('settings#mailing_repeat') ) ),
		));

		$cmb->add_field( array(
			'id' => $prefix . 'warning-email',
			'type' => 'view',
			'file' => 'hint-action-email',
			'render_row_cb' => 'cl_cmb_alert'
		) );

		// Create Account
		$cmb->add_field( array(
			'name' => esc_html__( 'Create Account', 'content-locker' ),
			'id' => $prefix . 'signup',
			'type' => 'radio_inline',
			'desc' => esc_html__( 'This action registers the user on your website (password will be generated automatically).', 'content-locker' ),
			'options' => array(
				'on' => esc_html__( 'Enable', 'content-locker' ),
				'off' => esc_html__( 'Disable', 'content-locker' ),
			),
			'default' => 'off',
		) );

		$cmb->add_field( array(
			'id' => $prefix . 'warning',
			'type' => 'view',
			'file' => 'hint-action-signup',
			'render_row_cb' => 'cl_cmb_alert'
		) );

	$cmb->add_field( array(
		'id' => 'email-connect-close',
		'type' => 'tab_end',
		'render_row_cb' => 'cl_cmb_tab_close_tag'
	) );
}
