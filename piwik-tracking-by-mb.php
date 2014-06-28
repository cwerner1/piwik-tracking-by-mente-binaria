<?php
/**
 * @package MB_PiwikTracking
 */

/*
Plugin Name: Piwik tracking, by Mente Binaria
Plugin URI: http://www.mentebinaria.com/
Description: Add the Piwik tracking code to your website.
Version: 1.0.6
Author: Mente Binaria
Author URI: http://www.mentebinaria.com/
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

/*
Copyright (C) 2013  Mente Binaria  (email : info@mentebinaria.com)

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 3
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

if ( !function_exists( 'add_action' ) ) {
	exit;
}

// Set some constants required throughout the application.
define( 'MB_PIWIKTRACKING_VERSION', '1.0.5' );
define( 'MB_PIWIKTRACKING_SLUG', 'piwik-tracking-by-mb' );
define( 'MB_PIWIKTRACKING_PATH', realpath( dirname( __FILE__ ) ) );

/**
 * Plugin's main controller class.
 *
 * Coordinates all of the plugin's functioning.
 *
 * @since 1.0.0
 */
abstract class MB_PiwikTracking {
	/**
	 * Initialise plugin.
	 *
	 * Performs all of the required actions for the plugin to work.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function init() {
		// Load the translations.
		load_plugin_textdomain( 'MB_PiwikTracking', false, basename( dirname( __FILE__ ) ) . '/assets/i18n' );

		// Load the controllers.
		require_once( 'controllers/backend.class.php' );
		require_once( 'controllers/frontend.class.php' );

		// Set things up.
		MB_PiwikTracking_ControllerBackend::init();
		MB_PiwikTracking_ControllerFrontend::init();
	}
}

// Load this plugin.
add_action( 'init', array( 'MB_PiwikTracking', 'init') );
