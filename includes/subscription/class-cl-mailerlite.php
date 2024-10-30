<?php
/**
 * MailerLite Subscription
 */

class CL_Subscription_MailerLite extends CL_Subscription_Base {

	public function init( $api_key ) {

		require_once 'libs/mailerlite/autoload.php';
		return new \MailerLiteApi\MailerLite( $api_key );
	}

	public function get_lists( $api_key ) {

		$mailerlite = $this->init( $api_key );
		$result = $mailerlite->groups()->get();

		$lists = array();
		foreach( $result as $list ) {
			$lists[ $list->id ] = $list->name;
		}

		return $lists;
	}

    public function subscribe( $identity, $context, $options ) {

		$mailerlite = $this->init( $options['api_key'] );
		$mailerlite = $mailerlite->groups();

		$name = $this->get_fullname( $identity );
		$result = $mailerlite->addSubscriber( $options['list'], array(
			'email'	=> $identity['email'],
			'fields' => array( 'name' => $name ),
			'type' => 'subscribed'
		));

		if( isset( $result->error ) ) {
			throw new Exception( $result->error->message );
		}

		if( isset( $result->id ) && isset( $result->email ) ) {
			return array(
				'status' => 'subscribed'
			);
		}

		throw new Exception( esc_html__( 'Unknown error.', 'content-locker' ) );
	}

    public function check( $identity, $list_id, $context ) {

	}
}
