<?php
/**
 * The Leads
 */

class CL_Leads extends CL_Base {

	/**
	 * [add description]
	 * @method add
	 * @param  array $identity [description]
	 */
	public static function add( $identity = array(), $context = array(), $email_confirmed = false, $subscription_confirmed = false, $temp = null ) {

		$email = isset( $identity['email'] ) ? $identity['email'] : false;
		$lead = self::get_by_email( $email );

		return self::save( $lead, $identity, $context, $email_confirmed, $subscription_confirmed, $temp );
	}

	public static function save( $lead = null, $identity = array(), $context = array(), $email_confirmed = false, $subscription_confirmed = false, $temp = null ) {
		global $wpdb;

		$email = isset( $identity['email'] ) ? $identity['email'] : false;
		if ( isset( $identity['social'] ) && $identity['social'] ) {
			$email_confirmed = true;
		}

		$locker_id = isset( $context['locker_id'] ) ? intval( $context['locker_id'] ) : 0;
		$post_id = isset( $context['post_id'] ) ? intval( $context['post_id'] ) : 0;

		$locker = get_post( $locker_id );
		$locker_title = $locker->post_title;
		$post_title = $context['post_title'];
		$post_url = $context['post_url'];

		$name = $identity['name'];
		$family = $identity['family'];
		$display_name = $identity['display_name'];

		$lead_id = empty( $lead ) ? null : $lead->ID;

		if( !$lead_id ) {

			// counts the number of new recivied emails
            CL_Stats::count_metric( $locker_id, $post_id, 'email-received' );

			// Count the number of confirmed emails (subscription)
			if( $subscription_confirmed ) {
				CL_Stats::count_metric( $locker_id, $post_id, 'email-confirmed' );
			}

			$data = array(
				'post_type' => 'cl-lead',
				'post_status' => $email_confirmed ? 'confirmed' : 'not-confirmed',
				'comment_status' => 'closed',
				'ping_status' => 'closed',

				// Data to saved
				'post_title' => $display_name,
				'post_content' => $post_title,
				'guid' => $post_url,
				'post_excerpt' => $email,
			);
			$the_post_id = wp_insert_post( $data );

            $metadata = array(
                '_mts_cl_lead_name' => $name,
                '_mts_cl_lead_family' => $family,
                '_mts_cl_lead_locker_id' => $locker_id,
                '_mts_cl_lead_post_id' => $post_id,
                '_mts_cl_lead_item_title' => $locker_title,
                '_mts_cl_lead_email_confirmed' => $email_confirmed ? 1 : 0,
                '_mts_cl_lead_subscription_confirmed' => $subscription_confirmed ? 1 : 0,
                '_mts_cl_lead_ip' => self::get_ip(),
                '_mts_cl_lead_temp' => !empty( $temp ) ? json_encode( $temp ) : null,
            );

			unset( $identity['email'], $identity['display_name'], $identity['name'], $identity['family'] );
			foreach( $identity as $name => $val ) {
				$metadata['_mts_cl_lead_'.$name] = $val;
			}


			if( $the_post_id ) {
				foreach( $metadata as $key => $val ) {
					update_post_meta( $the_post_id, $key, $val );
				}
			}

			$lead_id = $the_post_id;
		}
		// Update lead
		else {

		}

		return $lead_id;
	}

	/**
	 * [get_by_email description]
	 * @method get_by_email
	 * @param  [type]       $email [description]
	 * @return [type]              [description]
	 */
	public static function get_by_email( $email ) {

		global $wpdb;

		if( !$email ) {
			return null;
		}

		$id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_excerpt=%s", $email ) );
		$lead = isset( $id ) ? get_post( $id ) : null;

		return $lead;
	}

	/**
	 * [get_ip description]
	 * @method get_ip
	 * @return [type] [description]
	 */
	public static function get_ip() {

		$ip = '';

        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if ( array_key_exists($key, $_SERVER) !== true) continue;
            foreach (explode(',', $_SERVER[$key]) as $ip){
                $ip = trim($ip);
                if ( !self::validate_ip($ip) ) continue;
                return $ip;
            }
        }

        return $ip;
	}

	public static function validate_ip($ip) {
        if (strtolower($ip) === 'unknown') return false;

        // generate ipv4 network address
        $ip = ip2long($ip);

        // if the ip is set and not equivalent to 255.255.255.255
        if ($ip !== false && $ip !== -1) {
            // make sure to get unsigned long representation of ip
            // due to discrepancies between 32 and 64 bit OSes and
            // signed numbers (ints default to signed in PHP)
            $ip = sprintf('%u', $ip);
            // do private network range checking
            if ($ip >= 0 && $ip <= 50331647) return false;
            if ($ip >= 167772160 && $ip <= 184549375) return false;
            if ($ip >= 2130706432 && $ip <= 2147483647) return false;
            if ($ip >= 2851995648 && $ip <= 2852061183) return false;
            if ($ip >= 2886729728 && $ip <= 2887778303) return false;
            if ($ip >= 3221225984 && $ip <= 3221226239) return false;
            if ($ip >= 3232235520 && $ip <= 3232301055) return false;
            if ($ip >= 4294967040) return false;
        }
        return true;
    }

	// Admin Helpers -------------------------------------------------

	/**
	 * [bulk_actions description]
	 * @method bulk_actions
	 * @param  [type]       $actions [description]
	 * @return [type]                [description]
	 */
	public static function bulk_actions( $actions ) {
		unset( $actions['edit'] );
		return $actions;
	}

	/**
	 * [add_lead_columns description]
	 * @method add_lead_columns
	 * @param  [type]           $columns [description]
	 */
	public static function add_columns( $columns ) {

		return $columns = array(
			'cb' => '<input type="checkbox" />',
			'avatar' => '',
			'name' => esc_html__('Name', 'content-locker'),
			'channel' => esc_html__('Channel', 'content-locker'),
			'added' => esc_html__('Added', 'content-locker'),
			'status' => esc_html__('Status', 'content-locker'),
        );
	}

	/**
	 * [add_lead_sortable_columns description]
	 * @method add_lead_sortable_columns
	 * @param  [type]                    $columns [description]
	 */
	public static function add_sortable_columns( $columns ) {
		return array(
			'name'		=> 'name',
			'channel'	=> 'channel',
			'added'		=> 'added',
			'status'	=> 'status'
		);
	}

	/**
	 * [columns_content description]
	 * @method columns_content
	 * @param  [type]          $column  [description]
	 * @param  [type]          $post_id [description]
	 * @return [type]                   [description]
	 */
	public static function columns_content( $column, $post_id ) {

		$post = get_post( $post_id );

		if( 'avatar' === $column ) {
			$alt = esc_html__( 'User Avatar', 'content-locker' );

			$avatar = '<span class="mts-cl-avatar">';

				if( $url = $post->_mts_cl_lead_image ) {
					$avatar .= sprintf( '<img src="%s" alt="%s" width="40" height="40">', $url, $alt );
				}
				else {
					$avatar .= get_avatar( $post->post_excerpt, 40, '', $alt );
				}

			$avatar .= '</span>';

			echo $avatar;
		}

		elseif( 'name' === $column ) {

			/**
			 * Title
			 */
			if( $post->post_title ) {
				printf( '<strong class="mts-cl-name">%s</strong>', $post->post_title );
			}

			/**
			 * Social Icons
			 */
			if( $url = $post->_mts_cl_lead_facebook_url ) {
				printf( '<a href="%s" target="_blank" class="mts-cl-social-icon mts-cl-facebook"><i class="fa fa-facebook"></i></a>', $url );
			}
			if( $url = $post->_mts_cl_lead_google_url ) {
				printf( '<a href="%s" target="_blank" class="mts-cl-social-icon mts-cl-google"><i class="fa fa-google"></i></a>', $url );
			}
			if( $url = $post->_mts_cl_lead_linkedin_url ) {
				printf( '<a href="%s" target="_blank" class="mts-cl-social-icon mts-cl-linkedin"><i class="fa fa-linkedin"></i></a>', $url );
			}

			if ( ! empty( $post->post_excerpt ) ) {
            	echo '<br />' . $post->post_excerpt;
        	}
		}

		elseif( 'channel' === $column ) {

			$locker_id = $post->_mts_cl_lead_locker_id;
			$locker_title = $post->_mts_cl_lead_item_title;

			$locker = get_post( $locker_id );
			$locker_title = empty( $locker ) ? '<i>' . esc_html__('(unknown)', 'content-locker') . '</i>' : $locker->post_title;

	        $via = empty( $locker ) ? $locker_title : '<a href="' . admin_url( 'edit.php?post_type=content-locker&page=cl-stats&locker_id=' . $locker_id ) . '"><strong>' . $locker_title. '</strong></a>';
			$via = sprintf( esc_html__("Via: %s", 'content-locker'), $via );

	        $post_url = $post->guid;
	        $post_title = $post->post_content;

	        $referer = get_post( $post->_mts_cl_lead_post_id );
			if( !empty( $referer ) ) {
	            $post_url = get_permalink( $referer->ID );
	            $post_title = $referer->post_title;
	        }

 	        if ( empty( $post_title ) ) {
				$post_title = '<i>' . esc_html__('(no title)', 'content-locker') . '</i>';
			}
 	        $referer = '<a href="' . $post_url . '"><strong>' . $post_title . '</strong></a>';
 	        $where = sprintf( esc_html__("On Page: %s", 'content-locker'), $referer );

 	        echo $via . '<br />' . $where;
		}

		elseif( 'added' === $column ) {
			echo get_the_date() . get_the_time();
		}

		elseif( 'status' === $column ) {

			if( $post->_mts_cl_lead_email_confirmed ) {
			?>
				<span class="mts-cl-status-help" title="<?php esc_html_e('This email is real. It was received from social networks.', 'content-locker') ?>">
                    <i class="fa fa-check-circle-o"></i><i><?php esc_html_e('email confirmed', 'content-locker') ?></i>
                </span>
			<?php
			}
			else {
			?>
				<span class="mts-cl-status-help" title="<?php esc_html_e('This email was not confirmed. It means that actually this email address may be owned by another user.', 'content-locker') ?>">
                    <i class="fa fa-circle-o"></i><i><?php esc_html_e('email confirmed', 'content-locker') ?></i>
                </span>
			<?php
			}

			if( $post->_mts_cl_lead_subscription_confirmed ) {
			?>
				<br>
				<span class="mts-cl-status-help" title="<?php esc_html_e('This user confirmed his subscription.', 'content-locker') ?>">
					<i class="fa fa-check-circle-o"></i><i><?php esc_html_e('subscription confirmed', 'content-locker') ?></i>
				</span>
			<?php
			}
			else {
			?>
				<br>
				<span class="mts-cl-status-help" title="<?php esc_html_e('This user has not confirmed his subscription.', 'content-locker') ?>">
					<i class="fa fa-circle-o"></i><i><?php esc_html_e('subscription confirmed', 'content-locker') ?></i>
				</span>
			<?php
			}
		}
	}
}
