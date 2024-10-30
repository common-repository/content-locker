<?php
/**
 * The file contains common settings for the plugin.
 */
$cmb->add_field( array(
	'name' => '<i class="fa fa-envelope"></i>' . esc_html__( 'Subscription Options', 'content-locker' ),
	'id' => 'settings-subscription-tab',
	'type' => 'section',
	'render_row_cb' => 'cl_cmb_tab_open_tag'
) );

	$cmb->add_field( array(
		'id' => 'settings-subscription-hint',
		'desc' => esc_html__( 'Set up here how you would like to save emails of your subscribers.', 'content-locker' ),
		'type' => 'title',
		'render_row_cb' => 'cl_cmb_alert'
	) );

	$cmb->add_field( array(
		'name' => esc_html__( 'Default List', 'content-locker' ),
		'id' => 'default_mailing',
		'type' => 'select',
		'options' => cl_get_mailing_lists(),
		'desc' => esc_html__( 'Select a default list to be used when none is selected.', 'content-locker' ),
		'classes' => 'no-border'
	));

	$group_field_id = $cmb->add_field( array(
		'id' => 'mailing',
		'type' => 'group',
		'repeatable' => true,
		'classes' => 'accordion scheme-white',
		'options' => array(
			'group_title'   => __( 'List {#}', 'content-locker' ), // since version 1.1.4, {#} gets replaced by row number
			'add_button'    => __( 'Add List', 'content-locker' ),
			'group_title'   => __( 'Add Newsletter Service', 'cmb2' ),
			'remove_button' => __( 'Remove List', 'content-locker' ),
			'sortable' => false,
			'closed' => true
		)
	) );

	$cmb->add_group_field( $group_field_id, array(
		'id' => 'mailing-services',
		'type' => 'select-tabs',
		'render_row_cb' => 'cl_cmb_tab_open_tag'
	) );

		$cmb->add_group_field( $group_field_id, array(
			'id' => 'mailing_name',
			'type' => 'text',
			'name' => esc_html__( 'List Name', 'content-locker' ),
			'desc' => esc_html__( 'For internal use only.', 'content-locker' ),
			'classes' => 'no-border repeating-group-title'
		));

		$services = array( 'none' => esc_html__( 'Select service', 'content-locker' ) ) + cl_get_subscription_services();
		$cmb->add_group_field( $group_field_id, array(
			'name' => esc_html__( 'Mailing Service', 'content-locker' ),
			'id' => 'mailing',
			'type' => 'select',
			'options' => $services,
			'desc' => esc_html__( 'Add subscribers to your list.', 'content-locker' ),
			'classes' => 'cl-select-tabs'
		));

		foreach( $services as $func => $item ) {
			$func = "cl_subscription_option_{$func}";
			if( function_exists( $func) ) {
				$func( $cmb, $group_field_id );
			}
		}

	$cmb->add_group_field( $group_field_id, array(
		'id' => 'mailing-services-close',
		'type' => 'select-tabs',
		'render_row_cb' => 'cl_cmb_tab_close_tag'
	) );

$cmb->add_field( array(
	'id' => 'settings-subscription-tab-close',
	'type' => 'section_end',
	'render_row_cb' => 'cl_cmb_tab_close_tag'
) );

/**
 * [cl_subscription_option_mailchimp description]
 * @method cl_subscription_option_mailchimp
 * @param  [type]                           $cmb [description]
 * @return [type]                                [description]
 */
function cl_subscription_option_mailchimp( $cmb, $group_field_id ) {

	$prefix = 'mailchimp_';

	$cmb->add_group_field( $group_field_id, array(
		'id' => 'mailchimp',
		'type' => 'section',
		'render_row_cb' => 'cl_cmb_tab_open_tag'
	) );

		$cmb->add_group_field( $group_field_id, array(
			'id' => $prefix . 'api_key',
			'type' => 'text',
			'name' => esc_html__( 'API Key', 'content-locker' ),
			'desc' => esc_html__( 'The API key of your MailChimp account.', 'content-locker' ),
			'classes' => 'no-border service-api-key',
			'attributes' => array(
				'data-api-url' => 'http://kb.mailchimp.com/integrations/api-integrations/about-api-keys#Finding-or-generating-your-API-key',
				'data-api-id' => 'api_key'
			)
		));


		$list = array( 'none' => esc_html__( 'Select List', 'content-locker' ) ) + cl_get_service_list( 'mailchimp' );
		$cmb->add_group_field( $group_field_id, array(
			'name' => esc_html__( 'List', 'content-locker' ),
			'id' => $prefix . 'list',
			'type' => 'select',
			'options' => $list,
			'classes' => 'no-border service-lists',
			'attributes' => array(
				'data-service' => 'mailchimp'
			)
		));

	$cmb->add_group_field( $group_field_id, array(
		'id' => $prefix . 'close',
		'type' => 'section',
		'render_row_cb' => 'cl_cmb_tab_close_tag'
	) );
}

/**
 * [cl_subscription_option_mailerlite description]
 * @method cl_subscription_option_mailerlite
 * @param  [type]                            $cmb [description]
 * @return [type]                                 [description]
 */
function cl_subscription_option_mailerlite( $cmb, $group_field_id ) {

	$prefix = 'mailerlite_';

	$cmb->add_group_field( $group_field_id, array(
		'id' => 'mailerlite',
		'type' => 'section',
		'render_row_cb' => 'cl_cmb_tab_open_tag'
	) );

		$cmb->add_group_field( $group_field_id, array(
			'id' => $prefix . 'api_key',
			'type' => 'text',
			'name' => esc_html__( 'API Key', 'content-locker' ),
			'desc' => esc_html__( 'The API key of your MailChimp account.', 'content-locker' ),
			'classes' => 'no-border service-api-key',
			'attributes' => array(
				'data-api-url' => 'https://kb.mailerlite.com/does-mailerlite-offer-an-api/',
				'data-api-id' => 'api_key'
			)
		));

		$list = array( 'none' => esc_html__( 'Select List', 'content-locker' ) ) + cl_get_service_list( 'mailerlite' );
		$cmb->add_group_field( $group_field_id, array(
			'name' => esc_html__( 'List', 'content-locker' ),
			'id' => $prefix . 'list',
			'type' => 'select',
			'options' => $list,
			'classes' => 'no-border service-lists',
			'attributes' => array(
				'data-service' => 'mailerlite'
			)
		));

	$cmb->add_group_field( $group_field_id, array(
		'id' => $prefix . 'close',
		'type' => 'section',
		'render_row_cb' => 'cl_cmb_tab_close_tag'
	) );
}
