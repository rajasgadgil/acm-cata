<?php

class Feeds_model_wdi {

  private $page_number = null;

  private $search_text = "";

  public function __construct() {
    if ( WDILibrary::get('paged', 0, 'intval') != 0 ) {
      $this->page_number = WDILibrary::get('paged', 0, 'intval');
    } elseif ( WDILibrary::get('page_number', 0, 'intval') !=  0 ) {
      $this->page_number = WDILibrary::get('page_number', 0, 'intval');
    }
    if ( WDILibrary::get('search_value') != '' ) {
      $this->search_text = WDILibrary::get('search_value');
    } elseif ( WDILibrary::get('search', '', 'sanitize_text_field', 'GET' ) != '' ) {
      $this->search_text = WDILibrary::get('search', '', 'sanitize_text_field', 'GET' );
    }
  }

   public function get_slides_row_data($slider_id) {
    global $wpdb;
    $row = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . WDI_FEED_TABLE. " WHERE slider_id='%d' ORDER BY `order` ASC", $slider_id));
   if ( $row ) {
        //  $row->image_url = $row->image_url ? $row->image_url : WD_S_URL . '/images/no-image.png';
        //  $row->thumb_url = $row->thumb_url ? $row->thumb_url : WD_S_URL . '/images/no-image.png';
    }
    return $row;
  }

  public function get_rows_data() {
    global $wpdb;

    $where = ((!empty($this->search_text)) ? 'WHERE feed_name LIKE "%' . esc_html(stripslashes($this->search_text)) . '%"' : '');
    $asc_or_desc = WDILibrary::get('order') == 'asc' ? 'asc' : 'desc';

    $order_by_arr = array('id', 'feed_name', 'published');
    $order_by = WDILibrary::get('order_by');
    $order_by = (in_array($order_by, $order_by_arr)) ? $order_by : 'id';
    $order_by = ' ORDER BY `' . $order_by . '` ' . $asc_or_desc;
    if (isset($this->page_number) && $this->page_number) {
      $limit = ((int) $this->page_number - 1) * 20;
    }
    else {
      $limit = 0;
    }

    $query_limit = " LIMIT " . $limit . ",20";
    $query = "SELECT * FROM " . $wpdb->prefix . WDI_FEED_TABLE .' '. $where . $order_by.$query_limit;
    $rows = $wpdb->get_results($query);
    return $rows;
  }

  public function get_slider_prev_img($slider_id) { 
    global $wpdb;
    $prev_img_url = $wpdb->get_var($wpdb->prepare("SELECT `feed_thumb` FROM " . $wpdb->prefix . WDI_FEED_TABLE . " WHERE id='%d'", $slider_id));
    $prev_img_url = $prev_img_url ? $prev_img_url : WDI_URL . '/images/no-image.png';
    return $prev_img_url;
  }

  public function page_nav() {
    global $wpdb;
    $where = ((isset($this->search_text) && !empty($this->search_text) && (esc_html(stripslashes($this->search_text)) != '')) ? 'WHERE feed_name LIKE "%' . esc_html(stripslashes($this->search_text)) . '%"'  : '');
    $total = $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->prefix . WDI_FEED_TABLE. ' ' . $where);
    $page_nav['total'] = $total;
    if (isset($this->page_number) && $this->page_number) {
      $limit = ((int) $this->page_number - 1) * 20;
    }
    else {
      $limit = 0;
    }
    $page_nav['limit'] = (int) ($limit / 20 + 1);
    return $page_nav;
  }

  public static function wdi_get_feed_defaults() {
    global $wpdb;
    $query = $wpdb->prepare("SELECT id FROM " . $wpdb->prefix . WDI_THEME_TABLE . " WHERE default_theme='%d'", 1);
    $default_theme = WDILibrary::objectToArray($wpdb->get_results($query));
    $settings = array(
      'thumb_user' => '',
      'feed_name' => 'Sample Feed',
      'feed_thumb' => '',
      'published' => '1',
      'theme_id' => $default_theme[0]['id'],
      'feed_users' => '',
      'feed_display_view' => 'load_more_btn',
      'sort_images_by' => 'date',
      'display_order' => 'desc',
      'follow_on_instagram_btn' => '1',
      'display_header' => '0',
      'number_of_photos' => '20',
      'load_more_number' => '4',
      'pagination_per_page_number' => '12',
      'pagination_preload_number' => '10',
      'image_browser_preload_number' => '10',
      'image_browser_load_number' => '10',
      'number_of_columns' => '4',
      'resort_after_load_more' => '0',
      'show_likes' => '1', // @ToDo API Changes 2020 (change to 0)
      'show_description' => '1', // @ToDo API Changes 2020 (change to 0)
      'show_comments' => '1', // @ToDo API Changes 2020 (change to 0)
      'show_usernames' => '1', // @ToDo API Changes 2020 (change to 0)
      'display_user_info' => '1', // @ToDo API Changes 2020 (change to 0)
      'display_user_post_follow_number' => '1', // @ToDo API Changes 2020 (change to 0)
      'show_full_description' => '1', // @ToDo API Changes 2020 (change to 0)
      'disable_mobile_layout' => '0',
      'feed_type' => 'thumbnails',
      'feed_item_onclick' => 'lightbox',
      //lightbox defaults
      'popup_fullscreen' => '0',
      'popup_width' => '648',
      'popup_height' => '648',
      'popup_type' => 'fade',
      'popup_autoplay' => '0',
      'popup_interval' => '5',
      'popup_enable_filmstrip' => '1',
      'popup_filmstrip_height' => '70',
      'autohide_lightbox_navigation' => '1',
      'popup_enable_ctrl_btn' => '1',
      'popup_enable_fullscreen' => '1',
      'popup_enable_info' => '1',
      'popup_info_always_show' => '0',
      'popup_info_full_width' => '0',
      'popup_enable_comment' => '1', // @ToDo API Changes 2020 (change to 0)
      'popup_enable_fullsize_image' => '1',
      'popup_enable_download' => '0',
      'popup_enable_share_buttons' => '1',
      'popup_enable_facebook' => '0',
      'popup_enable_twitter' => '0',
      'popup_enable_google' => '0',
      'popup_enable_pinterest' => '0',
      'popup_enable_tumblr' => '0',
      'show_image_counts' => '0',
      'enable_loop' => '1',
      'popup_image_right_click' => '1',
      'conditional_filters' => '',
      'conditional_filter_type' => 'none',
      'show_username_on_thumb' => '0',
      'conditional_filter_enable' => '0',
      'liked_feed' => 'userhash',
      'mobile_breakpoint' => '640',
      'redirect_url' => '',
      'feed_resolution' => 'optimal',
      'hashtag_top_recent' => '1',
    );
    if(IS_FREE){
      $settings["show_description"] = "0";
      $settings["show_likes"] = "0";
      $settings["show_comments"] = "0";
      $settings["show_username_on_thumb"] = "0";
      $settings['popup_enable_filmstrip'] = '0';
      $settings['popup_info_always_show'] = '0';
      $settings['popup_info_full_width'] = '0';
      $settings['popup_enable_info'] = '0';
      $settings['popup_enable_comment'] = '0';
      $settings['popup_enable_share_buttons'] = '0';
    }
    return $settings;
  }

  public function get_sanitize_types(){
  $sanitize_types = array(
    'thumb_user'=>'string',
    'feed_name' => 'string',
    'feed_thumb'=>  'url',
    'published' => 'bool',
    'theme_id'=> 'number'/*$options['wdi_default_theme']*/,
    'feed_users'=>  'string',
    'feed_display_view' =>'string',
    'sort_images_by' => 'string',
    'display_order'=>  'string',
    'follow_on_instagram_btn' => 'bool',
    'display_header'=>  'bool',
    'number_of_photos'=>  'number',
    'load_more_number' => 'number',
    'pagination_per_page_number'=>'number',
    'pagination_preload_number'=>'number',
    'image_browser_preload_number'=>'number',
    'image_browser_load_number'=>'number',
    'number_of_columns'=>  'number',
    'resort_after_load_more'=>'bool',
    'show_likes'=>  'bool',
    'show_description'=> 'bool' ,
    'show_comments'=>  'bool',
    'show_username_on_thumb'=>'bool',
    'show_usernames'=>'bool',
    'display_user_info'=>'bool',
    'display_user_post_follow_number'=>'bool',
    'show_full_description'=>'bool',
    'disable_mobile_layout'=>'bool',
    'feed_type' => 'string',
    'feed_item_onclick' => 'string',

    //lightbox defaults
    'popup_fullscreen'=>'bool',
    'popup_width'=>'number',
    'popup_height'=>'number',
    'popup_type'=>'string',
    'popup_autoplay'=>'bool',
    'popup_interval'=>'number',
    'popup_enable_filmstrip'=>'bool',
    'popup_filmstrip_height'=>'number',
    'autohide_lightbox_navigation'=>'bool',
    'popup_enable_ctrl_btn'=>'bool',
    'popup_enable_fullscreen'=>'bool',
    'popup_enable_info'=>'bool',
    'popup_info_always_show'=>'bool',
    'popup_info_full_width'=>'bool',
    'popup_enable_comment'=>'bool',
    'popup_enable_fullsize_image'=>'bool',
    'popup_enable_download'=>'bool',
    'popup_enable_share_buttons'=>'bool',
    'popup_enable_facebook'=>'bool',
    'popup_enable_twitter'=>'bool',
    'popup_enable_google'=>'bool',
    'popup_enable_pinterest'=>'bool',
    'popup_enable_tumblr'=>'bool',
    'show_image_counts'=>'bool',
    'enable_loop'=>'bool',
    'popup_image_right_click'=>'bool',

    'conditional_filters' => 'string',
    'conditional_filter_enable'=>'number',
    'conditional_filter_type' => 'string',

    'liked_feed' => 'string',
    'mobile_breakpoint' => 'number',
    'redirect_url' => 'string',
    'feed_resolution' => 'string',
    'hashtag_top_recent' => 'bool',
  );
  return $sanitize_types;
}

  public function get_feed_row($current_id){
  global $wpdb;
  $feed_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM ". $wpdb->prefix.WDI_FEED_TABLE. " WHERE id ='%d' ", $current_id));
  return $feed_row;
}

  /**
   * Create Preview Instagram post.
   *
   * @return string $guid
   */
  public function get_instagram_preview_post() {
    global $wpdb;
    $post_type = 'wdi_instagram';
    $args = array(
      'post_type' => $post_type,
      'post_status' => 'private'
    );
    $row = get_posts($args);

    if ( !empty($row[0]) ) {
      return get_permalink($row[0]->ID);
    }
    else {
      $post_params = array(
        'post_author' => 1,
        'post_status' => 'private',
        'post_content' => '[wdi_preview]',
        'post_title' => 'Preview',
        'post_type' => $post_type,
        'comment_status' => 'closed',
        'ping_status' => 'closed',
        'post_parent' => 0,
        'menu_order' => 0,
        'import_id' => 0,
      );
      // Create new post by wdi_preview preview type.
      if ( wp_insert_post($post_params) ) {
        flush_rewrite_rules();

        return get_the_guid($wpdb->insert_id);
      }
      else {
        return "";
      }
    }
  }
}