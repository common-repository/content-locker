<?php
/**
 * The Base
 * The base class for all the classes
 */

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists( 'CL_Base' ) ):

/**
 * Base Class
 */
class CL_Base {

	/**
	 * [add_action description]
	 * @method add_action
	 * @param  [type]     $hook     [description]
	 * @param  [type]     $func     [description]
	 * @param  integer    $priority [description]
	 * @param  integer    $args     [description]
	 */
	protected function add_action( $hook, $func, $priority = 10, $args = 1 ) {
		add_action( $hook, array( &$this, $func ), $priority, $args );
	}

	/**
	 * [add_filter description]
	 * @method add_filter
	 * @param  [type]     $hook     [description]
	 * @param  [type]     $func     [description]
	 * @param  integer    $priority [description]
	 * @param  integer    $args     [description]
	 */
	protected function add_filter( $hook, $func, $priority = 10, $args = 1 ) {
		add_filter( $hook, array( &$this, $func ), $priority, $args );
	}

	/**
	 * [remove_action description]
	 * @method remove_action
	 * @param  [type]        $hook     [description]
	 * @param  [type]        $func     [description]
	 * @param  integer       $priority [description]
	 * @param  integer       $args     [description]
	 * @return [type]                  [description]
	 */
	protected function remove_action( $hook, $func, $priority = 10, $args = 1 ) {
		remove_action( $hook, array( &$this, $func ), $priority, $args );
    }

	/**
	 * [remove_filter description]
	 * @method remove_filter
	 * @param  [type]        $hook     [description]
	 * @param  [type]        $func     [description]
	 * @param  integer       $priority [description]
	 * @param  integer       $args     [description]
	 * @return [type]                  [description]
	 */
	protected function remove_filter( $hook, $func, $priority = 10, $args = 1 ) {
		remove_filter( $hook, array( &$this, $func ), $priority, $args );
    }

	/**
	 * [config description]
	 * @method config
	 * @param  array  $config [description]
	 * @return [type]         [description]
	 */
    protected function config( $config = array() ) {

        // check
        if( empty( $config ) ) {
            return;
        }

        foreach( $config as $key => $value ) {
            $this->$key = $value;
        }
    }

	/**
	 * [is_current_page description]
	 * @method is_current_page
	 * @return boolean         [description]
	 */
	protected function is_current_page() {
        $page = isset( $_GET['page'] ) && !empty( $_GET['page'] ) ? $_GET['page'] : false;
        return $page === $this->id;
    }
}

endif;

// Helper Function ----------------------------------------------------

if( ! function_exists( 'cl_action' ) ):

	function cl_action() {

		$args = func_get_args();

		if( ! isset( $args[0] ) || empty( $args[0] ) ) {
			return;
		}

		$action = 'contentlocker_' . $args[0];
		unset( $args[0] );

		$args = array_merge( array(), $args );

		do_action_ref_array( $action, $args );
	}

endif;

if( ! function_exists( 'cl_filter' ) ):

	function cl_filter() {

		$args = func_get_args();

		if( !isset( $args[0] ) || empty( $args[0] ) ) {
			return;
		}

		$action = 'contentlocker_' . $args[0];
		unset( $args[0] );

		$args = array_merge( array(), $args );

		return apply_filters_ref_array( $action, $args );
	}

endif;
