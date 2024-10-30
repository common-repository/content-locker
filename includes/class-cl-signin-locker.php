<?php
/**
 * The Sign-In Locker
 */

/**
 * Sign-In Locker Class
 */
class CL_Signin_Locker extends CL_Locker {

	/**
	 * [__construct description]
	 * @method __construct
	 */
	public function __construct( $id = null, $config = array() ) {
		$defaults = array(
			// Basic
			'theme' => 'great-attractor',
			'layout' => 'horizontal',
			'overlap_mode' => 'full',
			'relock' => false,
			'post_lock' => false,
		);
		$this->config( $defaults );
		parent::__construct( $id, $config );
	}
	/**
	 * [prepare_json description]
	 * @method prepare_json
	 * @return [type]       [description]
	 */
	public function to_json() {

		$json = array(

			'locker_id' => $this->id,
			'post_id' => cl()->manager->object->ID,
			'type' => $this->item_type,

			// Basic
			'theme' => $this->theme,
			'layout' => $this->layout,
			'overlap' => array(
				'mode' => $this->overlap_mode,
				'position' => $this->overlap_position,
				'intensity' => 5
			),

			// Visibility
			'expires' => false,
			'always' => $this->always,

			// Advance
			'close' => $this->close,
			'timer' => false,
			//'ajax' => $this->ajax,
			'highlight' => $this->highlight,

			'group' => array(
				'buttons' => $this->get_buttons()
			)
		);

		// Button Order
		$json['group']['order'] = array_keys( $json['group']['buttons'] );

		// Overlap
		if( 'full' === $this->overlap_mode ) {
			$json['overlap'] = false;
		}

		// Save for later use
		$this->json = $json;

		return $json;
	}

	/**
	 * [get_buttons description]
	 * @method get_buttons
	 * @return [type]      [description]
	 */
	public function get_buttons() {

		$buttons = array();

		if( !cl_cmb_facebook_errors() && $this->facebook_active ) {

			$actions = array(
				'lead' => $this->facebook_lead,
				'signup' => $this->facebook_signup
			);

			$buttons['facebook'] = array(
			    'actions' => array_keys( array_filter( $actions ) ),
				'list_id' => $this->get_mailing_list( $this->facebook_mailing )
			);
		}

		if( !cl_cmb_twitter_errors() && $this->twitter_active ) {

			$actions = array(
				'lead' => $this->twitter_lead,
				'signup' => $this->twitter_signup
			);

			$buttons['twitter'] = array(
		 	    'actions' => array_keys( array_filter( $actions ) ),
				'list_id' => $this->get_mailing_list( $this->twitter_mailing )
			);

			// Follow user
			if( $this->twitter_follow && $this->twitter_follow_user ) {
				$buttons['twitter']['actions'][] = 'follow';
				$buttons['twitter']['follow'] = array(
					'user' => $this->twitter_follow_user,
					'notification' => $this->twitter_notifications
				);
			}

			// Tweet message
			if( $this->twitter_tweet && $this->twitter_tweet_message ) {
				$buttons['twitter']['actions'][] = 'tweet';
				$buttons['twitter']['tweet'] = array(
					'message' => $this->twitter_tweet_message
				);
			}
		}

		if( !cl_cmb_google_errors() && $this->google_active ) {

			$actions = array(
				'lead' => $this->google_lead,
				'signup' => $this->google_signup
			);

			$buttons['google'] = array(
			    'actions' => array_keys( array_filter( $actions ) ),
				'list_id' => $this->get_mailing_list( $this->google_mailing )
			);

			if( $this->google_youtube_subscribe ) {
				$buttons['google']['youtube'] = array(
					'channel_id' => $this->google_youtube_channel_id
				);

				$buttons['google']['actions'][] = 'youtube_subscribe';
			}
		}

		if( ! cl_cmb_linkedin_errors() && $this->linkedin_active ) {

			$actions = array(
				'lead' => $this->linkedin_lead,
				'signup' => $this->linkedin_signup
			);

			$buttons['linkedin'] = array(
				'actions' => array_keys( array_filter( $actions ) ),
				'list_id' => $this->get_mailing_list( $this->linkedin_mailing )
			);
		}

		if( $this->email_active ) {

			$actions = array(
				'lead' => true,
				'signup' => $this->email_signup
			);

			$buttons['email'] = array(
				'actions' => array_keys( array_filter( $actions ) ),
				'list_id' => $this->get_mailing_list( $this->email_mailing )
			);
		}

		return $buttons;
	}

	public function get_mailing_list( $service ) {

		$default = cl()->settings->get('default_mailing');

		if( 'default' === $service ) {
			return $default;
		}

		return $service;
	}

	/**
	 * [get_controls description]
	 * @method get_controls
	 * @return [type]       [description]
	 */
	public function get_controls() {
		$json = $this->to_json();
		$group = $json['group'];

		echo '<div class="mts-cl-group mts-cl-signin-buttons">';

		foreach( $group['buttons'] as $name => $button ) {

			$classes = array(
				'mts-cl-control',
				'mts-cl-' . $name
			);

			$control = array( 'classes' => join( ' ', $classes ) );

			cl_get_template_part( 'control-wrapper', array(
				'control' => $control,
				'locker' => $this
			) );
		}
		echo '</div>';

		if( isset( $group['buttons']['email'] ) ) {
			$this->get_email_form();
		}
		$this->get_terms();
	}

	public function get_email_form() {

		echo '<div class="mts-cl-group mts-cl-subscription" style="display:none;">';
			echo '<div class="mts-cl-subscription-wrapper-inner">';

				cl_get_template_part( 'email-form', array(
					'locker' => $this
				) );

			echo '</div>';
		echo '</div>';
	}

	/**
	 * [get_terms description]
	 * @method get_terms
	 * @return [type]    [description]
	 */
	public function get_terms() {

		$settings = cl()->settings->get_globals();
		if( !( $settings['terms'] || $settings['policy'] ) ) {
			return;
		}

		$args = array(
			'locker' => $this,

			// settings
			'terms' => $settings['terms'],
			'policy' => $settings['policy'],

			// labels
			'label_terms' => cl()->settings->get( 'trans_misc_terms_of_use' ),
			'label_policy' => cl()->settings->get( 'trans_misc_privacy_policy' ),
			'label_agree_with' => cl()->settings->get( 'trans_misc_your_agree_with' )
		);

		cl_get_template_part( 'terms', $args );
	}
}
