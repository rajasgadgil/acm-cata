<?php
/*
  Plugin Name: CM Product Recommendations
  Plugin URI: https://www.cminds.com/
  Description: Parses posts for defined recommended products and adds the widget with the image, short definition and links.
  Version: 1.0.14
  Author: CreativeMindsSolutions
  Author URI: https://www.cminds.com/
 */

if( !ini_get('max_execution_time') || ini_get('max_execution_time') < 300 )
{
    /*
     * Setup the high max_execution_time to avoid timeouts during lenghty operations like importing big glossaries,
     * or rebuilding related articles index
     */
    ini_set('max_execution_time', 300);
    set_time_limit(300);
}

/**
 * Define Plugin Version
 *
 * @since 1.0
 */
if( !defined('CMCRPR_VERSION') )
{
    define('CMCRPR_VERSION', '1.0.9');
}

/**
 * Define Plugin short name
 *
 * @since 1.0
 */
if( !defined('CMCRPR_SHORTNAME') )
{
    define('CMCRPR_SHORTNAME', 'CM Product Recommendations');
}

/**
 * Define Plugin name
 *
 * @since 1.0
 */
if( !defined('CMCRPR_NAME') )
{
    define('CMCRPR_NAME', 'CM Context Related Product Recommendations');
}

/**
 * Define Plugin canonical name
 *
 * @since 1.0
 */
if( !defined('CMCRPR_CANONICAL_NAME') )
{
    define('CMCRPR_CANONICAL_NAME', 'CM Context Related Product Recommendations');
}

/**
 * Define Plugin license name
 *
 * @since 1.0
 */
if( !defined('CMCRPR_LICENSE_NAME') )
{
    define('CMCRPR_LICENSE_NAME', 'CM Context Related Product Recommendations');
}

/**
 * Define Plugin File Name
 *
 * @since 1.0
 */
if( !defined('CMCRPR_PLUGIN_FILE') )
{
    define('CMCRPR_PLUGIN_FILE', __FILE__);
}

/**
 * Define Plugin release notes url
 *
 * @since 1.0
 */
if( !defined('CMCRPR_RELEASE_NOTES') )
{
    define('CMCRPR_RELEASE_NOTES', 'https://www.cminds.com/release-notes/');
}

/**
 * Define Plugin release notes url
 *
 * @since 1.0
 */
if( !defined('CMCRPR_WORDPRESS_URL') )
{
    define('CMCRPR_WORDPRESS_URL', 'http://wordpress.org/plugins/cm-context-related-product-recommendations/');
}

/**
 * Define Plugin release notes url
 *
 * @since 1.0
 */
if( !defined('CMCRPR_WORDPRESS_REVIEW_URL') )
{
    define('CMCRPR_WORDPRESS_REVIEW_URL', 'http://wordpress.org/support/view/plugin-reviews/cm-context-related-product-recommendations');
}

/**
 * Define Plugin release notes url
 *
 * @since 1.0
 */
if( !defined('CMCRPR_SHOP_URL') )
{
    define('CMCRPR_SHOP_URL', 'https://www.cminds.com/store/cm-context-related-product-recommendations/');
}

include_once plugin_dir_path(__FILE__) . "classes/Base.php";
register_activation_hook(__FILE__, array('CMCRPR_Base', '_install'));
register_activation_hook(__FILE__, array('CMCRPR_Base', '_flush_rewrite_rules'));

CMCRPR_Base::init();