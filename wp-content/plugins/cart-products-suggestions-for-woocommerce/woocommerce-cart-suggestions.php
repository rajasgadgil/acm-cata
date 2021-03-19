<?php
/**
 * Plugin Name: Products Suggestions for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/cart-products-suggestions-for-woocommerce/
 * Description: Displays some additional products for your customers after cart.
 * Version: 1.0.12
 * Author: BeRocket
 * Requires at least: 4.0
 * Author URI: http://berocket.com
 * Text Domain: BeRocket_cart_suggestion_domain
 * Domain Path: /languages/
 * WC tested up to: 3.4.6
 */
define( "BeRocket_cart_suggestion_version", '1.0.12' );
define( "BeRocket_cart_suggestion_domain", 'BeRocket_cart_suggestion_domain'); 
define( "cart_suggestion_TEMPLATE_PATH", plugin_dir_path( __FILE__ ) . "templates/" );
load_plugin_textdomain('BeRocket_cart_suggestion_domain', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
require_once(plugin_dir_path( __FILE__ ).'includes/admin_notices.php');
require_once(plugin_dir_path( __FILE__ ).'includes/functions.php');
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

class BeRocket_cart_suggestion {

    public static $info = array( 
        'id'                    => 11,
        'version'               => BeRocket_cart_suggestion_version,
        'plugin'                => '',
        'slug'                  => '',
        'key'                   => '',
        'name'                  => ''
    );

    /**
     * Defaults values
     */
    public static $defaults = array(
        'display_before_cart_table' => '0',
        'display_after_cart_table'  => '1',
        'display_after_cart_total'  => '0',
        'widget_style'              => 'default',
        'suggestions_title'         => 'Maybe You want something from this...',
        'max_suggestions_count'     => '3',
        'default_suggest'           => array(),
        'category_suggest'          => array(),
        'product_suggest'           => array(),
        'custom_css'                => '',
        'script'                    => array(
            'js_page_load'              => '',
        ),
        'plugin_key'                => '',
    );
    public static $values = array(
        'settings_name' => 'br-cart_suggestion-options',
        'option_page'   => 'br-cart_suggestion',
        'premium_slug'  => 'woocommerce-cart-suggestions',
        'free_slug'     => 'cart-products-suggestions-for-woocommerce',
    );
    
    function __construct () {
        register_uninstall_hook(__FILE__, array( __CLASS__, 'deactivation' ) );
        add_filter( 'BeRocket_updater_add_plugin', array( __CLASS__, 'updater_info' ) );
        add_filter( 'berocket_admin_notices_rate_stars_plugins', array( __CLASS__, 'rate_stars_plugins' ) );

        if ( ( is_plugin_active( 'woocommerce/woocommerce.php' ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) && 
            br_get_woocommerce_version() >= 2.1 ) {
            $options = self::get_option();
            
            add_action ( 'init', array( __CLASS__, 'init' ) );
            add_action ( 'wp_head', array( __CLASS__, 'set_styles' ) );
            add_action ( 'admin_init', array( __CLASS__, 'admin_init' ) );
            add_action ( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue_scripts' ) );
            add_action ( 'admin_menu', array( __CLASS__, 'options' ) );
            add_action( 'current_screen', array( __CLASS__, 'current_screen' ) );
            add_action( "wp_ajax_br_cart_suggestion_settings_save", array ( __CLASS__, 'save_settings' ) );
            add_filter( 'plugin_row_meta', array( __CLASS__, 'plugin_row_meta' ), 10, 2 );
            $plugin_base_slug = plugin_basename( __FILE__ );
            add_filter( 'plugin_action_links_' . $plugin_base_slug, array( __CLASS__, 'plugin_action_links' ) );
            add_filter( 'is_berocket_settings_page', array( __CLASS__, 'is_settings_page' ) );
        }
        add_filter('berocket_admin_notices_subscribe_plugins', array(__CLASS__, 'admin_notices_subscribe_plugins'));
    }

    public static function rate_stars_plugins($plugins) {
        $info = get_plugin_data( __FILE__ );
        self::$info['name'] = $info['Name'];
        $plugin = array(
            'id'            => self::$info['id'],
            'name'          => self::$info['name'],
            'free_slug'     => self::$values['free_slug'],
        );
        $plugins[self::$info['id']] = $plugin;
        return $plugins;
    }

    public static function updater_info ( $plugins ) {
        self::$info['slug'] = basename( __DIR__ );
        self::$info['plugin'] = plugin_basename( __FILE__ );
        self::$info = self::$info;
        $info = get_plugin_data( __FILE__ );
        self::$info['name'] = $info['Name'];
        $plugins[] = self::$info;
        return $plugins;
    }
    public static function admin_notices_subscribe_plugins($plugins) {
        $plugins[] = self::$info['id'];
        return $plugins;
    }
    public static function is_settings_page($settings_page) {
        if( ! empty($_GET['page']) && $_GET['page'] == self::$values[ 'option_page' ] ) {
            $settings_page = true;
        }
        return $settings_page;
    }
    public static function plugin_action_links($links) {
		$action_links = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page='.self::$values['option_page'] ) . '" title="' . __( 'View Plugin Settings', 'BeRocket_products_label_domain' ) . '">' . __( 'Settings', 'BeRocket_products_label_domain' ) . '</a>',
		);
		return array_merge( $action_links, $links );
    }
    public static function plugin_row_meta($links, $file) {
        $plugin_base_slug = plugin_basename( __FILE__ );
        if ( $file == $plugin_base_slug ) {
			$row_meta = array(
				'docs'    => '<a href="http://berocket.com/docs/plugin/'.self::$values['premium_slug'].'" title="' . __( 'View Plugin Documentation', 'BeRocket_products_label_domain' ) . '" target="_blank">' . __( 'Docs', 'BeRocket_products_label_domain' ) . '</a>',
				'premium'    => '<a href="http://berocket.com/product/'.self::$values['premium_slug'].'" title="' . __( 'View Premium Version Page', 'BeRocket_products_label_domain' ) . '" target="_blank">' . __( 'Premium Version', 'BeRocket_products_label_domain' ) . '</a>',
			);

			return array_merge( $links, $row_meta );
		}
		return (array) $links;
    }
    public static function init () {
        $options = self::get_option();
        wp_enqueue_script("jquery");
        if( is_admin() ) {
            wp_register_style( 'font-awesome', plugins_url( 'css/font-awesome.min.css', __FILE__ ) );
            wp_enqueue_style( 'font-awesome' );
        }
        wp_register_style( 'berocket_cart_suggestion_style', plugins_url( 'css/frontend.css', __FILE__ ), "", BeRocket_cart_suggestion_version );
        wp_enqueue_style( 'berocket_cart_suggestion_style' );
        if($options['display_before_cart_table']) {
            add_action( 'woocommerce_before_cart_table', array( __CLASS__, 'after_cart' ) );
        }
        if($options['display_after_cart_table']) {
            add_action( 'woocommerce_after_cart_table', array( __CLASS__, 'after_cart' ) );
        }
        if($options['display_after_cart_total']) {
            add_action( 'woocommerce_after_cart', array( __CLASS__, 'after_cart' ) );
        }
        if( isset($options['display_hooks']) && is_array($options['display_hooks']) ) {
            foreach($options['display_hooks'] as $hook) {
                if( $hook == 'before_the_content' ) {
                    add_filter('the_content', array(__CLASS__, 'before_the_content'));
                } elseif( $hook == 'after_the_content' ) {
                    add_filter('the_content', array(__CLASS__, 'after_the_content'));
                } else {
                    add_action( $hook, array( __CLASS__, 'after_cart' ) );
                }
            }
        }
    }

    public static function before_the_content($content) {
        if( is_main_query() && is_cart() ) {
            ob_start();
            self::after_cart();
            $after_cart = ob_get_clean();
            $content = $after_cart . $content;
        }
        return $content;
    }

    public static function after_the_content($content) {
        if( is_main_query() && is_cart() ) {
            ob_start();
            self::after_cart();
            $after_cart = ob_get_clean();
            $content = $content . $after_cart;
        }
        return $content;
    }

    public static function after_cart() {
        $options = BeRocket_cart_suggestion::get_option();
        $products = self::suggested_products();
        $additional = array();
        ob_start();
        self::print_products($products, $options['widget_style'], true, $additional);
        $products_list = ob_get_clean();
        if( ! empty( $products_list ) ) {
            echo '<div class="br_cart_suggestions_cart">';
            if( isset($options['suggestions_title']) && $options['suggestions_title'] ) {
                echo '<h4>'.$options['suggestions_title'].'</h4>';
            }
            echo $products_list;
            echo '</div>';
        }
        ?>
        <script>
        (function ($){
            $(document).ready( function () {
                $('body').on('added_to_cart',function(){
                    if( $('.br_cart_suggestions_cart').length > 0 ) {
                        $.get(location.href, function(data) {
                            if( $(data).find('.br_cart_suggestions_cart').length > 0 ) {
                                $('.br_cart_suggestions_cart').html($(data).find('.br_cart_suggestions_cart').html());
                            } else {
                                $('.br_cart_suggestions_cart').html('');
                            }
                        });
                    }
                });
            });
        })(jQuery);
        </script>
        <?php
    }

    public static function print_products ( $products, $display_type = false, $add_to_cart = false, $additional = array() ) {
        $options = self::get_option();
        if (! empty($products) ) {
            $args = array(
                'post_type'         => array('product', 'product_variation'),
                'post__in'          => $products,
                'posts_per_page'    => '-1',
                'orderby'           => 'rand'
            );
        } else {
            return;
        }
        global $wp_query;
        $old_query = $wp_query;
        $wp_query = new WP_Query( $args );
        $slider_count_max = 3;
        echo '<style>.br_cart_suggestions .brcs_product{width:'.(100 / ($slider_count_max+1)).'%!important;}</style>';
        ?>
        <div class="br_cart_suggestions">
        <?php
        if ($display_type === false || $display_type == 'default' ) {
            echo '<style>.brcs_products > * {display: inline-block;float:left;}</style>';
            add_filter ( 'post_class', array( __CLASS__, 'product_class' ), 9999, 3 );
            echo '<ul class="brcs_products">';
            $i = 0;

            if (have_posts()) : while (have_posts()) : the_post(); global $product, $post;
                $product = wc_get_product(get_the_ID());
                $post = get_post( get_the_ID() );
                if ( !$product->is_visible() ) continue;
                wc_get_template_part( 'content', 'product' );
                if( $slider_count_max <= $i ) {
                    $i = 0;
                    echo '<div style="clear: both;"></div>';
                } else {
                    $i++;
                }
            endwhile; endif;
            echo '</ul>';
            remove_filter ( 'post_class', array( __CLASS__, 'product_class' ), 9999, 3 );
        } elseif( $display_type == 'image' || $display_type == 'image_title' || $display_type == 'image_title_price' ) {
            ?>
            <ul class="brcs_image">
            <?php
                if (have_posts()) : while (have_posts()) : the_post(); global $product;
                    $product = wc_get_product(get_the_ID());
                    $product_id = br_wc_get_product_id($product);
                    if ( !$product->is_visible() ) continue;
                    echo '<li class="brcs_product"><a href="', get_permalink($product_id), '">', woocommerce_get_product_thumbnail(), ($display_type == 'image_title' ? $product->get_title() : ($display_type == 'image_title_price' ? $product->get_title().' - '.( function_exists('wc_price') ? wc_price( $product->get_price() ) : woocommerce_price( $product->get_price() ) ) : '')), '</a>';
                    if ( $add_to_cart ) {
                        woocommerce_template_loop_add_to_cart();
                    }
                    echo '</li>';
                endwhile; endif;
            ?>
            </ul>
            <?php
        } elseif( $display_type == 'title' || $display_type == 'title_price' ) {
            ?>
            <ul class="brcs_name">
            <?php
                if (have_posts()) : while (have_posts()) : the_post(); global $product;
                    $product = wc_get_product(get_the_ID());
                    $product_id = br_wc_get_product_id($product);
                    if ( !$product->is_visible() ) continue;
                    echo '<li class="brcs_product"><a href="', get_permalink($product_id), '">', ($display_type == 'title' ? $product->get_title() : ($display_type == 'title_price' ? $product->get_title().' - '.( function_exists('wc_price') ? wc_price( $product->get_price() ) : woocommerce_price( $product->get_price() ) ) : '')), '</a>';
                    if ( $add_to_cart ) {
                        woocommerce_template_loop_add_to_cart();
                    }
                    echo '</li>';
                endwhile; endif;
            ?>
            </ul>
            <?php
        }
        ?>
        <div style="clear:both; height:1px;"></div>
        </div>
        <?php
        $wp_query = $old_query;
        wp_reset_query();
    }
    public static function product_class($classes) {
        $classes[] = 'brcs_product';
        return $classes;
    }

    public static function suggested_products($total_count = false) {
        $options = self::get_option();
        if( ! is_numeric( $total_count ) ) {
            $total_count = $options['max_suggestions_count'];
        }
        $suggested_products = array();
        $cart = WC()->cart->get_cart();
        $product_ids = array();
        $categories_ids = array();
        $default_language = apply_filters( 'wpml_default_language', NULL );
        foreach($cart as $cart_item_key => $values) {
            $_product = $values['data'];
            if( $_product->is_type( 'variation' ) ) {
                $product_id = $values['variation_id'];
                $terms = get_the_terms( $product_id, 'product_cat' );
                $product_id = apply_filters( 'wpml_object_id', $product_id, 'product_variation', true, $default_language );
            } else {
                $product_id = $values['product_id'];
                $terms = get_the_terms( $product_id, 'product_cat' );
                $product_id = apply_filters( 'wpml_object_id', $product_id, 'product', true, $default_language );
            }
            if( isset($terms) && is_array($terms) ) {
                foreach( $terms as $term ) {
                    $categories_id = apply_filters( 'wpml_object_id', $term->term_id, 'product_cat', true, $default_language );
                    if( ! in_array( $categories_id, $categories_ids ) ) {
                        $categories_ids[] = $categories_id;
                    }
                }
            }
            $product_ids[] = $product_id;
        }
        if( isset($options['product_suggest']) && is_array($options['product_suggest']) ) {
            foreach( $options['product_suggest'] as $suggest ) {
                if( isset($suggest['product_ids']) && is_array($suggest['product_ids']) && array_intersect($suggest['product_ids'], $product_ids) ) {
                    $suggested_products = self::add_additional_suggest($suggested_products, $total_count, $suggest['products'], $product_ids);
                }
                if($total_count <= count($suggested_products)) {
                    break;
                }
            }
        }
        if($total_count > count($suggested_products)) {
            if( isset($options['category_suggest']) && is_array($options['category_suggest']) ) {
                $new_cat_ids = array();
                foreach( $categories_ids as $category_id ) {
                    if( isset($options['category_suggest'][$category_id]) ) {
                        $new_cat_ids[$options['category_suggest'][$category_id]['position']] = $category_id;
                    }
                }
                ksort ( $new_cat_ids, SORT_NUMERIC );
                foreach( $new_cat_ids as $category_id ) {
                    if( isset($options['category_suggest'][$category_id]['products']) && is_array($options['category_suggest'][$category_id]['products']) ) {
                        $suggested_products = self::add_additional_suggest($suggested_products, $total_count, $options['category_suggest'][$category_id]['products'], $product_ids);
                    }
                    if($total_count <= count($suggested_products)) {
                        break;
                    }
                }
            }
        }
        if($total_count > count($suggested_products)) {
            $suggested_products = self::add_additional_suggest($suggested_products, $total_count, $options['default_suggest'], $product_ids);
        }
        shuffle($suggested_products);
        return $suggested_products;
    }
    
    public static function add_additional_suggest ( $current_array, $needed_count, $added_array, $exist_products ) {
        if( is_array($added_array) ) {
            if( ! isset($exist_products) || ! is_array($exist_products) ) {
                $exist_products = array();
            }
            $added_array = array_diff( $added_array, $current_array, $exist_products );
            $needed_count = $needed_count - count($current_array);
            if( count($added_array) < $needed_count ) {
                $current_array += array_merge($current_array, $added_array);
                $current_array = array_unique( $current_array );
            } else {
                $random = array_rand ( $added_array, $needed_count );
                if( is_array($random) ) {
                    foreach ( $random as $rand ) {
                        $current_array[] = $added_array[$rand];
                    }
                } else {
                    $current_array[] = $added_array[$random];
                }
            }
        }
        return $current_array;
    }
    /**
     * Function set styles in wp_head WordPress action
     *
     * @return void
     */
    public static function set_styles () {
        $options = self::get_option();
        echo '<style>'.$options['custom_css'].'</style>';
    }
    /**
     * Load template
     *
     * @access public
     *
     * @param string $name template name
     *
     * @return void
     */
    public static function br_get_template_part( $name = '' ) {
        $template = '';

        // Look in your_child_theme/woocommerce-cart_suggestion/name.php
        if ( $name ) {
            $template = locate_template( "woocommerce-cart_suggestion/{$name}.php" );
        }

        // Get default slug-name.php
        if ( ! $template && $name && file_exists( cart_suggestion_TEMPLATE_PATH . "{$name}.php" ) ) {
            $template = cart_suggestion_TEMPLATE_PATH . "{$name}.php";
        }

        // Allow 3rd party plugin filter template file from their plugin
        $template = apply_filters( 'cart_suggestion_get_template_part', $template, $name );

        if ( $template ) {
            load_template( $template, false );
        }
    }

    public static function admin_enqueue_scripts() {
        if ( function_exists( 'wp_enqueue_media' ) ) {
            wp_enqueue_media();
        } else {
            wp_enqueue_style( 'thickbox' );
            wp_enqueue_script( 'media-upload' );
            wp_enqueue_script( 'thickbox' );
        }
    }

    /**
     * Function adding styles/scripts and settings to admin_init WordPress action
     *
     * @access public
     *
     * @return void
     */
    public static function admin_init () {
        wp_enqueue_script( 'berocket_cart_suggestion_admin', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ), BeRocket_cart_suggestion_version );
        wp_register_style( 'berocket_cart_suggestion_admin_style', plugins_url( 'css/admin.css', __FILE__ ), "", BeRocket_cart_suggestion_version );
        wp_enqueue_style( 'berocket_cart_suggestion_admin_style' );
        wp_enqueue_script( 'berocket_global_admin', plugins_url( 'js/admin_global.js', __FILE__ ), array( 'jquery' ) );
        wp_localize_script( 'berocket_global_admin', 'berocket_global_admin', array(
            'security' => wp_create_nonce("search-products")
        ) );
    }
    /**
     * Function add options button to admin panel
     *
     * @access public
     *
     * @return void
     */
    public static function options() {
        add_submenu_page( 'woocommerce', __('Cart Suggestions settings', 'BeRocket_cart_suggestion_domain'), __('Cart Suggestions', 'BeRocket_cart_suggestion_domain'), 'manage_options', 'br-cart_suggestion', array(
            __CLASS__,
            'option_form'
        ) );
    }
    /**
     * Function add options form to settings page
     *
     * @access public
     *
     * @return void
     */
    public static function option_form() {
        $plugin_info = get_plugin_data(__FILE__, false, true);
        $paid_plugin_info = self::$info;
        include cart_suggestion_TEMPLATE_PATH . "settings.php";
    }
    /**
     * Function remove settings from database
     *
     * @return void
     */
    public static function deactivation () {
        delete_option( self::$values['settings_name'] );
    }
    public static function save_settings () {
        if( current_user_can( 'manage_options' ) ) {
            if( isset($_POST[self::$values['settings_name']]) ) {
                update_option( self::$values['settings_name'], self::sanitize_option($_POST[self::$values['settings_name']]) );
                echo json_encode($_POST[self::$values['settings_name']]);
            }
        }
        wp_die();
    }

    public static function current_screen() {
        $screen = get_current_screen();
        if(strpos($screen->id, 'br-cart_suggestion') !== FALSE) {
            wp_enqueue_script( 'jquery-ui-sortable' );
        }
    }

    public static function sanitize_option( $input ) {
        $default = self::$defaults;
        $result = self::recursive_array_set( $default, $input );
        return $result;
    }
    public static function recursive_array_set( $default, $options ) {
        $result = array();
        foreach( $default as $key => $value ) {
            if( array_key_exists( $key, $options ) ) {
                if( is_array( $value ) ) {
                    if( is_array( $options[$key] ) ) {
                        $result[$key] = self::recursive_array_set( $value, $options[$key] );
                    } else {
                        $result[$key] = self::recursive_array_set( $value, array() );
                    }
                } else {
                    $result[$key] = $options[$key];
                }
            } else {
                if( is_array( $value ) ) {
                    $result[$key] = self::recursive_array_set( $value, array() );
                } else {
                    $result[$key] = '';
                }
            }
        }
        foreach( $options as $key => $value ) {
            if( ! array_key_exists( $key, $result ) ) {
                $result[$key] = $value;
            }
        }
        return $result;
    }
    public static function get_option() {
        $options = get_option( self::$values['settings_name'] );
        if ( @ $options && is_array ( $options ) ) {
            $options = array_merge( self::$defaults, $options );
        } else {
            $options = self::$defaults;
        }
        return $options;
    }
}

new BeRocket_cart_suggestion;

berocket_admin_notices::generate_subscribe_notice();

/**
 * Creating admin notice if it not added already
 */
if( ! function_exists('BeRocket_generate_sales_2018') ) {
    function BeRocket_generate_sales_2018($data = array()) {
        if( time() < strtotime('-7 days', $data['end']) ) {
            $close_text = 'hide this for 7 days';
            $nothankswidth = 115;
        } else {
            $close_text = 'not interested';
            $nothankswidth = 90;
        }
        $data = array_merge(array(
            'righthtml'  => '<a class="berocket_no_thanks">'.$close_text.'</a>',
            'rightwidth'  => ($nothankswidth+20),
            'nothankswidth'  => $nothankswidth,
            'contentwidth'  => 400,
            'subscribe'  => false,
            'priority'  => 15,
            'height'  => 50,
            'repeat'  => '+7 days',
            'repeatcount'  => 3,
            'image'  => array(
                'local' => plugin_dir_url( __FILE__ ) . 'images/44p_sale.jpg',
            ),
        ), $data);
        new berocket_admin_notices($data);
    }
    BeRocket_generate_sales_2018(array(
        'start'         => 1529532000,
        'end'           => 1530392400,
        'name'          => 'SALE_LABELS_2018',
        'for_plugin'    => array('id' => 18, 'version' => '2.0', 'onlyfree' => true),
        'html'          => 'Save <strong>$20</strong> with <strong>Premium Product Labels</strong> today!
     &nbsp; <span>Get your <strong class="red">44% discount</strong> now!</span>
     <a class="berocket_button" href="https://berocket.com/product/woocommerce-advanced-product-labels" target="_blank">Save $20</a>',
    ));
    BeRocket_generate_sales_2018(array(
        'start'         => 1530396000,
        'end'           => 1531256400,
        'name'          => 'SALE_MIN_MAX_2018',
        'for_plugin'    => array('id' => 9, 'version' => '2.0', 'onlyfree' => true),
        'html'          => 'Save <strong>$20</strong> with <strong>Premium Min/Max Quantity</strong> today!
     &nbsp; <span>Get your <strong class="red">44% discount</strong> now!</span>
     <a class="berocket_button" href="https://berocket.com/product/woocommerce-minmax-quantity" target="_blank">Save $20</a>',
    ));
    BeRocket_generate_sales_2018(array(
        'start'         => 1531260000,
        'end'           => 1532120400,
        'name'          => 'SALE_LOAD_MORE_2018',
        'for_plugin'    => array('id' => 3, 'version' => '2.0', 'onlyfree' => true),
        'html'          => 'Save <strong>$20</strong> with <strong>Premium Load More Products</strong> today!
     &nbsp; <span>Get your <strong class="red">44% discount</strong> now!</span>
     <a class="berocket_button" href="https://berocket.com/product/woocommerce-load-more-products" target="_blank">Save $20</a>',
    ));
}
