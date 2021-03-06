<?php
/**
 * Plugin Name: Imagify
 * Plugin URI: https://wordpress.org/plugins/imagify/
 * Description: Dramaticaly reduce image file sizes without losing quality, make your website load faster, boost your SEO and save money on your bandwidth using Imagify, the new most advanced image optimization tool.
 * Version: 1.7
 * Author: WP Media
 * Author URI: https://wp-media.me/
 * Licence: GPLv2
 *
 * Text Domain: imagify
 * Domain Path: languages
 *
 * Copyright 2017 WP Media
 */

defined( 'ABSPATH' ) || die( 'Cheatin\' uh?' );

// Imagify defines.
define( 'IMAGIFY_VERSION'       , '1.7' );
define( 'IMAGIFY_SLUG'          , 'imagify' );
define( 'IMAGIFY_FILE'          , __FILE__ );
define( 'IMAGIFY_PATH'          , realpath( plugin_dir_path( IMAGIFY_FILE ) ) . '/' );
define( 'IMAGIFY_INC_PATH'      , realpath( IMAGIFY_PATH . 'inc/' ) . '/' );
define( 'IMAGIFY_ADMIN_PATH'    , realpath( IMAGIFY_INC_PATH . 'admin' ) . '/' );
define( 'IMAGIFY_COMMON_PATH'   , realpath( IMAGIFY_INC_PATH . 'common' ) . '/' );
define( 'IMAGIFY_FUNCTIONS_PATH', realpath( IMAGIFY_INC_PATH . 'functions' ) . '/' );
define( 'IMAGIFY_CLASSES_PATH'  , realpath( IMAGIFY_INC_PATH . 'classes' ) . '/' );
define( 'IMAGIFY_3RD_PARTY_PATH', realpath( IMAGIFY_INC_PATH . '3rd-party' ) . '/' );
define( 'IMAGIFY_URL'           , plugin_dir_url( IMAGIFY_FILE ) );
define( 'IMAGIFY_INC_URL'       , IMAGIFY_URL . 'inc/' );
define( 'IMAGIFY_ADMIN_URL'     , IMAGIFY_INC_URL . 'admin/' );
define( 'IMAGIFY_ASSETS_URL'    , IMAGIFY_URL . 'assets/' );
define( 'IMAGIFY_ASSETS_JS_URL' , IMAGIFY_ASSETS_URL . 'js/' );
define( 'IMAGIFY_ASSETS_CSS_URL', IMAGIFY_ASSETS_URL . 'css/' );
define( 'IMAGIFY_ASSETS_IMG_URL', IMAGIFY_ASSETS_URL . 'images/' );
define( 'IMAGIFY_MAX_BYTES'     , 5242880 );
define( 'IMAGIFY_INT_MAX'       , PHP_INT_MAX - 30 );

add_action( 'plugins_loaded', '_imagify_init' );
/**
 * Tell WP what to do when plugin is loaded.
 *
 * @since 1.0
 */
function _imagify_init() {
	// Load translations.
	load_plugin_textdomain( 'imagify', false, dirname( plugin_basename( IMAGIFY_FILE ) ) . '/languages/' );

	// Nothing to do if autosave.
	if ( defined( 'DOING_AUTOSAVE' ) ) {
		return;
	}

	require( IMAGIFY_FUNCTIONS_PATH . 'compat.php' );
	require( IMAGIFY_FUNCTIONS_PATH . 'deprecated.php' );
	require( IMAGIFY_FUNCTIONS_PATH . 'common.php' );

	// Register classes.
	spl_autoload_register( 'imagify_autoload' );

	require( IMAGIFY_FUNCTIONS_PATH . 'options.php' );
	require( IMAGIFY_FUNCTIONS_PATH . 'formatting.php' );
	require( IMAGIFY_FUNCTIONS_PATH . 'files.php' );
	require( IMAGIFY_FUNCTIONS_PATH . 'admin.php' );
	require( IMAGIFY_FUNCTIONS_PATH . 'api.php' );
	require( IMAGIFY_FUNCTIONS_PATH . 'attachments.php' );
	require( IMAGIFY_FUNCTIONS_PATH . 'process.php' );
	require( IMAGIFY_FUNCTIONS_PATH . 'admin-ui.php' );
	require( IMAGIFY_FUNCTIONS_PATH . 'admin-stats.php' );
	require( IMAGIFY_FUNCTIONS_PATH . 'i18n.php' );
	require( IMAGIFY_FUNCTIONS_PATH . 'partners.php' );
	require( IMAGIFY_COMMON_PATH . 'attachments.php' );
	require( IMAGIFY_COMMON_PATH . 'admin-bar.php' );
	require( IMAGIFY_COMMON_PATH . 'partners.php' );
	require( IMAGIFY_3RD_PARTY_PATH . '3rd-party.php' );

	Imagify_Options::get_instance()->init();
	Imagify_Data::get_instance()->init();
	Imagify_Folders_DB::get_instance()->init();
	Imagify_Files_DB::get_instance()->init();
	Imagify_Cron_Library_Size::get_instance()->init();
	Imagify_Cron_Rating::get_instance()->init();
	Imagify_Cron_Sync_Files::get_instance()->init();

	if ( is_admin() ) {
		require( IMAGIFY_ADMIN_PATH . 'upgrader.php' );
		require( IMAGIFY_ADMIN_PATH . 'heartbeat.php' );
		require( IMAGIFY_ADMIN_PATH . 'upload.php' );
		require( IMAGIFY_ADMIN_PATH . 'media.php' );
		require( IMAGIFY_ADMIN_PATH . 'meta-boxes.php' );
		require( IMAGIFY_ADMIN_PATH . 'custom-folders.php' );

		Imagify_Notices::get_instance()->init();
		Imagify_Admin_Ajax_Post::get_instance()->init();
		Imagify_Settings::get_instance()->init();
		Imagify_Views::get_instance()->init();
	}

	if ( ! wp_doing_ajax() ) {
		Imagify_Assets::get_instance()->init();
	}

	/**
	* Fires when Imagify is correctly loaded.
	*
	* @since 1.0
	*/
	do_action( 'imagify_loaded' );
}
