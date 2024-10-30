<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'cmb2_init', 'cl_add_metabox_social_options' );
/**
 * [cl_add_metabox_social_options description]
 * @method cl_add_metabox_social_options
 */
function cl_add_metabox_social_options() {

	$cmb = new_cmb2_box( array(
		'id'           => 'cl-social-options',
		'title'        => esc_html__( 'Social Options', 'content-locker' ),
		'object_types' => array( 'content-locker' ),
		'context'      => 'normal',
		'priority'     => 'default',
		'classes'		=> 'convert-to-tabs',
		'show_on_cb'   => function() {
			return 'social-locker' == cl_get_item_type() ? true : false;
		}
	) );

	$cmb->add_field( array(
		'id' => '_mts_cl_button_order',
		'type' => 'hidden'
	) );

	$cmb->add_field( array(
		'name' => '<strong>'. esc_html__( 'Hint:', 'content-locker' ) .'</strong> ' . esc_html__( 'Drag and drop the tabs to change the order of the buttons.', 'content-locker' ),
		'id' => '_mts_cl_show_counters',
		'type' => 'radio_inline',
		'desc' => esc_html__( 'Show counters', 'content-locker' ),
		'options' => array(
			'on' => esc_html__( 'On', 'content-locker' ),
			'off' => esc_html__( 'Off', 'content-locker' )
		),
		'default' => 'on',
		'classes' => 'no-border cmb-split50 cmb-th-left cmb-td-right',
	) );

	$cmb->add_field( array(
		'id' => 'social-options-tabs',
		'type' => 'tabs',
		'sortable' => true,
		'order_id' => '_mts_cl_button_order',
		'render_row_cb' => 'cl_cmb_tabs_open_tag'
	) );

		$order = array( 'facebook_like', 'twitter_tweet', 'google_plus', 'facebook_share', 'twitter_follow', 'google_share', 'youtube_subscribe', 'linkedin_share' );
		if( $post_order = get_post_meta( $cmb->object_id, '_mts_cl_button_order', true ) ) {
			$post_order = explode( ',', str_replace( '-', '_', $post_order ) );
			$order = array_unique( array_merge( $post_order, $order ) );
		}

		foreach( $order as $func ) {
			$func = "cl_social_option_{$func}";

			if( function_exists( $func ) ) {
				$func( $cmb );
			}
		}

	$cmb->add_field( array(
		'id' => 'social-options-tabs-close',
		'type' => 'tabs',
		'render_row_cb' => 'cl_cmb_tabs_close_tag'
	) );
}

/**
 * [cl_social_option_facebook_like description]
 * @method cl_social_option_facebook_like
 * @param  [type]                         $cmb [description]
 * @return [type]                              [description]
 */
function cl_social_option_facebook_like( $cmb ) {

	$prefix = '_mts_cl_facebook_like_';

	$cmb->add_field( array(
		'name' => '<i class="fa fa-facebook-square"></i>' . esc_html__( 'Like', 'content-locker' ),
		'id' => 'facebook-like',
		'type' => 'section',
		'render_row_cb' => 'cl_cmb_tab_open_tag'
	) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Active', 'content-locker' ),
			'id' => $prefix . 'active',
			'type' => 'radio_inline',
			'desc' => esc_html__( 'Set On, to activate the button.', 'content-locker' ),
			'classes' => 'service-checker',
			'attributes'  => array(
				'data-for' => 'facebook-like'
			),
			'options' => array(
				'on' => esc_html__( 'On', 'content-locker' ),
				'off' => esc_html__( 'Off', 'content-locker' )
			),
			'default' => 'on'
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'URL to like', 'content-locker' ),
			'id' => $prefix . 'url',
			'type' => 'text',
			'desc' => esc_html__( 'Optional, set an URL (a facebook page or website page link) which user has to like in order to unlock the content. Leave this field empty to use an URL of the page where the locker will be located.', 'content-locker' ),
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Button Title', 'content-locker' ),
			'id' => $prefix . 'button_title',
			'type' => 'text',
			'default' => 'like',
			'desc' => esc_html__( 'Optional, a title of the button that is situated on the covers in some themes.', 'content-locker' ),
		) );

	$cmb->add_field( array(
		'id' => 'fb_like_close',
		'type' => 'tab_end',
		'render_row_cb' => 'cl_cmb_tab_close_tag'
	) );
}

/**
 * [cl_social_option_twitter_tweet description]
 * @method cl_social_option_twitter_tweet
 * @param  [type]                         $cmb [description]
 * @return [type]                              [description]
 */
function cl_social_option_twitter_tweet( $cmb ) {

	$prefix = '_mts_cl_tweet_';

	$cmb->add_field( array(
		'name' => '<i class="fa fa-twitter-square"></i>' . esc_html__( 'Tweet', 'content-locker' ),
		'id' => 'twitter-tweet',
		'type' => 'section',
		'render_row_cb' => 'cl_cmb_tab_open_tag'
	) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Active', 'content-locker' ),
			'id' => $prefix . 'active',
			'type' => 'radio_inline',
			'desc' => esc_html__( 'Set On, to activate the button.', 'content-locker' ),
			'classes' => 'service-checker',
			'attributes'  => array(
				'data-for' => 'twitter-tweet'
			),
			'options' => array(
				'on' => esc_html__( 'On', 'content-locker' ),
				'off' => esc_html__( 'Off', 'content-locker' )
			),
			'default' => 'on'
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'URL to tweet', 'content-locker' ),
			'id' => $prefix . 'url',
			'type' => 'text',
			'desc' => esc_html__( 'Optional, set an URL which user has to tweet in order to unlock your content. Leave this field empty to use an URL of the page where the locker will be located.', 'content-locker' ),
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Tweet', 'content-locker' ),
			'id' => $prefix . 'text',
			'type' => 'textarea_small',
			'desc' => esc_html__( 'Optional, type a message to tweet. Leave this field empty to use default "Page title + URL". Also you can use the shortcode [post_title] in order to automatically insert a post title.', 'content-locker' ),
		) );

		/*$cmb->add_field( array(
			'name' => esc_html__( 'Double Check', 'content-locker' ),
			'id' => $prefix . 'auth',
			'type' => 'radio_inline',
			'desc' => esc_html__( 'Optional. Checks whether the user actually has tweeted or not. Requires the user to authorize the ContentLocker app.', 'content-locker' ),
			'options' => array(
				'on' => esc_html__( 'On', 'content-locker' ),
				'off' => esc_html__( 'Off', 'content-locker' )
			),
			'default' => 'off'
		) );*/

		$cmb->add_field( array(
			'name' => esc_html__( 'Via', 'content-locker' ),
			'id' => $prefix . 'via',
			'type' => 'text',
			'desc' => esc_html__( 'Optional, Twitter user name to attribute the Tweet to (without @).', 'content-locker' ),
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Button Title', 'content-locker' ),
			'id' => $prefix . 'button_title',
			'type' => 'text',
			'default' => 'tweet',
			'desc' => esc_html__( 'Optional, a title of the button that is situated on the covers in some themes.', 'content-locker' ),
		) );

	$cmb->add_field( array(
		'id' => 'tweet_close',
		'type' => 'tab_end',
		'render_row_cb' => 'cl_cmb_tab_close_tag'
	) );
}

/**
 * [cl_social_option_google_plus description]
 * @method cl_social_option_google_plus
 * @param  [type]                       $cmb [description]
 * @return [type]                            [description]
 */
function cl_social_option_google_plus( $cmb ) {

	$prefix = '_mts_cl_google_plus_';

	$cmb->add_field( array(
		'name' => '<i class="fa fa-google-plus-square"></i>' . esc_html__( '+1', 'content-locker' ),
		'id' => 'google-plus',
		'type' => 'section',
		'render_row_cb' => 'cl_cmb_tab_open_tag'
	) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Active', 'content-locker' ),
			'id' => $prefix . 'active',
			'type' => 'radio_inline',
			'desc' => esc_html__( 'Set On, to activate the button.', 'content-locker' ),
			'classes' => 'service-checker',
			'attributes'  => array(
				'data-for' => 'google-plus'
			),
			'options' => array(
				'on' => esc_html__( 'On', 'content-locker' ),
				'off' => esc_html__( 'Off', 'content-locker' )
			),
			'default' => 'on'
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'URL to +1', 'content-locker' ),
			'id' => $prefix . 'url',
			'type' => 'text',
			'desc' => esc_html__( 'Optional, set an URL which user has to +1 in order to unlock your content. Leave this field empty to use an URL of the page where the locker will be located.', 'content-locker' ),
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Button Title', 'content-locker' ),
			'id' => $prefix . 'button_title',
			'type' => 'text',
			'default' => '+1 us',
			'desc' => esc_html__( 'Optional, title of the button that is situated on the covers in some themes.', 'content-locker' ),
		) );

	$cmb->add_field( array(
		'id' => 'google_plus_close',
		'type' => 'tab_end',
		'render_row_cb' => 'cl_cmb_tab_close_tag'
	) );
}

/**
 * [cl_social_option_facebook_share description]
 * @method cl_social_option_facebook_share
 * @param  [type]                          $cmb [description]
 * @return [type]                               [description]
 */
function cl_social_option_facebook_share( $cmb ) {

	$prefix = '_mts_cl_facebook_share_';

	$cmb->add_field( array(
		'name' => '<i class="fa fa-facebook"></i>' . esc_html__( 'Share', 'content-locker' ),
		'id' => 'facebook-share',
		'type' => 'section',
		'render_row_cb' => 'cl_cmb_tab_open_tag'
	) );

		$cmb->add_field( array(
			'id' => $prefix . 'pro',
			'type' => 'view',
			'file' => 'hint-pro-feature',
			'render_row_cb' => 'cl_cmb_alert'
		) );

	$cmb->add_field( array(
		'id' => 'facebook_share_close',
		'type' => 'tab_end',
		'render_row_cb' => 'cl_cmb_tab_close_tag'
	) );
}

/**
 * [cl_social_option_twitter_follow description]
 * @method cl_social_option_twitter_follow
 * @param  [type]                          $cmb [description]
 * @return [type]                               [description]
 */
function cl_social_option_twitter_follow( $cmb ) {

	$prefix = '_mts_cl_twitter_follow_';

	$cmb->add_field( array(
		'name' => '<i class="fa fa-twitter"></i>' . esc_html__( 'Follow', 'content-locker' ),
		'id' => 'twitter-follow',
		'type' => 'section',
		'render_row_cb' => 'cl_cmb_tab_open_tag'
	) );

		$cmb->add_field( array(
			'id' => $prefix . 'pro',
			'type' => 'view',
			'file' => 'hint-pro-feature',
			'render_row_cb' => 'cl_cmb_alert'
		) );

	$cmb->add_field( array(
		'id' => 'twitter_follow_close',
		'type' => 'tab_end',
		'render_row_cb' => 'cl_cmb_tab_close_tag'
	) );
}

/**
 * [cl_social_option_google_share description]
 * @method cl_social_option_google_share
 * @param  [type]                        $cmb [description]
 * @return [type]                             [description]
 */
function cl_social_option_google_share( $cmb ) {

	$prefix = '_mts_cl_google_share_';

	$cmb->add_field( array(
		'name' => '<i class="fa fa-google-plus"></i>' . esc_html__( 'Share', 'content-locker' ),
		'id' => 'google-share',
		'type' => 'section',
		'render_row_cb' => 'cl_cmb_tab_open_tag'
	) );

		$cmb->add_field( array(
			'id' => $prefix . 'pro',
			'type' => 'view',
			'file' => 'hint-pro-feature',
			'render_row_cb' => 'cl_cmb_alert'
		) );

	$cmb->add_field( array(
		'id' => 'google_share_close',
		'type' => 'tab_end',
		'render_row_cb' => 'cl_cmb_tab_close_tag'
	) );
}

/**
 * [cl_social_option_youtube_subscribe description]
 * @method cl_social_option_youtube_subscribe
 * @param  [type]                             $cmb [description]
 * @return [type]                                  [description]
 */
function cl_social_option_youtube_subscribe( $cmb ) {

	$prefix = '_mts_cl_youtube_subscribe_';

	$cmb->add_field( array(
		'name' => '<i class="fa fa-youtube-play"></i>' . esc_html__( 'Youtube', 'content-locker' ),
		'id' => 'youtube-subscribe',
		'type' => 'section',
		'render_row_cb' => 'cl_cmb_tab_open_tag'
	) );

		$cmb->add_field( array(
			'id' => $prefix . 'pro',
			'type' => 'view',
			'file' => 'hint-pro-feature',
			'render_row_cb' => 'cl_cmb_alert'
		) );

	$cmb->add_field( array(
		'id' => 'youtube_channel_close',
		'type' => 'tab_end',
		'render_row_cb' => 'cl_cmb_tab_close_tag'
	) );
}

/**
 * [cl_social_option_linkedin_share description]
 * @method cl_social_option_linkedin_share
 * @param  [type]                          $cmb [description]
 * @return [type]                               [description]
 */
function cl_social_option_linkedin_share( $cmb ) {

	$prefix = '_mts_cl_linkedin_share_';

	$cmb->add_field( array(
		'name' => '<i class="fa fa-linkedin"></i>' . esc_html__( 'Share', 'content-locker' ),
		'id' => 'linkedin-share',
		'type' => 'section',
		'render_row_cb' => 'cl_cmb_tab_open_tag'
	) );

		$cmb->add_field( array(
			'id' => $prefix . 'pro',
			'type' => 'view',
			'file' => 'hint-pro-feature',
			'render_row_cb' => 'cl_cmb_alert'
		) );

	$cmb->add_field( array(
		'id' => 'linkedin_share_close',
		'type' => 'tab_end',
		'render_row_cb' => 'cl_cmb_tab_close_tag'
	) );
}
