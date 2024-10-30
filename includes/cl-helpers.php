<?php
/**
 * Helper Functions
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Check if the string begins with the given value.
 *
 * @param  string	$needle   The sub-string to search for
 * @param  string	$haystack The string to search
 *
 * @return bool
 */
function cl_str_start_with( $needle, $haystack ) {
	return substr_compare( $haystack, $needle, 0, strlen( $needle ) ) === 0;
}

/**
 * [cl_str_contains description]
 * @method cl_str_contains
 * @param  [type]          $needle   [description]
 * @param  [type]          $haystack [description]
 * @return [type]                    [description]
 */
function cl_str_contains( $needle, $haystack ) {
	return strpos( $haystack, $needle ) !== false;
}

/**
 * [cl_get_item_type description]
 * @method cl_get_item_type
 * @param  [type]           $post [description]
 * @return [type]                 [description]
 */
function cl_get_item_type( $post = null ) {

	if( isset( $_GET['cl_item_type'] ) ) {
		return $_GET['cl_item_type'];
	}

	$post = get_post( $post );
	if( $post ) {
		return get_post_meta( $post->ID, '_mts_cl_item_type', true );
	}

	return '';
}

/**
 * [cl_cmb_tabs_open_tag description]
 * @method cl_cmb_tabs_open_tag
 * @param  [type]              $field_args [description]
 * @param  [type]              $field      [description]
 * @return [type]                          [description]
 */
function cl_cmb_tabs_open_tag( $field_args, $field ) {

	$cmb = $field->get_cmb();

	echo '<div class="' . $field->row_classes() . '">';

		echo '<ul class="cl-cmb-tabs-menu" data-id="' . $field->args( 'order_id' ) . '" data-sortable="'. ( $field->args( 'sortable' ) ? 'true' : 'false'  ) .'">';

			$first = ' class="active"';
			$drag = $field->args( 'sortable' ) ? '<i class="fa fa-th-large ui-drag"></i>' : '';
			foreach( $cmb->meta_box['fields'] as $field_name => $field ) {
				if( 'section' === $field['type'] ) {
					printf( '<li id="%1$s">%3$s<a href="#section-%1$s"%4$s>%2$s</a></li>', $field['id'], $field['name'], $drag, $first );
					$first = '';
				}
			}

		echo '</ul>';
}

/**
 * [cl_cmb_tabs_close_tag description]
 * @method cl_cmb_tabs_close_tag
 * @param  [type]               $field_args [description]
 * @param  [type]               $field      [description]
 * @return [type]                           [description]
 */
function cl_cmb_tabs_close_tag( $field_args, $field ) {
	echo '</div>';
}

/**
 * [cl_cmb_tab_open_tag description]
 * @method cl_cmb_tab_open_tag
 * @param  [type]              $field_args [description]
 * @param  [type]              $field      [description]
 * @return [type]                          [description]
 */
function cl_cmb_tab_open_tag( $field_args, $field ) {
	$id = $field->args( 'id' );
	echo '<div class="' . $field->row_classes() . '" id="section-' . $id . '">';
}

/**
 * [cl_cmb_tab_close_tag description]
 * @method cl_cmb_tab_close_tag
 * @param  [type]               $field_args [description]
 * @param  [type]               $field      [description]
 * @return [type]                           [description]
 */
function cl_cmb_tab_close_tag( $field_args, $field ) {
	echo '</div>';
}

/**
 * [cl_cmb_alert description]
 * @method cl_cmb_alert
 * @param  [type]       $field_args [description]
 * @param  [type]       $field      [description]
 * @return [type]                   [description]
 */
function cl_cmb_alert( $field_args, $field ) {

	if( 'view' === $field->args( 'type' ) ) {
		include cl()->plugin_dir() . '/admin/views/' . $field->args( 'file' ) . '.php';
		return;
	}

	if( 'title' === $field->args( 'type' ) ) {
		echo '<div class="' . $field->row_classes() . '">';

			if( $name = $field->args( 'name' ) ) {
				printf( '<h3>%s</h3>', $name );
			}

			echo wpautop( wp_kses_post( $field->args( 'desc' ) ) );

		echo '</div>';
		return;
	}

	echo '<div class="alert alert-' . $field->args( 'type' ) . ' ' . $field->row_classes() . '">';

		if( $name = $field->args( 'name' ) ) {
			printf( '<p class="alert-title">%s</p>', $name );
		}

		echo wp_kses_post( $field->args( 'desc' ) );

	echo '</div>';
}

/**
 * [cl_cmb_more_open_tag description]
 * @method cl_cmb_more_open_tag
 * @param  [type]               $field_args [description]
 * @param  [type]               $field      [description]
 * @return [type]                           [description]
 */
function cl_cmb_more_open_tag( $field_args, $field ) {

	echo '<div class="' . $field->row_classes() . '">';

		printf( '<a href="#" class="more-link-show">%s</a>', $field->args( 'name' ) );
		echo '<a href="#" class="more-link-hide" style="display:none"><span>'. esc_html__( 'hide extra options', 'content-locker' ) .'</span></a>';

		echo '<div class="cmb-type-more-content">';
}

/**
 * [cl_cmb_more_close_tag description]
 * @method cl_cmb_more_close_tag
 * @param  [type]                $field_args [description]
 * @param  [type]                $field      [description]
 * @return [type]                            [description]
 */
function cl_cmb_more_close_tag( $field_args, $field ) {

		echo '</div>';

	echo '</div>';
}

/**
 * [cl_cmb_post_types description]
 * @method cl_cmb_post_types
 * @return [type]            [description]
 */
function cl_cmb_post_types() {
	global $wp_post_types;

	$cpts = $wp_post_types;
	unset( $cpts[ 'nav_menu_item' ], $cpts[ 'revision' ], $cpts[ 'content-locker' ], $cpts[ 'cl-lead' ], $cpts[ 'vc_grid_item' ], $cpts[ 'customize_changeset' ], $cpts[ 'custom_css' ] );
	$cpts = wp_list_pluck( $cpts, 'label' );

	return $cpts;
}

// Data Helpers ------------------------------------

/**
 * [cl_cmb_get_pages description]
 * @method cl_cmb_get_pages
 * @return [type]           [description]
 */
function cl_cmb_get_pages() {
	$pages = get_pages( 'numer=30' );

	return wp_list_pluck( $pages, 'post_title', 'ID' );
}

/**
 * [cl_get_lockers description]
 * @method cl_get_lockers
 * @param  [type]         $type   [description]
 * @param  [type]         $output [description]
 * @return [type]                 [description]
 */
function cl_get_lockers( $type, $output = null ) {


	$args = array(
		'post_type'		=> 'content-locker',
        'numberposts'	=> -1
	);

	if( ! empty( $type ) ) {
		$args['meta_key'] = '_mts_cl_item_type';
		$args['meta_value'] = $type;
	}

	$lockers = get_posts( $args );

	foreach( $lockers as $locker ) {
        $locker->post_title = empty( $locker->post_title )  ? sprintf( __( '(no titled, ID=%s)', 'content-locker' ), $locker->ID ) : $locker->post_title;
    }

	// CMB
	if( 'cmb' === $output ) {

		$result = array();
        foreach ( $lockers as $locker ) {
			$locker->post_title = empty( $locker->post_title )  ? sprintf( __( '(no titled, ID=%s)', 'content-locker' ), $locker->ID ) : $locker->post_title;
			if( $type = get_post_meta( $locker->ID, '_mts_cl_item_type', true ) ) {
				$locker->post_title = sprintf( '[%s] %s', $type, $locker->post_title );
			}

			$result[ $locker->ID ] = $locker->post_title;
		}

		return $result;
	}

	// Visual Composer format
	if( 'vc' === $output ) {
		$result = array();
        foreach ( $lockers as $locker ) {
			$result[$locker->post_title] = $locker->ID;
		}
        return $result;
	}

	// TinyMCE format
	if( 'tinymce' === $output ) {
		$result = array();
        foreach ( $lockers as $locker ) {
			$result[] = array(
				'text' => $locker->post_title,
				'value' => $locker->ID
			);
		}
        return $result;
	}

	return $lockers;
}


/**
 * [normalize_data description]
 * @method normalize_data
 * @param  [type]         $value [description]
 * @return [type]                [description]
 */
function cl_normalize_data( $value ) {

	if( 'true' === $value || 'on' === $value ) {
		$value = true;
	}
	elseif( 'false' === $value || 'off' === $value ) {
		$value = false;
	}
	elseif( '0' === $value || '1' === $value ) {
		$value = intval( $value );
	}

	return $value;
}

/**
 * [cl_cmb_get_term_options description]
 * @method cl_cmb_get_term_options
 * @param  [type]                  $field [description]
 * @return [type]                         [description]
 */
function cl_cmb_get_term_options( $field ) {
    $args = $field->args( 'get_terms_args' );
    $args = is_array( $args ) ? $args : array();

    $args = wp_parse_args( $args, array( 'taxonomy' => 'category' ) );

    $taxonomy = $args['taxonomy'];

    $terms = (array) cmb2_utils()->wp_at_least( '4.5.0' )
        ? get_terms( $args )
        : get_terms( $taxonomy, $args );

    // Initate an empty array
    $term_options = array();
    if ( ! empty( $terms ) ) {
        foreach ( $terms as $term ) {
            $term_options[ $term->term_id ] = $term->name;
        }
    }

    return $term_options;
}

/**
 * [cl_get_template_part description]
 * @method cl_get_template_part
 * @param  [type]               $template_name [description]
 * @param  [type]               $args          [description]
 * @return [type]                              [description]
 */
function cl_get_template_part( $template_name, $args ) {

	if ( ! empty( $args ) && is_array( $args ) ) {
		extract( $args );
	}

	$located = cl_locate_template( $template_name, $locker->theme, $locker->item_type );

	if ( ! file_exists( $located ) ) {
		_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $located ), '2.1' );
		return;
	}

	include( $located );
}

/**
 * [cl_locate_template description]
 * @method cl_locate_template
 * @param  [type]             $template_name [description]
 * @param  [type]             $theme         [description]
 * @return [type]                            [description]
 */
function cl_locate_template( $template_name, $theme, $type = 'social' ) {

	$template_path = cl()->template_path();

	$templates = array(

		// By type
		"{$template_path}{$theme}/{$type}-{$template_name}.php",
		"{$template_path}{$theme}/{$template_name}.php",

		"{$template_path}{$type}-{$template_name}.php",
		"{$template_path}{$template_name}.php"
	);

	/**
	 * Look within passed path within the theme - this is priority.
	 *
	 * child-theme/template_path/theme_name/template_name
	 * child-theme/template_path/template_name
	 *
	 * theme/template_path/theme_name/template_name
	 * theme/template_path/template_name
	 */
	$template = locate_template( $templates );

	// Fallback to default
	if( ! $template ) {
		$template_path = cl()->plugin_dir() . '/templates/';

		/**
		 * Look within passed path within the plugin - this is priority.
		 *
		 * plugin/templates/theme_name/template_name
		 * plugin/templates/template_name
		 */
		$templates = array(
			$template_path . $theme . '/' . $template_name . '.php',
			$template_path . $template_name . '.php'
		);

		foreach( $templates as $name ) {

			if( file_exists( $name ) ) {
				$template = $name;
				break;
			}
		}
	}

	// Return what we found.
	return $template;
}

/**
 * [cl_attributes description]
 * @method cl_attributes
 * @param  array         $attributes [description]
 * @return [type]                    [description]
 */
function cl_attributes( $attributes = array(), $prefix = '' ) {

	// If empty return false
	if ( empty( $attributes ) ) {
		return false;
	}

	$out = '';
	foreach ( $attributes as $key => $value ) {

		$key = $prefix . $key;

		if( true === $value ) {
			$value = 'true';
		}

		if( false === $value ) {
			$value = 'false';
		}

		$out .= sprintf( ' %s="%s"', esc_html( $key ), esc_attr( $value ) );
	}

	return $out;
}

/**
 * [cl_cmb_facebook_errors description]
 * @method cl_cmb_facebook_errors
 * @return [type]                 [description]
 */
function cl_cmb_facebook_errors() {

	$app_id = cl()->settings->get('facebook_appid');
	if( empty( $app_id ) || '117100935120196' === $app_id ) {
		$url = cl_get_admin_url('settings#facebook_appid');
		return wp_kses_post( sprintf( __( 'To enable this service, you need to register a Facebook App for your website. Please <a href="%s" target="_blank">click here</a> to know more.', 'content-locker' ), $url ) );
	}

	return false;
}

/**
 * [cl_cmb_twitter_errors description]
 * @method cl_cmb_twitter_errors
 * @return [type]                [description]
 */
function cl_cmb_twitter_errors() {

	$key = cl()->settings->get('twitter_consumer_key');
	$secret = cl()->settings->get('twitter_consumer_secret');

	if( empty( $key ) || empty( $secret ) ) {
		$url = cl_get_admin_url('settings#twitter_consumer_key');
		return wp_kses_post( sprintf( __( 'To enable this service, please set the Key & Secret of your Twitter App for your website. Please <a href="%s" target="_blank">click here</a> to know more.', 'content-locker' ), $url ) );
	}

	return false;
}


/**
 * [cl_cmb_google_errors description]
 * @method cl_cmb_google_errors
 * @return [type]               [description]
 */
function cl_cmb_google_errors() {

	$client_id = cl()->settings->get('google_client_id');
	if( empty( $client_id ) ) {
		$url = cl_get_admin_url('settings#google_client_id');
		return wp_kses_post( sprintf( __( 'To enable this service, you need to get a Google Client ID for your website. Please <a href="%s" target="_blank">click here</a> to know more.', 'content-locker' ), $url ) );
	}

	return false;
}

/**
 * [cl_cmb_linkedin_errors description]
 * @method cl_cmb_linkedin_errors
 * @return [type]                 [description]
 */
function cl_cmb_linkedin_errors() {

	$client_id = cl()->settings->get('linkedin_client_id');
	$client_secret = cl()->settings->get('linkedin_client_secret');

	if( empty( $client_id ) || empty( $client_secret ) ) {
		$url = cl_get_admin_url('settings#linkedin_client_id');
		return wp_kses_post( sprintf( __( 'To enable this service, you need to get LinkedIn Client ID and Secret for your website. Please <a href="%s" target="_blank">click here</a> to learn more.', 'content-locker' ), $url ) );
	}

	return false;
}

/**
 * [cl_get_human_size description]
 * @method cl_get_human_size
 * @param  [type]                $bytes [description]
 * @return [type]                       [description]
 */
function cl_get_human_size( $bytes ) {

	if ($bytes >= 1073741824) {
        $bytes = number_format( $bytes / 1073741824, 2 ) . ' GB';
    }
    elseif ($bytes >= 1048576) {
        $bytes = number_format( $bytes / 1048576, 2 ) . ' MB';
    }
    elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    }
    elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    }
    elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    }
    else {
        $bytes = '0 bytes';
    }

    return $bytes;
}

/**
 * [cl_get_mailing_lists description]
 * @method cl_get_mailing_lists
 * @return [type]               [description]
 */
function cl_get_mailing_lists( $type = 'options' ) {

	$lists = cl()->settings->get('mailing');

	if( empty( $lists ) ) {
		return array(
			'database' => esc_html__( 'Database', 'content-locker' )
		);
	}

	$data = array();
	if( 'options' === $type ) {
		$data['database'] = esc_html__( 'Database', 'content-locker' );
	}
	elseif( 'list' === $type ) {
		$data['default'] = esc_html__( 'Default', 'content-locker' );
	}

	foreach( $lists as $i => $list ) {

		$name = $list['mailing'];
		$id = isset( $list[$name . '_list'] ) ? $name . '_' . $list[$name . '_list'] : $name;
		$search = $name . '_';

		$data[$id] = !empty( $list['mailing_name'] ) ? $list['mailing_name'] : esc_html__( 'No Title', 'content-locker' );
		$new[$id] = array(
			//'api_key' => $list[ $name . '_api_key'],
			//'list_id' => $list_id,
			'service' => $name,
			'title' => !empty( $list['mailing_name'] ) ? $list['mailing_name'] : esc_html__( 'No Title', 'content-locker' )
		);

		foreach( $list as $key => $val ) {

			if( cl_str_start_with( $search, $key ) ) {
				$new[$id][ str_replace( $search, '', $key ) ] = $val;
			}
		}
	}

	if( 'setting' === $type ) {
		return $new;
	}

	return $data;
}

/**
 * [cl_get_mailing_options description]
 * @method cl_get_mailing_options
 * @param  [type]                 $id [description]
 * @return [type]                     [description]
 */
function cl_get_mailing_options( $id ) {

	$lists = cl_get_mailing_lists( 'setting' );
	return isset( $lists[$id] ) ? $lists[$id] : array();
}

/**
 * [cl_get_subscription_services description]
 * @method cl_get_subscription_services
 * @return [type]                       [description]
 */
function cl_get_subscription_services( $type = 'list' ) {

	$result = apply_filters( 'mts_cl_subscription_services', array() );

	$helper = array();
    foreach( $result as $id => $data ) {
        $helper[$id] = $data['title'];
    }

    //array_multisort( $result, $helper );

	unset( $helper['database'] );

	return 'list' === $type ? $helper : $result;
}

/**
 * [cl_get_subscription_info description]
 * @method cl_get_subscription_info
 * @param  [type]                   $name [description]
 * @return [type]                         [description]
 */
function cl_get_subscription_info( $name ) {

	$result = cl_get_subscription_services( 'result' );

	return isset( $result[ $name ] ) ? $result[ $name ] : null;
}

/**
 * [cl_get_subscription_service description]
 * @method cl_get_subscription_service
 * @param  [type]                      $name [description]
 * @return [type]                            [description]
 */
function cl_get_subscription_service( $name ) {

	$info = cl_get_subscription_info( $name );

	if( is_null( $info ) ) {
		return null;
	}

	return new $info['class']( $info );
}

/**
 * [cl_get_service_list description]
 * @method cl_get_service_list
 * @param  string              $name [description]
 * @return [type]                    [description]
 */
function cl_get_service_list( $name = '' ) {

	if( !$name ) {
		return;
	}

	$list = get_transient( 'mts_cl_'. $name . '_lists' );

	return empty( $list ) ? array() : $list;
}

/**
 * [cl_get_admin_url description]
 * @method cl_get_admin_url
 * @param  string           $page [description]
 * @param  array            $args [description]
 * @return [type]                 [description]
 */
function cl_get_admin_url( $page = 'help', $args = array() ) {

	$base = admin_url('edit.php?post_type=content-locker');
	$args['page'] = 'cl-' . $page;

	return add_query_arg( $args, $base );
}

/**
 * [cl_get_help_url description]
 * @method cl_get_help_url
 * @param  [type]          $page [description]
 * @return [type]                [description]
 */
function cl_get_help_url( $page = null ) {
	return cl_get_admin_url( 'help', array( 'cl-page' => $page ) );
}
