<?php

class CMCRPR_Pro {

	public static function init() {
		global $cmcrpr_isLicenseOk;

		add_filter( 'manage_edit-' . CMCRPR_Base::POST_TYPE . '_columns', array( __CLASS__, 'editScreenColumns' ) );
//        add_filter('manage_' . CMBusinessDirectoryShared::POST_TYPE . '_pages_custom_column', array(__CLASS__, 'editScreenColumnsContent'), 10, 2);
		add_filter( 'manage_' . CMCRPR_Base::POST_TYPE . '_posts_custom_column', array( __CLASS__, 'editScreenColumnsContent' ), 10, 2 );
		add_filter( 'manage_edit-' . CMCRPR_Base::POST_TYPE . '_sortable_columns', array( __CLASS__, 'editScreenSortableColumns' ) );

		add_filter( 'admin_post_thumbnail_html', array( __CLASS__, 'addFeaturedImageNotice' ), 10, 2 );

		if ( $cmcrpr_isLicenseOk ) {
			add_filter( 'cmcrpr_get_all_plugin_items_single', array( __CLASS__, 'additionalFields' ), 10, 2 );
		}
	}

	public static function addFeaturedImageNotice( $content, $post_id ) {
		$post_type = get_post_type( $post_id );
		if ( CMCRPR_Base::POST_TYPE !== $post_type ) {
			return $content;
		}
		$notice = CMCRPR_Base::__( 'To achieve the best looking results, please ensure that the images you are using have the same/similar width to height ratio.' );
		return $notice . $content;
	}

	public static function editScreenColumns( $columns ) {

		$postition = 1;

		$columns = array_slice( $columns, 0, $postition, true ) +
		array( "thumbnail" => __( 'Image' ) ) +
		array_slice( $columns, $postition, count( $columns ) - $postition, true );

		$postition = 3;

		$columns = array_slice( $columns, 0, $postition, true ) +
		array( "description" => __( 'Description' ) ) +
		array_slice( $columns, $postition, count( $columns ) - $postition, true );

		return $columns;
	}

	public static function editScreenColumnsContent( $column, $post_id ) {

		switch ( $column ) {
			case 'thumbnail' :
				$url = wp_get_attachment_url( get_post_thumbnail_id( $post_id ) );
				if ( empty( $url ) ) {
					return;
				}
				echo '<img width="100" height="100" src="' . esc_attr( $url ) . '">';
				break;
			case 'description' :
				$description = get_post_meta( $post_id, '_cmcrpr_description', true );
				if ( empty( $description ) ) {
					return;
				}
				echo $description;
				break;
		}
	}

	public static function editScreenSortableColumns( $columns ) {
//		$columns = array(
//			'title'			 => 'title',
//			'status'		 => 'status',
//			'categories'	 => 'categories',
//			'tags'			 => 'tags',
//			'purchase_link'	 => 'purchase_link',
//			'info_link'		 => 'info_link',
//			'date'			 => 'date',
//		);

		return $columns;
	}

	public static function additionalFields( $obj, $post ) {
		$obj->url			 = get_post_meta( $post->ID, '_cmcrpr_url', true );
		$obj->description	 = '';
		$obj->weight		 = 1;

//		$image = get_the_post_thumbnail($post->ID);
		$image		 = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
		$obj->image	 = $image;
		return $obj;
	}

}
