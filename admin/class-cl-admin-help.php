<?php
/**
 * The Help Manager
 */

class CL_Help_Manager extends CL_Base {

	public function __construct() {
		$this->setup();
	}

	public function display() {

		$nav = $content = '';
		$page = isset( $_GET['cl-page'] ) ? $_GET['cl-page'] : 'getting-started';
		$base = cl_get_admin_url();

		foreach( $this->sections as $label => $view ) {
			$active = $child = '';
			$key = $view;

			if( is_array( $view ) ) {
				$key = current($view);

				$child .= '<ul>';
				foreach( $view as $c_label => $c_view ) {

					$c_active = '';
					if( $c_view === $page ) {
						$c_active = ' class="active"';
						$active = ' class="active"';
					}
					$child .= sprintf( '<li%4$s><a href="%1$s&cl-page=%3$s">%2$s</a></li>', $base, $c_label, $c_view, $c_active );
				}
				$child .= '</ul>';
			}

			if( $key === $page ) {
				$active = ' class="active"';
			}

			$nav .= sprintf( '<li%5$s><a href="%1$s&cl-page=%4$s">%2$s</a>%3$s</li>', $base, $label, $child, $key, $active );
		}
		?>
		<div class="mts-cl-help-wrapper">

			<div class="mts-cl-help-menu">
				<div class="mts-cl-help-logo"></div>
				<ul>
					<?php echo $nav ?>
				</ul>
			</div>
			<div class="mts-cl-help-content">
				<?php include "views/how-to-use/{$page}.php"; ?>
			</div>
		</div>
		<?php
	}

	private function setup() {

		$this->sections = array(
			'Getting Started' => 'getting-started',
			'Social Locker' => 'social-locker-guide',
			'Sign-In Locker' => 'signin-locker-guide',
			'Creating Social Apps' => array(
				'Creating Facebook App' => 'creating-facebook-app',
				'Creating Twitter App' => 'creating-twitter-app',
				'Getting Google Client ID' => 'creating-google-app',
				'Getting LinkedIn API Key' => 'creating-linkedin-app',
			)
		);
	}
}
