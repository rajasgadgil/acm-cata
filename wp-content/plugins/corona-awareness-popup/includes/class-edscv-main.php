<?php

/**
 * The core plugin class.
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks and other important functions
 * 
 * @since 1.0.0
 */

class EDSCV_Main {

    public function __construct() {
        $this->load_dependencies();
    }

    private function load_dependencies() { 

        // Initialize settings page
        require_once EDSCV_PLUGIN_PATH . 'includes/class-edscv-setting.php';

    }

    /**
     * Function that will initialize all the actions and fitler hooks
     *
     * @return void
     */
    public function run() {
        // set locale
        add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
        
        // Settings related actions
        $edscv_setting = new EDSCV_Setting();
        add_action( 'admin_menu', array( $edscv_setting, 'add_admin_menu' ) );
        add_action( 'admin_init', array( $edscv_setting, 'init_settings' ) );
        add_filter( 'plugin_action_links_' . EDSCV_PLUGIN_BASENAME, array( $edscv_setting, 'add_plugin_page_settings_link' ) );

        // Enqueue frontend scripts and styles
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_scripts_and_styles' ) );
    }

    /**
     * Function to load plugin text domain for localization and translation
     *
     * @return void
     */
    public function load_plugin_textdomain() {
        
        load_plugin_textdomain(
            'edscv-corona',
            false,
            EDSCV_PLUGIN_PATH . 'languages'
        );

    }

    /**
     * Function to add css and js in public side of the website
     * 
     * @return void
     */
    public function enqueue_public_scripts_and_styles() {
 
        $settings = EDSCV_Setting::get_settings();
        
        // Custom CSS
        wp_enqueue_style( 'edscv-css', 
            plugins_url( 'assets/css/edscv.css', dirname(__FILE__) ) );      

        wp_add_inline_style( 'edscv-css', $settings['custom_css'] );

        // Enquue Scripts
        wp_enqueue_script( 'edscv-site-js', 
            plugins_url( 'assets/js/edscv.site.js', dirname(__FILE__) ),
            array( 'jquery' ),
            false, true ); 

        // Localize Data 
        wp_localize_script( 'edscv-site-js', 'edscv_obj', array( 
            'settings'	=> $settings
        ));

    }
}