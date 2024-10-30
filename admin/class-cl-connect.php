<?php
/**
 * The Connect
 */

class CL_Connect extends CL_Base {

	/**
	 * [__construct description]
	 * @method __construct
	 */
	public function __construct() {

		// AJAX
		$this->add_action( 'wp_ajax_mts_cl_connect', 'handle' );
		$this->add_action( 'wp_ajax_nopriv_mts_cl_connect', 'handle' );
	}

	/**
	 * [handle description]
	 * @method handle
	 * @return [type]        [description]
	 */
	public function handle() {

		// Security
		/*if( !$this->verify() ) {
			wp_send_json(array(
				'success' => false,
				'message' => esc_html__( 'Not permitted.', 'content-locker' )
			));
		}*/

		$handler = isset( $_REQUEST['handler'] ) ? $_REQUEST['handler'] : null;
		$allowed = array( 'twitter', 'signup', 'lead' );

		if ( empty( $handler ) || !in_array( $handler, $allowed ) ) {
			wp_send_json(array(
				'success' => false,
				'message' => esc_html__( 'Not permitted.', 'content-locker' )
			));
	    }

		$context = isset( $_POST['context'] ) ? $_POST['context'] : array();
		$locker_id = isset( $context['locker_id'] ) ? intval( $context['locker_id'] ) : 0;

		if( in_array( $handler, array( 'lead', 'signup' ) ) ) {
			if( empty( $locker_id ) ) {
				wp_send_json(array(
					'success' => false,
					'message' => esc_html__( 'No item mention for action.', 'content-locker' )
				));
			}
		}

		$func = "{$handler}_handler";
		$this->$func( $context );
	}

	protected function twitter_handler( $context ) {

		require_once cl()->plugin_dir() . '/admin/class-cl-twitter.php';
		$tw = new CL_Twitter_Handler;
		$tw->init();
	}

	protected function lead_handler( $context ) {

		$identity = isset( $_POST['identity'] ) ? $_POST['identity'] : array();
		$list_id = isset( $identity['list_id'] ) ? $identity['list_id'] : false;

		// Email check
		if( empty( $identity['email'] ) ) {
			wp_send_json(array(
				'success' => false,
				'error' => esc_html__( 'Unable to subscribe. The email is not specified.', 'content-locker' )
			));
		}

		// List Id
		if( ! $list_id ) {
			wp_send_json(array(
				'success' => false,
				'error' => esc_html__( 'Unable to subscribe. The list ID is not specified.', 'content-locker' )
			));
		}

		if( 'database' === $list_id ) {
			require_once 'class-cl-leads.php';
			CL_Leads::add( $identity, $context );

			wp_send_json(array(
				'success' => true,
				'message' => esc_html__( 'Lead saved.', 'content-locker' )
			));
		}
		else {

			// Get the service name and list id
			$options = cl_get_mailing_options( $list_id );
			$name = isset( $options['service'] ) ? $options['service'] : false;

			// Service name
			if( empty( $name ) ) {
				wp_send_json(array(
					'success' => false,
					'error' => esc_html__( 'The subscription service is not set.', 'content-locker' )
				));
	        }

			$result = array();
			try {
				$service = cl_get_subscription_service( $name );
				$result = $service->subscribe( $identity, $context, $options );
			}
			catch( Exception $e ) {
				wp_send_json(array(
					'success' => false,
					'error' => $e->getMessage()
				));
			}

			if( ! empty( $result['status'] ) ) {

				if( 'subscribed' === $result['status'] ) {
					require_once 'class-cl-leads.php';
					CL_Leads::add( $identity, $context, true, true );

					wp_send_json(array(
						'success' => true,
						'message' => esc_html__( 'Email subscribed.', 'content-locker' )
					));
				}

				if( 'pending' === $result['status'] ) {
					wp_send_json(array(
						'success' => true,
						'message' => esc_html__( 'Waiting subscription confirmation.', 'content-locker' )
					));
				}

				if( 'error' === $result['status'] ) {
					wp_send_json(array(
						'success' => false,
						'error' => esc_html__( 'Error.', 'content-locker' )
					));
				}
			}
		}
	}

	protected function signup_handler( $context ) {

		require_once 'class-cl-leads.php';
		$identity = isset( $_POST['identity'] ) ? $_POST['identity'] : array();
		CL_Leads::add( $identity, $context );


		if ( is_user_logged_in() ) {
            wp_send_json(array(
				'success' => true,
				'message' => esc_html__( 'Already registered.', 'content-locker' )
			));
        }

        $email = $identity['email'];
        if ( empty( $email ) ) {
			// @TODO: change to error and success to false
			wp_send_json(array(
				'success' => true,
				'message' => esc_html__( 'No Email to register.', 'content-locker' )
			));
		}

        if ( !email_exists( $email ) && $username = $this->generate_username( $email ) ) {

            $random_password = wp_generate_password( $length = 12, false );
            $user_id = wp_create_user( $username, $random_password, $email );

            if ( $user_id ) {
                if ( isset( $identity['name'] ) ) {
					update_user_meta( $user_id, 'first_name', $identity['name'] );
				}
                if ( isset( $identity['family'] ) ) {
					update_user_meta( $user_id, 'last_name', $identity['family'] );
				}
            }

            wp_new_user_notification( $user_id, $random_password );

			// context
			$post_id = isset( $context['post_id'] ) ? $context['post_id']: null;
			$locker_id = isset( $context['locker_id'] ) ? $context['locker_id']: null;
			CL_Stats::count_metric( $locker_id, $post_id, 'account-registered' );

			// For Developer
			do_action( 'mt_cl_registered', $identity, $context, $user_id );

			wp_send_json(array(
				'success' => true,
				'message' => esc_html__( 'Register successfull.', 'content-locker' )
			));
        }
		else {
            wp_send_json(array(
				'success' => true,
				'message' => esc_html__( 'Already registered.', 'content-locker' )
			));
        }
	}

	protected function generate_username( $email ) {

		$parts = explode ('@', $email);
        if ( count( $parts ) < 2 ) {
			return false;
		}

        $username = $parts[0];
        if ( !username_exists( $username ) ) {
			return $username;
		}

        $index = 0;
        while( true ) {
           $index++;
           $username = $parts[0] . $index;

           if ( !username_exists( $username ) ) {
			   return $username;
		   }
        }
	}

	/**
	 * [verify description]
	 * @method verify
	 * @return [type] [description]
	 */
	public function verify() {
		return check_admin_referer( 'mts_cl_security', 'security', false );
	}
}
new CL_Connect;
