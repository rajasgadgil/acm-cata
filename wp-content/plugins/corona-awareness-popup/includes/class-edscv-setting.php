<?php

/**
 * The class is used to handle the settings page for the plugin 
 * 
 * @since 1.0.0
 */

class EDSCV_Setting {

    const menu_slug = 'edscv-corona-awareness';
    const option_group = 'edscv_setting_group';
    const option_section = 'edscv_setting_section';
    const option_name = 'edscv_setting';    

    public static function get_default_data() {

        $options = array( 'popup_content' => '',
            'popup_image' => '',
            'readmore_text' => 'MORE INFO',
            'readmore_link' => 'https://www.who.int/health-topics/coronavirus',
            'readmore_new_tab'  => 'y',
            'popup_frequency' => 'day',
            'custom_css' => '');

        $options['custom_css'] = ' .eds-corona-image-icon-wrapper{
            display: inline-block;
            text-align: center;
            width: 160px;
            vertical-align: top;
        } 
        .eds-corona-image-icon-wrapper img{
            display: block;
            margin-left: auto;
            margin-right: auto;
            max-width: 70px;
        }'; 

        $options['popup_image'] = plugins_url( 'assets/images/corona-popup-image.jpg', dirname(__FILE__) );
        ob_start();
        include EDSCV_PLUGIN_PATH . 'templates/popup-body.php';
		$options['popup_content'] = ob_get_contents();
		ob_end_clean();
        return $options;
    }

    public static function get_settings()  {
        $data =  (array) get_option( self::option_name, self::get_default_data() );
        return $data;
    }
    
    public function __construct() { }

    /**
     * Add submenu page to the Settings main menu.
     *
     * @return void
     */
    public function add_admin_menu() {

        $page_hook = add_options_page( __( 'Corona Awareness Popup', 'edscv-corona' ), 
        __( 'Corona Awareness', 'edscv-corona' ), 
        'manage_options',  
        self::menu_slug,
        array( $this, 'init_view' ) );       

        if ( $page_hook !== false ) {
            add_action( "admin_print_styles-$page_hook", array( $this, 'print_menu_styles' ) );
		    add_action( "admin_print_scripts-$page_hook", array( $this, 'print_menu_scripts' ) );
        }       

    }

    /**
     * Enqueue  css for the settings page.
     *
     * @return void
     */
    public function print_menu_styles() {

        $option = self::get_settings();        

        // Add the color picker css file       
        wp_enqueue_style( 'wp-color-picker' ); 

        // Custom CSS
        wp_enqueue_style( 'edscv-css', 
            plugins_url( 'assets/css/edscv.css',
            dirname(__FILE__) ) );      
    }

    /**
     * Enqueue scripts for the settings page.
     *
     * @return void
     */
    public function print_menu_scripts() {

        wp_enqueue_media();

        // Include our custom jQuery file
        wp_enqueue_script( 'edscv-admin-js', 
            plugins_url( 'assets/js/edscv.admin.js', dirname(__FILE__) ), 
            array( 'jquery', 'wp-color-picker' ), false, true );
    }

    /**
     * This function initializes the settings page view.
     *
     * @return void
     */
    public function init_view() {             
        ?>
        <div class="wrap">
            <h1><?php _e( 'Corona Awareness Popup', 'edscv-corona' ); ?></h1>
            <form id="edcsv-setting-form" action='options.php' method='post'>       
            <?php
            settings_fields( self::option_group );       
            do_settings_sections( self::option_group ); 
            submit_button();
            ?>
            </form>        
        </div>
        <?php   
    }


    /**
     * This function registers the settings using wordpress settings api.
     *
     * @return void
     */
    public function init_settings() {       

        register_setting( self::option_group, 
            self::option_name,  
            array( 
                'sanitize_callback' => array( $this, 'sanitize_data' )
            ) 
        );

        add_settings_section(
            self::option_section,
            __( 'General Settings', 'edscv-corona' ),
            array( $this, 'render_section' ),
            self::option_group
        );        

        // Popup Content
        add_settings_field(
            'popup_content',
            __( 'Popup Content', 'edscv-corona' ),
            array( $this, 'render_popup_content' ),
            self::option_group,
            self::option_section
        );

        // Popup Image
        add_settings_field(
            'popup_image',
            __( 'Popup Image', 'edscv-corona' ),
            array( $this, 'render_popup_image' ),
            self::option_group,
            self::option_section
        );

        // Readmore Text
        add_settings_field(
            'readmore_text',
            __( 'Button Text', 'edscv-corona' ),
            array( $this, 'render_readmore_text' ),
            self::option_group,
            self::option_section
        );

        // Readmore Link
        add_settings_field(
            'readmore_link',
            __( 'Button Link', 'edscv-corona' ),
            array( $this, 'render_readmore_link' ),
            self::option_group,
            self::option_section
        );

        // Readmore New Tab
        add_settings_field(
            'readmore_new_tab',
            __( 'Open link in new tab', 'edscv-corona' ),
            array( $this, 'render_readmore_new_tab' ),
            self::option_group,
            self::option_section
        );

         // Popup Frequency
        add_settings_field(
            'popup_frequency',
            __( 'Popup Frequency', 'edscv-corona' ),
            array( $this, 'render_popup_frequency' ),
            self::option_group,
            self::option_section
        );

        // Custom Css
        add_settings_field(
            'custom_css',
            __( 'Custom CSS', 'edscv-corona' ),
            array( $this, 'render_custom_css' ),
            self::option_group,
            self::option_section
        );
    }

    /**
     * This function renders text for setting section.
     *
     * @return void
     */
    public function render_section() { }

    /**
     * This function renders field popup_content
     *
     * @return void
     */
    public function render_popup_content() {
		$option = self::get_settings();
		$field_value = $option[ 'popup_content' ];		
        wp_editor($field_value, 'edscv-popup-content', array(
            'textarea_name' => self::option_name . '[popup_content]'
        ));
    }

    /**
     * This function renders field popup_image
     *
     * @return void
     */
    public function render_popup_image() {
		$option = self::get_settings();
		$field_value = $option[ 'popup_image' ];
		?>
		<input   
            id="edscv-popup-image" 
			type="url" 
			name="<?php echo self::option_name . '[popup_image]' ;?>"
			value="<?php echo $field_value; ?>"
		/>
        <a href="#" id="edscv-popup-image-btn" class="button"><?php _e('Select', 'edscv-corona'); ?></a>
		<?php
    }

    /**
     * This function renders field readmore_text
     *
     * @return void
     */
    public function render_readmore_text() {
		$option = self::get_settings();
		$field_value = $option[ 'readmore_text' ];
		?>
		<input 
			type="text" 
			name="<?php echo self::option_name . '[readmore_text]' ;?>"
			value="<?php echo $field_value; ?>"
		/>
		<?php
    }

    /**
     * This function renders field readmore_link
     *
     * @return void
     */
    public function render_readmore_link() {
		$option = self::get_settings();
		$field_value = $option[ 'readmore_link' ];
		?>
		<input 
			type="url" 
			name="<?php echo self::option_name . '[readmore_link]' ;?>"
			value="<?php echo $field_value; ?>"
		/>
		<?php
    }

   
    /**
     * This function renders field readmore_new_tab
     *
     * @return void
     */
    public function render_readmore_new_tab() {
		$option = self::get_settings();
		$field_checked = isset($option[ 'readmore_new_tab' ]) && !empty($option[ 'readmore_new_tab' ]) ? 'checked' : '';
		?>
		<input 
			type="checkbox" 
			name="<?php echo self::option_name . '[readmore_new_tab]' ;?>"
			value="y"  
            <?php echo $field_checked; ?>          
		/>
		<?php
    }

    /**
     * This function renders field popup_frequency
     *
     * @return void
     */
    public function render_popup_frequency() {
		$option = self::get_settings();
		$field_value = $option[ 'popup_frequency' ];
		?>
		<select name="<?php echo self::option_name . '[popup_frequency]' ;?>">
            <option value="always" <?php echo ($field_value == 'always') ? 'selected': ''; ?>><?php _e('Show on every refresh', 'eds-coronoa'); ?></option>
            <option value="day" <?php echo ($field_value == 'day') ? 'selected': ''; ?>><?php _e('Once a day', 'eds-coronoa'); ?></option>
            <option value="week" <?php echo ($field_value == 'week') ? 'selected': ''; ?>><?php _e('Once a week', 'eds-coronoa'); ?></option>
        </select>
		<?php
    }

    
    /**
     * This function renders field custom_css
     *
     * @return void
     */
    public function render_custom_css() {
		$option = self::get_settings();
		$field_value = $option[ 'custom_css' ];
		?>
		<textarea 			
			name="<?php echo self::option_name . '[custom_css]' ;?>"			
            rows="10"
            cols="50"            
            class="edscv-textarea-field"
		><?php echo $field_value; ?></textarea>
		<?php
    }
    
    /**
     * This function sanitize data before saving it
     *
     * @return data
     */
    public function sanitize_data( $data ) {    
        $option = self::get_settings();
        $data['popup_content'] = wp_kses_post( $data['popup_content'] );
        $data['popup_image'] = esc_url_raw( $data['popup_image'] );
        $data['readmore_text'] = sanitize_text_field( $data['readmore_text'] );
        $data['readmore_link'] = esc_url_raw( $data['readmore_link'] );
        $data['readmore_new_tab'] = isset($data['readmore_new_tab']) ? sanitize_text_field($data['readmore_new_tab']) : '';
        $data['popup_frequency'] = sanitize_text_field( $data['popup_frequency'] );        
        $data['custom_css'] = sanitize_textarea_field( $data['custom_css'] );
       
        return $data;
    }

    /**
     * Delete the options related to settings in db, used during deactivation.
     *
     * @return void
     */
    public static function delete_options() {
        delete_option( self::option_name );
    }

    /**
     * Add settings link in plugin list
     */
    function add_plugin_page_settings_link( $links ) { 	
        $links[] = '<a href="' .  
            admin_url( 'options-general.php?page=' . self::menu_slug ) .  '">' .
            __('Settings', 'edscv-corona') . '</a>'; 	
        return $links; 
    }

}