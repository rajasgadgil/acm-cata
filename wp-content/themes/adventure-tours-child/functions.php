<?php

/**
 * Includes 'style.css'.
 * Disable this filter if you don't use child style.css file.
 *
 * @param  assoc $default_set set of styles that will be loaded to the page
 * @return assoc
 */
function filter_adventure_tours_get_theme_styles($default_set) {
    $default_set['child-style'] = get_stylesheet_uri();
    return $default_set;
}

add_filter('get-theme-styles', 'filter_adventure_tours_get_theme_styles');

add_filter('woocommerce_variable_sale_price_html', 'wc_wc20_variation_price_format', 10, 2);
add_filter('woocommerce_variable_price_html', 'wc_wc20_variation_price_format', 10, 2);

function wc_wc20_variation_price_format($price, $product) {
    // Main Price
    $prices = array($product->get_variation_price('min', true), $product->get_variation_price('max', true));
    $price = $prices[0] !== $prices[1] ? sprintf(__('Semaine &agrave; partir de </ br> %1$s', 'woocommerce'), wc_price($prices[0])) : wc_price($prices[0]);

    // Sale Price
    $prices = array($product->get_variation_regular_price('min', true), $product->get_variation_regular_price('max', true));
    sort($prices);
    $saleprice = $prices[0] !== $prices[1] ? sprintf(__('Semaine &agrave; partir de </ br> %1$s', 'woocommerce'), wc_price($prices[0])) : wc_price($prices[0]);

    if ($price !== $saleprice) {
        $price = '<del>' . $saleprice . '</del> <ins>' . $price . '</ins>';
    }

    return $price;
}

//Remove inquiry hook
add_action('init', 'atdoor_remove_class_action', 99);

function atdoor_remove_class_action() {
    global $quoteup;
    if (!empty($quoteup->wcCart)) {
        remove_action('woocommerce_before_calculate_totals', array($quoteup->wcCart, 'addPrice'));
    }
}

//Action to add support for inquiry quate price
add_action('woocommerce_cart_loaded_from_session', 'atdoor_get_cart_from_session', 98, 1);

function atdoor_get_cart_from_session($cart) {
return;
    global $quoteup, $woocommerce, $wpdb;

    $quotationProducts = $quoteup->wcCartSession->get('quotationProducts');

    $enq_id = $quotationProducts[0]['enquiry_id'];
    $versionTbl = $wpdb->prefix . 'enquiry_quotation_version';
    $sql = $wpdb->get_row($wpdb->prepare("SELECT product_id FROM $versionTbl WHERE enquiry_id = %d AND variation_index_in_enquiry = %d ORDER BY version", $enq_id, 0), ARRAY_A);
    $first_variation_pro_id = $sql['product_id'];

    if (!empty($quotationProducts[0]['enquiry_id'])) {

        $enquiry_id = $quotationProducts[0]['enquiry_id'];
        if (empty($woocommerce->cart->applied_coupons)) {
            //Get coupon codes
            $meta_tbl = $wpdb->prefix . 'enquiry_meta';
            $coupon_mkey = '_coupon_code_id';
            $discounted_coupons = $wpdb->get_col($wpdb->prepare("SELECT meta_value FROM $meta_tbl WHERE meta_key = '%s' AND enquiry_id = %d", $coupon_mkey, $enquiry_id));
            $discounted_coupons = !empty($discounted_coupons[0]) ? maybe_unserialize($discounted_coupons[0]) : '';

            if (!empty($discounted_coupons)) {
                $_coupon = new WC_Coupon($discounted_coupons[0]);
                $woocommerce->cart->add_discount($_coupon->code);
                //$woocommerce->show_messages();
            }
        }
    }
    if (sizeof($cart->cart_contents) > 0 && $quotationProducts) {
        foreach ($cart->cart_contents as $cart_item_key => $cart_item) {

            if ($cart_item['product_id'] != $first_variation_pro_id) {
                $woocommerce->cart->remove_cart_item($cart_item_key);
            }

            foreach ($quotationProducts as $row) {

                $variations = unserialize($row['variation']);
                $newVariation = array();
                // if ($variations !="" || !empty($variations)) {
                if (!empty($variations)) {
                    foreach ($variations as $attributeName => $attributeValue) {
                        $newVariation['attribute_' . trim($attributeName)] = trim($attributeValue);
                    }

                    if ($cart_item['product_id'] == $row['product_id'] && $cart_item['variation_id'] == $row['variation_id'] && $cart_item['variation'] === $newVariation) {
                        $cart_item['data']->set_price($row['newprice']);
                    }
                } else {
                    if ($cart_item['product_id'] == $row['product_id']) {
                        $cart_item['data']->set_price($row['newprice']);
                    }
                }
            }
        }
    }
}

//Filter to change array combinations
add_filter('quoteup_variation_array_combinations', 'atdoor_variation_array_combinations', 10, 2);

function atdoor_variation_array_combinations($result, $arrays) {
    $result = array();
    foreach ($arrays as $property => $property_values) {
        foreach ($property_values as $property_value) {
            $result[] = array($property => $property_value);
        }
    }

    return $result;
}

//Filter to allow check cart content counts
add_filter('quoteup_cart_contents_count_check', 'quoteup_cart_contents_count_check', 10, 1);

function quoteup_cart_contents_count_check($check) {
    if (!empty(WC()->cart)) {
        return true;
    }
    return $check;
}

//Filter to mannual text translation
add_filter('gettext', 'quoteup_text_translation', 10, 3);

function quoteup_text_translation($translated_text, $text, $domain) {

    //Fixed translation
    $need_translation = array(
        'Product Quantity' => array(
            'FR' => 'Passagers',
            'DE' => 'Anzahl der Passagiere',
            'EN' => 'Number of passengers',
        ),
        'characters remaining' => array(
            'FR' => 'caract&#232;res restants',
            'DE' => 'Zeichen &#252;brig',
        ),
    );

    //Get need to translation
    $need_like_text_translation = array(
        'Your Quotation Session has started. Hence, you cannot add any more products. To end the session' => array(
            'FR' => 'Vous etes sur la page d&#233;di&#233;e &#224; la validation du devis. Vous ne pouvez plus ajouter de produits. Pour mettre fin &#224; la session',
            'DE' => 'Ihre Zitat-Session hat begonnen. Daher k&#246;nnen Sie keine weiteren Produkte hinzuf&#252;gen. Um die Sitzung zu beenden, wird'
        ),
    );

    //Get current language
    $language_code = strtoupper(ICL_LANGUAGE_CODE);

    //Translate fixed
    if (!empty($need_translation[$text][$language_code])) {
        $translated_text = $need_translation[$text][$language_code];
    }

    //Check and translate each sentances
    foreach ($need_like_text_translation as $like_text => $like_trans) {
        if (strpos($text, $like_text) != false && !empty($like_trans[$language_code])) {
            $translated_text = str_replace($like_text, $like_trans[$language_code], $text);
            break;
        }
    }

    return $translated_text;
}

// Etape 1 - crÃ©ez une page et rÃ©cupÃ©rez son ID
// Etape 2 - collez l'ensemble de ces fonctions dans votre fichier functions.php
// Dans cet exemple, fiches techniques est la page ainsi que le custom endpoint
// Ce code va gÃ©nÃ©rer un nouveau endpoint nommÃ© fiches techniques dans l'interface du compte client 

function custom_wc_end_point() {
    if (class_exists('WooCommerce')) {
        add_rewrite_endpoint('infos', EP_ROOT | EP_PAGES);
    }
}

add_action('init', 'custom_wc_end_point');

function custom_endpoint_query_vars($vars) {
    $vars[] = 'infos';
    return $vars;
}

add_filter('query_vars', 'custom_endpoint_query_vars', 0);

function ac_custom_flush_rewrite_rules() {
    flush_rewrite_rules();
}

add_action('after_switch_theme', 'ac_custom_flush_rewrite_rules');

// ajoute le custom endpoint comme un nouvel item au menu du compte client
function custom_endpoint_acct_menu_item($items) {
    $logout = $items['customer-logout'];
    unset($items['customer-logout']);
    $items['infos'] = __('Infos complÃ©mentaires', 'woocommerce'); // remplacer "fiches techniques" par votre custom endpoint
    $items['customer-logout'] = $logout;
    return $items;
}

add_filter('woocommerce_account_menu_items', 'custom_endpoint_acct_menu_item');

// rÃ©cupÃ¨re le contenu de votre page (dans notre cas la page fiches techniques dont l'ID est 3476)
function fetch_content_custom_endpoint() {
    global $post;
    $id = "3476"; // l'ID de la page. 
    ob_start();
    $output = apply_filters('the_content', get_post_field('post_content', $id));
    $output .= ob_get_contents();
    ob_end_clean();
    echo $output;
}

add_action('woocommerce_account_infos_endpoint', 'fetch_content_custom_endpoint');

function pep_add_last_name($enq_fields) {
    $last_name = array(
        'id' => 'duree',
        'class' => 'wdm-modal_text',
        'type' => 'text',
        'placeholder' => 'DurÃ©e souhaitÃ©e',
        'required' => 'no',
        'required_message' => '',
        'validation' => '',
        'validation_message' => '',
        'include_in_admin_mail' => 'yes',
        'include_in_customer_mail' => 'yes',
        'label' => 'DurÃ©e',
        'value' => ''
    );

    // ****** IMPORTANT********
    // placing the field after $enq_fields, will place the last name, below the name field
    $enq_fields = array($last_name, $enq_fields);
    return $enq_fields;
}

/* since we will be adding this field next to the name field, hook the function on pep_fields_custname */
add_filter('pep_fields_txtsubject', 'pep_add_last_name', 10, 1);

//Filter to change date month to orignal
add_filter('pep_quoteupDateField', 'pep_quoteupDateField_process');

function pep_quoteupDateField_process($dateField) {

    $month_traslations = array(
        "janvier" => "january",
        "février" => "february",
        "fvrier" => "february",
        "mars" => "march",
        "avril" => "april",
        "mai" => "may",
        "juin" => "june",
        "juillet" => "july",
        "août" => "august",
        "ao&#251;t" => "august",
        "septembre" => "september",
        "octobre" => "october",
        "novembre" => "november",
        "décembre" => "december",
        "d&#233;cembre" => "december",
    );

    if (isset($_POST['txtdate'])) {
        $dateField = $_POST['txtdate'];
        if (!empty($dateField)) {
            $dateField = str_replace(array_keys($month_traslations), array_values($month_traslations), utf8_decode($dateField));
            $dateField = date('Y-m-d', strtotime($dateField));
        }
    } else {
        $dateField = null;
    }

    return $dateField;
}

add_action('mep_custom_fields', 'pep_quote_add_custom_field_content', 99, 1);

function pep_quote_add_custom_field_content($enquiry_id) {

    global $wpdb;

    $tbl = $wpdb->prefix . 'enquiry_meta';
    $sql = "SELECT * FROM {$tbl} WHERE enquiry_id='$enquiry_id'";
    $enq_metas = $wpdb->get_results($sql);

    if (!empty($enq_metas)) {
        foreach ($enq_metas as $enq_meta) {
            if ($enq_meta->meta_key == 'nuits') {
                $nuits = $enq_meta->meta_value;
            } elseif ($enq_meta->meta_key == 'caution') {
                $caution = $enq_meta->meta_value;
            } elseif ($enq_meta->meta_key == 'franchise') {
                $franchise = $enq_meta->meta_value;
            } elseif ($enq_meta->meta_key == 'franchise_rachat') {
                $franchise_rachat = $enq_meta->meta_value;
            } elseif ($enq_meta->meta_key == 'text_boat_field') {
                $text_boat_field = $enq_meta->meta_value;
            } elseif ($enq_meta->meta_key == 'embarquement') {
                $embarquement = $enq_meta->meta_value;
            } elseif ($enq_meta->meta_key == 'debarquement') {
                $debarquement = $enq_meta->meta_value;
            }
        }
    }

    if (empty($nuits)) {
        ?>
        <div class='wdm-user-custom-info'>
            <input type='text' value='<?php //echo $this->enquiry_details->enquiry_ip; ?>' class='wdm-input-custom-info wdm-input' disabled name='nuits'>
            <label placeholder="<?php _e('Nombre de nuits', 'quoteup') ?>" alt="nuits"></label>
        </div>
    <?php }
    if (empty($caution)) {
        ?>
        <div class='wdm-user-custom-info'>
            <input type='text' value='<?php //echo $this->enquiry_details->enquiry_ip;?>' class='wdm-input-custom-info wdm-input' disabled name='caution'>
            <label placeholder="<?php _e('Caution', 'quoteup') ?>" alt="caution"></label>
        </div>
    <?php }
    if (empty($franchise)) {
        ?>
        <div class='wdm-user-custom-info'>
            <input type='text' value='<?php //echo $this->enquiry_details->enquiry_ip;?>' class='wdm-input-custom-info wdm-input' disabled name='franchise'>
            <label placeholder="<?php _e('Franchise', 'quoteup') ?>" alt="franchise"></label>
        </div>
    <?php }
    if (empty($franchise_rachat)) {
        ?>
        <div class='wdm-user-custom-info'>
            <input type='text' value='<?php //echo $this->enquiry_details->enquiry_ip;?>' class='wdm-input-custom-info wdm-input' disabled name='franchise_rachat'>
            <label placeholder="<?php _e('Rachat de franchise', 'quoteup') ?>" alt="franchise_rachat"></label>
        </div>
    <?php }
    if (empty($text_boat_field)) {
        ?>
        <div class='wdm-user-custom-info'>
            <input type='text' value='<?php //echo $this->enquiry_details->enquiry_ip;?>' class='wdm-input-custom-info wdm-input' disabled name='text_boat_field'>
            <label placeholder="<?php _e('Complement catamaran', 'quoteup') ?>" alt="text_boat_field"></label>
        </div>
    <?php } 
    if (empty($embarquement)) { ?>
        <div class='wdm-user-custom-info'>
            <input type='text' value='<?php //echo $this->enquiry_details->enquiry_ip; ?>' class='wdm-input-custom-info wdm-input' disabled name='embarquement'>
            <label placeholder="<?php _e('Embarquement', 'quoteup') ?>" alt="embarquement"></label>
        </div>
    <?php } 
    if (empty($debarquement)) { ?>
        <div class='wdm-user-custom-info'>
            <input type='text' value='<?php //echo $this->enquiry_details->enquiry_ip;?>' class='wdm-input-custom-info wdm-input' disabled name='debarquement'>
            <label placeholder="<?php _e('Debarquement', 'quoteup') ?>" alt="debarquement"></label>
        </div>
    <?php } ?>
    <div class='wdm-user-custom-info pep_quote_save_quote_button_wrap'>
        <a class="button button-primary pep_quote_edit_quote_button" href="javascript:void(0);"><?php _e('Edit', 'woocommerce'); ?></a>
        <a class="button button-primary pep_quote_save_quote_button" href="javascript:void(0);" style="display:none;"><?php _e('Save', 'woocommerce'); ?></a>
        <span class="load-ajax" style="display: none;"></span>
    </div>
    <?php
}

add_action('admin_enqueue_scripts', 'pep_quote_enqueue_scripts');

function pep_quote_enqueue_scripts($hook_suffix) {

    global $post, $typenow;

    if ($hook_suffix == 'admin_page_quoteup-details-edit') {

        //Admin js register for Ajax
        wp_register_script('pep_quote_admin_script', get_stylesheet_directory_uri() . '/js/script-admin.js', array('jquery'), null, true);

        //localize script to pass some variable to javascript file from php file
        //pass ajax url to access wordpress ajax file at admin side for only admin
        wp_localize_script('pep_quote_admin_script', 'Pep_Quote_Admin', array(
            'ajaxurl' => admin_url('admin-ajax.php', ( is_ssl() ? 'https' : 'http')),
        ));
        wp_enqueue_script('pep_quote_admin_script');
    }
}

//add action to call ajax for export template
add_action('wp_ajax_pep_quote_save_quote_options', 'pep_quote_save_quote_options_process');
add_action('wp_ajax_nopriv_pep_quote_save_quote_options', 'pep_quote_save_quote_options_process');

function pep_quote_save_quote_options_process() {

    global $wpdb;
    $res = array();

    $enquiry_id = !empty($_POST['enquiry_id']) ? filter_var($_POST['enquiry_id'], FILTER_SANITIZE_NUMBER_INT) : '';

    if (!empty($enquiry_id)) {

        $enq_tbl = $wpdb->prefix . 'enquiry_detail_new';
        $name = !empty($_POST['form_fields']['cust_name']) ? filter_var($_POST['form_fields']['cust_name'], FILTER_SANITIZE_STRING) : '';
        $email = !empty($_POST['form_fields']['cust_email']) ? filter_var($_POST['form_fields']['cust_email'], FILTER_SANITIZE_EMAIL) : '';
        $cust_ip = !empty($_POST['form_fields']['cust_ip']) ? filter_var($_POST['form_fields']['cust_ip'], FILTER_SANITIZE_STRING) : '';
        $cust_telephone = !empty($_POST['form_fields']['cust_telephone']) ? filter_var($_POST['form_fields']['cust_telephone'], FILTER_SANITIZE_NUMBER_INT) : '';
        $enquiry_date = !empty($_POST['form_fields']['enquiry_date']) ? filter_var($_POST['form_fields']['enquiry_date'], FILTER_SANITIZE_STRING) : '';
        $cust_date_field = !empty($_POST['form_fields']['cust_date_field']) ? filter_var($_POST['form_fields']['cust_date_field'], FILTER_SANITIZE_STRING) : '';
        $custom_fields = !empty($_POST['form_fields']['custom_fields']) ? $_POST['form_fields']['custom_fields'] : array();
      
        $wpdb->update(
                $enq_tbl, array(
            'name' => $name,
            'email' => $email,
            'phone_number' => $cust_telephone,
            'enquiry_date' => date('Y-m-d', strtotime($enquiry_date)),
            'enquiry_ip' => $cust_ip,
            'date_field' => date('Y-m-d', strtotime($cust_date_field)),
                ), array('enquiry_id' => $enquiry_id), array(
            '%s',
            '%s',
            '%d',
            '%s',
            '%s',
            '%s',
                ), array('%d')
        );

        if (!empty($custom_fields)) {

            $meta_tbl = $wpdb->prefix . 'enquiry_meta';

            foreach ($custom_fields as $mkey => $mvalue) {

                $meta_d = $wpdb->get_col($wpdb->prepare("SELECT * FROM $meta_tbl WHERE meta_key = '%s' AND enquiry_id = %d", $mkey, $enquiry_id));
                if (!empty($meta_d)) {
                    $wpdb->update(
                            $meta_tbl, array(
                        'meta_value' => $mvalue,
                            ), array('meta_key' => $mkey), array(
                        '%s',
                            ), array('%s')
                    );
                } else {
                    $wpdb->insert(
                            $meta_tbl, array(
                        'enquiry_id' => $enquiry_id,
                        'meta_key' => $mkey,
                        'meta_value' => $mvalue,
                            ), array(
                        '%d',
                        '%s',
                        '%s',
                            )
                    );
                }
            }
        }

        $res['success'] = true;
    } else {
        $res['success'] = false;
    }

    echo json_encode($res);
    exit;
}

add_filter('woocommerce_email_attachments', 'pep_quote_email_attachments', 10, 2);

function pep_quote_email_attachments($attachments, $this_obj) {

    if ($this_obj->template_html == 'emails/quote.php' || $this_obj->template_plain == 'emails/quote.php') {
        $attachments[] = get_stylesheet_directory() . "/Preparer-votre-stage-aux-Antilles.pdf";
    }
    return $attachments;
}

add_action('wp', 'test_func');

function test_func() {

    global $wpdb;

    //$tblenq = $wpdb->prefix.'enquiry_detail_new';
    //$tbl = $wpdb->prefix.'enquiry_meta';
    //$sql_enq = "SELECT * FROM {$tblenq} WHERE enquiry_id ='71'";
    //$sql = "SELECT * FROM {$tbl} WHERE enquiry_id='71'";
    //$results_enq = $wpdb->get_results($sql_enq);
    //$results = $wpdb->get_results($sql);

    /* echo "<pre>";
      print_r($results_enq);
      echo "</pre>"; */
    /* echo "<pre>";
      print_r($results);
      echo "</pre>"; */
    /* $meta_d = $wpdb->get_col( $wpdb->prepare( "SELECT * FROM $tbl WHERE meta_key = '%s' AND enquiry_id = %d", 'quotation_lang_code', 71 ) );
      echo "<pre>";
      print_r($meta_d);
      echo "</pre>"; */
    if (!empty($_GET['test_func'])) {
        $month_traslations = array(
            "janvier" => "january",
            "février" => "february",
            "fvrier" => "february",
            "mars" => "march",
            "avril" => "april",
            "mai" => "may",
            "juin" => "june",
            "juillet" => "july",
            /* "août" 		=> "august", */
            htmlspecialchars_decode("août") => "august",
            /* "ao&#251;t" => "august", */
            "septembre" => "september",
            "octobre" => "october",
            "novembre" => "november",
            "décembre" => "december",
            "d&#233;cembre" => "december",
        );
        echo "<pre>";
        print_r($month_traslations);
        echo "</pre>";
        $dateField = "15 août 2017";
        $dateField = str_replace(array_keys($month_traslations), array_values($month_traslations), $dateField);
        echo "<pre>date ";
        print_r($dateField);
        echo "</pre>";
    }
}

/**
 * Add custom hook in 
 * PATH : /plugins/product-inquery-pro/includes/admin/class-quoteup-generate-pdf.php
 * Line : 84
 * Filter : apply_filters( 'wp_custom_pdf_generation_css_url' , QUOTEUP_PLUGIN_DIR.'/css/admin/pdf-generation.css' );
 */
// Add custom pdf generation style
add_filter('wp_custom_pdf_generation_css_url', 'custom_pdf_generation_css_url', 10, 1);

function custom_pdf_generation_css_url($stylesheeturl) {

    $stylesheeturl = get_stylesheet_directory() . '/css/custom-pdf-generation-style.css';
    return $stylesheeturl;
}

add_action('wwt_quoteup_product_add_after', 'wwt_quoteup_add_coupon_content', 10, 6);

function wwt_quoteup_add_coupon_content($quotationbTN, $res, $quotationDownload, $result, $email, $excludedProducts) {

    global $wpdb;

    $args = array(
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'asc',
        'post_type' => 'shop_coupon',
        'post_status' => 'publish',
    );

    $coupons = get_posts($args);
    if (!empty($coupons) && !empty($_GET['id'])) {

        //Get details
        $enquiry_id = $_GET['id'];
        $meta_tbl = $wpdb->prefix . 'enquiry_meta';
        $coupon_mkey = '_coupon_code_id';
        $discounted_coupons = $wpdb->get_col($wpdb->prepare("SELECT meta_value FROM $meta_tbl WHERE meta_key = '%s' AND enquiry_id = %d", $coupon_mkey, $enquiry_id));
        $discounted_coupons = !empty($discounted_coupons[0]) ? maybe_unserialize($discounted_coupons[0]) : '';
        ?>
        <table class="wdm-tbl-prod wdmpe-detailtbl wdmpe-quotation-table admin-quote-table" id="wwt_coupon_discount_wrap">
            <thead class="wdmpe-detailtbl-head">
                <tr class="wdmpe-detailtbl-head-row">
                    <th class="wdmpe-detailtbl-head-item item-head-add-to-quote"></th>
                    <th align="left" class="wdmpe-detailtbl-head-item1 item-head-name"><?php _e('Coupon Name', 'queue'); ?></th>
                    <th align="left" class="wdmpe-detailtbl-head-item1 item-head-discount"><?php _e('Discount', 'queue'); ?></th>
                </tr>
            </thead>
            <tbody class="wdmpe-detailtbl-content1 wwt_coupon_wrap">
                <?php
                if (!empty($discounted_coupons)) {
                    foreach ($discounted_coupons as $key => $discounted_coupon) {

                        $_coupon = new WC_Coupon($discounted_coupon);
                        $discount_type = $_coupon->get_discount_type();
                        $amount = $_coupon->get_amount();
                        ?>
                        <tr class="wdmpe-detailtbl-content-row">
                            <td class="quote-product-remove">
                                <a href="javascript:void(0);" class="remove wwt_remove_coupon_button" data-coupon_id="<?php echo $discounted_coupon; ?>">x</a>
                            </td>
                            <td class="wdmpe-detailtbl-content-item item-content-name">
                                <a href="<?php echo get_edit_post_link($discounted_coupon) ?>"><?php echo get_the_title($discounted_coupon); ?></a>
                            </td>
                            <td class="wdmpe-detailtbl-content-item item-content-discount">
                        <?php echo sprintf('%s %s', $amount, $discount_type == 'percent' ? '%' : '' ); ?>
                            </td>
                        </tr>
                    <?php
                }
            } else {
                echo '<td colspan="3" class="wwt_not_found" align="center">' . __('No coupon added yet.', 'quoteup') . '</tr>';
            }
            ?>
            </tbody>
            <?php /* <tfoot>
              <tr class="total_amount_row">
              <td></td>
              <td align="left" class="wdmpe-detailtbl-head-item1 amount-total-label" id="amount_total_label"><?php _e( 'Total', 'quoteup' );?></td>
              <td align="left" class="wdmpe-detailtbl-content-item1 item-content-cost quote-final-total" id="amount_total">
              <?php echo wc_price(0);?>
              </td>
              </tr>
              </tfoot> */ ?>
        </table>
        <div class="formfield" style="margin:14px;clear: both;">
            <select class="wwt_coupon_id" name="wwt_coupon_id" data-placeholder="<?php esc_attr_e('Search for a coupon&hellip;', 'quoteup'); ?>" style="width:250px;">
                <?php
                foreach ($coupons as $coupon) {
                    $coupon_data = new WC_Coupon($coupon->ID);
                    $coupon_name = $coupon->post_title;

                    if (is_object($coupon_data)) {
                        echo '<option value="' . esc_attr($coupon->ID) . '">' . wp_kses_post($coupon_name) . '</option>';
                    }
                }
                ?>
            </select>
            <a class="button wwt_add_coupon_button button-primary" href="javascript:void(0);"><?php _e('Add coupon', 'quoteup'); ?></a>
            <span id="productLoad" class="productLoad"></span>
        </div>

        <?php
    }
}

add_action('wp_ajax_wwt_add_coupon_quote_process', 'wwt_add_coupon_quote_process_func');
add_action('wp_ajax_nopriv_wwt_add_coupon_quote_process', 'wwt_add_coupon_quote_process_func');

function wwt_add_coupon_quote_process_func() {

    global $wpdb;

    //Get details
    $response = array();
    $enquiry_id = !empty($_POST['enquiry_id']) ? $_POST['enquiry_id'] : '';
    $coupon_id = !empty($_POST['coupon_id']) ? $_POST['coupon_id'] : '';

    if (!empty($enquiry_id) && !empty($coupon_id)) {

        $meta_tbl = $wpdb->prefix . 'enquiry_meta';
        $coupon_mkey = '_coupon_code_id';

        $discounted_coupons = $wpdb->get_col($wpdb->prepare("SELECT meta_value FROM $meta_tbl WHERE meta_key = '%s' AND enquiry_id = %d", $coupon_mkey, $enquiry_id));

        //Check if meta already exist
        if (count($discounted_coupons) > 0) {

            $discounted_coupons = !empty($discounted_coupons[0]) ? maybe_unserialize($discounted_coupons[0]) : '';
            if (!in_array($coupon_id, $discounted_coupons)) {

                //Update new coupon id
                $discounted_coupons[] = $coupon_id;
                $discounted_coupons = array_unique($discounted_coupons);

                $wpdb->update(
                        $meta_tbl, array(
                    'meta_value' => maybe_serialize($discounted_coupons),
                        ), array('enquiry_id' => $enquiry_id, 'meta_key' => $coupon_mkey), array(
                    '%s',
                        ), array('%d', '%s')
                );

                $response['success'] = 'success';
            } else {
                $response['error'] = 'error';
            }
        } else {

            $wpdb->insert(
                    $meta_tbl, array(
                'enquiry_id' => $enquiry_id,
                'meta_key' => $coupon_mkey,
                'meta_value' => maybe_serialize(array($coupon_id)),
                    ), array(
                '%d',
                '%s',
                '%s',
                    )
            );

            $response['success'] = 'success';
        }

        //Check if successfully added then build raw
        if (!empty($response['success'])) {

            $_coupon = new WC_Coupon($coupon_id);
            $discount_type = $_coupon->get_discount_type();
            $amount = $_coupon->get_amount();
            sprintf('%s %s', $amount, $discount_type == 'percent' ? '%' : '' );

            $coupon_html = '<tr class="wdmpe-detailtbl-content-row">
	                    	<td class="quote-product-remove">
	                        	<a href="javascript:void(0);" class="remove wwt_remove_coupon_button" data-coupon_id="' . $coupon_id . '">x</a>
	                    	</td>
	                    	<td class="wdmpe-detailtbl-content-item1 item-content-name"><a href="' . get_edit_post_link($coupon_id) . '">' . get_the_title($coupon_id) . '</a></td>
	                    	<td class="wdmpe-detailtbl-content-item1 item-content-discount">' . sprintf('%s %s', $amount, $discount_type == 'percent' ? '%' : '') . '</td>
	                    </tr>';

            $response['html'] = $coupon_html;
        }
    } else {
        $response['error'] = 'error';
    }

    echo json_encode($response);
    exit;
}

add_action('wp_ajax_wwt_remove_coupon_quote_process', 'wwt_remove_coupon_quote_process_func');
add_action('wp_ajax_nopriv_wwt_remove_coupon_quote_process', 'wwt_remove_coupon_quote_process_func');

function wwt_remove_coupon_quote_process_func() {

    global $wpdb;

    //Get details
    $response = array();
    $enquiry_id = !empty($_POST['enquiry_id']) ? $_POST['enquiry_id'] : '';
    $coupon_id = !empty($_POST['coupon_id']) ? $_POST['coupon_id'] : '';

    if (!empty($enquiry_id) && !empty($coupon_id)) {

        $meta_tbl = $wpdb->prefix . 'enquiry_meta';
        $coupon_mkey = '_coupon_code_id';

        $discounted_coupons = $wpdb->get_col($wpdb->prepare("SELECT meta_value FROM $meta_tbl WHERE meta_key = '%s' AND enquiry_id = %d", $coupon_mkey, $enquiry_id));
        $discounted_coupons = !empty($discounted_coupons[0]) ? maybe_unserialize($discounted_coupons[0]) : '';

        if (!empty($discounted_coupons)) {

            if (($coup_key = array_search($coupon_id, $discounted_coupons)) !== false) {

                //Update new coupon id
                unset($discounted_coupons[$coup_key]);
                $discounted_coupons = array_unique($discounted_coupons);

                $wpdb->update(
                        $meta_tbl, array(
                    'meta_value' => maybe_serialize($discounted_coupons),
                        ), array('enquiry_id' => $enquiry_id, 'meta_key' => $coupon_mkey), array(
                    '%s',
                        ), array('%d', '%s')
                );

                $response['success'] = 'success';

                if (count($discounted_coupons) <= 0) {
                    $response['html'] = '<td colspan="3" class="wwt_not_found" align="center">' . __('No coupon added yet.', 'quoteup') . '</tr>';
                }
            } else {
                $response['error'] = 'error';
            }
        }
    } else {
        $response['error'] = 'error';
    }

    echo json_encode($response);
    exit;
}
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
