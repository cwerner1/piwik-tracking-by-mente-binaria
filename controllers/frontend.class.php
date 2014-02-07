<?php
/**
 * @package MB_PiwikTracking\Controllers\Frontend
 */

if ( !defined( 'MB_PIWIKTRACKING_VERSION' ) ) {
	exit;
}

/**
 * Frontend controller class.
 *
 * Controls the frontend interface.
 *
 * @since 1.0.1
 */
abstract class MB_PiwikTracking_ControllerFrontend {
	/**
	 * Set up.
	 *
	 * Performs all of the required actions for the frontend to work.
	 *
	 * @see output_code()
	 *
	 * @since 1.0.1
	 *
	 * @return void
	 */
	public static function init() {
		// If the required files exist and the current user is admin, prepare Wordpress.
		if ( is_file( MB_PIWIKTRACKING_PATH . '/models/options.class.php' )
				&& is_file( MB_PIWIKTRACKING_PATH . '/views/frontend/script.class.php' ) ) {
			add_action( 'wp_footer', array( __CLASS__, 'output_code' ), 9999 );
		}
	}

	/**
	 * Output the code.
	 *
	 * Gets the stored options and outputs the script.
	 *
	 * @since 1.0.1
	 *
	 * @return void
	 */
	public static function output_code() {
		// Load the model.
		require_once( MB_PIWIKTRACKING_PATH . '/models/options.class.php' );
		// Load the view.
		require_once( MB_PIWIKTRACKING_PATH . '/views/frontend/script.class.php' );

		// Get the stored options.
		$options = MB_PiwikTracking_ModelOptions::get_options();
		// If the plugin is enabled, display the code.
		if ( $options['enable'] ) {
			MB_PiwikTracking_ViewFrontendScript::output( $options );
		}
	}
}
