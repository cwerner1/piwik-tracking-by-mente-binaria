<?php
/**
 * @package MB_PiwikTracking\Models
 */

if ( !defined( 'MB_PIWIKTRACKING_VERSION' ) ) {
	exit;
}

/**
 * Options model class.
 *
 * Handles all option-related operations.
 *
 * @since 1.0.0
 */
abstract class MB_PiwikTracking_ModelOptions {
	/**
	 * Set up Wordpress.
	 *
	 * Prepares Wordpress' options subsystem.
	 *
	 * @see sanitize_address()
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function set_up() {
		add_option( 'MB_PiwikTracking', array(
			'enable' => false,
			'address' => $_SERVER["SERVER_NAME"] . '/piwik',
			'ssl_compat' => false,
			'site_id' => 0,
			'log_usernames' => false
			) , '', 'no' );
		register_setting( 'MB_PiwikTracking', 'MB_PiwikTracking', array( 'MB_PiwikTracking_ControllerBackend', 'sanitize_options' ) );
	}

	/**
	 * Get saved options.
	 *
	 * Gets the options stored in Wordpress.
	 *
	 * @since 1.0.0
	 *
	 * @return array The stored options.
	 */
	public static function get_options() {
		$options = get_option( 'MB_PiwikTracking' );
		return $options;
	}

	/**
	 * Get saved option.
	 *
	 * Gets one of the options stored in Wordpress.
	 *
	 * @see get_options()
	 *
	 * @since 1.0.1
	 *
	 * @param string $name The name of the option to retrieve.
	 * @return mixed The value, or 'null' if the option doesn't exist.
	 */
	public static function get_option( $name ) {
		$options = get_option( 'MB_PiwikTracking' );
		if ( array_key_exists( $name, $options ) ) {
			return $options[$name];
		}
		return null;
	}

	/**
	 * Sanitize the new options.
	 *
	 * Checks the supplied options for errors.
	 * If an option is not valid, its value is replaced with the previously stored one.
	 *
	 * @see get_options()
	 *
	 * @since 1.0.0
	 *
	 * @param array $new_options {
	 *     The array of options to sanitize.
	 *     @type bool 'enable' Enable the plugin or not.
	 *     @type string 'address' Address of the Piwik install to use.
	 *     @type bool 'ssl_compat' Is Piwik compatible SSL access or not.
	 *     @type int 'site_id' The id of this site in Piwik's configuration.
	 *                         Accepts any integer number greater than zero.
	 * }
	 * @return array A list of found errors.
	 */
	public static function sanitize_options( &$new_options ) {
		$old_options = static::get_options();
		$errors = array();

		static::sanitize_checkbox( $new_options['enable'] );
		if ( !static::sanitize_address( $new_options['address'] ) ) {
			$new_options['address'] = $old_options['address'];
			$errors[] = 'address';
		}
		static::sanitize_checkbox( $new_options['ssl_compat'] );
		if ( !static::sanitize_id( $new_options['site_id'] ) ) {
			$new_options['site_id'] = $old_options['site_id'];
			$errors[] = 'site_id';
		}
		static::sanitize_checkbox( $new_options['log_usernames'] );

		return $errors;
	}

	/**
	 * Sanitize checkbox.
	 *
	 * Converts value to its boolean equivalent.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value The value to sanitize.
	 *
	 * @return bool Always returns 'true'.
	 */
	public static function sanitize_checkbox( &$value ) {
		$value = (bool) $value;
		return true;
	}

	/**
	 * Sanitize address.
	 *
	 * Converts value to a valid URL - but removes its protocol.
	 *
	 * @since 1.0.0
	 *
	 * @return bool Returns 'true' if the value is a valid URL (without protocol) after sanitization, or 'false' if otherwise.
	 */
	public static function sanitize_address( &$value ) {
		// remove whitespace
		$value = preg_replace( '{[\s]*}', '', $value );
		// discard protocol
		$value = preg_replace( '{^https?://}i', '', $value, 1 );
		// discard port
		//preg_replace( '{:[0-9]+/?}', '', $value, 1 );
		// remove invalid characters
		$value = preg_replace( '{[^a-zA-Z0-9\-_/.:#?&%]*}', '', $value );
		// remove trailing slash
		$value = preg_replace( '{/$}', '', $value );

		// check length and format of address
		if ( strlen( $value ) < 6 || !preg_match( '{^localhost|([a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+)(:[0-9]{1,5})?(/[a-zA-Z0-9\-_.#?&%]*)*$}', $value ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Sanitize id.
	 *
	 * Converts value to a valid integer number.
	 *
	 * @since 1.0.0
	 *
	 * @return bool Returns 'true' if after sanitization the value is an integer number greater than zero, or 'false' if otherwise.
	 */
	public static function sanitize_id( &$value ) {
		// remove invalid characters
		$value = preg_replace( '$[^0-9]*$', '', $value );
		$value = (int) $value;

		if ( $value < 1 ) {
			return false;
		}
		return true;
	}

	/**
	 * Get extra data.
	 *
	 * Get any extra required data.
	 *
	 * @since 1.0.4
	 *
	 * @return array The data.
	 */
	public static function get_data() {
		$data = array();
		if ( static::get_option( 'log_usernames' ) ) {
			if ( is_user_logged_in() ) {
				$currentUser = wp_get_current_user();
				$data['username'] = $currentUser->user_nicename;
			}
			// if user isn't logged in or something went wrong
			if ( !isset( $data['username'] ) ) {
				$data['username'] = '_unknown';
			}
		}
		return $data;
	}
}
