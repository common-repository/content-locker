<?php
/**
 * MailChimp Subscription
 */

class CL_Subscription_MailChimp extends CL_Subscription_Base {

	public function init( $api_key ) {

		require_once 'libs/mailchimp.php';
		return new MailChimp( $api_key );
	}

	public function get_lists( $api_key ) {

		$mailchimp = $this->init( $api_key );
		$result = $mailchimp->get('lists');

		if( $mailchimp->getLastError() ) {
			throw new Exception( $mailchimp->getLastError() );
		}

		$lists = array();
		foreach( $result['lists'] as $list ) {
			$lists[ $list['id'] ] = $list['name'];
		}

		return $lists;
	}

    public function subscribe( $identity, $context, $options ) {

		$vars = array();
        if ( empty( $vars['FNAME'] ) && !empty( $identity['name'] ) ) $vars['FNAME'] = $identity['name'];
        if ( empty( $vars['LNAME'] ) && !empty( $identity['family'] ) ) $vars['LNAME'] = $identity['family'];
        if ( empty( $vars['FNAME'] ) && !empty( $identity['display_name'] ) ) $vars['FNAME'] = $identity['display_name'];

		$mailchimp = $this->init( $options['api_key'] );
		$subscriber_hash = $mailchimp->subscriberHash($identity['email']);
		$result = $mailchimp->put( 'lists/'. $options['list'] .'/members/' . $subscriber_hash, [
            'email_address'	=> $identity['email'],
			'merge_fields'	=> empty( $vars ) ? new stdClass : $vars,
            'status'		=> 'subscribed'
        ]);

		if( $mailchimp->getLastError() ) {
			throw new Exception( $mailchimp->getLastError() );
		}

        return array(
			'status' => 'subscribed'
		);
	}

    public function check( $identity, $list_id, $context ) {

	}

	public function map( $identity ) {

	}
}
