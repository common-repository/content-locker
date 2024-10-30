<?php

abstract class CL_Subscription_Base {

	public $config;

	public function __construct( $config = array() ) {

		$this->config = $config;
	}

	//public abstract function get_lists( $api_key );
    public abstract function subscribe( $identity, $context, $options );
    public abstract function check( $identity, $list_id, $context );

	public function is_email( $email ) {
        return filter_var( $email, FILTER_VALIDATE_EMAIL );
    }

	public function has_single_optin() {
        return in_array( 'quick', $this->config['modes'] );
    }

	public function get_fullname( $identity ) {

        if ( !empty( $identity['name'] ) && !empty( $identity['family'] ) ) {
			return $identity['name'] . ' ' . $identity['family'];
		}

		if ( !empty( $identity['name'] ) ) {
			return $identity['name'];
		}

		if ( !empty( $identity['family'] ) ) {
			return $identity['family'];
		}

		if( !empty( $identity['display_name'] ) ) {
			return $identity['display_name'];
		}

		return '';
	}

	/*public function refine( $identity, $clear_empty = false ) {

		if ( empty( $identity ) ) {
			return $identity;
		}

        unset( $identity['html'] );
        unset( $identity['label'] );
        unset( $identity['separator'] );
        unset( $identity['name'] );
        unset( $identity['family'] );
        unset( $identity['display_name'] );
        unset( $identity['fullname'] );

        if ( $clear_empty ) {
            foreach ( $identity as $key => $value ) {
                if ( empty( $value ) ) {
					unset( $identity[$key] );
				}
            }
        }

        return $identity;
	}*/
}
