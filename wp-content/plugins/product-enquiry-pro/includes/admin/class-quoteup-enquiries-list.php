<?php

namespace Includes\Admin;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('WP_List_Table')) {
    require_once ABSPATH.'wp-admin/includes/class-wp-list-table.php';
}

class QuoteupEnquiriesList extends \WP_List_Table
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

        $removable_query_args = array_merge($removable_query_args, $customArgs);

        return apply_filters( 'quoteup_add_custom_removable_args', $removable_query_args);
    }

    /**
     * This function is used to get enquiries list from database.
     * @param  integer $per_page    [description]
     * @param  integer $page_number [description]
     * @return [type]               [description]
     */
    public static function getEnquiries(&$total_items, $per_page = 10, $page_number = 1)
    {
        global $wpdb;
        $filterByStatus = '';

        if (isset($_POST['s']) && $_POST['s'] !="") {
            $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            header("Location: $actual_link&s=$_POST[s]");
        } elseif (isset($_POST['s']) && $_POST['s'] == '') {
            $requestedURL = get_admin_url('', 'admin.php?page=quoteup-details-new');
            header("Location: $requestedURL");
        }

        if (isset($_GET['s']) && $_GET['s'] != '') {
            $searchBy = filter_var($_GET['s'], FILTER_SANITIZE_STRING);
            $resultsForSearch = self::getSearchResults($searchBy);
            if (!$resultsForSearch || $resultsForSearch == '') {
                $total_items = false;
                $filterByStatus = "  WHERE enquiry_id IN ('0')";
            } else {
                $total_items = substr_count($resultsForSearch, ',');
                $total_items = self::makeTotalValid($total_items);
                $filterByStatus = "  WHERE enquiry_id IN ($resultsForSearch)";
            }
        }

        $sql = "SELECT enquiry_id,product_details,name,email,enquiry_date,message FROM {$wpdb->prefix}enquiry_detail_new $filterByStatus";

        if (!empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY '.esc_sql($_REQUEST['orderby']);
            $sql .= !empty($_REQUEST['order']) ? ' '.esc_sql($_REQUEST['order']) : ' ASC';
        } else {
            $sql .= ' ORDER BY enquiry_id DESC';
        }

        $sql .= " LIMIT $per_page";

        $sql .= ' OFFSET '.($page_number - 1) * $per_page;

        $result = $wpdb->get_results($sql, 'ARRAY_A');
        $new_results = array();
        $admin_path = get_admin_url();
        foreach ($result as $res) {
            $id = $res['enquiry_id'];
            $details = array();
            if ($res['product_details'] != '') {
                $details = unserialize($res['product_details']);
            }
            $count = count($details);
            $name = $res['name'];

            $email = $res['email'];
            $date = $res['enquiry_date'];
            $msg = $res['message'];
            $tooltip = '';
            $current_data = array('enquiry_id' => "<a href='{$admin_path}admin.php?page=quoteup-details-edit&id=$id'>$id</a>",
                'product_details' => "<a class = 'Items-hover' title='$tooltip'  href='{$admin_path}admin.php?page=quoteup-details-edit&id=$id'> {$count} Items </a>",
                'name' => $name,
                'email' => $email,
                'enquiry_date' => $date,
                'message' => $msg,
            );

            $new_results[] = apply_filters('enquiry_list_table_data', $current_data, $res);
        }

        return $new_results;
    }

    public static function getSearchResults($searchBy)
    {
        global $wpdb;
        $tableName = $wpdb->prefix.'enquiry_detail_new';
        $sql = "SELECT enquiry_id FROM $tableName WHERE (name LIKE '%".$searchBy."%' OR email LIKE '%".$searchBy."%');";

        $res = $wpdb->get_col($sql);

        return implode(',', $res);
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

    public function single_row( $item )
    {
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

    /**
     * This function is used to delete enquiry
     * @param  [int] $enquiry_id [enquiry id to be deleted]
     * @return [type]             [description]
     */
    public static function deleteEnquiry($enquiry_id)
    {
        global $wpdb;

        do_action('quoteup_before_enquiry_delete_enquiry', $enquiry_id);

        $wpdb->delete("{$wpdb->prefix}enquiry_detail_new", array('enquiry_id' => $enquiry_id), array('%d'));
        $wpdb->delete("{$wpdb->prefix}enquiry_history", array('enquiry_id' => $enquiry_id), array('%d'));
        $wpdb->delete("{$wpdb->prefix}enquiry_meta", array('enquiry_id' => $enquiry_id), array('%d'));
        $wpdb->delete("{$wpdb->prefix}enquiry_quotation", array('enquiry_id' => $enquiry_id), array('%d'));
        $wpdb->delete("{$wpdb->prefix}enquiry_thread", array('enquiry_id' => $enquiry_id), array('%d'));
        $wpdb->delete("{$wpdb->prefix}enquiry_quotation_version", array('enquiry_id' => $enquiry_id), array('%d'));

        do_action('quoteup_after_enquiry_delete_enquiry', $enquiry_id);
    }

    /*
     * This function is used to count the total number of records.
     */
    public static function recordCount()
    {
        global $wpdb;

        $sql = "SELECT COUNT(enquiry_id) FROM {$wpdb->prefix}enquiry_detail_new";

        return $wpdb->get_var($sql);
    }

    /*
     * text to be displayed when there are no records
     */
    public function no_items()
    {
        _e('No enquiries avaliable.', 'quoteup');
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
            'product_details' => __('Items', 'quoteup'),
            'name' => __('Customer Name', 'quoteup'),
            'email' => __('Customer Email', 'quoteup'),
            'enquiry_date' => __('Enquiry Date', 'quoteup'),
            'message' => __('Message', 'quoteup'),
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
            $total_items = $total_items = self::recordCount();
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
        global $quoteupEnquiriesList;
        $currentPage = $quoteupEnquiriesList->get_pagenum();
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
