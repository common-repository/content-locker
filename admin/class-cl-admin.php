<?php
/**
 * The Admin
 */

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists( 'CL_Admin' ) ) :

/**
 * @class CL_Admin
 */
class CL_Admin extends CL_Base {

	/**
	 * [__construct description]
	 * @method __construct
	 */
	public function __construct() {
		$this->includes();
		$this->hooks();
	}

	/**
	 * [includes description]
	 * @method includes
	 * @return [type]   [description]
	 */
	private function includes() {

		include_once 'class-cl-admin-help.php';
		include_once 'class-cl-export-leads.php';
		include_once 'extras/visual-composer/vc-init.php';
	}

	/**
	 * [hooks description]
	 * @method hooks
	 * @return [type] [description]
	 */
	private function hooks() {

		$this->add_action( 'init', 'init', 1 );
		$this->add_action( 'current_screen', 'redirect_to_new_item' );
		$this->add_action( 'admin_enqueue_scripts', 'enqueue', 11 );

		// Locker and Lead Menu Management
		$this->add_action( 'admin_menu', 'add_menu', 11 );
		$this->add_action( 'parent_file', 'parent_file' );
		$this->add_action( 'submenu_file', 'submenu_file' );
		$this->add_action( 'edit_form_after_editor', 'locker_option_tabs' );

		// TinyMCE Quick Tags
		$this->add_action( 'admin_print_footer_scripts', 'register_quicktags' );
		$this->add_filter( 'mce_buttons', 'register_button' );
		$this->add_filter( 'mce_external_plugins', 'add_plugin' );

		// Leads
		add_filter( 'bulk_actions-edit-cl-lead', array( 'CL_Leads', 'bulk_actions' ) );
		add_filter( 'manage_cl-lead_posts_columns', array( 'CL_Leads', 'add_columns' ) );
		add_filter( 'manage_edit-cl-lead_sortable_columns', array( 'CL_Leads', 'add_sortable_columns' ) );
		add_filter( 'manage_cl-lead_posts_custom_column', array( 'CL_Leads', 'columns_content' ), 10, 2 );

		// Subscription
		$this->add_filter( 'mts_cl_subscription_services', 'subscription_services' );

		// Ajax
		$this->add_action( 'wp_ajax_mts-cl-clear-stats-data', 'clear_stats' );
		$this->add_action( 'wp_ajax_mts-cl-get-service-list', 'get_service_lists' );
	}

	/**
	 * [init description]
	 * @method init
	 * @return [type] [description]
	 */
	public function init() {

		$this->register_pages();
		$this->register_metaboxes();

		cl()->help_manager = new CL_Help_Manager();
	}

	/**
	 * [register_pages description]
	 * @method register_pages
	 * @return [type]         [description]
	 */
	private function register_pages() {

		$parent = 'edit.php?post_type=content-locker';

		new CL_Admin_Page( 'cl-new', esc_html__('+ New Locker','content-locker'), array(
            'position' => 10,
			'parent' => $parent,
            'onrender' => function() {
				include_once 'views/page-new.php';
			}
        ));

		new CL_Admin_Page( 'cl-settings', esc_html__('Settings','content-locker'), array(
			'position' => 12,
			'parent' => $parent,
			'onrender' => function() {
				include_once 'views/page-settings.php';
			}
		));

		new CL_Admin_Page( 'cl-help', esc_html__('How to use?','content-locker'), array(
			'position' => 12,
			'parent' => $parent,
			'onrender' => function() {
				include_once 'views/page-help.php';
			}
		));
	}

	/**
	 * [register_metaboxes description]
	 * @method register_metaboxes
	 * @return [type]             [description]
	 */
	public function register_metaboxes() {

		$boxes = array( 'basic-options', 'visibility-options', 'advance-options', 'social-options', 'signin-options', 'manual-locking' );

		foreach( $boxes as $box ) {
			include_once "metaboxes/$box.php";
		}
	}

	/**
	 * Registers the quick tags for the wp editors.
	 *
	 * @method register_quicktags
	 * @return [type]             [description]
	 */
	public function register_quicktags() {
	?>
	    <script type="text/javascript">
	        (function(){
	            if (! window.QTags ) {
					 return;
				}

				window.QTags.addButton( 'sociallocker', 'sociallocker', '[sociallocker]', '[/sociallocker]' );
				window.QTags.addButton( 'signinlocker', 'signinlocker', '[signinlocker]', '[/signinlocker]' );

				window.mts_cl_social_lockers = <?php echo json_encode( cl_get_lockers( 'social-locker', 'tinymce' ) ) ?>;
				window.mts_cl_signin_lockers = <?php echo json_encode( cl_get_lockers( 'signin-locker', 'tinymce' ) ) ?>;
	        }());
	    </script>
	<?php
	}

	/**
	 * Registers the button for the TinyMCE
	 *
	 * @see mce_buttons
	 */
	public function register_button( $buttons ) {

		$post = get_post();
		$post_id = $post ? $post->ID : 0;
	    if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $buttons;
		}

		array_push( $buttons, 'contentlocker' );
		return $buttons;
	}

	/**
	 * Registers the plugin for the TinyMCE
	 *
	 * @see mce_external_plugins
	 */
	function add_plugin( $plugin_array ) {

		$post = get_post();
		$post_id = $post ? $post->ID : 0;
	    if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $plugin_array;
		}

		$plugin_array['contentlocker'] = cl()->plugin_url() . '/admin/assets/js/contentlocker.tinymce4.js';

	    return $plugin_array;
	}

	/**
	 * [enqueue description]
	 * @method enqueue
	 * @return [type]  [description]
	 */
    public function enqueue() {

		$screen = get_current_screen();

		wp_register_style( 'font-awesome', cl()->plugin_url() . '/admin/assets/vendors/font-awesome/css/font-awesome.min.css', array(), cl()->get_version() );
		wp_enqueue_style( 'cl-admin', cl()->plugin_url() . '/admin/assets/css/cl-admin.css', array( 'font-awesome' ), cl()->get_version() );

		// Scripts
		wp_enqueue_script( 'zen-clipboard', cl()->plugin_url() . '/admin/assets/js/clipboard.min.js', null, cl()->get_version(), true );
		$deps = array(
			'jquery',
			'jquery-ui-sortable',
			'zen-clipboard'
		);
		wp_enqueue_script( 'cl-admin', cl()->plugin_url() . '/admin/assets/js/cl-admin.js', $deps, cl()->get_version(), true );
		wp_localize_script( 'cl-admin', 'mts_cl', array(
			'assets' => cl()->plugin_url() . '/admin/assets'
		));
    }

	/**
	 * [add_menu description]
	 * @method add_menu
	 */
	public function add_menu() {

		global $menu, $submenu;

		unset( $submenu['edit.php?post_type=content-locker'][10] );
		unset( $submenu['edit.php?post_type=content-locker'][11] );
		unset( $submenu['edit.php?post_type=content-locker'][12] );

		// New Social Locker
		add_submenu_page(
        	'edit.php?post_type=content-locker',
        	esc_html__('+ Social Locker','content-locker'),
			esc_html__('+ Social Locker','content-locker'),
			'edit_posts',
			'post-new.php?post_type=content-locker&cl_item_type=social-locker'
		);

		// New Sign-In Locker
		add_submenu_page(
        	'edit.php?post_type=content-locker',
        	esc_html__('+ Sign-In Locker','content-locker'),
			esc_html__('+ Sign-In Locker','content-locker'),
			'edit_posts',
			'post-new.php?post_type=content-locker&cl_item_type=signin-locker'
		);

		// Lead Menu
		$count = wp_count_posts( 'cl-lead' );
		$count = intval( $count->publish ) + intval( $count->confirmed ) + intval( $count->{'not-confirmed'} );
		$label = sprintf( esc_html__('Leads (%d)','content-locker'), $count );

		add_submenu_page(
        	'edit.php?post_type=content-locker',
        	$label,
			$label,
			'edit_posts',
			'edit.php?post_type=cl-lead'
		);
	}

	/**
	 * [parent_file description]
	 * @method parent_file
	 * @param  [type]      $file [description]
	 * @return [type]            [description]
	 */
	public function parent_file( $file ) {
		$screen = get_current_screen();

		if( in_array( $screen->base, array( 'post', 'edit' ) ) && 'cl-lead' == $screen->post_type ) {
			$file = 'edit.php?post_type=content-locker';
		}

		return $file;
	}

	/**
	 * [submenu_file description]
	 * @method submenu_file
	 * @param  [type]       $file [description]
	 * @return [type]             [description]
	 */
	public function submenu_file( $file ) {
		$screen = get_current_screen();

		if( 'post' == $screen->base && 'content-locker' == $screen->post_type ) {

			if( 'add' === $screen->action ) {
				$file = 'post-new.php?post_type=content-locker&cl_item_type=' . $_REQUEST['cl_item_type'];
			}
			else {
				$file = 'edit.php?post_type=content-locker';
			}
		}

		if( in_array( $screen->base, array( 'post', 'edit' ) ) && 'cl-lead' == $screen->post_type ) {
			$file = 'edit.php?post_type=cl-lead';
		}

		return $file;
	}

	/**
	 * [locker_option_tabs description]
	 * @method locker_option_tabs
	 * @param  [type]             $post [description]
	 * @return [type]                   [description]
	 */
	public function locker_option_tabs( $post ) {

		if( 'content-locker' !== get_post_type( $post ) ) {
			return;
		}
		?>
		<div class="cl-main-tabs">
			<a href="#cl-social-options"><i class="fa fa-lock"></i><?php esc_html_e( 'Locker', 'content-locker' ) ?></a><a href="#cl-signin-options"><i class="fa fa-lock"></i><?php esc_html_e( 'Locker', 'content-locker' ) ?></a><a href="#cl-basic-options"><i class="fa fa-pencil"></i><?php esc_html_e( 'General', 'content-locker' ) ?></a><a href="#cl-visibility-options"><i class="fa fa-eye"></i><?php esc_html_e( 'Visibility', 'content-locker' ) ?></a><a href="#cl-advance-options"><i class="fa fa-sliders"></i><?php esc_html_e( 'Advance', 'content-locker' ) ?></a>
		</div>
		<?php
	}

	/**
	 * [locker_shortcode description]
	 * @method locker_shortcode
	 * @param  [type]           $post [description]
	 * @return [type]                 [description]
	 */
	public function locker_shortcode( $post ) {

		if( 'content-locker' !== get_post_type( $post ) ) {
			return;
		}
		?>
		<div class="misc-pub-section">
			<hr>
			<?php esc_html_e( 'Wrap content you want to lock via the following shortcode in your post editor: ', 'content-locker' ); ?>
		</div>
		<?php
	}

	/**
	 * [redirect_to_new_item description]
	 * @method redirect_to_new_item
	 * @return [type]               [description]
	 */
	public function redirect_to_new_item() {
		$screen = get_current_screen();

		// checks
		if ( empty( $screen ) ) {
			return;
		}
	    if ( 'add' !== $screen->action || 'post' !== $screen->base || 'content-locker' !== $screen->post_type ) {
			return;
		}
	    if ( isset( $_GET['cl_item_type'] ) ) {
			return;
		}

	    wp_safe_redirect( 'edit.php?post_type=content-locker&page=cl-new' );
	    exit;
	}

	/**
	 * [subscription_services description]
	 * @method subscription_services
	 * @return [type]                [description]
	 */
	public function subscription_services( $services ) {

		$items = array(

			'database' => array(
	            'title' => esc_html__( 'Database', 'content-locker' ),
	            'description' => esc_html__( 'Emails of subscribers will be saved in the WP database.', 'content-locker' ),

				'class' => 'CL_Subscription_Database',
				'modes' => array( 'quick' ),
	        ),

			'mailchimp' => array(
	            'title' => esc_html__('MailChimp', 'content-locker'),
	            'description' => esc_html__('Adds subscribers to your MailChimp account.', 'content-locker'),

				'class' => 'CL_Subscription_MailChimp',
	            'modes' => array( 'quick' )
	        ),

			'mailerlite' => array(
	            'title' => esc_html__('MailerLite', 'content-locker'),
	            'description' => esc_html__('Adds subscribers to your MailerLite account.', 'content-locker'),

	            'class' => 'CL_Subscription_MailerLite',
	            'modes' => array( 'quick' )
	        ),

			'aweber' => array(
	            'title' => esc_html__('Aweber (Pro)', 'content-locker'),
	            'description' => esc_html__('Adds subscribers to your MailChimp account.', 'content-locker'),

				//'class' => 'CL_Subscription_MailChimp',
	            'modes' => array( 'quick' )//,'modes' => array( 'double-optin', 'quick-double-optin', 'quick' )
	        ),
		);

		return array_merge( $services, $items );
	}

	/**
	 * [clear_stats description]
	 * @method clear_stats
	 * @return [type]      [description]
	 */
	public function clear_stats() {

		global $wpdb;
        $wpdb->query("DELETE FROM {$wpdb->prefix}mts_locker_stats");

        $lockers = cl_get_lockers();

		foreach( $lockers as $locker ) {
			delete_post_meta( $locker->ID, '_mts_cl_imperessions' );
            delete_post_meta( $locker->ID, '_mts_cl_unlocks' );
		}

		exit('success');
	}

	/**
	 * [get_service_lists description]
	 * @method get_service_lists
	 * @return [type]            [description]
	 */
	public function get_service_lists() {

		$name = $_REQUEST['service'];
		$args = $_REQUEST['args'];

		if( empty( $name ) || empty( $args ) ) {
			wp_send_json(array(
				'success' => false,
				'error' => esc_html__( 'Not permitted.', 'content-locker' )
			));
		}

		$service = cl_get_subscription_service( $name );

		if( is_null( $service ) ) {
			wp_send_json(array(
				'success' => false,
				'error' => esc_html__( 'Service not defined.', 'content-locker' )
			));
		}

		try {
			$lists = call_user_func_array( array( $service, 'get_lists' ), $args );
		}
		catch( Exception $e ) {
			wp_send_json(array(
				'success' => false,
				'error' => $e->getMessage()
			));
		}

		if( empty( $lists ) ) {
			wp_send_json(array(
				'success' => false,
				'error' => esc_html__( 'No lists found.', 'content-locker' )
			));
		}

		// Save for letter use
		set_transient( 'mts_cl_'. $name . '_lists', $lists );

		wp_send_json(array(
			'success' => true,
			'lists' => $lists
		));
	}
}

endif;
new CL_Admin;
