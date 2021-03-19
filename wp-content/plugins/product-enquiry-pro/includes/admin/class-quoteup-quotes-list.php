<?php

namespace Includes\Admin;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('WP_List_Table')) {
    require_once ABSPATH.'wp-admin/includes/class-wp-list-table.php';
}

class QuoteupQuotesList extends \WP_List_Table
{
    public $countFilter;
    public function __construct()
    {
        parent::__construct(array(
            'singular' => __('Enquiry', 'quoteup')/* singular name of the listed records */,
            'plural' => __('Enquiries', 'quoteup'), /* plural name of the listed records */
        ));

        add_filter('removable_query_args', array($this, 'addCustomRemovableArgs'), 10, 1);
    }

    /**
     * This function add arguments which should be removed from URL on load.
     * @param [type] $removable_query_args [description]
     */
    public function addCustomRemovableArgs($removable_query_args)
    {
        $customArgs = array('action', 'id', '_wpnonce', 'bulk-delete');

        return array_merge($removable_query_args, $customArgs);
    }

    /**
     * This function gives sql query as per status.
     *
     * @return [type] [description]
     */
    public static function getSqlStatus($filter)
    {
        global $wpdb;
        $tableName = $wpdb->prefix.'enquiry_history';

        if($filter == "saved")
        {
            $sql = "SELECT s1.enquiry_id
                FROM $tableName s1
                LEFT JOIN $tableName s2 ON s1.enquiry_id = s2.enquiry_id
                AND s1.id < s2.id
                WHERE s2.enquiry_id IS NULL AND (s1.status ='".$filter."' OR s1.status ='Quote Created') AND s1.enquiry_id > 0 AND s1.ID > 0";

        } else {
            $sql = "SELECT s1.enquiry_id
                FROM $tableName s1
                LEFT JOIN $tableName s2 ON s1.enquiry_id = s2.enquiry_id
                AND s1.id < s2.id
                WHERE s2.enquiry_id IS NULL AND s1.status ='".$filter."'AND s1.enquiry_id > 0 AND s1.ID > 0";
        }
        $res = $wpdb->get_col($sql);

        return implode(',', $res);
    }

    public static function getSearchResults($searchBy)
    {
        global $wpdb;
        $tableName = $wpdb->prefix.'enquiry_detail_new';
        $sql = "SELECT enquiry_id FROM $tableName WHERE (name LIKE '%".$searchBy."%' OR email LIKE '%".$searchBy."%');";

        $res = $wpdb->get_col($sql);

        return implode(',', $res);
    }

    public static function getStatusImage($status)
    {
        $statusImage = '';

        switch ($status) {
            case 'Requested':
                return "<span class = 'status-span'>".__('Requested', 'quoteup')."</span><img class='status-image' title = '".__('Requested', 'quoteup')."' src = ".QUOTEUP_PLUGIN_URL.'/images/requested.png >';

            case 'Saved':
                return "<span class = 'status-span'>".__('Saved', 'quoteup')."</span><img class='status-image' title = '".__('Saved', 'quoteup')."' src = ".QUOTEUP_PLUGIN_URL.'/images/saved.png >';

            case 'Sent':
                return "<span class = 'status-span'>".__('Sent', 'quoteup')."</span><img class='status-image' title = '".__('Sent', 'quoteup')."' src = ".QUOTEUP_PLUGIN_URL.'/images/sent.png >';

            case 'Approved':
                return "<span class = 'status-span'>".__('Approved', 'quoteup')."</span><img class='status-image' title = '".__('Approved', 'quoteup')."' src = ".QUOTEUP_PLUGIN_URL.'/images/approved.png >';

            case 'Rejected':
                $statusImage = "<span class = 'status-span'>".__('Rejected', 'quoteup')."</span><img class='status-image' title = '".__('Rejected', 'quoteup')."' src = ".QUOTEUP_PLUGIN_URL.'/images/rejected.png >';

                return $statusImage;

            case 'Order Placed':
                return "<span class = 'status-span'>".__('Order Placed', 'quoteup')."</span><img class='status-image' title = '".__('Order Placed', 'quoteup')."' src = ".QUOTEUP_PLUGIN_URL.'/images/completed.png >';

            case 'Expired':
                return "<span class = 'status-span'>".__('Expired', 'quoteup')."</span><img class='status-image' title = '".__('Expired', 'quoteup')."' src = ".QUOTEUP_PLUGIN_URL.'/images/expired.png >';

            case 'Quote Created':
                return "<span class = 'status-span'>".__('Quote Created', 'quoteup')."</span><img class='status-image' title = '".__('Quote Created', 'quoteup')."' src = ".QUOTEUP_PLUGIN_URL.'/images/quote-created.png >';
        }
    }

    public static function orderByQuery()
    {
        if (!empty($_REQUEST[ 'orderby' ])) {
            $sql = ' ORDER BY '.esc_sql($_REQUEST[ 'orderby' ]);
            $sql .= !empty($_REQUEST[ 'order' ]) ? ' '.esc_sql($_REQUEST[ 'order' ]) : ' ASC';
        } else {
            $sql = ' ORDER BY enquiry_id DESC';
        }

        return $sql;
    }

    /**
     * This function is used to get enquiries list from database.
     * @param  integer $per_page    [description]
     * @param  integer $page_number [description]
     * @return [type] [description]
     */
    public static function getEnquiries(&$total_items, $per_page = 10, $page_number = 1)
    {
        global $wpdb,$quoteupManageHistory;
        if (isset($_POST['s']) && $_POST['s'] !="") {
            $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            header("Location: $actual_link&s=$_POST[s]");
        } elseif (isset($_POST['s']) && $_POST['s'] == '') {
            $requestedURL = get_admin_url('', 'admin.php?page=quoteup-details-new');
            header("Location: $requestedURL");
        }


        $filterByStatus = '';
        $resultSet = false;
        $resultsForSearch = false;

        if (isset($_GET['status'])) {
            $filter = filter_var($_GET['status'], FILTER_SANITIZE_STRING);
        }

        if (isset($filter) && $filter == 'admin-created') {
            $metaTableName = $wpdb->prefix.'enquiry_meta';
            $sql = "SELECT enquiry_id FROM $metaTableName WHERE meta_key = '_admin_quote_created'";
            $res = $wpdb->get_col($sql);
            $resultSet = implode(',', $res);
            if (null == $resultSet) {
                return;
            }
            $total_items = substr_count($resultSet, ',');
            $total_items = self::makeTotalValid($total_items);
            $filterByStatus = "  WHERE enquiry_id IN ($resultSet)";
        } elseif (isset($filter)) {
            $resultSet = self::getSqlStatus($filter);
            if (null == $resultSet) {
                return;
            }
            $total_items = substr_count($resultSet, ',');
            $total_items = self::makeTotalValid($total_items);
            $filterByStatus = "  WHERE enquiry_id IN ($resultSet)";
        }

        if (isset($_GET['s']) && $_GET['s'] != '') {
            $searchBy = filter_var($_GET['s'], FILTER_SANITIZE_STRING);
            $resultsForSearch = self::getSearchResults($searchBy);
            if (!$resultsForSearch || $resultsForSearch == '') {
                $total_items = false;
                $filterByStatus = "  WHERE enquiry_id IN ('0')";
            } elseif (($resultSet || $resultSet !='') && ($resultsForSearch || $resultsForSearch !='')) {
                $resultSet = explode(",", $resultSet);
                $resultsForSearch = explode(",", $resultsForSearch);
                $resultSet = array_intersect($resultSet, $resultsForSearch);
                $resultSet = implode(',', $resultSet);
                if($resultSet == ''){
                    $total_items = false;
                    $filterByStatus = "  WHERE enquiry_id IN ('0')";
                } else {
                    $total_items = substr_count($resultSet, ',');
                    $total_items = self::makeTotalValid($total_items);
                    $filterByStatus = "  WHERE enquiry_id IN ($resultSet)";                    
                }
            } elseif (!$resultSet || $resultSet == '') {
                $total_items = substr_count($resultsForSearch, ',');
                $total_items = self::makeTotalValid($total_items);
                $filterByStatus = "  WHERE enquiry_id IN ($resultsForSearch)";
            }
        }

        $sql = "SELECT enquiry_id, product_details, name, email, enquiry_date, message, total FROM {$wpdb->prefix}enquiry_detail_new $filterByStatus";

        if ($total_items == 0 && !isset($filter) && !isset($_GET['s']) ) {
            $sql2 = "SELECT count(enquiry_id) FROM {$wpdb->prefix}enquiry_detail_new $filterByStatus";

            $total_items = $wpdb->get_var($sql2);            
        }


        $sql .= self::orderByQuery();

        $sql .= " LIMIT $per_page";

        $sql .= ' OFFSET '.($page_number - 1) * $per_page;

        $result = $wpdb->get_results($sql, 'ARRAY_A');
        $new_results = array();
        $admin_path = get_admin_url();
        foreach ($result as $res) {
            $id = $res[ 'enquiry_id' ];
            $status = $quoteupManageHistory->getLastAddedHistory($id);
            $orderid = \Includes\QuoteupOrderQuoteMapping::getOrderIdOfQuote($id);
            if ($orderid == '0' || $orderid == null) {
                $orderid = '-';
            } else {
                $orderid = '<a href="'.admin_url('post.php?post='.absint($orderid).'&action=edit').'" >'.$orderid.'</a>';
            }
            $statusImage = self::getStatusImage($status['status']);
            $details = array();
            if ($res[ 'product_details' ] != '') {
                $details = unserialize($res[ 'product_details' ]);
            }
            $count = count($details);
            $name = $res[ 'name' ];

            $email = $res[ 'email' ];
            $date = $res[ 'enquiry_date' ];
            $msg = $res[ 'message' ];
            $tooltip = '';
            $current_data = array('enquiry_id' => "<a href='{$admin_path}admin.php?page=quoteup-details-edit&id=$id'>$id</a>",
                'status' => $statusImage,
                'product_details' => "<a class = 'Items-hover' title='$tooltip'  href='{$admin_path}admin.php?page=quoteup-details-edit&id=$id'> {$count} ".__('Items', 'quoteup').' </a>',
                'name' => $name,
                'email' => $email,
                'enquiry_date' => $date,
                'message' => $msg,
                'amount' => ($res['total'] == null || empty($res['total']))  ? '-' : wc_price($res['total']),
                'order_number' => $orderid,
            );

            $new_results[] = apply_filters('enquiry_list_table_data', $current_data, $res);
        }

        return $new_results;
    }

    public static function makeTotalValid($total_items)
    {
        if ($total_items == 0)
        {
            $total_items = 1;
        } else {
            $total_items = $total_items+1;
        }
        return $total_items;
    }

    public function single_row( $item ) {
        global $wpdb;
        $metaTbl = $wpdb->prefix.'enquiry_meta';
        $class = '';

        $html = new \DOMDocument('1.0', 'UTF-8');

        // set error level
        $internalErrors = libxml_use_internal_errors(true);

        $html->loadHTML($item['enquiry_id']); // loads html
        $nodelist = $html->getElementsByTagName('a'); // nodes
        foreach ($nodelist as $node) {
            $enquiryID = $node->nodeValue;
        }
        libxml_use_internal_errors($internalErrors);
        $sql = "SELECT meta_value FROM $metaTbl WHERE meta_key = '_unread_enquiry' AND enquiry_id= $enquiryID";
        $metaValue = $wpdb->get_var($sql);
        if ($metaValue == 'yes') {
            $class = 'unread-enquiry';
        }
        echo '<tr class = "'. $class .'">';
        $this->single_row_columns( $item );
        echo '</tr>';
    }

    public function get_views()
    {
        $countAll = $countRequested = $countSaved = $countSent = $countApproved = $countRejected = $countPlaced = $countExpired = $countAdminQuote = 0;

        global $wpdb;
        $tableName = $wpdb->prefix.'enquiry_history';
        $metaTableName = $wpdb->prefix.'enquiry_meta';

        $sql = "SELECT s1.status,COUNT(s1.enquiry_id) AS EnquiryCount FROM $tableName s1 LEFT JOIN $tableName s2 ON s1.enquiry_id = s2.enquiry_id AND s1.id < s2.id WHERE s2.enquiry_id IS NULL AND s1.status IN ('requested','saved','sent','approved','rejected','Order Placed','expired','Quote Created') AND s1.enquiry_id > 0 AND s1.ID > 0 GROUP BY s1.status";
        $res = $wpdb->get_results($sql, ARRAY_A);
        $sql1 = "SELECT COUNT(enquiry_id) AS EnquiryCount FROM $metaTableName WHERE meta_key = '_admin_quote_created'";
        $res1 = $wpdb->get_var($sql1);
        $adminCreatedQuoteArray = array(
            'status' => 'admin-created',
            'EnquiryCount' => $res1,
            );
        array_push($res, $adminCreatedQuoteArray);
        $this->countFilter = $res;

        self::getCount($res, $countAll, $countRequested, $countSaved, $countSent, $countApproved, $countRejected, $countPlaced, $countExpired, $countAdminQuote);

        $requestedURL = get_admin_url('', 'admin.php?page=quoteup-details-new');
        $currentAll = $currentRequested = $currentSaved = $currentSent = $currentApproved = $currentRejected = $currentPlaced = $currentExpired = $currentAdminCreated = '';
        if (isset($_GET['status'])) {
            switch ($_GET['status']) {
                case 'requested':
                    $currentRequested = 'current';
                    break;

                case 'saved':
                    $currentSaved = 'current';
                    break;

                case 'sent':
                    $currentSent = 'current';
                    break;

                case 'approved':
                    $currentApproved = 'current';
                    break;

                case 'rejected':
                    $currentRejected = 'current';
                    break;

                case 'Order Placed':
                    $currentPlaced = 'current';
                    break;

                case 'expired':
                    $currentExpired = 'current';
                    break;
                case 'admin-created':
                    $currentAdminCreated = 'current';
                    break;
            }
        } else {
            $currentAll = 'current';
        }
        $status_links = array(
            'all' => "<a class=$currentAll id='all' href='".$requestedURL."'>".__('All', 'quoteup')." <span class='count'>(".$countAll.')</span></a>',
        );
        if ($countRequested>0){
            $status_links['requested'] = "<a class='".$currentRequested."'  id='requested' href='".$requestedURL."&status=requested'>".__('Requested', 'quoteup')."<span class='count'>(".$countRequested.')</span></a>';
        }

        if ($countSaved>0){
            $status_links['saved'] = "<a class='".$currentSaved."'  id='saved' href='".$requestedURL."&status=saved'>".__('Saved', 'quoteup')." <span class='count'>(".$countSaved.')</span></a>';
        }

        if ($countSent>0){
            $status_links['sent'] = "<a class='".$currentSent."'  id='sent' href='".$requestedURL."&status=sent'>".__('Sent', 'quoteup')." <span class='count'>(".$countSent.')</span></a>';
        }

        if ($countApproved>0){
            $status_links['approved'] = "<a class='".$currentApproved."'  id='approved' href='".$requestedURL."&status=approved'>".__('Approved', 'quoteup')." <span class='count'>(".$countApproved.')</span></a>';
        }

        if ($countRejected>0){
            $status_links['rejected'] = "<a class='".$currentRejected."'  id='rejected' href='".$requestedURL."&status=rejected'>".__('Rejected', 'quoteup')." <span class='count'>(".$countRejected.')</span></a>';
        }

        if ($countPlaced>0){
            $status_links['Order Placed'] = "<a class='".$currentPlaced."'  id='completed' href='".$requestedURL."&status=Order Placed'>".__('Order Placed', 'quoteup')." <span class='count'>(".$countPlaced.')</span></a>';
        }

        if ($countExpired>0){
            $status_links['expired'] = "<a class='".$currentExpired."' id='expired' href='".$requestedURL."&status=expired'>".__('Expired', 'quoteup')." <span class='count'>(".$countExpired.')</span></a>';
        }

        if ($countAdminQuote>0){
            $status_links['admin'] = "<a class='".$currentAdminCreated."' id='adminCreated' href='".$requestedURL."&status=admin-created'>".__('Admin Created Quotes', 'quoteup')." <span class='count'>(".$countAdminQuote.')</span></a>';
        }

        return $status_links;
    }

    public static function tooltipOnHover($res)
    {
        $tooltip = '<table>';
        $tooltip .= '<thead>';
        $tooltip .= '<th>'.__('Items', 'quoteup').'</th>';
        $tooltip .= '<th>'.__('Quantity', 'quoteup').'</th>';
        $tooltip .= '</thead>';
        $details = maybe_unserialize($res[ 'product_details' ]);

        foreach ((array) $details as $row) {
            foreach ((array) $row as $attribute) {
                if (!empty($attribute['variation_id'])) {
                    $productAvailable = isProductAvailable($attribute[ 'variation_id' ]);
                    $variationString = '';
                    if (isset($attribute['variation'])) {
                        foreach ($attribute['variation'] as $attributeName => $attributeValue) {
                            if (!empty($variationString)) {
                                $variationString .= ',';
                            }
                            $variationString .= '<b>'.wc_attribute_label($attributeName).' </b>:'.$attributeValue;
                        }
                    }
                    if ($productAvailable) {
                        $tooltip .= '<tr>';
                        $tooltip .= '<td>'.$attribute[ 'title' ].'('.$variationString.')</td>';
                        $tooltip .= '<td>'.$attribute[ 'quant' ].'</td>';
                        $tooltip .= '</tr>';
                    } else {
                        $tooltip .= '<tr>';
                        $tooltip .= '<td> <del>'.$attribute[ 'title' ].'('.$variationString.')</del></td>';
                        $tooltip .= '<td> <del>'.$attribute[ 'quant' ].'</del></td>';
                        $tooltip .= '</tr>';
                    }
                } else {
                    $productAvailable = isProductAvailable($attribute[ 'id' ]);
                    if ($productAvailable) {
                        $tooltip .= '<tr>';
                        $tooltip .= '<td>'.$attribute[ 'title' ].'</td>';
                        $tooltip .= '<td>'.$attribute[ 'quant' ].'</td>';
                        $tooltip .= '</tr>';
                    } else {
                        $tooltip .= '<tr>';
                        $tooltip .= '<td> <del>'.$attribute[ 'title' ].'</del></td>';
                        $tooltip .= '<td> <del>'.$attribute[ 'quant' ].'</del></td>';
                        $tooltip .= '</tr>';
                    }
                }
            }
        }

        return $tooltip.'</table>';
    }

    /**
     * This function is used to delete enquiry.
     *
     * @param [int] $enquiry_id [enquiry id to be deleted]
     *
     * @return [type] [description]
     */
    public static function deleteEnquiry($enquiry_id)
    {
        global $wpdb;

        do_action('quoteup_before_quote_delete_enquiry', $enquiry_id);

        $wpdb->delete("{$wpdb->prefix}enquiry_detail_new", array('enquiry_id' => $enquiry_id), array('%d'));
        $wpdb->delete("{$wpdb->prefix}enquiry_history", array('enquiry_id' => $enquiry_id), array('%d'));
        $wpdb->delete("{$wpdb->prefix}enquiry_meta", array('enquiry_id' => $enquiry_id), array('%d'));
        $wpdb->delete("{$wpdb->prefix}enquiry_quotation", array('enquiry_id' => $enquiry_id), array('%d'));
        $wpdb->delete("{$wpdb->prefix}enquiry_thread", array('enquiry_id' => $enquiry_id), array('%d'));
        $wpdb->delete("{$wpdb->prefix}enquiry_quotation_version", array('enquiry_id' => $enquiry_id), array('%d'));

        do_action('quoteup_after_quote_delete_enquiry', $enquiry_id);
    }

    public static function getCount($res, &$countAll, &$countRequested, &$countSaved, &$countSent, &$countApproved, &$countRejected, &$countPlaced, &$countExpired, &$countAdminQuote)
    {
        foreach ($res as $key) {
            switch ($key['status']) {
                case 'Requested':
                    $countRequested = $key['EnquiryCount'];
                    $countAll = $countAll + $countRequested;
                    break;

                case 'Saved':
                    $countSaved = $countSaved + $key['EnquiryCount'];
                    $countAll = $countAll + $countSaved;
                    break;

                case 'Sent':
                    $countSent = $key['EnquiryCount'];
                    $countAll = $countAll + $countSent;
                    break;

                case 'Approved':
                    $countApproved = $key['EnquiryCount'];
                    $countAll = $countAll + $countApproved;
                    break;

                case 'Rejected':
                    $countRejected = $key['EnquiryCount'];
                    $countAll = $countAll + $countRejected;
                    break;

                case 'Order Placed':
                    $countPlaced = $key['EnquiryCount'];
                    $countAll = $countAll + $countPlaced;
                    break;

                case 'Expired':
                    $countExpired = $key['EnquiryCount'];
                    $countAll = $countAll + $countExpired;
                    break;

                case 'Quote Created':
                    $countSaved = $countSaved + $key['EnquiryCount'];

                case 'admin-created':
                    $countAdminQuote = $key['EnquiryCount'];
                    break;
            }
        }
    }

    /*
     * This function is used to count the total number of records.
     */
    public static function recordCount($res)
    {
        $countAll = $countRequested = $countSaved = $countSent = $countApproved = $countRejected = $countPlaced = $countExpired = $countAdminQuote = 0;

        self::getCount($res, $countAll, $countRequested, $countSaved, $countSent, $countApproved, $countRejected, $countPlaced, $countExpired, $countAdminQuote);

        if (isset($_GET['status'])) {
            switch ($_GET['status']) {
                case 'requested':
                    return $countRequested;

                case 'saved':
                    return  $countSaved;

                case 'sent':
                    return $countSent;

                case 'approved':
                    return $countApproved;

                case 'rejected':
                    return $countRejected;

                case 'Order Placed':
                    return $countPlaced;

                case 'expired':
                    return $countExpired;

                case 'admin-created':
                    return $countAdminQuote;
            }
        } else {
            return $countAll;
        }
    }

    /*
     * text to be displayed when there are no records
     */
    public function no_items()
    {
        _e('No enquiry & quote details available.', 'quoteup');
    }

    public function column_enquiry_id($item)
    {
        $enquiry_id = strip_tags($item[ 'enquiry_id' ]);
        $nonce = wp_create_nonce('wdm_enquiry_actions');
        $admin_path = get_admin_url();
        $currentPage = $this->get_pagenum();

        $actions = array(
            'edit' => sprintf('<a href="%sadmin.php?page=%s&id=%s">%s</a>', $admin_path, 'quoteup-details-edit', $enquiry_id, __('Edit', 'quoteup')),

            'delete' => sprintf('<a href="?page=%s&paged=%s&action=%s&id=%s&_wpnonce=%s">%s</a>', esc_attr($_REQUEST[ 'page' ]), $currentPage, 'delete', $enquiry_id, $nonce, __('Delete', 'quoteup')),
        );

        return sprintf('%s %s', $item[ 'enquiry_id' ], $this->row_actions($actions));
    }

    public function column_default($item, $column_name)
    {
        return $item[ $column_name ];
    }

    public function column_cb($item)
    {
        $enquiry_id = strip_tags($item[ 'enquiry_id' ]);

        return sprintf('<input type="checkbox" name="bulk-delete[]" pr-id="%d" value="%s" />', $enquiry_id, $item[ 'enquiry_id' ]);
    }

    public function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'enquiry_id' => __('ID', 'quoteup'),
            'status' => __('Status', 'quoteup'),
            'product_details' => __('Items', 'quoteup'),
            'name' => __('Customer Name', 'quoteup'),
            'email' => __('Customer Email', 'quoteup'),
            'enquiry_date' => __('Enquiry Date', 'quoteup'),
            'amount' => __('Total', 'quoteup'),
            'order_number' => __('Order #', 'quoteup'),
        );

        return apply_filters('quoteup_enquiries_get_columns', $columns);
    }

    public function get_hidden_columns()
    {
        $hidden_columns = get_user_option('managetoplevel_page_quoteup-details-newcolumnshidden');
        if (!$hidden_columns) {
            $hidden_columns = array();
        }

        return $hidden_columns;
    }

    public function get_sortable_columns()
    {
        $sortable_columns = array(
            'enquiry_id' => array('enquiry_id', false),
            'name' => array('name', true),
            'email' => array('email', true),
            'enquiry_date' => array('enquiry_date', true),
        );

        return apply_filters('quoteup_enquiries_get_sortable_columns', $sortable_columns);
    }

    public function get_bulk_actions()
    {
        $actions = array(
            'bulk-export' => __('Export', 'quoteup'),
            'bulk-export-all' => __('Export all enquiries', 'quoteup'),
            'bulk-delete' => __('Delete', 'quoteup'),
        );

        return apply_filters('quoteup_enquiries_get_sortable_columns', $actions);
    }

    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);

        /* Process bulk action */
        $this->process_bulk_action();
        $this->views();
        $per_page = $this->get_items_per_page('request_per_page', 10);
        $current_page = $this->get_pagenum();
        $total_items = 0;

        $this->items = self::getEnquiries($total_items, $per_page, $current_page);
        if ($total_items === 0) {
            $total_items = self::recordCount($this->countFilter);
        } elseif (!$total_items) {
            $total_items = 0;
        }
        $this->set_pagination_args(array(
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page' => $per_page, //WE have to determine how many items to show on a page
        ));

    }

    public function process_bulk_action()
    {
        global $quoteupQuotesList;
        $currentPage = $quoteupQuotesList->get_pagenum();
        $sendback = admin_url('/admin.php?page=quoteup-details-new');
        if (!$this->current_action()) {
            $args = $_GET;
            foreach ($args as $key => $value) {
                switch ($key) {
                    case 'action':
                        if ($value == 'delete') {
                            echo $div = "<div class='updated'><p>Enquiry is deleted successfully</p></div>";
                            break;
                        }

                    case 'bulk-delete':
                        echo $div = "<div class='updated'><p> $value ".__('enquiries are deleted', 'quoteup').'</p></div>';
                        break;

                    case 'selectnone':
                        echo $div = "<div class='error'><p>".__('Select Enquiries to delete', 'quoteup').'</p></div>';
                        break;
                }
            }
        }
        $sendback = add_query_arg('paged', $currentPage, $sendback);
        //Detect when a single delete is being triggered...
        if ('delete' === $this->current_action()) {
            $nonce = esc_attr($_REQUEST[ '_wpnonce' ]);

            if (!wp_verify_nonce($nonce, 'wdm_enquiry_actions')) {
                die('Go get a life script kiddies');
            } else {
                self::deleteEnquiry(absint($_GET[ 'id' ]));
                echo $div = "<div class='updated'><p>Enquiry is deleted successfully</p></div>";
                $sendback = add_query_arg('delete', 'yes', $sendback);
            }
        }

        // If the delete bulk action is triggered
        if ((isset($_POST[ 'action' ]) && $_POST[ 'action' ] == 'bulk-delete') || (isset($_POST[ 'action2' ]) && $_POST[ 'action2' ] == 'bulk-delete')
        ) {
            if (isset($_POST[ 'bulk-delete' ])) {
                $delete_ids = esc_sql($_POST[ 'bulk-delete' ]);

                // loop over the array of record IDs and delete them
                foreach ($delete_ids as $id) {
                    $id = strip_tags($id);
                    self::deleteEnquiry($id);
                }
                $count = count($delete_ids);

                $div = "<div class='updated'><p> $count ".__('enquiries are deleted', 'quoteup').'</p></div>';
                $sendback = add_query_arg('bulk-delete', $count, $sendback);
            } else {
                $sendback = add_query_arg('selectnone', 'yes', $sendback);
                echo $div = "<div class='error'><p>".__('Select Enquiries to delete', 'quoteup').'</p></div>';
            }

            if ($this->current_action()) {
                wp_redirect($sendback);
                exit;
            }
        }
    }
}
