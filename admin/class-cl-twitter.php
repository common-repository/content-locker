<?php
/**
 * The Stats
 */

class CL_Twitter_Handler extends CL_Base {

	/**
	 * [init description]
	 * @method init
	 * @return [type] [description]
	 */
	public function init() {

		// which action we should to run
        $request = !empty( $_REQUEST['request'] ) ? $_REQUEST['request'] : null;

        // allowed request types
        $allowed = array( 'init', 'callback', 'user_info', 'follow', 'tweet', 'get_tweets', 'get_followers' );

        if ( empty( $request ) || !in_array( $request, $allowed ) ) {
			wp_send_json(array(
				'success' => false,
				'message' => esc_html__( 'Invalid request type.', 'content-locker' )
			));
		}

        // the visitor id is used as a key for the storage where all the tokens are saved
        $visitor_id = !empty( $_REQUEST['visitor_id'] ) ? $_REQUEST['visitor_id'] : null;

		require_once cl()->plugin_dir() . '/includes/lib/twitter/twitteroauth.php';

		$this->options['consumer_key'] = cl()->settings->get('twitter_consumer_key');
		$this->options['consumer_secret'] = cl()->settings->get('twitter_consumer_secret');

		$func = 'do_' . $request;
		$this->$func( $visitor_id );
	}

	public function do_init( $visitor_id ) {

		$options = $this->options;

        if ( empty( $visitor_id ) ) {
			$visitor_id = $this->get_guid();
		}
        $keep_open = !empty( $_REQUEST['cl_keep_open'] ) ? (bool)$_REQUEST['cl_keep_open'] : null;
        $readOnly = !empty( $_REQUEST['cl_read_only'] ) ? (bool)$_REQUEST['cl_read_only'] : null;

        $oauth = new CL_TwitterOAuth( $options['consumer_key'], $options['consumer_secret'] );
        $request_token = $oauth->getRequestToken( $this->get_callback_url( $visitor_id, $keep_open ) );

        $token = $request_token['oauth_token'];
        $secret = $request_token['oauth_token_secret'];

        $this->save_value( $visitor_id, 'twitter_token', $token );
        $this->save_value( $visitor_id, 'twitter_secret', $secret );

        $authorize_url = $oauth->getAuthorizeURL( $token, false );

        header("Location: $authorize_url");
        exit;
	}

	/**
     * Handles a callback from Twitter (when the user has been redirected)
     */
    public function do_callback( $visitor_id ) {

		$options = $this->options;
        $keep_open = !empty( $_REQUEST['cl_keep_open'] ) ? (bool)$_REQUEST['cl_keep_open'] : null;

        if ( empty( $visitor_id ) ) {
			wp_send_json(array(
				'success' => false,
				'message' => esc_html__( 'Invalid visitor ID.', 'content-locker' )
			));
		}

		$denied = isset( $_REQUEST['denied'] );
        if ( $denied ) {
        ?>
            <script>
                if( window.opener ) {
					 window.opener.mts_cl_twitter_oauth_denied( '<?php echo $visitor_id ?>' );
				}
                window.close();
            </script>
        <?php
        exit;
        }

        $token = !empty( $_REQUEST['oauth_token']) ? $_REQUEST['oauth_token'] : null;
        $verifier = !empty( $_REQUEST['oauth_verifier']) ? $_REQUEST['oauth_verifier'] : null;

        if ( empty( $token ) || empty( $verifier ) ) {
			wp_send_json(array(
				'success' => false,
				'message' => esc_html__( 'The verifier value is invalid.', 'content-locker' )
			));
        }

        $secret = $this->get_value( $visitor_id, 'twitter_secret' );

        if ( empty( $secret ) ) {
			wp_send_json(array(
				'success' => false,
				'message' => esc_html__( 'The secret of the request token is invalid for ', 'content-locker' ) . $visitor_id
			));
        }

		$connection = new CL_TwitterOAuth( $options['consumer_key'], $options['consumer_secret'], $token, $secret );
        $accessToken = $connection->getAccessToken( $verifier );

        $this->save_value( $visitor_id, 'twitter_token', $accessToken['oauth_token'] );
        $this->save_value( $visitor_id, 'twitter_secret', $accessToken['oauth_token_secret'] );

        ?>
            <script>
                if( window.opener ) {
					window.opener.mts_cl_twitter_oauth_completed( '<?php echo $visitor_id ?>' );
				}
                <?php if ( !$keepOpen ) { ?>
                    window.close();
                <?php } ?>

                window.updateState = function( url, width, height, x, y ){
                    window.location.href = url;
                    window.resizeTo && window.resizeTo(width, height);
                    window.moveTo && window.moveTo(x, y);
                }
            </script>
        <?php

        exit;
    }

	public function do_user_info( $visitor_id, $return = false ) {

        $oauth = $this->get_oauth( $visitor_id );
        $response = $oauth->get('account/verify_credentials', array( 'skip_status' => 1, 'include_email' => 'true' ) );
        if ( $return ) {
			 return $response;
		}

        echo json_encode( $response );
        exit;
	}

	public function do_get_tweets( $visitor_id ) {

		$oauth = $this->get_oauth( $visitor_id );

		$response = $oauth->get('statuses/user_timeline', array( 'count' => 3 ) );

        echo json_encode( $response );
        exit;
	}

	public function do_get_followers( $visitor_id ) {

		$oauth = $this->get_oauth( $visitor_id );
		$screen_name = isset( $_REQUEST['screen_name'] ) ? $_REQUEST['screen_name'] : null;

        $response = $oauth->get('friendships/lookup', array( 'screen_name' => $screen_name ) );

        echo json_encode( $response );
        exit;
	}

	public function do_tweet( $visitor_id ) {


		$context = isset( $_POST['context'] ) ? $_POST['context'] : array();
        $tweet_message = isset( $_REQUEST['tweet_message'] ) ? $_REQUEST['tweet_message'] : null;

        if ( empty( $tweet_message ) ) {
			wp_send_json(array(
				'success' => false,
				'error' => esc_html__( 'The tweet message is not specified', 'content-locker' )
			));
		}

		$oauth = $this->get_oauth( $visitor_id );
		$response = $oauth->post('statuses/update', array(
            'status' => $tweet_message
        ));

        if ( isset( $response->errors ) ) {

            // the tweet already is twitted
            if ( $response->errors[0]->code == 187 ) {
                wp_send_json(array(
					'success' => true,
					'message' => esc_html__( 'The tweet already is twitted.', 'content-locker' )
				));
            }

            wp_send_json(array(
				'success' => false,
				'error' => $response->errors[0]->message
			));
        }

		// context
		$post_id = isset( $context['post_id'] ) ? $context['post_id']: null;
		$locker_id = isset( $context['locker_id'] ) ? $context['locker_id']: null;
		CL_Stats::count_metric( $locker_id, $post_id, 'tweet-posted' );

		// For Developer
		do_action( 'mts_cl_tweet_posted', $context );

        wp_send_json(array(
			'success' => true,
			'message' => $response
		));
	}

	public function do_follow( $visitor_id ) {


		$context = isset( $_POST['context'] ) ? $_POST['context'] : array();
        $follow_to = isset( $_REQUEST['follow_to'] ) ? $_REQUEST['follow_to'] : null;

        if ( empty( $follow_to ) ) {
			wp_send_json(array(
				'success' => false,
				'error' => esc_html__( 'The user name to follow is not specified', 'content-locker' )
			));
		}

        $notifications = isset( $_REQUEST['notification'] ) ? $_REQUEST['notification'] : false;

		$oauth = $this->get_oauth( $visitor_id );
		$response = $oauth->get('friendships/lookup', array(
            'screen_name' => $follow_to
        ));

        if ( isset( $response->errors ) ) {
			wp_send_json(array(
				'success' => false,
				'error' => $response->errors[0]->message
			));
        }

        if ( isset( $response[0]->connections ) && in_array( 'following', $response[0]->connections ) ) {
			wp_send_json(array(
				'success' => true,
				'message' => 'Already following'
			));
        }

        $response = $oauth->post('friendships/create', array(
            'screen_name' => $follow_to,
            'follow' => $notifications
        ));

        if ( isset( $response->errors ) ) {
            wp_send_json(array(
				'success' => false,
				'error' => $response->errors[0]->message
			));
        }

		// context
		$post_id = isset( $context['post_id'] ) ? $context['post_id']: null;
		$locker_id = isset( $context['locker_id'] ) ? $context['locker_id']: null;
		CL_Stats::count_metric( $locker_id, $post_id, 'got-twitter-follower' );

		// For Developer
		do_action( 'mts_cl_got_twitter_follower', $context );

		wp_send_json(array(
			'success' => true,
			'message' => $response
		));
	}

	/**
     * Build the callback URL for Twitter.
     */
    public function get_callback_url( $visitor_id, $keep_open ) {
        return admin_url( 'admin-ajax.php?action=mts_cl_connect&handler=twitter&request=callback&security='. wp_create_nonce( 'mts_cl_security' ) .'&visitor_id=' . $visitor_id . '&cl_keep_open=' . ( $keep_open ? '1' : '0' ) );
    }

	protected function get_oauth( $visitor_id = null, $token = null, $secret = null ) {
        $options = $this->options;

        if ( empty( $visitor_id ) && ( empty( $token ) || empty( $secret ) ) ) {
			wp_send_json(array(
				'success' => false,
				'message' => esc_html__( 'Invalid visitor ID.', 'content-locker' )
			));
		}

        if ( empty( $token ) ) {
            $token = $this->get_value( $visitor_id, 'twitter_token' );
            if ( empty( $token ) ) {
				wp_send_json(array(
					'success' => false,
					'message' => esc_html__( 'The access token not found for ', 'content-locker' ) . $visitor_id
				));
			}
        }

        if ( empty( $secret ) ) {
            $secret = $this->get_value( $visitor_id, 'twitter_secret' );
            if ( empty( $secret ) ) {
				wp_send_json(array(
					'success' => false,
					'message' => esc_html__( 'The secret of the access token is invalid for ', 'content-locker' ) . $visitor_id
				));
			}
        }

        return new CL_TwitterOAuth( $options['consumer_key'], $options['consumer_secret'], $token, $secret );
    }

	public function save_value( $key, $name, $value ) {

		if ( defined('W3TC') ) {
            setcookie( 'mts_cl_' . md5($key . '_' . $name), $value, time() + 60 * 60 * 24 , COOKIEPATH, COOKIE_DOMAIN  );
        } else {
            set_transient( 'mts_cl_' . md5($key . '_' . $name), $value, 60 * 60 * 24 );
        }
	}

	public function get_value( $key, $name, $default = null ) {

		if ( defined('W3TC') ) {

            $cookieName = 'mts_cl_' . md5($key . '_' . $name);
            $value = isset( $_COOKIE[$cookieName] ) ? $_COOKIE[$cookieName] : null;
            if ( empty( $value ) ) return $default;
            return $value;

        } else {
            $value = get_transient( 'mts_cl_' . md5($key . '_' . $name) );
            if ( empty( $value ) ) return $default;
            return $value;
        }
	}

	protected function get_guid() {

		if( true === function_exists( 'com_create_guid' ) ) {
			return trim( com_create_guid(), '{}' );
		}

        return sprintf(
			'%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
			mt_rand(0, 65535),
			mt_rand(0, 65535),
			mt_rand(0, 65535),
			mt_rand(16384, 20479),
			mt_rand(32768, 49151),
			mt_rand(0, 65535),
			mt_rand(0, 65535),
			mt_rand(0, 65535)
		);
    }
}
