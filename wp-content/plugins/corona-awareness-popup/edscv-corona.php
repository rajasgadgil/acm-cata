<?php
/**
 * Plugin Name: Corona Awareness Popup
 * Text Domain: edscv-corona
 * Domain Path: /languages
 * Description: Plugin to make people aware of corona virus using simple popup
 * Version: 1.0.0
 * Author: eLEOPARD Design Studios 
 * Author URI: https://www.eleopard.in/
 * License: GNU General Public License version 2 or later; see LICENSE.txt
 *  http://www.gnu.org/copyleft/gpl.html GNU/GPL
    (C) 2014 Cloud Custom Solutions. All rights reserved
   
   	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2 or later, as 
	published by the Free Software Foundation.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	
	or see <http://www.gnu.org/licenses/>.
	* For any other query please contact us at contact[at]eleopard[dot]in
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Constants related to plugin and will be used through out the plugin code
 */
define( 'EDSCV_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'EDSCV_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );


/**
 * The code that runs during plugin activation.
 */
function edscv_activate() {
    global $wp_version;

    // Check if the current user is allowed to activate plugins
    if ( ! current_user_can( 'activate_plugins' ) ) {
        // Deactivate the plugin.
        deactivate_plugins( plugin_basename( __FILE__ ) );
        $error_message = __( 'You do not have proper authorization to activate a plugin!', 'edscv-corona' );
        die( esc_html( $error_message ) );
    }

    // Check for php version
    if ( version_compare( PHP_VERSION, '5.6.0', '<' ) ) {
        // Deactivate the plugin.
        deactivate_plugins( plugin_basename( __FILE__ ) );
        // Throw an error in the WordPress admin console.
        $error_message = __( 'This plugin requires php version >= 5.6.0', 'edscv-corona' );
        die( esc_html( $error_message ) );
    }

    // Check for WordPress version		 
    if ( version_compare( $wp_version, '4.6.0', '<' ) ) {
        // Deactivate the plugin.
        deactivate_plugins( plugin_basename( __FILE__ ) );
        // Throw an error in the WordPress admin console.
        $error_message = __( 'This plugin requires WordPress version >= 4.6.0', 'edscv-corona' );
        die( esc_html( $error_message ) );
    }

}
register_activation_hook( __FILE__, 'edscv_activate' );

/**
 * The code that runs during plugin deactivation. 
 */
function edscv_deactivate() {
    // Delete options related to settings
    require_once EDSCV_PLUGIN_PATH . 'includes/class-edscv-setting.php';
    EDSCV_Setting::delete_options();
}
register_deactivation_hook( __FILE__, 'edscv_deactivate' );
 

/**
 * Initialize the core plugin class
 */
require EDSCV_PLUGIN_PATH . 'includes/class-edscv-main.php';

/**
 * Begins execution of the plugin. 
 */
function edscv_run() {

	$plugin = new EDSCV_Main();
	$plugin->run();

}
edscv_run();


