<?php
/**
 * The Admin Page Framework
 */

class CL_Admin_Page extends CL_Base {

    /**
     * Unique ID used for menu_slug.
     * @var string
     */
    public $id;

    /**
     * The text to be displayed in the title tags of the page.
     * @var string
     */
    public $title;

    /**
     * The slug name for the parent menu.
     * @var string
     */
    public $parent = null;

    /**
     * The The on-screen name text for the menu.
     * @var string
     */
    public $menu_title;

    /**
     * The capability required for this menu to be displayed to the user.
     * @var string
     */
    public $capability = 'manage_options';

    /**
     * The icon for this menu.
     * @var string
     */
    public $icon = 'dashicons-art';

    /**
     * The position in the menu order this menu should appear.
     * @var string
     */
    public $position;

    /**
     * The function that displays the page content for the menu page.
     * @var fucntion
     */
    public $onrender = null;

    /**
     * The function that run on page POST to save data.
     * @var fucntion
     */
    public $onsave = null;


    /**
     * Constructor
     * @method __construct
     * @param  [type]      $id     [description]
     * @param  [type]      $title  [description]
     * @param  array       $config [description]
     */
    public function __construct( $id, $title, $config = array() ) {

        // check
        if( ! $id ) {
            wp_die( esc_html__( '$id variable required', 'content-locker' ), esc_html__( 'Variable Required', 'content-locker' ) );
        }

        if( ! $title ) {
            wp_die( esc_html__( '$title variable required', 'content-locker' ), esc_html__( 'Variable Required', 'content-locker' ) );
        }

        $this->id    = $id;
        $this->title = $title;
        $this->config( $config );

        if( ! $this->menu_title ) {
            $this->menu_title = $title;
        }

        $this->add_action( 'init', 'init' );
    }

    /**
     * Init admin page when WordPress Initialises.
     */
    public function init() {

        $priority = -1;
        if( $this->parent ) {
            $priority = intval( $this->position );
        }
        $this->add_action( 'admin_menu', 'register_menu', $priority );

        // If not the page is not this page stop here
        if( ! $this->is_current_page() ) {
            return;
        }

        $this->add_action( 'admin_body_class', 'body_class' );

        if( $this->onsave ) {
            $this->add_action( 'admin_init', 'save' );
        }
    }

    /**
     * Register Admin Menu.
     */
    public function register_menu() {

        // Parent
        if( !$this->parent ) {
            add_menu_page(
				$this->title,
				$this->menu_title,
				$this->capability,
				$this->id,
				array( $this, 'render' ),
				$this->icon,
				$this->position
			);
        }

		// Child Page
        else {
            add_submenu_page(
				$this->parent,
				$this->title,
				$this->menu_title,
				$this->capability,
				$this->id,
				array( $this, 'render' )
			);
        }
    }

    /**
     * Render admin page content using onrender function you passed in config.
     */
    public function render() {

		cl_action( 'before_admin_page', $this );
        cl_action( 'before_admin_page_' . $this->id, $this );

            if( $this->onrender ) {
                call_user_func( $this->onrender, $this );
            }

        cl_action( 'admin_page_' . $this->id, $this );
        cl_action( 'admin_page', $this);
    }

    /**
     * Save anything you want using onsave function you passed in config.
     * For Developer to hook.
     */
    public function save() {
        call_user_func( $this->onsave, $this );
    }

    /**
     * Add classes to <body> of wordpress admin.
     *
     * @param  string     $classes
     *
     * @return string
     */
    public function body_class( $classes = '' ) {
        return $classes . ' content-locker-page';
    }

    // API ------------------------------------

    /**
     * Create sub-page this page as parent.
     *
     * @param  string	$id
     * @param  string	$title
     * @param  array 	$config
     *
     * @return CL_Admin_Page
     */
    public function create_subpage( $id, $title, $config = array() ) {
        $config['parent'] = $this->id;

        return new self( $id, $title, $config );
    }
}
