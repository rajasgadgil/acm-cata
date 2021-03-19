<?php
/*
Plugin Name: WP CSS Dropdown Menu JS add-on
Plugin URI: http://www.zackdesign.biz/category/wp-plugins/css-dropdown-menu
Description: A complementary javascript plugin to the CSS dropdown menu. This uses a different stylesheet found in <plugin folder>/js/superfish/css/superfish.css
Version: 4.0.2
Author: Isaac Rowntree
Author URI: http://www.zackdesign.biz

*/
   
   add_action('init', 'includeSuperfish');
   
   function includeSuperfish()
   {
	wp_deregister_style('wp-css-dropdown-menu-style');
	wp_enqueue_script('superfish',plugins_url('/js/superfish/js/superfish.js', __FILE__), array('jquery'));
	wp_enqueue_script('superfish-hover',plugins_url('/js/superfish/js/hoverIntent.js', __FILE__), array('jquery'));
	
	// Show superfish.css depending on what's available
        if (file_exists(TEMPLATEPATH.'/superfish.css'))
            wp_enqueue_style('superfish', get_bloginfo('template_directory').'/superfish.css');
        else
            wp_enqueue_style('superfish',plugins_url('/js/superfish/css/superfish.css', __FILE__));
	
	update_option('wp_css_menu_jclass', 'sf-menu');
	
	wp_enqueue_script('start-superfish',plugins_url('/js/start-superfish.js', __FILE__));
   }
   
   register_deactivation_hook( __FILE__, 'includeSuperfish_deactivate' );

   function includeSuperfish_deactivate()
   {
	delete_option('wp_css_menu_jclass');
   }
   
   ?>
