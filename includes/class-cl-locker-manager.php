<?php
/**
 * The LOcker
 */

/**
 * Locker Manager Class
 */
class CL_Locker_Manager extends CL_Base {

	/**
	 * [$lockers description]
	 * @var array
	 */
	private $lockers = array();

	/**
	 * [$batch_id description]
	 * @var [type]
	 */
	private $batch_id = null;

	/**
	 * [$object description]
	 * @var [type]
	 */
	public $object = null;

	/**
	 * [$is_excluded description]
	 * @var boolean
	 */
	private $is_excluded = false;

	/**
	 * [__construct description]
	 * @method __construct
	 */
	public function __construct() {

		$this->add_action( 'template_redirect', 'init', 1 );
	}

	/**
	 * [init description]
	 * @method init
	 * @return [type] [description]
	 */
	public function init() {

		if( ! $this->handle_auto_unlock() ) {
			$this->handle_passcode();
			$this->handle_batch_locker();
			$this->handle_post_exclusion();

			if( ! $this->excluded() ) {
				$this->object = get_post();
			}
		}
	}

	/**
	 * [handle_auto_unlock description]
	 * @method handle_auto_unlock
	 * @return [type]             [description]
	 */
	public function handle_auto_unlock() {

		$result = apply_filters( 'mts_cl_auto_unlock', null, get_post() );
		if( ! is_null( $result ) ) {
			return $result;
		}
	}

	/**
	 * [handle_passcode description]
	 * @method handle_passcode
	 * @todo confuse for permenant passcode
	 * @return [type]          [description]
	 */
	public function handle_passcode() {

		$passcode = cl()->settings->get('passcode');
		$permenant = cl()->settings->get('permanent_passcode');

		if( empty( $passcode ) ) {
			return false;
		}

		if( $permenant && isset( $_REQUEST[$passcode] ) ) {

			if( empty( $this->cookie_passcode ) ) {
				$this->cookie_passcode = 'mts_cl_' . wp_create_nonce( 'passcode' );

				if( isset( $_COOKIE[ $this->cookie_passcode ] ) && $this->cookie_set )  {
					return $this->excluded( true );
				}

				if( ! headers_sent() ) {
					setcookie( $this->cookie_passcode, 1, time() + 60*60*24*5000, '/' );
                    $this->cookie_set = true;
				}

				return $this->excluded( true );
			}
		}
		elseif( isset( $_REQUEST[$passcode] ) ) {
			return $this->excluded( true );
		}

		return false;
	}

	/**
	 * [handle_batch_locker description]
	 * @method handle_batch_locker
	 * @return boolean          [description]
	 */
	public function handle_batch_locker() {

		$post = get_post();
		$post_type = get_post_type( $post );
		$options = cl()->settings->get( $post_type );

		if( isset( $options['locker'] ) ) {
			$id = $options['locker'];
			$options['is_batch'] = true;
			unset( $options['locker'] );

			cl()->manager->add( $id, $options );
		}
	}

	/**
	 * [handle_post_exclusion description]
	 * @method handle_post_exclusion
	 * @return [type]                [description]
	 */
	public function handle_post_exclusion() {

		if( !$this->batch_id ) {
			return;
		}
		// Get current post
		$post = get_post();

		// if Preview mode of post
		if( 'publish' !== get_post_status( $post ) ) {
			return $this->excluded( true );
		}

		// Exclude if have any taxonomy set by batch locker
		$options = cl()->settings->get( $post->post_type );
		$taxonomies = get_object_taxonomies( $post->post_type );

		foreach( $taxonomies as $taxonomy ) {

			if( !empty( $options[ $taxonomy ] ) ) {
				$terms = get_the_terms( $post, $taxonomy );
				if( !empty( $terms ) ) {
					foreach( $terms as $term ) {
						if( in_array( $term->term_id, $options[ $taxonomy ] ) ) {
							$this->excluded( true );
							break;
						}
					}
				}
			}
		}
	}

	/**
	 * [get_post_url description]
	 * @method get_post_url
	 * @return [type]       [description]
	 */
	public function get_post_url() {

		$url = ! empty( $this->object ) ? get_permalink( $this->object->ID ) : null;
		return $url;
	}

	/**
	 * [add description]
	 * @method add
	 * @param  [type] $id     [description]
	 * @param  array  $config [description]
	 */
	public function add( $id = null, $config = array() ) {

		if( is_null($id) ) {
			//wp_die( __( '<div><strong>[Locker] The locker has no id.</strong></div>', 'content-locker' ), __FUNCTION__ );
            return;
		}

		$locker = null;
		$type = get_post_meta( $id, '_mts_cl_item_type', true );

		switch( $type ) {
			case 'social-locker':
				$locker = new CL_Social_Locker( $id, $config );
				break;

			case 'signin-locker':
				$locker = new CL_Signin_Locker( $id, $config );
				break;
		}

		if( ! is_null( $locker ) ) {
			$this->lockers[$id] = $locker;

			// is batch locker
			if( $locker->is_batch ) {
				$this->batch_id = $id;
			}
			return $locker;
		}

		return false;
	}

	/**
	 * [get description]
	 * @method get
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function get( $id ) {

		if( 'all' === $id ) {
			return $this->lockers;
		}

		return isset( $this->lockers[ $id ] ) ? $this->lockers[ $id ] : false;
	}

	/**
	 * [batch_locker description]
	 * @method batch_locker
	 * @return [type]       [description]
	 */
	public function batch_locker() {
		return $this->get( $this->batch_id );
	}

	/**
	 * [is_empty description]
	 * @method is_empty
	 * @return boolean  [description]
	 */
	public function is_empty() {
		return empty( $this->lockers );
	}

	/**
	 * [excluded description]
	 * @method excluded
	 * @return [type]   [description]
	 */
	public function excluded( $set = null ) {

		if( !is_null( $set ) ) {
			$this->is_excluded = $set;
		}

		return $this->is_excluded;
	}
}
cl()->manager = new CL_Locker_Manager;
