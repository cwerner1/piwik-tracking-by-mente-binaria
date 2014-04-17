<?php
/**
 * @package MB_PiwikTracking\Views\Backend
 */

if ( !defined( 'MB_PIWIKTRACKING_VERSION' ) ) {
	exit;
}

/**
 * Backend options class.
 *
 * Displays the backend interface.
 *
 * @since 1.0.0
 */
abstract class MB_PiwikTracking_ViewBackendSettings {
	/**
	 * Set up Wordpress.
	 *
	 * Prepares Wordpress for the settings page.
	 *
	 * @see MB_PiwikTracking_ControllerBackend::add_settings_link(),MB_PiwikTracking_ControllerBackend::output_settings_page(),MB_PiwikTracking_ControllerBackend::output_settings_section(),MB_PiwikTracking_ControllerBackend::output_settings_field()
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function set_up() {
		add_filter( 'plugin_action_links_' . MB_PIWIKTRACKING_SLUG . '/' . MB_PIWIKTRACKING_SLUG . '.php', array( 'MB_PiwikTracking_ControllerBackend', 'add_settings_link' ) );

		add_options_page( __( 'Piwik tracking settings', 'MB_PiwikTracking' ), __( 'Piwik tracking', 'MB_PiwikTracking' ), 'manage_options', MB_PIWIKTRACKING_SLUG, array( 'MB_PiwikTracking_ControllerBackend', 'output_settings_page' ) );

		add_settings_section( 'general', ''/*__( 'General', 'MB_PiwikTracking' )*/, array( 'MB_PiwikTracking_ControllerBackend', 'output_settings_section' ) , MB_PIWIKTRACKING_SLUG );
		add_settings_field( 'MB_PiwikTracking-enable', __( 'Enable', 'MB_PiwikTracking' ), array( 'MB_PiwikTracking_ControllerBackend', 'output_settings_field' ), MB_PIWIKTRACKING_SLUG, 'general', array( 'label_for' => 'MB_PiwikTracking-enable' ) );
		add_settings_field( 'MB_PiwikTracking-address', __( 'Address', 'MB_PiwikTracking' ), array( 'MB_PiwikTracking_ControllerBackend', 'output_settings_field' ), MB_PIWIKTRACKING_SLUG, 'general', array( 'label_for' => 'MB_PiwikTracking-address' ) );
		add_settings_field( 'MB_PiwikTracking-ssl_compat', __( 'SSL compatibility', 'MB_PiwikTracking' ), array( 'MB_PiwikTracking_ControllerBackend', 'output_settings_field' ), MB_PIWIKTRACKING_SLUG, 'general', array( 'label_for' => 'MB_PiwikTracking-ssl_compat' ) );
		add_settings_field( 'MB_PiwikTracking-site_id', __( 'Site Id', 'MB_PiwikTracking' ), array( 'MB_PiwikTracking_ControllerBackend', 'output_settings_field' ), MB_PIWIKTRACKING_SLUG, 'general', array( 'label_for' => 'MB_PiwikTracking-site_id') );
		add_settings_field( 'MB_PiwikTracking-log_usernames', __( 'Log usernames', 'MB_PiwikTracking' ), array( 'MB_PiwikTracking_ControllerBackend', 'output_settings_field' ), MB_PIWIKTRACKING_SLUG, 'general', array( 'label_for' => 'MB_PiwikTracking-log_usernames' ) );
	}

	/**
	 * Generate settings link.
	 *
	 * Returns the HTML code of the settings link.
	 *
	 * @since 1.0.0
	 *
	 * @param string $url The URL.
	 * @return string The code.
	 */
	public static function get_settings_link( $url ) {
		return '<a href="' . $url . '"> '.__( 'Settings', 'MB_PiwikTracking' ).' </a>';
	}

	/**
	 * Output settings page.
	 *
	 * Outputs the HTML code of the settings page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function output_page() {
?>
<div id="MB_PiwikTracking" class="wrap"><?php screen_icon(); ?>
<h2><?php _e( 'Piwik tracking settings', 'MB_PiwikTracking' ); ?></h2>
<form method="post" action="options.php">
<?php settings_fields( 'MB_PiwikTracking' ); ?>
<?php do_settings_sections( MB_PIWIKTRACKING_SLUG ); ?>
<?php submit_button(); ?>
</form>
</div>
<?php
	}

	/**
	 * Output form fields.
	 *
	 * Depending on the arguments received, outputs a field for the settings form.
	 * It gets the saved options to display the field accordingly.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name The name of the option.
	 * @param mixed $value The value of the option.
	 * @return void
	 */
	public static function output_field( $name, $value ) {
		switch ( $name ) {
			case 'enable':
?>
<input id="MB_PiwikTracking-<?php echo $name; ?>" name="MB_PiwikTracking[<?php echo $name; ?>]" type="checkbox" value="1"<?php echo $value ? ' checked="checked"' : ''; ?> />
<p class="description"><?php _e( 'Enable Piwik tracking?', 'MB_PiwikTracking' ); ?></p>
<?php
				break;
			case 'address':
?>
<input id="MB_PiwikTracking-<?php echo $name; ?>" name="MB_PiwikTracking[<?php echo $name; ?>]" type="text" class="regular-text" value="<?php echo $value; ?>" />
<p class="description"><?php printf( __( 'The address of your Piwik install, without protocol. (e.g. %s/piwik)', 'MB_PiwikTracking' ), $_SERVER["SERVER_NAME"] ); ?></p>
<?php
				break;
			case 'ssl_compat':
?>
<input id="MB_PiwikTracking-<?php echo $name; ?>" name="MB_PiwikTracking[<?php echo $name; ?>]" type="checkbox" value="1"<?php echo $value ? ' checked="checked"' : ''; ?> />
<p class="description"><?php _e( 'Does your Piwik install support SSL access? (HTTP<b>S</b>://)', 'MB_PiwikTracking' ); ?></p>
<?php
				break;
			case 'site_id':
?>
<input id="MB_PiwikTracking-<?php echo $name; ?>" name="MB_PiwikTracking[<?php echo $name; ?>]" type="text" class="regular-text" value="<?php echo $value; ?>" />
<p class="description"><?php _e( 'The id of this site on your Piwik install.', 'MB_PiwikTracking' ); ?></p>
<?php
				break;
			case 'log_usernames':
?>
<input id="MB_PiwikTracking-<?php echo $name; ?>" name="MB_PiwikTracking[<?php echo $name; ?>]" type="checkbox" value="1"<?php echo $value ? ' checked="checked"' : ''; ?> />
<p class="description"><?php _e( 'Do you want Piwik to log the usernames of logged in users?', 'MB_PiwikTracking' ); ?></p>
<?php
				break;
			default:
				break;
		}
	}

	/**
	 * Output error messages.
	 *
	 * Sets Wordpress to display an error message according to the supplied option id.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name The name of the option.
	 * @return void
	 */
	public static function output_error( $name ) {
		switch ( $name ) {
			case 'address':
				add_settings_error( 'MB_PiwikTracking-address', 'invalid-MB_PiwikTracking-address', __( 'The "Address" seems invalid.<br />Please check this field and try again.', 'MB_PiwikTracking' ) );
				break;
			case 'site_id':
				add_settings_error( 'MB_PiwikTracking-site_id', 'invalid-MB_PiwikTracking-site_id', __( '"Site Id" must be an integer number greater than zero.<br />Please check this field and try again.', 'MB_PiwikTracking' ) );
				break;
			default:
				break;
		}
	}
}
