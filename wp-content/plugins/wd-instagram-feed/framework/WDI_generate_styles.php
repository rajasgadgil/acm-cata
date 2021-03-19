<?php
/**
 * Created by PhpStorm.
 * User: mher
 * Date: 12/6/17
 * Time: 6:08 PM
 */

class WDI_generate_styles {

  private $theme_id;
  private $theme_options;
  private $folder_name = 'wd-instagram-feed';
  private $file_name_format = "wdi_theme_%s.css";//theme_id
  private $css = "";
  private $file_key = null;

  public function __construct($theme_id, $theme_options){
    $this->theme_id = $theme_id;
    $this->theme_options = $theme_options;
  }

  public function all_views_styles($write_in_file = true, $minify = true){
    $css = "";
    $css .= $this->common_styles();
    $css .= $this->thumbnail_view_styles();
    $css .= $this->masonry_view_styles();
    $css .= $this->blog_view_styles();
    $css .= $this->browser_view_styles();
    $css .= $this->lightbox_styles();

    if($minify == true) {
      require_once(WDI_DIR . '/framework/WDILibrary.php');
      $css = WDILibrary::minify_styles($css);
    }

    $this->css = $css;

    if($write_in_file === true) {
      return $this->update_file($css);
    }

    return $css;
  }

  public function common_styles(){

    $style = $this->theme_options;
    $theme_id = $this->theme_id;

    ob_start();
    ?>
      <style>

          .wdi_feed_theme_<?php echo $theme_id; ?> .wdi_feed_wrapper {
              width: <?php echo $style['feed_wrapper_width']?>; /*feed_wrapper_width,column number * image size*/
              background-color: <?php echo $style['feed_wrapper_bg_color']?>; /*feed_wrapper_bg_color*/
              text-align: <?php echo $style['header_position']?>; /*header_position*/
          }

          .wdi_feed_theme_<?php echo $theme_id; ?> .wdi_feed_header {
              margin: <?php echo $style['header_margin']?>; /*header_margin*/
              padding: <?php echo $style['header_padding']?>; /*header_padding*/
              border: <?php echo $style['header_border_size']?> solid <?php echo $style['header_border_color']?>; /*header_border_size, header_border_color*/
              text-align: <?php echo $style['header_position']?>; /*header_position*/
          }

          .wdi_feed_theme_<?php echo $theme_id; ?> .wdi_header_text {
              font-size: <?php echo $style['header_text_font_size']?>;
              font-style: <?php echo $style['header_text_font_style']?>;
              padding: <?php echo $style['header_text_padding']?>; /*header_text_padding*/
              color: <?php echo $style['header_text_color']?>; /*header_text_color*/
              font-weight: <?php echo $style['header_font_weight']?>; /*header_font_weight*/
              line-height: <?php echo $style['header_img_width']?>px; /*header_img_width*/
          }

          .wdi_feed_theme_<?php echo $theme_id; ?> .wdi_single_user {
              padding-top: <?php echo $style['user_padding']?>; /*user_padding*/
              padding-bottom: <?php echo $style['user_padding']?>; /*user_padding*/
              padding-left: <?php echo $style['user_padding']?>; /*user_padding*/
              padding-right: <?php echo $style['user_padding']?>; /*user_padding*/
          }

          .wdi_feed_theme_<?php echo $theme_id; ?> .wdi_header_hashtag h3 {
              margin-top: <?php echo (intval($style['user_img_width']) - intval($style['users_text_font_size']))/2?>px;
          }

          .wdi_feed_theme_<?php echo $theme_id; ?> .wdi_header_user_text h3 {
              font-size: <?php echo $style['users_text_font_size']?>;
              font-style: <?php echo $style['users_text_font_style']?>;
              line-height: <?php echo $style['users_text_font_size']?>;
              color: <?php echo $style['users_text_color']?>; /*header_text_color*/;
          }

          .wdi_feed_theme_<?php echo $theme_id; ?> .wdi_user_img_wrap img {
              height: <?php echo $style['user_img_width']?>px;
              width: <?php echo $style['user_img_width']?>px;
              border-radius: <?php echo $style['user_border_radius']?>px;
          }

          .wdi_feed_theme_<?php echo $theme_id; ?> .wdi_media_info {
              /*display: inline-block;*/
              margin-left: <?php echo intval($style['user_img_width']) + 10;?>px;
              line-height: <?php echo $style['users_text_font_size']?>;
              color: <?php echo $style['users_text_color']?>; /*header_text_color !mmm/ seperate*/
          }

          .wdi_feed_theme_<?php echo $theme_id; ?> .wdi_bio {
              color: <?php echo $style['users_text_color']?>; /*header_text_color*/
              font-size: <?php echo $style['user_description_font_size']?>; /*header_text_color*/
          }

          .wdi_feed_theme_<?php echo $theme_id; ?> .wdi_follow_btn {
              border-radius: <?php echo $style['follow_btn_border_radius']?>px;
              font-size: <?php echo $style['follow_btn_font_size']?>px;
              background-color: <?php echo $style['follow_btn_bg_color']?>;
              border-color: <?php echo $style['follow_btn_border_color']?>;
              color: <?php echo $style['follow_btn_text_color']?>;
              margin-left: <?php echo $style['follow_btn_margin']?>px;
              padding: 0 <?php echo $style['follow_btn_padding']?>px;
          }

          .wdi_feed_theme_<?php echo $theme_id; ?> .wdi_follow_btn:hover {
              border-color: <?php echo $style['follow_btn_border_hover_color']?>;
              color: <?php echo $style['follow_btn_text_hover_color']?>;
              background-color: <?php echo $style['follow_btn_background_hover_color']?>;
          }

          .wdi_feed_theme_<?php echo $theme_id; ?> .wdi_filter_overlay {
              width: <?php echo $style['user_img_width'];?>px; /*user_img_width*/
              height: <?php echo $style['user_img_width'];?>px; /*user_img_width*/
              border-radius: <?php echo $style['user_border_radius']?>px; /*user_img_width*/
              background-color: <?php echo $style['th_overlay_hover_color'];?>;
          }

          .wdi_feed_theme_<?php echo $theme_id; ?> .wdi_filter_icon span {
              width: <?php echo $style['user_img_width'];?>px; /*header_img_width*/
              height: <?php echo $style['user_img_width'];?>px; /*header_img_width*/
          }

          .wdi_feed_theme_<?php echo $theme_id; ?> .wdi_load_more_text {
              color: <?php echo $style['load_more_text_color']?>; /*load_more_text_color*/
              font-size: <?php echo $style['load_more_text_font_size']?>; /*load_more_text_font_size*/
          }

          .wdi_feed_theme_<?php echo $theme_id; ?> .wdi_load_more_text img {
              height: <?php echo $style['load_more_height']?>; /*load_more_height*/
              width: <?php echo $style['load_more_height']?>; /*load_more_height*/
          }

          .wdi_feed_theme_<?php echo $theme_id; ?> .wdi_load_more_wrap:hover {
              background-color: <?php echo $style['load_more_wrap_hover_color']?>; /*load_more_wrap_hover_color*/
          }

          .wdi_feed_theme_<?php echo $theme_id; ?> .wdi_pagination {

              text-align: <?php echo $style['pagination_position']?>; /*load_more_position*/
              color: <?php echo $style['pagination_ctrl_color']?>; /*pagination_ctrl_color*/
              font-size: <?php echo $style['pagination_size']?>; /*pagination_size*/
          }

          .wdi_feed_theme_<?php echo $theme_id; ?> .wdi_pagination_ctrl {
              margin: <?php echo $style['pagination_ctrl_margin']?>;
          }

          .wdi_feed_theme_<?php echo $theme_id; ?> .wdi_pagination_ctrl:hover {
              color: <?php echo $style['pagination_ctrl_hover_color']?>;
          }

          .wdi_feed_theme_<?php echo $theme_id; ?> .wdi_filter_active_col {
              color: <?php echo $style['active_filter_bg_color'];?>;
              border-color: <?php echo $style['active_filter_bg_color'];?>;
          }

      </style>
    <?php
    $css = ob_get_contents();
    ob_end_clean();

    $css = strip_tags($css);

    return $css;
  }

  public function thumbnail_view_styles(){
    $style = $this->theme_options;
    $theme_id = $this->theme_id;
    ob_start();
    ?>
      <style>
          .wdi_feed_<?php echo 'thumbnail_' . $theme_id; ?> .wdi_feed_container {
              width: <?php echo $style['feed_container_width']?>;
              background-color: <?php echo $style['feed_container_bg_color']?>; /*feed_container_bg_color*/
              border-bottom: 5px solid <?php echo $style['feed_container_bg_color']?>; /*feed_container_bg_color*/;
          }

          .wdi_feed_<?php echo 'thumbnail_' . $theme_id; ?> .wdi_header_img_wrap {
              height: <?php echo $style['header_img_width']?>px; /*header_img_width*/
              width: <?php echo $style['header_img_width']?>px; /*header_img_width*/
              border-radius: <?php echo $style['header_border_radius']?>px; /*header_img_width*/
          }

          .wdi_feed_<?php echo 'thumbnail_' . $theme_id; ?> .wdi_photo_wrap {
              padding: <?php echo $style['th_photo_wrap_padding']?>; /*photo_wrap_padding*/
              width: calc(100% - 2 *<?php echo $style['th_photo_wrap_padding']?>);
          }

          .wdi_feed_<?php echo 'thumbnail_' . $theme_id; ?> .wdi_photo_wrap_inner {
              border: <?php echo $style['th_photo_wrap_border_size']?> solid <?php echo $style['th_photo_wrap_border_color']?>; /*photo_wrap_border_size,photo_wrap_border_color*/
              background-color: <?php echo $style['th_photo_wrap_bg_color']?>; /*photo_wrap_bg_color*/
          }

          .wdi_feed_<?php echo 'thumbnail_' . $theme_id; ?> .wdi_photo_img {
              border-radius: <?php echo $style['th_photo_img_border_radius']?>; /*photo_img_border_radius*/
          }

          .wdi_feed_<?php echo 'thumbnail_' . $theme_id; ?> .wdi_photo_meta {
              background-color: <?php echo $style['th_photo_meta_bg_color']?>; /*photo_meta_bg_color*/
          }

          .wdi_feed_<?php echo 'thumbnail_' . $theme_id; ?> .wdi_thumb_likes {
              width: <?php echo ($style['th_photo_meta_one_line']=='1')? '50%' : '100%' ?>; /*photo_meta_one_line==false else 100%*/
              float: <?php echo ($style['th_photo_meta_one_line']=='1')? 'left' : 'none'?>; /*photo_meta_one_line==true else float none*/
              font-size: <?php echo $style['th_like_comm_font_size']?>; /*photo_caption_font_size*/;
              color: <?php echo $style['th_like_text_color']?>; /*like_text_color*/

          }

          .wdi_feed_<?php echo 'thumbnail_' . $theme_id; ?> .wdi_thumb_comments {
              width: <?php echo ($style['th_photo_meta_one_line']=='1')? '50%' : '100%' ?>; /*photo_meta_one_line==false else 100%*/
              float: <?php echo ($style['th_photo_meta_one_line']=='1')? 'left' : 'none'?>; /*photo_meta_one_line==true else float none*/
              font-size: <?php echo $style['th_like_comm_font_size']?>; /*photo_caption_font_size*/;
              color: <?php echo $style['th_comment_text_color']?>; /*comment_text_color*/

          }

          .wdi_feed_<?php echo 'thumbnail_' . $theme_id; ?> .wdi_photo_title {
              font-size: <?php echo $style['th_photo_caption_font_size']?>; /*photo_caption_font_size*/
              color: <?php echo $style['th_photo_caption_color']?>; /*photo_caption_color*/
          }

          .wdi_feed_<?php echo 'thumbnail_' . $theme_id; ?> .wdi_photo_title:hover {
              color: <?php echo $style['th_photo_caption_hover_color']?>;
          }

          .wdi_feed_<?php echo 'thumbnail_' . $theme_id; ?> .wdi_load_more_spinner {
              color: <?php echo $style['load_more_text_color']?>; /*load_more_text_color*/
              font-size: <?php echo intval($style['load_more_text_font_size'])*1.3?>px; /*load_more_text_font_size*/
          }

          .wdi_feed_<?php echo 'thumbnail_' . $theme_id; ?> .wdi_load_more,
          .wdi_feed_<?php echo 'thumbnail_' . $theme_id; ?> .wdi_spinner {
              background-color: <?php echo $style['feed_container_bg_color']?>; /*feed_container_bg_color*/
              text-align: <?php echo $style['load_more_position']?>; /*load_more_position*/
          }

          .wdi_feed_<?php echo 'thumbnail_' . $theme_id; ?> .wdi_load_more_wrap,
          .wdi_feed_<?php echo 'thumbnail_' . $theme_id; ?> .wdi_spinner_wrap {
              padding: <?php echo $style['load_more_padding']?>; /*load_more_padding*/
              background-color: <?php echo $style['load_more_bg_color']?>; /*load_more_bg_color*/
              border-radius: <?php echo $style['load_more_border_radius']?>; /*load_more_border_radius*/
              height: <?php echo $style['load_more_height']?>; /*load_more_height*/
              width: <?php echo $style['load_more_width']?>; /*load_more_width*/
              border: <?php echo $style['load_more_border_size']?> solid <?php echo $style['load_more_border_color']?>; /*load_more_border_size, load_more_border_color*/;
          }

          <?php
            require_once(WDI_DIR . '/framework/WDILibrary.php');
            $rgb_overlay = WDILibrary::wdi_spider_hex2rgb($style['th_overlay_hover_color']);
            $rgba_overlay = 'rgba('. $rgb_overlay['red'] .',' . $rgb_overlay['green'] . ',' . $rgb_overlay['blue'] . ','.((100 - intval($style['th_overlay_hover_transparent']))/100).')';
          ?>
          .wdi_feed_<?php echo 'thumbnail_' . $theme_id; ?> .wdi_photo_overlay:hover {
              background-color: <?php echo $rgba_overlay;?>
          }

          .wdi_feed_<?php echo 'thumbnail_' . $theme_id; ?> .wdi_photo_overlay i {
              font-size: <?php echo $style['th_overlay_hover_icon_font_size'];?>;
          }

          .wdi_feed_<?php echo 'thumbnail_' . $theme_id; ?> .wdi_photo_overlay:hover i {
              color: <?php echo $style['th_overlay_hover_icon_color'];?>
          }

          <?php
            switch($style['th_photo_img_hover_effect']){
              case 'rotate': {
                $effect = 'rotate(5deg)';
                break;
              }
              case 'scale':{
                $effect = 'scale(1.2)';
                break;
              }
              case 'rotate_and_scale':{
                $effect = 'scale(1.2) rotate(5deg)';
                break;
              }
              default:{
                $effect = 'none';
                break;
              }
            }
          ?>
          .wdi_feed_<?php echo 'thumbnail_' . $theme_id; ?> .wdi_photo_img:hover img {
              -webkit-transform: translateX(-50%) translateY(-50%) <?php echo $effect?>;
              -moz-transform: translateX(-50%) translateY(-50%) <?php echo $effect?>;
              -ms-transform: translateX(-50%) translateY(-50%) <?php echo $effect?>;
              -o-transform: translateX(-50%) translateY(-50%) <?php echo $effect?>;
              transform: translateX(-50%) translateY(-50%) <?php echo $effect?>;
          }

          .wdi_feed_<?php echo 'thumbnail_' . $theme_id; ?> .wdi_filter_active_bg {
              background-color: <?php echo $style['active_filter_bg_color'];?>;
          }

          .wdi_feed_<?php echo 'thumbnail_' . $theme_id; ?> .wdi_media_user {
              color: <?php echo $style['th_thumb_user_color'];?>;;
              background-color: <?php echo $style['th_thumb_user_bg_color'];?>;;
          }

      </style>
    <?php
    $css = ob_get_contents();
    ob_end_clean();

    $css = strip_tags($css);

    return $css;
  }

  public function masonry_view_styles(){
    $style = $this->theme_options;
    $theme_id = $this->theme_id;
    ob_start();
    ?>
      <style>
          .wdi_feed_<?php echo 'masonry_' . $theme_id; ?> .wdi_feed_container {
              width: <?php echo $style['feed_container_width']?>;
              background-color: <?php echo $style['feed_container_bg_color']?>; /*feed_container_bg_color*/
              border-bottom: 5px solid <?php echo $style['feed_container_bg_color']?>; /*feed_container_bg_color*/;
          }

          .wdi_feed_<?php echo 'masonry_' . $theme_id; ?> .wdi_header_img_wrap,
          .wdi_feed_<?php echo 'masonry_' . $theme_id; ?> .wdi_users_img_wrap {
              height: <?php echo $style['header_img_width']?>px; /*header_img_width*/
              width: <?php echo $style['header_img_width']?>px; /*header_img_width*/
              border-radius: <?php echo $style['header_border_radius']?>px; /*header_img_width*/
          }

          .wdi_feed_<?php echo 'masonry_' . $theme_id; ?> .wdi_photo_wrap {
              padding: <?php echo $style['mas_photo_wrap_padding']?>; /*photo_wrap_padding*/
          }

          .wdi_feed_<?php echo 'masonry_' . $theme_id; ?> .wdi_photo_wrap_inner {
              border: <?php echo $style['mas_photo_wrap_border_size']?> solid <?php echo $style['mas_photo_wrap_border_color']?>; /*photo_wrap_border_size,photo_wrap_border_color*/
              background-color: <?php echo $style['mas_photo_wrap_bg_color']?>; /*photo_wrap_bg_color*/
          }

          .wdi_feed_<?php echo 'masonry_' . $theme_id; ?> .wdi_photo_img {
              border-radius: <?php echo $style['mas_photo_img_border_radius']?>; /*photo_img_border_radius*/
          }

          .wdi_feed_<?php echo 'masonry_' . $theme_id; ?> .wdi_photo_meta {
              background-color: <?php echo $style['mas_photo_meta_bg_color']?>; /*photo_meta_bg_color*/
          }

          .wdi_feed_<?php echo 'masonry_' . $theme_id; ?> .wdi_thumb_likes {
              width: <?php echo ($style['mas_photo_meta_one_line']=='1')? '50%' : '100%' ?>; /*photo_meta_one_line==false else 100%*/
              float: <?php echo ($style['mas_photo_meta_one_line']=='1')? 'left' : 'none'?>; /*photo_meta_one_line==true else float none*/
              font-size: <?php echo $style['mas_like_comm_font_size']?>; /*photo_caption_font_size*/;
              color: <?php echo $style['mas_like_text_color']?>; /*like_text_color*/

          }

          .wdi_feed_<?php echo 'masonry_' . $theme_id; ?> .wdi_thumb_comments {
              width: <?php echo ($style['mas_photo_meta_one_line']=='1')? '50%' : '100%' ?>; /*photo_meta_one_line==false else 100%*/
              float: <?php echo ($style['mas_photo_meta_one_line']=='1')? 'left' : 'none'?>; /*photo_meta_one_line==true else float none*/
              font-size: <?php echo $style['mas_like_comm_font_size']?>; /*photo_caption_font_size*/;
              color: <?php echo $style['mas_comment_text_color']?>; /*comment_text_color*/

          }

          .wdi_feed_<?php echo 'masonry_' . $theme_id; ?> .wdi_photo_title {
              font-size: <?php echo $style['mas_photo_caption_font_size']?>; /*photo_caption_font_size*/
              color: <?php echo $style['mas_photo_caption_color']?>; /*photo_caption_color*/
          }

          .wdi_feed_<?php echo 'masonry_' . $theme_id; ?> .wdi_photo_title:hover {
              color: <?php echo $style['mas_photo_caption_hover_color']?>;
          }

          .wdi_feed_<?php echo 'masonry_' . $theme_id; ?> .wdi_load_more_spinner {
              color: <?php echo $style['load_more_text_color']?>; /*load_more_text_color*/
              font-size: <?php echo intval($style['load_more_text_font_size'])*1.5?>px; /*load_more_text_font_size*/
          }

          .wdi_feed_<?php echo 'masonry_' . $theme_id; ?> .wdi_load_more,
          .wdi_feed_<?php echo 'masonry_' . $theme_id; ?> .wdi_spinner {
              background-color: <?php echo $style['feed_container_bg_color']?>; /*feed_container_bg_color*/
              text-align: <?php echo $style['load_more_position']?>; /*load_more_position*/
          }

          .wdi_feed_<?php echo 'masonry_' . $theme_id; ?> .wdi_load_more_wrap,
          .wdi_feed_<?php echo 'masonry_' . $theme_id; ?> .wdi_spinner_wrap {
              padding: <?php echo $style['load_more_padding']?>; /*load_more_padding*/
              background-color: <?php echo $style['load_more_bg_color']?>; /*load_more_bg_color*/
              border-radius: <?php echo $style['load_more_border_radius']?>; /*load_more_border_radius*/
              height: <?php echo $style['load_more_height']?>; /*load_more_height*/
              width: <?php echo $style['load_more_width']?>; /*load_more_width*/
              border: <?php echo $style['load_more_border_size']?> solid <?php echo $style['load_more_border_color']?>; /*load_more_border_size, load_more_border_color*/;
          }

          <?php
            require_once(WDI_DIR . '/framework/WDILibrary.php');
            $rgb_overlay = WDILibrary::wdi_spider_hex2rgb($style['mas_overlay_hover_color']);
            $rgba_overlay = 'rgba('. $rgb_overlay['red'] .',' . $rgb_overlay['green'] . ',' . $rgb_overlay['blue'] . ','.((100 - intval($style['mas_overlay_hover_transparent']))/100).')';
          ?>

          .wdi_feed_<?php echo 'masonry_' . $theme_id; ?> .wdi_photo_overlay:hover {
              background-color: <?php echo $rgba_overlay;?>
          }

          .wdi_feed_<?php echo 'masonry_' . $theme_id; ?> .wdi_photo_overlay i {
              font-size: <?php echo $style['th_overlay_hover_icon_font_size'];?>;
          }

          .wdi_feed_<?php echo 'masonry_' . $theme_id; ?> .wdi_photo_overlay:hover i {
              color: <?php echo $style['th_overlay_hover_icon_color'];?>
          }

          <?php
            switch($style['mas_photo_img_hover_effect']){
              case 'rotate': {
                $effect = 'rotate(5deg)';
                break;
              }
              case 'scale':{
                $effect = 'scale(1.2)';
                break;
              }
              case 'rotate_and_scale':{
                $effect = 'scale(1.2) rotate(5deg)';
                break;
              }
              default:{
                $effect = 'none';
                break;
              }
            }
          ?>
          .wdi_feed_<?php echo 'masonry_' . $theme_id; ?> .wdi_photo_img:hover img {
              transform: <?php echo $effect?>
          }

          .wdi_feed_<?php echo 'masonry_' . $theme_id; ?> .wdi_filter_active_bg {
              background-color: <?php echo $style['active_filter_bg_color'];?>;
          }

          .wdi_feed_<?php echo 'masonry_' . $theme_id; ?> .wdi_media_user {
              color: <?php echo $style['mas_thumb_user_color'];?>;
              background-color: <?php echo $style['mas_thumb_user_bg_color'];?>;
          }

      </style>
    <?php

    $css = ob_get_contents();
    ob_end_clean();

    $css = strip_tags($css);

    return $css;
  }

  public function blog_view_styles(){
    $style = $this->theme_options;
    $theme_id = $this->theme_id;
    ob_start();
    ?>
      <style>
          .wdi_feed_<?php echo 'blog_' . $theme_id; ?> .wdi_feed_container {
              width: <?php echo $style['feed_container_width']?>;
              background-color: <?php echo $style['feed_container_bg_color']?>; /*feed_container_bg_color*/
          }

          .wdi_feed_<?php echo 'blog_' . $theme_id; ?> .wdi_header_img_wrap,
          .wdi_feed_<?php echo 'blog_' . $theme_id; ?> .wdi_users_img_wrap {
              height: <?php echo $style['header_img_width']?>px; /*header_img_width*/
              width: <?php echo $style['header_img_width']?>px; /*header_img_width*/
              border-radius: <?php echo $style['header_border_radius']?>px; /*header_img_width*/
          }

          .wdi_feed_<?php echo 'blog_' . $theme_id; ?> .wdi_photo_wrap {
              padding: <?php echo $style['blog_style_photo_wrap_padding']?>; /*photo_wrap_padding*/
          }

          .wdi_feed_<?php echo 'blog_' . $theme_id; ?> .wdi_photo_wrap_inner {
              border: <?php echo $style['blog_style_photo_wrap_border_size']?> solid <?php echo $style['blog_style_photo_wrap_border_color']?>; /*photo_wrap_border_size,photo_wrap_border_color*/
              background-color: <?php echo $style['blog_style_photo_wrap_bg_color']?>; /*photo_wrap_bg_color*/
          }

          .wdi_feed_<?php echo 'blog_' . $theme_id; ?> .wdi_photo_img {
              border-radius: <?php echo $style['blog_style_photo_img_border_radius']?>; /*photo_img_border_radius*/
          }

          .wdi_feed_<?php echo 'blog_' . $theme_id; ?> .wdi_photo_meta {
              background-color: <?php echo $style['blog_style_photo_meta_bg_color']?>; /*photo_meta_bg_color*/
          }

          .wdi_feed_<?php echo 'blog_' . $theme_id; ?> .wdi_thumb_likes {
              width: <?php echo ($style['blog_style_photo_meta_one_line']=='1')? '50%' : '100%' ?>; /*photo_meta_one_line==false else 100%*/
              float: <?php echo ($style['blog_style_photo_meta_one_line']=='1')? 'left' : 'none'?>; /*photo_meta_one_line==true else float none*/
              font-size: <?php echo $style['blog_style_like_comm_font_size']?>; /*photo_caption_font_size*/;
              color: <?php echo $style['blog_style_like_text_color']?>; /*like_text_color*/

          }

          .wdi_feed_<?php echo 'blog_' . $theme_id; ?> .wdi_thumb_comments {
              width: <?php echo ($style['blog_style_photo_meta_one_line']=='1')? '50%' : '100%' ?>; /*photo_meta_one_line==false else 100%*/
              float: <?php echo ($style['blog_style_photo_meta_one_line']=='1')? 'left' : 'none'?>; /*photo_meta_one_line==true else float none*/
              font-size: <?php echo $style['blog_style_like_comm_font_size']?>; /*photo_caption_font_size*/;
              color: <?php echo $style['blog_style_comment_text_color']?>; /*comment_text_color*/

          }

          .wdi_feed_<?php echo 'blog_' . $theme_id; ?> .wdi_photo_title {
              font-size: <?php echo $style['blog_style_photo_caption_font_size']?>; /*photo_caption_font_size*/
              color: <?php echo $style['blog_style_photo_caption_color']?>; /*photo_caption_color*/
          }

          .wdi_feed_<?php echo 'blog_' . $theme_id; ?> .wdi_photo_title:hover {
              color: <?php echo $style['blog_style_photo_caption_hover_color']?>;
          }

          .wdi_feed_<?php echo 'blog_' . $theme_id; ?> .wdi_load_more_spinner {
              color: <?php echo $style['load_more_text_color']?>; /*load_more_text_color*/
              font-size: <?php echo intval($style['load_more_text_font_size'])*1.5?>px; /*load_more_text_font_size*/
          }

          .wdi_feed_<?php echo 'blog_' . $theme_id; ?> .wdi_load_more,
          .wdi_feed_<?php echo 'blog_' . $theme_id; ?> .wdi_spinner {
              background-color: <?php echo $style['feed_container_bg_color']?>; /*feed_container_bg_color*/
              text-align: <?php echo $style['load_more_position']?>; /*load_more_position*/
          }

          .wdi_feed_<?php echo 'blog_' . $theme_id; ?> .wdi_load_more_wrap,
          .wdi_feed_<?php echo 'blog_' . $theme_id; ?> .wdi_spinner_wrap {
              padding: <?php echo $style['load_more_padding']?>; /*load_more_padding*/
              background-color: <?php echo $style['load_more_bg_color']?>; /*load_more_bg_color*/
              border-radius: <?php echo $style['load_more_border_radius']?>; /*load_more_border_radius*/
              height: <?php echo $style['load_more_height']?>; /*load_more_height*/
              width: <?php echo $style['load_more_width']?>; /*load_more_width*/
              border: <?php echo $style['load_more_border_size']?> solid <?php echo $style['load_more_border_color']?>; /*load_more_border_size, load_more_border_color*/;
          }

      </style>
    <?php
    $css = ob_get_contents();
    ob_end_clean();

    $css = strip_tags($css);

    return $css;
  }

  public function browser_view_styles(){
    $style = $this->theme_options;
    $theme_id = $this->theme_id;
    ob_start();
    ?>
      <style>
          .wdi_feed_<?php echo 'browser_' . $theme_id; ?> .wdi_feed_container {
              width: <?php echo $style['feed_container_width']?>;
              background-color: <?php echo $style['feed_container_bg_color']?>; /*feed_container_bg_color*/
              border-bottom: 5px solid <?php echo $style['feed_container_bg_color']?>; /*feed_container_bg_color*/;
          }

          .wdi_feed_<?php echo 'browser_' . $theme_id; ?> .wdi_header_img_wrap,
          .wdi_feed_<?php echo 'browser_' . $theme_id; ?> .wdi_users_img_wrap {
              height: <?php echo $style['header_img_width']?>px; /*header_img_width*/
              width: <?php echo $style['header_img_width']?>px; /*header_img_width*/
              border-radius: <?php echo $style['header_border_radius']?>px; /*header_img_width*/
          }

          .wdi_feed_<?php echo 'browser_' . $theme_id; ?> .wdi_photo_wrap {
              padding: <?php echo $style['image_browser_photo_wrap_padding']?>; /*photo_wrap_padding*/

          }

          .wdi_feed_<?php echo 'browser_' . $theme_id; ?> .wdi_photo_wrap_inner {
              border: <?php echo $style['image_browser_photo_wrap_border_size']?> solid <?php echo $style['image_browser_photo_wrap_border_color']?>; /*photo_wrap_border_size,photo_wrap_border_color*/
              background-color: <?php echo $style['image_browser_photo_wrap_bg_color']?>; /*photo_wrap_bg_color*/
          }

          .wdi_feed_<?php echo 'browser_' . $theme_id; ?> .wdi_photo_img {
              border-radius: <?php echo $style['image_browser_photo_img_border_radius']?>; /*photo_img_border_radius*/
          }

          .wdi_feed_<?php echo 'browser_' . $theme_id; ?> .wdi_photo_meta {
              background-color: <?php echo $style['image_browser_photo_meta_bg_color']?>; /*photo_meta_bg_color*/
          }

          .wdi_feed_<?php echo 'browser_' . $theme_id; ?> .wdi_thumb_likes {
              width: <?php echo ($style['image_browser_photo_meta_one_line']=='1')? '50%' : '100%' ?>; /*photo_meta_one_line==false else 100%*/
              float: <?php echo ($style['image_browser_photo_meta_one_line']=='1')? 'left' : 'none'?>; /*photo_meta_one_line==true else float none*/
              font-size: <?php echo $style['image_browser_like_comm_font_size']?>; /*photo_caption_font_size*/;
              color: <?php echo $style['image_browser_like_text_color']?>; /*like_text_color*/

          }

          .wdi_feed_<?php echo 'browser_' . $theme_id; ?> .wdi_thumb_comments {
              width: <?php echo ($style['image_browser_photo_meta_one_line']=='1')? '50%' : '100%' ?>; /*photo_meta_one_line==false else 100%*/
              float: <?php echo ($style['image_browser_photo_meta_one_line']=='1')? 'left' : 'none'?>; /*photo_meta_one_line==true else float none*/
              font-size: <?php echo $style['image_browser_like_comm_font_size']?>; /*photo_caption_font_size*/;
              color: <?php echo $style['image_browser_comment_text_color']?>; /*comment_text_color*/

          }

          .wdi_feed_<?php echo 'browser_' . $theme_id; ?> .wdi_photo_title {
              font-size: <?php echo $style['image_browser_photo_caption_font_size']?>; /*photo_caption_font_size*/
              color: <?php echo $style['image_browser_photo_caption_color']?>; /*photo_caption_color*/
          }

          .wdi_feed_<?php echo 'browser_' . $theme_id; ?> .wdi_photo_title:hover {
              color: <?php echo $style['image_browser_photo_caption_hover_color']?>;
          }

          .wdi_feed_<?php echo 'browser_' . $theme_id; ?> .wdi_load_more {
              background-color: <?php echo $style['feed_container_bg_color']?>; /*feed_container_bg_color*/
              text-align: <?php echo $style['load_more_position']?>; /*load_more_position*/
          }

          .wdi_feed_<?php echo 'browser_' . $theme_id; ?> .wdi_load_more_wrap {
              padding: <?php echo $style['load_more_padding']?>; /*load_more_padding*/
              background-color: <?php echo $style['load_more_bg_color']?>; /*load_more_bg_color*/
              border-radius: <?php echo $style['load_more_border_radius']?>; /*load_more_border_radius*/
              height: <?php echo $style['load_more_height']?>; /*load_more_height*/
              width: <?php echo $style['load_more_width']?>; /*load_more_width*/
              border: <?php echo $style['load_more_border_size']?> solid <?php echo $style['load_more_border_color']?>; /*load_more_border_size, load_more_border_color*/;
          }

      </style>
    <?php
    $css = ob_get_contents();
    ob_end_clean();

    $css = strip_tags($css);

    return $css;
  }

  public function lightbox_styles(){
    $style = $this->theme_options;
    $theme_id = $this->theme_id;
    $rgb_lightbox_ctrl_cont_bg_color = WDILibrary::wdi_spider_hex2rgb($style['lightbox_ctrl_cont_bg_color']);
    $rgb_wdi_image_info_bg_color = WDILibrary::wdi_spider_hex2rgb($style['lightbox_info_bg_color']);

    $filmstrip_direction = 'horizontal';
    if ($style['lightbox_filmstrip_pos'] == 'right' || $style['lightbox_filmstrip_pos'] == 'left') {
      $filmstrip_direction = 'vertical';
    }

    $lb_container = '.wdi_lightbox_theme_' . $theme_id;
    $feed_conatiner = '.wdi_feed_theme_' . $theme_id;
    ob_start();
    ?>
      <style>
          /*@import url(https://fonts.googleapis.com/css?family=Maven+Pro:400,500);*/
          .wdi_spider_popup_wrap * {
              -moz-box-sizing: border-box;
              -webkit-box-sizing: border-box;
              box-sizing: border-box;
          }

          <?php echo $feed_conatiner; ?> .wdi_spider_popup_overlay {
              background-color: <?php echo $style['lightbox_overlay_bg_color']; ?>;
              opacity: <?php echo (intval($style['lightbox_overlay_bg_transparent'])/100); ?>;
          }

          <?php echo $lb_container; ?>.wdi_spider_popup_wrap {
              background-color: <?php echo $style['lightbox_bg_color']; ?>;
              display: inline-block;
              left: 50%;
              outline: medium none;
              position: fixed;
              text-align: center;
              top: 50%;
              z-index: 100000;
          }

          <?php echo $lb_container; ?> .wdi_ctrl_btn {
              color: <?php echo $style['lightbox_ctrl_btn_color']; ?>;
              font-size: <?php echo $style['lightbox_ctrl_btn_height']; ?>px;
              margin: <?php echo $style['lightbox_ctrl_btn_margin_top']; ?>px <?php echo $style['lightbox_ctrl_btn_margin_left']; ?>px;
              opacity: <?php echo number_format($style['lightbox_ctrl_btn_transparent'] / 100, 2, ".", ""); ?>;
              filter: Alpha(opacity=<?php echo $style['lightbox_ctrl_btn_transparent']; ?>);
          }

          <?php echo $lb_container; ?> .wdi_toggle_btn {
              color: <?php echo $style['lightbox_ctrl_btn_color']; ?>;
              font-size: <?php echo $style['lightbox_toggle_btn_height']; ?>px;
              margin: 0;
              opacity: <?php echo number_format($style['lightbox_ctrl_btn_transparent'] / 100, 2, ".", ""); ?>;
              filter: Alpha(opacity=<?php echo $style['lightbox_ctrl_btn_transparent']; ?>);
              padding: 0;
          }

          .wdi_btn_container {
              bottom: 0;
              left: 0;
              overflow: hidden;
              position: absolute;
              right: 0;
              top: 0;
          }

          <?php echo $lb_container; ?>  .wdi_ctrl_btn_container {
              background-color: rgba(<?php echo $rgb_lightbox_ctrl_cont_bg_color['red']; ?>, <?php echo $rgb_lightbox_ctrl_cont_bg_color['green']; ?>, <?php echo $rgb_lightbox_ctrl_cont_bg_color['blue']; ?>, <?php echo number_format($style['lightbox_ctrl_cont_transparent'] / 100, 2, ".", ""); ?>);
              /*background: none repeat scroll 0 0 #
          <?php echo $style['lightbox_ctrl_cont_bg_color']; ?> ;*/
          <?php
          if ($style['lightbox_ctrl_btn_pos'] == 'top') { ?> border-bottom-left-radius: <?php echo $style['lightbox_ctrl_cont_border_radius']; ?>px;
              border-bottom-right-radius: <?php echo $style['lightbox_ctrl_cont_border_radius']; ?>px;
          <?php } else { ?> bottom: 0;
              border-top-left-radius: <?php echo $style['lightbox_ctrl_cont_border_radius']; ?>px;
              border-top-right-radius: <?php echo $style['lightbox_ctrl_cont_border_radius']; ?>px;
          <?php } ?> height: <?php echo $style['lightbox_ctrl_btn_height'] + 2 * $style['lightbox_ctrl_btn_margin_top']; ?>px;
              /*opacity:
          <?php echo number_format($style['lightbox_ctrl_cont_transparent'] / 100, 2, ".", ""); ?> ;
        filter: Alpha(opacity=
          <?php echo $style['lightbox_ctrl_cont_transparent']; ?> );*/
              position: absolute;
              text-align: <?php echo $style['lightbox_ctrl_btn_align']; ?>;
              width: 100%;
              z-index: 10150;
              padding: 8px;
          }

          <?php echo $lb_container; ?> .wdi_toggle_container {
              background: none repeat scroll 0 0 <?php echo $style['lightbox_ctrl_cont_bg_color']; ?>;
          <?php if ($style['lightbox_ctrl_btn_pos'] == 'top') { ?>
              border-bottom-left-radius: <?php echo $style['lightbox_ctrl_cont_border_radius']; ?>px;
              border-bottom-right-radius: <?php echo $style['lightbox_ctrl_cont_border_radius']; ?>px;
              border-radius: <?php echo $style['lightbox_ctrl_cont_border_radius']; ?>px;
              top: <?php echo $style['lightbox_ctrl_btn_height'] + 2 * $style['lightbox_ctrl_btn_margin_top']; ?>px;
          <?php } else { ?>
              border-top-left-radius: <?php echo $style['lightbox_ctrl_cont_border_radius']; ?>px;
              border-top-right-radius: <?php echo $style['lightbox_ctrl_cont_border_radius']; ?>px;
              border-radius: <?php echo $style['lightbox_ctrl_cont_border_radius']; ?>px;
              bottom: <?php echo $style['lightbox_ctrl_btn_height'] + 2 * $style['lightbox_ctrl_btn_margin_top']; ?>px;
          <?php }?>
              cursor: pointer;
              left: 50%;
              line-height: 0;
              margin-left: -<?php echo $style['lightbox_toggle_btn_width'] / 2; ?>px;
              opacity: <?php echo number_format($style['lightbox_ctrl_cont_transparent'] / 100, 2, ".", ""); ?>;
              filter: Alpha(opacity=<?php echo $style['lightbox_ctrl_cont_transparent']; ?>);
              position: absolute;
              text-align: center;
              width: <?php echo $style['lightbox_toggle_btn_width']; ?>px;
              z-index: 10149;
          }

          <?php echo $lb_container; ?> .wdi_close_btn {
              opacity: <?php echo number_format($style['lightbox_close_btn_transparent'] / 100, 2, ".", ""); ?>;
              filter: Alpha(opacity=<?php echo $style['lightbox_close_btn_transparent']; ?>);
          }

          <?php echo $lb_container; ?> .wdi_spider_popup_close {
              background-color: <?php echo $style['lightbox_close_btn_bg_color']; ?>;
              border-radius: <?php echo $style['lightbox_close_btn_border_radius']; ?>px;
              border: <?php echo $style['lightbox_close_btn_border_width']; ?>px <?php echo $style['lightbox_close_btn_border_style']; ?> <?php echo $style['lightbox_close_btn_border_color']; ?>;
              box-shadow: <?php echo $style['lightbox_close_btn_box_shadow']; ?>;
              color: <?php echo $style['lightbox_close_btn_color']; ?>;
              height: <?php echo $style['lightbox_close_btn_height']; ?>px;
              font-size: <?php echo $style['lightbox_close_btn_size']; ?>px;
              right: <?php echo $style['lightbox_close_btn_right']; ?>px;
              top: <?php echo $style['lightbox_close_btn_top']; ?>px;
              width: <?php echo $style['lightbox_close_btn_width']; ?>px;
          }

          <?php echo $lb_container; ?> .wdi_spider_popup_close_fullscreen {
              color: <?php echo $style['lightbox_close_btn_full_color']; ?>;
              font-size: <?php echo $style['lightbox_close_btn_size']; ?>px;
              right: 15px;
          }

          .wdi_spider_popup_close span,
          #wdi_spider_popup_left-ico span,
          #wdi_spider_popup_right-ico span {
              display: table-cell;
              text-align: center;
              vertical-align: middle;
          }

          <?php echo $lb_container; ?> #wdi_spider_popup_left-ico,
          <?php echo $lb_container; ?>  #wdi_spider_popup_right-ico {
              background-color: <?php echo $style['lightbox_rl_btn_bg_color']; ?>;
              /***/
              border-radius: <?php echo $style['lightbox_rl_btn_border_radius']; ?>px;
              /***/
              border: <?php echo $style['lightbox_rl_btn_border_width']; ?>px <?php echo $style['lightbox_rl_btn_border_style']; ?> <?php echo $style['lightbox_rl_btn_border_color']; ?>;
              box-shadow: <?php echo $style['lightbox_rl_btn_box_shadow']; ?>;
              color: <?php echo $style['lightbox_rl_btn_color']; ?>;
              height: <?php echo $style['lightbox_rl_btn_height']; ?>px;
              font-size: <?php echo $style['lightbox_rl_btn_size']; ?>px;
              width: <?php echo $style['lightbox_rl_btn_width']; ?>px;
              opacity: <?php echo number_format($style['lightbox_rl_btn_transparent'] / 100, 2, ".", ""); ?>;
              filter: Alpha(opacity=<?php echo $style['lightbox_rl_btn_transparent']; ?>);
          }

          <?php echo $lb_container; ?> .wdi_ctrl_btn:hover,
           <?php echo $lb_container; ?> .wdi_toggle_btn:hover,
           <?php echo $lb_container; ?> #wdi_spider_popup_left-ico:hover,
           <?php echo $lb_container; ?> #wdi_spider_popup_right-ico:hover {
              color: <?php echo $style['lightbox_close_rl_btn_hover_color']; ?>;
              cursor: pointer;
          }

          <?php echo $lb_container; ?> .wdi_spider_popup_close:hover,
          <?php echo $lb_container; ?> .wdi_spider_popup_close_fullscreen:hover{
              color:  <?php echo $style['lightbox_close_btn_hover_color'];?>;
              cursor: pointer;
          }

          .wdi_image_wrap {
              height: inherit;
              display: table;
              position: absolute;
              text-align: center;
              width: inherit;
          }
          .wdi_image_wrap * {
              -moz-user-select: none;
              -khtml-user-select: none;
              -webkit-user-select: none;
              -ms-user-select: none;
              user-select: none;
          }
          .wdi_embed_frame{
              text-align:center;
          }
          .wdi_comment_wrap {
              bottom: 0;
              left: 0;
              overflow: hidden;
              position: absolute;
              right: 0;
              top: 0;
              z-index: -1;
          }

          <?php echo $lb_container; ?> .wdi_comment_container {
              -moz-box-sizing: border-box;
              background-color: <?php echo $style['lightbox_comment_bg_color']; ?>;
              color: <?php echo $style['lightbox_comment_font_color']; ?>;
              font-size: <?php echo $style['lightbox_comment_font_size']; ?>px;
              font-family: <?php echo $style['lightbox_comment_font_style']; ?>;
              height: 100%;
              overflow: hidden;
              position: absolute;
          <?php echo $style['lightbox_comment_pos']; ?>: -<?php echo $style['lightbox_comment_width']; ?>px;
              top: 0;
              width: <?php echo $style['lightbox_comment_width']; ?>px;
              z-index: 10103;
          }

          <?php echo $lb_container; ?> .wdi_comments {
              bottom: 0;
              font-size: <?php echo $style['lightbox_comment_font_size']; ?>px;
              font-family: <?php echo $style['lightbox_comment_font_style']; ?>;
              height: 100%;
              left: 0;
              overflow-x: hidden;
              overflow-y: auto;
              position: absolute;
              top: 0;
              width: 100%;
              z-index: 10101;
          }

          .wdi_comments p,
          .wdi_comment_body_p {
              margin: 5px !important;
              text-align: left;
              word-wrap: break-word;
              word-break: break-word;
          }
          .wdi_no_comment{
              text-align: center !important;
          }

          <?php echo $lb_container; ?> .wdi_comments input[type="submit"] {
              background: none repeat scroll 0 0 <?php echo $style['lightbox_comment_button_bg_color']; ?>;
              border: <?php echo $style['lightbox_comment_button_border_width']; ?>px <?php echo $style['lightbox_comment_button_border_style']; ?> <?php echo $style['lightbox_comment_button_border_color']; ?>;
              border-radius: <?php echo $style['lightbox_comment_button_border_radius']; ?>px;
              color: <?php echo $style['lightbox_comment_font_color']; ?>;
              cursor: pointer;
              padding: <?php echo $style['lightbox_comment_button_padding']; ?>;
          }


          <?php echo $lb_container; ?> .wdi_comments input[type="text"],
          <?php echo $lb_container; ?> .wdi_comments textarea {
              background: none repeat scroll 0 0 <?php echo $style['lightbox_comment_input_bg_color']; ?>;
              border: <?php echo $style['lightbox_comment_input_border_width']; ?>px <?php echo $style['lightbox_comment_input_border_style']; ?> <?php echo $style['lightbox_comment_input_border_color']; ?>;
              /***/
              border-radius: <?php echo $style['lightbox_comment_input_border_radius']; ?>px;
              /***/
              color: <?php echo $style['lightbox_comment_font_color']; ?>;
              padding: <?php echo $style['lightbox_comment_input_padding']; ?>;
              width: 100%;
          }

          .wdi_comments textarea {
              resize: vertical;
          }
          <?php echo $lb_container; ?> .wdi_comment_header_p {
              border-top: <?php echo $style['lightbox_comment_separator_width']; ?>px <?php echo $style['lightbox_comment_separator_style']; ?> <?php echo $style['lightbox_comment_separator_color']; ?>;
          }

          <?php echo $lb_container; ?> .wdi_comment_header {
              margin-top: 2px;
              display: inline-block;
              color: <?php echo $style['lightbox_comment_font_color']; ?>;
              font-size: <?php echo $style['lightbox_comment_author_font_size']; ?>px;
          }

          <?php echo $lb_container; ?> .wdi_comment_date {
              color: <?php echo $style['lightbox_comment_font_color']; ?>;
              float: right;
              font-size: <?php echo $style['lightbox_comment_date_font_size']; ?>px;
          }

          <?php echo $lb_container; ?> .wdi_comment_body {
              color: <?php echo $style['lightbox_comment_font_color']; ?>;
              font-size: <?php echo $style['lightbox_comment_body_font_size']; ?>px;
          }
          .wdi_comment_delete_btn {
              color: #FFFFFF;
              cursor: pointer;
              float: right;
              font-size: 14px;
              margin: 2px;
          }

          <?php echo $lb_container; ?> .wdi_comments_close {
              cursor: pointer;
              line-height: 0;
              position: absolute;
              font-size: 13px;
              text-align: <?php echo (($style['lightbox_comment_pos'] == 'left') ? 'right' : 'left'); ?>;
              margin: 5px;
          <?php echo (($style['lightbox_comment_pos'] == 'left') ? 'right' : 'left'); ?> : 0;
              top: 0;
              background-color: <?php echo $style['lightbox_comment_bg_color']; ?>;
              z-index: 10202;
          }

          .wdi_comments_close i{
              z-index: 10201;
          }
          .wdi_comment_textarea::-webkit-scrollbar {
              width: 4px;
          }
          .wdi_comment_textarea::-webkit-scrollbar-track {
          }
          .wdi_comment_textarea::-webkit-scrollbar-thumb {
              background-color: rgba(255, 255, 255, 0.55);
              border-radius: 2px;
          }
          .wdi_comment_textarea::-webkit-scrollbar-thumb:hover {
              background-color: #D9D9D9;
          }
          .wdi_ctrl_btn_container a,
          .wdi_ctrl_btn_container a:hover {
              text-decoration: none;
          }

          .wdi_facebook:hover {
              color: #3B5998 !important;
              cursor: pointer;
          }
          .wdi_twitter:hover {
              color: #4099FB !important;
              cursor: pointer;
          }
          .wdi_google:hover {
              color: #DD4B39 !important;
              cursor: pointer;
          }
          .wdi_pinterest:hover {
              color: #cb2027 !important;
              cursor: pointer;
          }
          .wdi_tumblr:hover {
              color: #2F5070 !important;
              cursor: pointer;
          }
          .wdi_linkedin:hover {
              color: #0077B5 !important;
              cursor: pointer;
          }

          <?php echo $lb_container; ?> .wdi_facebook,
          <?php echo $lb_container; ?> .wdi_twitter,
          <?php echo $lb_container; ?> .wdi_google,
          <?php echo $lb_container; ?> .wdi_pinterest,
          <?php echo $lb_container; ?> .wdi_tumblr,
          <?php echo $lb_container; ?> .wdi_linkedin {
              color: <?php echo $style['lightbox_comment_share_button_color']; ?> !important;
          }

          <?php echo $lb_container; ?> .wdi_thumb_active {
              opacity: 1;
              filter: Alpha(opacity=100);
              border: <?php echo $style['lightbox_filmstrip_thumb_active_border_width']; ?>px solid <?php echo $style['lightbox_filmstrip_thumb_active_border_color']; ?>;
          }

          <?php echo $lb_container; ?> .wdi_thumb_deactive {
              opacity: <?php echo number_format($style['lightbox_filmstrip_thumb_deactive_transparent'] / 100, 2, ".", ""); ?>;
              filter: Alpha(opacity=<?php echo $style['lightbox_filmstrip_thumb_deactive_transparent']; ?>);
          }
          <?php echo $lb_container; ?> .wdi_filmstrip_thumbnail_img {
              display: block;
              opacity: 1;
              filter: Alpha(opacity=100);
          }

          <?php echo $lb_container; ?> .wdi_filmstrip_left i,
          <?php echo $lb_container; ?> .wdi_filmstrip_right i {
              color: <?php echo $style['lightbox_filmstrip_rl_btn_color']; ?>;
              font-size: <?php echo $style['lightbox_filmstrip_rl_btn_size']; ?>px;
          }

          .wdi_none_selectable {
              -webkit-touch-callout: none;
              -webkit-user-select: none;
              -khtml-user-select: none;
              -moz-user-select: none;
              -ms-user-select: none;
              user-select: none;
          }
          .wdi_slide_container {
              display: table-cell;
              position: absolute;
              vertical-align: middle;
              width: 100%;
              height: 100%;
          }
          .wdi_slide_bg {
              margin: 0 auto;
              width: inherit;
              height: inherit;
          }
          .wdi_slider {
              height: inherit;
              width: inherit;
          }
          .wdi_popup_image_spun {
              height: inherit;
              display: table-cell;
              filter: Alpha(opacity=100);
              opacity: 1;
              position: absolute;
              vertical-align: middle;
              width: inherit;
              z-index: 2;
          }
          .wdi_popup_image_second_spun {
              width: inherit;
              height: inherit;
              display: table-cell;
              filter: Alpha(opacity=0);
              opacity: 0;
              position: absolute;
              vertical-align: middle;
              z-index: 1;
          }
          .wdi_grid {
              display: none;
              height: 100%;
              overflow: hidden;
              position: absolute;
              width: 100%;
          }
          .wdi_gridlet {
              opacity: 1;
              filter: Alpha(opacity=100);
              position: absolute;
          }

          <?php echo $lb_container; ?> .wdi_image_info_spun {
              text-align: <?php echo $style['lightbox_info_align']; ?>;
              vertical-align: <?php echo $style['lightbox_info_pos']; ?>;
          }

          <?php echo $lb_container; ?> .wdi_image_title,
          <?php echo $lb_container; ?> .wdi_image_title * {
              color: <?php echo $style['lightbox_title_color']; ?> !important;
              font-family: <?php echo $style['lightbox_title_font_style']; ?>;
              font-size: <?php echo $style['lightbox_title_font_size']; ?>px;
              font-weight: <?php echo $style['lightbox_title_font_weight']; ?>;
          }

          <?php echo $lb_container; ?> .wdi_image_description,
          <?php echo $lb_container; ?> .wdi_image_description * {
              color: <?php echo $style['lightbox_description_color']; ?> !important;
              font-family: <?php echo $style['lightbox_description_font_style']; ?>;
              font-size: <?php echo $style['lightbox_description_font_size']; ?>px;
              font-weight: <?php echo $style['lightbox_description_font_weight']; ?>;
          }

          .wdi_title{
              display: inline-table;
          }
          .wdi_title:hover{
              cursor: pointer;
          }
          .wdi_title .wdi_users_img_wrap{
              width: 50px;
              display: table-cell;
              vertical-align: middle;
              float: right;
              padding-left: 10px;
              overflow: hidden;
          }
          .wdi_title .wdi_users_img_wrap img{
              width: 100%;
          }
          .wdi_title .wdi_header_text{
              display: table-cell;
              vertical-align: middle;
              word-break: break-all;
          }

          <?php echo $lb_container ; ?> .wdi_share_btns{
              position: absolute;
            <?php $shareBtn = $style['lightbox_ctrl_btn_height'] + $style['lightbox_ctrl_btn_margin_top'] + 8; ?>
          <?php echo ($style['lightbox_ctrl_btn_pos'] == 'top') ? 'bottom' : 'top';?>: <?php echo -$shareBtn;?>px;
              height: <?php echo $style['lightbox_ctrl_btn_height'] + $style['lightbox_ctrl_btn_margin_top'];?>px;
              width: <?php echo $style['lightbox_ctrl_btn_height']*7.5;?>px;
              border-radius: 3px;
              right: -145px;
              margin: 0;
              z-index: 200000;
              background-color: rgba(<?php echo $rgb_lightbox_ctrl_cont_bg_color['red']; ?>, <?php echo $rgb_lightbox_ctrl_cont_bg_color['green']; ?>, <?php echo $rgb_lightbox_ctrl_cont_bg_color['blue']; ?>, <?php echo number_format($style['lightbox_ctrl_cont_transparent'] / 100, 2, ".", ""); ?>);
              padding-top: 5px;
          }

          .wdi_share_btns_container{
              position: relative;
              display: inline-block;
          }

          <?php echo $lb_container ; ?> .wdi_share_popup_btn{
              color: <?php echo $style['lightbox_ctrl_btn_color']; ?>;
              padding: 3px;
              opacity: <?php echo number_format($style['lightbox_ctrl_btn_transparent'] / 100, 2, ".", ""); ?>;
              filter: Alpha(opacity=<?php echo $style['lightbox_ctrl_btn_transparent']; ?>);
              font-size: <?php echo $style['lightbox_ctrl_btn_height'];?>px;
          }

          <?php echo $lb_container ; ?> .wdi_share_caret{
              font-size: 15px;
              color: <?php echo $style['lightbox_ctrl_cont_bg_color']; ?>;
              opacity: <?php echo number_format($style['lightbox_ctrl_cont_transparent'] / 100, 2, ".", ""); ?>;
              position: absolute;
              top: -25px;
              left: 13px;
          }

          .wdi_share_toggler{
              display: block !important;
          }
          .wdi_facebook_btn:hover{
              color: #45619D;
          }
          .wdi_facebook_btn:hover{
              color: #4099FB;
          }

          <?php echo $lb_container;?> .wdi_comment_header a,
          <?php echo $lb_container;?> .wdi_comment_header a:visited{
              color: <?php echo $style['lightbox_comment_author_font_color']; ?> !important;
          }

          <?php echo $lb_container;?> .wdi_comm_text_link,
          <?php echo $lb_container;?> a.wdi_comm_text_link:visited{
              color: <?php echo $style['lightbox_comment_author_font_color']; ?>!important;
          }
          <?php echo $lb_container;?> .wdi_comment_header a:hover,
          <?php echo $lb_container;?> a.wdi_comm_text_link:hover{
              text-decoration: none;
              color: <?php echo $style['lightbox_comment_author_font_color_hover']; ?>!important;
          }

          <?php echo $lb_container;?> .wdi_load_more_comments{
              display: inline-block;
              color: <?php echo $style['lightbox_comment_load_more_color']; ?>;
              background-color: <?php echo $style['lightbox_comment_bg_color']; ?>;
              -webkit-user-select: none; /* Chrome/Safari */
              -moz-user-select: none; /* Firefox */
              -ms-user-select: none; /* IE10+ */
              /* Rules below not implemented in browsers yet */
              -o-user-select: none;
              user-select: none;
              font-family: 'Verdana', sans-serif;
              text-transform: capitalize;
              font-size: 17px;
          }

          <?php echo $lb_container;?> .wdi_load_more_comments:hover{
              color: <?php echo $style['lightbox_comment_load_more_color_hover'];?>;
              cursor: pointer;
          }


          /*partly*/

          .wdi_popup_image {
              vertical-align: middle;
              display: inline-block;
          }


          .wdi_popup_embed {
              vertical-align: middle;
              text-align: center;
              display: inline-block;
          }

          .wdi_image_container{
              display: table;
              position: absolute;
              text-align: center;
              vertical-align: middle;
              width: 100%;
          }

          <?php  echo $lb_container; ?> .wdi_filmstrip_container {
              display: <?php echo ($filmstrip_direction == 'horizontal'? 'table' : 'block'); ?>;
              position: absolute;
              z-index: 10105;
          <?php echo $style['lightbox_filmstrip_pos']; ?>: 0;
          }

          .wdi_filmstrip {
              overflow: hidden;
              position: absolute;
              z-index: 10106;
          }

          .wdi_filmstrip_thumbnails {
              margin: 0 auto;
              overflow: hidden;
              position: relative;
          }

          <?php echo $lb_container; ?> .wdi_filmstrip_thumbnail {
              position: relative;
              background: none;
              border: <?php echo $style['lightbox_filmstrip_thumb_border_width']; ?>px <?php echo $style['lightbox_filmstrip_thumb_border_style']; ?> <?php echo $style['lightbox_filmstrip_thumb_border_color']; ?>;
              border-radius: <?php echo $style['lightbox_filmstrip_thumb_border_radius']; ?>px;
              cursor: pointer;
              float: left;
              margin: <?php echo $style['lightbox_filmstrip_thumb_margin']; ?>;
              overflow: hidden;
          }

          <?php echo $lb_container; ?>  .wdi_filmstrip_left {
              background-color: <?php echo $style['lightbox_filmstrip_rl_bg_color']; ?>;
              cursor: pointer;
              display: <?php echo ($filmstrip_direction == 'horizontal' ? 'table-cell' : 'block') ?>;
              vertical-align: middle;
              z-index: 10106;
              <?php echo ($filmstrip_direction == 'horizontal' ? '' : 'position: absolute;'); ?>
              <?php echo ($filmstrip_direction == 'horizontal' ? '' : 'width: 100%;'); ?>
          }

          <?php echo $lb_container; ?> .wdi_filmstrip_right {
              background-color: <?php echo $style['lightbox_filmstrip_rl_bg_color']; ?>;
              cursor: pointer;
              <?php echo($filmstrip_direction == 'horizontal' ? 'right' : 'bottom') ?>: 0;
              display: <?php echo ($filmstrip_direction == 'horizontal' ? 'table-cell' : 'block') ?>;
              vertical-align: middle;
              z-index: 10106;
              <?php echo ($filmstrip_direction == 'horizontal' ? '' : 'position: absolute;') ?>
              <?php echo ($filmstrip_direction == 'horizontal' ? '' : 'width: 100%;') ?>
          }

          <?php echo $lb_container; ?> .wdi_image_info{
              background: rgba(<?php echo $rgb_wdi_image_info_bg_color['red']; ?>, <?php echo $rgb_wdi_image_info_bg_color['green']; ?>, <?php echo $rgb_wdi_image_info_bg_color['blue']; ?>, <?php echo number_format($style['lightbox_info_bg_transparent'] / 100, 2, ".", ""); ?>);
              border: <?php echo $style['lightbox_info_border_width']; ?>px <?php echo $style['lightbox_info_border_style']; ?> <?php echo $style['lightbox_info_border_color']; ?>;
              border-radius: <?php echo $style['lightbox_info_border_radius']; ?>px;
              padding: <?php echo $style['lightbox_info_padding']; ?>;
              overflow: auto;
          }

          @media (max-width: 480px) {
              .wdi_image_count_container {
                  display: none;
              }

              .wdi_image_title,
              .wdi_image_title * {
                  font-size: 12px;
              }

              .wdi_image_description,
              .wdi_image_description * {
                  font-size: 10px;
              }
          }

          .wdi_image_count_container {
              left: 0;
              line-height: 1;
              position: absolute;
              vertical-align: middle;
          }
      </style>
    <?php
    $css = ob_get_contents();
    ob_end_clean();

    $css = strip_tags($css);

    return $css;
  }

  /**
   * @param string $file_name
   * @param string $file_content
   * @return WP_Error|boolean
   * */
  public function update_file($file_content){

    $file_name = sprintf($this->file_name_format, $this->theme_id);

    $wp_upload_dir = wp_upload_dir();
    $wp_upload_dir = $wp_upload_dir['basedir'];

    $folder = $wp_upload_dir . '/' . $this->folder_name;

    if(!is_dir($folder)) {
      if(mkdir($folder, 0777, true) === false) {
        return new WP_Error('wdi_failed_create_folder');
      }

      if(file_put_contents($folder . '/index.html', "Index file.") === false) {
        return new WP_Error('wdi_failed_create_index_file');
      }

    }

    if(file_put_contents($folder . '/' . $file_name, $file_content) === false) {
      return new WP_Error('wdi_failed_create_css_file');
    }

    $this->refresh_file_key();
    return true;
  }


  /**
   * @param $view_type string
   * @return string empty if file dose not exists or file url
   * */
  public function get_file_url(){
    $file_name = sprintf($this->file_name_format, $this->theme_id);

    $wp_upload_dir = wp_upload_dir();
    $basedir = $wp_upload_dir['basedir'];
    $baseurl = $wp_upload_dir['baseurl'];

    if(is_ssl()) {
      $baseurl = str_replace('http://', 'https://', $baseurl);
    }

    $dir = $basedir . '/' . $this->folder_name . '/' . $file_name;
    if(file_exists($dir)) {
      return $baseurl . '/' . $this->folder_name . '/' . $file_name;
    } else {
      return "";
    }
  }

  public function get_css(){
    return $this->css;
  }

  public function refresh_file_key(){
    $option = get_option('wdi_theme_keys');

    if(!is_array($option)) {
      $option = array();
    }

    $this->file_key = uniqid();
    $option[$this->theme_id] = $this->file_key;
    update_option('wdi_theme_keys', $option);
    return $option;
  }

  public function get_file_key(){
    if($this->file_key !== null) {
      $this->file_key;
    }

    $option = get_option('wdi_theme_keys');
    if(!isset($option[$this->theme_id])) {
      $option = $this->refresh_file_key();
    }

    return $option[$this->theme_id];
  }

}