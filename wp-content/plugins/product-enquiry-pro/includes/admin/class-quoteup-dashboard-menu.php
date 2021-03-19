<?php

namespace Includes\Admin;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class QuoteUpDashboardMenu
{
    /**
     * @var Singleton The reference to *Singleton* instance of this class
     */
    private static $instance;

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return Singleton The *Singleton* instance.
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    protected function __construct()
    {
        add_action('admin_menu', array($this, 'dashboardMenu'), 11);
        add_filter('set-screen-option', array($this, 'wdmSetScreenOption'), 10, 3);
    }

    /**
     * Include required files and create a menu for QuoteUp.
     *
     * @global array $quoteup_plugin_data
     */
    public function dashboardMenu()
    {
        global $quoteup_plugin_data, $quoteup;
        require_once QUOTEUP_PLUGIN_DIR.'/includes/class-wdm-get-license-data.php';
        require_once QUOTEUP_PLUGIN_DIR.'/includes/admin/class-quoteup-quotes-list.php';
        require_once QUOTEUP_PLUGIN_DIR.'/includes/admin/class-quoteup-enquiries-list.php';
        $getDataFromDb = \Includes\QuoteupGetData::getDataFromDb($quoteup_plugin_data, false);
        $optionData = quoteupSettings();
        $unreadEnquiryCount = $this->getUnreadEnquiryCount();
        if ($getDataFromDb == 'available') {
            if ($unreadEnquiryCount > 0) {
                $menuName = sprintf(__('Product Enquiry Pro %s %s %s', 'quoteup'), '<span class="unread-enquiry-count update-plugins"><span class="enquiry-count">', $unreadEnquiryCount, '</span></span>');
            } else {
                $menuName = __('Product Enquiry Pro', 'quoteup');
            }
            add_menu_page(__('QuoteUp', 'quoteup'), $menuName, 'manage_options', 'quoteup-details-new', array($quoteup->quoteDetails, 'displayQuoteDetails'), QUOTEUP_PLUGIN_URL.'/images/pep.png');
            add_submenu_page('admin.php?page=quoteup-details-edit', __('Edit Enquiry', 'quoteup'), __('Quote Details', 'quoteup'), 'manage_options', 'quoteup-details-edit', array($quoteup->quoteDetailsEdit, 'editQuoteDetails'));
            if (isset($optionData['enable_disable_quote']) && $optionData['enable_disable_quote'] == 1) {
                $menu = add_submenu_page('quoteup-details-new', __('Enquiry Details', 'quoteup'), __('Enquiry Details', 'quoteup'), 'manage_options', 'quoteup-details-new', array($quoteup->quoteDetails, 'displayQuoteDetails'));
            } else {
                $menu = add_submenu_page('quoteup-details-new', __('Enquiry & Quote Details', 'quoteup'), __('Enquiry & Quote Details', 'quoteup'), 'manage_options', 'quoteup-details-new', array($quoteup->quoteDetails, 'displayQuoteDetails'));
                add_submenu_page('quoteup-details-new', __('Create New Quote', 'quoteup'), __('Create New Quote', 'quoteup'), 'manage_options', 'quoteup-create-quote', array($quoteup->quoteCreateQuotation, 'createDashboardQuotation'));
            }
            add_action("load-{$menu}", array($this, 'menuActionLoadHook'));
            do_action('quoteup_dashboard_menu');
            add_submenu_page('quoteup-details-new', __('QuoteUp Settings', 'quoteup'), __('Settings', 'quoteup'), 'manage_options', 'quoteup-for-woocommerce', array($quoteup->displaySettingsPage, 'displaySettings'));
            add_action('admin_enqueue_scripts', array($quoteup->displaySettingsPage, 'enqueueScripts'));
        }
    }

    public function getUnreadEnquiryCount()
    {
        global $wpdb;
        $metaTbl = $wpdb->prefix.'enquiry_meta';
        $sql = "SELECT COUNT(enquiry_id) FROM $metaTbl WHERE meta_key= '_unread_enquiry' AND meta_value = 'yes'";
        return $wpdb->get_var($sql);
    }

    /**
     * This function is used to load data
     * @return [type] [description]
     */
    public function menuActionLoadHook()
    {
        global $quoteupQuotesList,$quoteupEnquiriesList;
        $optionData = quoteupSettings();
        if (isset($optionData['enable_disable_quote']) && $optionData['enable_disable_quote'] == 1) {
            $quoteupEnquiriesList = new QuoteupEnquiriesList();
        } else {
            $quoteupQuotesList = new QuoteupQuotesList();
        }
        $option = 'per_page';
 
        $args = array(
            'label' => __('Number of items per page : ', 'quoteup'),
            'default' => 10,
            'option' => 'request_per_page'
        );
         
        add_screen_option($option, $args);
    }
 
    public function wdmSetScreenOption($status, $option, $value)
    {
        if ('request_per_page' == $option) {
            return $value;
        }
     
        return $status;
    }
}

QuoteUpDashboardMenu::getInstance();
