<?php
/**
 * The file contains common settings for the plugin.
 */

$cmb->add_field( array(
	'name' => '<i class="fa fa-list-alt"></i>' . esc_html__( 'Front-end Text', 'content-locker' ),
	'id' => 'settings-text-tab',
	'type' => 'section',
	'render_row_cb' => 'cl_cmb_tab_open_tag'
) );

	$cmb->add_field( array(
		'id' => 'settings-text-hint',
		'desc' => esc_html__( 'You can change primary front-end text in the settings of a particular locker. Here you can change the remaining text. It will be applied to all your lockers.', 'content-locker' ),
		'type' => 'title',
		'render_row_cb' => 'cl_cmb_alert'
	) );

	$cmb->add_field( array(
		'name' => esc_html__( 'Sign-In Buttons', 'content-locker' ),
		'id' => 'settings-text-hint-signin',
		'type' => 'hint',
		'desc' => esc_html__( 'The text which will appear in the Sign-In Buttons.', 'content-locker' ),
		'render_row_cb' => 'cl_cmb_alert'
	) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Long Text', 'content-locker' ),
			'id' => 'trans_signin_long',
			'type' => 'text_medium',
			'classes' => 'no-border',
			'default' => 'Sign in via {name}',
			'desc' => esc_html__( 'Displayed on a wide Sign-In Button', 'content-locker' ),
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Short Text', 'content-locker' ),
			'id' => 'trans_signin_short',
			'type' => 'text_medium',
			'default' => 'via {name}',
			'desc' => esc_html__( 'Displayed on a narrow Sign-In Button', 'content-locker' ),
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Facebook', 'content-locker' ),
			'id' => 'trans_signin_facebook_name',
			'type' => 'text_medium',
			'classes' => 'no-border',
			'default' => 'Facebook'
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Twitter', 'content-locker' ),
			'id' => 'trans_signin_twitter_name',
			'type' => 'text_medium',
			'classes' => 'no-border',
			'default' => 'Twitter'
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Google', 'content-locker' ),
			'id' => 'trans_signin_google_name',
			'type' => 'text_medium',
			'classes' => 'no-border',
			'default' => 'Google'
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'LinkedIn', 'content-locker' ),
			'id' => 'trans_signin_linkedin_name',
			'type' => 'text_medium',
			'classes' => 'no-border',
			'default' => 'LinkedIn'
		) );

	$cmb->add_field( array(
		'name' => esc_html__( 'Miscellaneous', 'content-locker' ),
		'id' => 'settings-text-hint-misc',
		'type' => 'hint',
		'desc' => esc_html__( 'Miscellaneous text used usually with all lockers.', 'content-locker' ),
		'render_row_cb' => 'cl_cmb_alert'
	) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Processing Data', 'content-locker' ),
			'id' => 'trans_misc_data_processing',
			'type' => 'text',
			'classes' => 'no-border',
			'default' => 'Processing data, please wait...'
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Enter Your Email Address', 'content-locker' ),
			'id' => 'trans_misc_enter_your_email',
			'type' => 'text',
			'default' => 'enter your email address'
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'You Agree With', 'content-locker' ),
			'id' => 'trans_misc_your_agree_with',
			'type' => 'text',
			'classes' => 'no-border',
			'default' => 'By clicking on the button(s), you agree with {links}',
			'desc' => esc_html__( 'Use the tag {links} to display the links to the Terms Of Use and Privacy Policy.', 'content-locker' )
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Terms Of Use', 'content-locker' ),
			'id' => 'trans_misc_terms_of_use',
			'type' => 'text_medium',
			'classes' => 'no-border',
			'default' => 'Terms Of Use'
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Privacy Policy', 'content-locker' ),
			'id' => 'trans_misc_privacy_policy',
			'type' => 'text_medium',
			'default' => 'Privacy Policy'
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Close', 'content-locker' ),
			'id' => 'trans_misc_close',
			'type' => 'text_medium',
			'classes' => 'no-border',
			'default' => 'close'
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Or', 'content-locker' ),
			'id' => 'trans_misc_or',
			'type' => 'text_medium',
			'classes' => 'no-border',
			'default' => 'OR'
		) );

	$cmb->add_field( array(
		'name' => esc_html__( 'The Screen "One Step To Complete"', 'content-locker' ),
		'id' => 'settings-text-hint-one',
		'type' => 'hint',
		'desc' => esc_html__( 'Appears when a social network does not return an email address and the locker asks the users to enter it manually.', 'content-locker' ),
		'render_row_cb' => 'cl_cmb_alert'
	) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Header', 'content-locker' ),
			'id' => 'trans_onestep_screen_title',
			'type' => 'text',
			'classes' => 'no-border',
			'default' => 'One Step To Complete'
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Instruction', 'content-locker' ),
			'id' => 'trans_onestep_screen_instruction',
			'type' => 'textarea_small',
			'classes' => 'no-border',
			'default' => 'Please enter your email below to continue.'
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Button', 'content-locker' ),
			'id' => 'trans_onestep_screen_button',
			'type' => 'text',
			'classes' => 'no-border',
			'default' => 'OK, complete'
		) );

	$cmb->add_field( array(
		'name' => esc_html__( 'Errors & Notices', 'content-locker' ),
		'id' => 'settings-text-hint-errors',
		'type' => 'hint',
		'desc' => esc_html__( 'The text which users see when something goes wrong.', 'content-locker' ),
		'render_row_cb' => 'cl_cmb_alert'
	) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Empty Email', 'content-locker' ),
			'id' => 'trans_errors_empty_email',
			'type' => 'text',
			'classes' => 'no-border',
			'default' => 'Please enter your email address.'
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Incorrect Email', 'content-locker' ),
			'id' => 'trans_errors_inorrect_email',
			'type' => 'text',
			'classes' => 'no-border',
			'default' => 'It seems you entered an incorrect email address. Please check it.'
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Empty Name', 'content-locker' ),
			'id' => 'trans_errors_empty_name',
			'type' => 'text',
			'default' => 'Please enter your name.'
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Not Signed In', 'content-locker' ),
			'id' => 'trans_errors_not_signed_in',
			'type' => 'text',
			'classes' => 'no-border',
			'default' => 'Sorry, but you have not signed in. Please try again.'
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Not Granted Permissions', 'content-locker' ),
			'id' => 'trans_errors_not_granted',
			'type' => 'text',
			'desc' => esc_html__( 'Use the tag {permissions} to show required permissions.', 'content-locker' ),
			'default' => 'Sorry, but you have not granted all the required permissions ({permissions}). Please try again.'
		) );

$cmb->add_field( array(
	'id' => 'settings-text-tab-close',
	'type' => 'section_end',
	'render_row_cb' => 'cl_cmb_tab_close_tag'
) );
