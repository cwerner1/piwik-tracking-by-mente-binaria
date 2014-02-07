<?php
/**
 * @package MB_PiwikTracking\Controllers\Backend
 */

if ( !defined( 'MB_PIWIKTRACKING_VERSION' ) ) {
	exit;
}

/**
 * Backend controller class.
 *
 * Controls the backend interface.
 *
 * @since 1.0.1
 */
abstract class MB_PiwikTracking_ControllerBackend {
	/**
	 * Initialise backend.
	 *
	 * Performs all of the required actions for the backend to work.
	 *
	 * @see set_up()
	 *
	 * @since 1.0.1
	 *
	 * @return void
	 */
	public static function init() {
		// If the required files exist and the current user is admin, prepare Wordpress.
		if ( is_file( MB_PIWIKTRACKING_PATH . '/models/options.class.php' )
				&& is_file( MB_PIWIKTRACKING_PATH . '/views/backend/settings.class.php' )
				&& is_admin() ) {
			add_action( 'admin_menu', array( __CLASS__, 'set_up' ) );
		}
	}

	/**
	 * Set up backend.
	 *
	 * Does the necessary Wordpress configurations.
	 *
	 * @see MB_PiwikTracking_ModelOptions::set_up(),MB_PiwikTracking_ViewBackendSettings::set_up()
	 *
	 * @since 1.0.1
	 *
	 * @return void
	 */
	public static function set_up() {
		// Load the model.
		require_once( MB_PIWIKTRACKING_PATH . '/models/options.class.php' );
		// Load the view.
		require_once( MB_PIWIKTRACKING_PATH . '/views/backend/settings.class.php' );

		MB_PiwikTracking_ModelOptions::set_up();
		MB_PiwikTracking_ViewBackendSettings::set_up();
	}

	/**
	 * Sanitize options.
	 *
	 * Checks and corrects the supplied options. Displays any errors found.
	 *
	 * @see MB_PiwikTracking_ModelOptions::sanitize_options(),MB_PiwikTracking_ViewBackendSettings::output_error()
	 *
	 * @since 1.0.1
	 *
	 * @param array $options The array of options to sanitize.
	 * @return array The sanitized options.
	 */
	public static function sanitize_options( $options ) {
		// Load the model.
		require_once( MB_PIWIKTRACKING_PATH . '/models/options.class.php' );
		// Load the view.
		require_once( MB_PIWIKTRACKING_PATH . '/views/backend/settings.class.php' );

		$errors = MB_PiwikTracking_ModelOptions::sanitize_options( $options );

		foreach( $errors as &$error ) {
			MB_PiwikTracking_ViewBackendSettings::output_error( $error );
		}

		return $options;
	}

	/**
	 * Add settings link.
	 *
	 * Adds the settings link to the list of links displayed in the plugins page.
	 *
	 * @see MB_PiwikTracking_ViewBackendSettings::get_settings_link()
	 *
	 * @since 1.0.1
	 *
	 * @param array $links The original plugin links.
	 * @return array The updated plugin links.
	 */
	public static function add_settings_link( $links ) {
		// Load the view.
		require_once( MB_PIWIKTRACKING_PATH . '/views/backend/settings.class.php' );

		$link = MB_PiwikTracking_ViewBackendSettings::get_settings_link( admin_url( 'options-general.php?page=' . MB_PIWIKTRACKING_SLUG ) );
		array_unshift( $links, $link );

		return $links;
	}

	/**
	 * Output settings page.
	 *
	 * Outputs the HTML code of the settings page.
	 *
	 * @see MB_PiwikTracking_ViewBackendSettings::output_page()
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function output_settings_page() {
		// Load the view.
		require_once( MB_PIWIKTRACKING_PATH . '/views/backend/settings.class.php' );

		MB_PiwikTracking_ViewBackendSettings::output_page();
	}

	/**
	 * Output section header code.
	 *
	 * Outputs the HTML code to display at the top of the General section.
	 * Though required, it is useless for the time being - but it may still have some use in the future.
	 *
	 * @since 1.0.1
	 *
	 * @return void
	 */
	public static function output_settings_section() {
	}

	/**
	 * Output form fields.
	 *
	 * Depending on the arguments received, outputs a field for the settings form.
	 * It gets the saved options to display the field accordingly.
	 *
	 * @see MB_PiwikTracking_ModelOptions::get_options(),MB_PiwikTracking_ViewBackendSettings::output_field()
	 *
	 * @since 1.0.1
	 *
	 * @param array $args {
	 *     An array of arguments.
	 *     @type type 'id' The name of the option.
	 *                     Accepts 'enable', 'address', 'ssl_compat', 'site_id'.
	 *     @type type 'label_for' The unique id of the option's field.
	 *                            Accepts 'MB_PiwikTracking-enable', 'MB_PiwikTracking-address', 'MB_PiwikTracking-ssl_compat', 'MB_PiwikTracking-site_id'.
	 * }
	 * @return void
	 */
	public static function output_settings_field( $args ) {
		// Load the model.
		require_once( MB_PIWIKTRACKING_PATH . '/models/options.class.php' );
		// Load the view.
		require_once( MB_PIWIKTRACKING_PATH . '/views/backend/settings.class.php' );

		$name = array_key_exists( 'id', $args ) ? $args['id'] : str_replace( 'MB_PiwikTracking-', '', $args['label_for'] );
		$value = MB_PiwikTracking_ModelOptions::get_option( $name );
		MB_PiwikTracking_ViewBackendSettings::output_field( $name, $value );
	}
}
