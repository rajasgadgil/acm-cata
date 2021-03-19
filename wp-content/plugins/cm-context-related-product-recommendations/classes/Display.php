<?php

class CMCRPR_Display {

	public static function init() {

		global $cmcrpr_isLicenseOk;

		if ( $cmcrpr_isLicenseOk ) {

			add_filter( 'cmcrpr_parse_end', array( __CLASS__, 'outputItems' ) );

			add_filter( 'the_title', array( __CLASS__, 'addTitlePrefix' ), 10006, 2 );

			/*
			 * Filter for the BuddyPress record
			 */
			add_filter( 'bp_blogs_record_comment_post_types', array( __CLASS__, 'addPostTypeToBPComments' ) );

			/*
			 * Item Content ADD
			 */
			add_filter( 'cmcrpr_item_content_add', array( __CLASS__, 'stripShortcodesFromContent' ), 4, 2 );
			add_filter( 'cmcrpr_item_content_add', array( __CLASS__, 'addItemsDescriptionFold' ), 1000, 2 );
			add_filter( 'cmcrpr_item_content_add', array( __CLASS__, 'addEditlinkToItem' ), 2000, 2 );

			/*
			 * "Normal" Item Content
			 */
			add_filter( 'cmcrpr_item_content', array( __CLASS__, 'getItemsContentBase' ), 10, 2 );
			add_filter( 'cmcrpr_item_content', array( __CLASS__, 'stripShortcodesFromContent' ), 20, 2 );
		}
	}

	public static function getItemsContent( $atts = array() ) {
		global $cmcrpr_replacedTerms;
		static $filtered = FALSE;
		static $shuffled = FALSE;

		$itemsContent = '';

		$itemDisplayHeaders = CMCRPR_Base::_getOptions( 'displayHeadersForItem' );

		$numberOfItemsDisplayed	 = apply_filters( 'cmcrpr_get_number_of_items_to_display', 3, $atts );
		$numberOfLinesDisplayed	 = apply_filters( 'cmcrpr_get_number_of_lines_to_display', 1, $atts );

		$classMap = array(
			'3'	 => 'big',
			'4'	 => 'medium',
			'8'	 => 'small',
		);

		$class = isset( $classMap[ $numberOfItemsDisplayed ] ) ? $classMap[ $numberOfItemsDisplayed ] : 'medium';

		if ( !empty( $cmcrpr_replacedTerms ) ) {

			if ( !$filtered ) {
				$postIds = array();
				foreach ( $cmcrpr_replacedTerms as $key => $value ) {
					if ( !in_array( $value[ 'postID' ], $postIds ) ) {
						$postIds[] = $value[ 'postID' ];
					} else {
						unset( $cmcrpr_replacedTerms[ $key ] );
					}
				}
				$filtered = TRUE;
			}

			if ( !$shuffled ) {
				$arrayWeight = 0;
				foreach ( $cmcrpr_replacedTerms as $value ) {
					$arrayWeight += $value[ 'post' ]->weight;
				}

				function weightedshuffle( $a, $b ) {
					$rand = rand( 0, $a[ 'post' ]->weight + $b[ 'post' ]->weight );
					return $rand >= $a[ 'post' ]->weight;
				}
				uasort( $cmcrpr_replacedTerms, 'weightedshuffle' );
//				shuffle( $cmcrpr_replacedTerms );
				$shuffled = TRUE;
			}

			$itemsContent .= '<div class="cmcrpr_items_wrapper ' . $class . '">';

			$widgetLabel = CMCRPR_Base::_getOptions( 'widgetLabel' );
			if ( $widgetLabel ) {
				$itemsContent .= '<div class="cmcrpr_items_label">' . $widgetLabel . '</div>';
			}

			$itemsContent .= '<div class="cmcrpr_items_table">';

			if ( $itemDisplayHeaders ) {
				$itemsContent .= '<tr class="cmcrpr_items_row_headers">';
				$itemsContent .= '<th>' . CMCRPR_Base::__( CMCRPR_Base::_getOptions( 'headerAnchorTitle', 'Anchor/Title' ) ) . '</th>';
				$itemsContent .= '<th>' . CMCRPR_Base::__( CMCRPR_Base::_getOptions( 'headerDescription', 'Description' ) ) . '</th>';
				$itemsContent .= '</tr>';
			}

			$numberOfItemsRequired = intval( $numberOfItemsDisplayed ) * intval( $numberOfLinesDisplayed );

			/*
			 * Get the required amount of products to display
			 */
			$itemsToDisplay = array_splice( $cmcrpr_replacedTerms, 0, $numberOfItemsRequired );

//			if ( $numberOfItemsRequired < count( $cmcrpr_replacedTerms ) ) {
//				shuffle( $cmcrpr_replacedTerms );
//				$itemsToDisplay = array_splice( $cmcrpr_replacedTerms, 0, $numberOfItemsRequired );
//			} else {
//				/*
//				 * Display all of the items
//				 */
//				$itemsToDisplay = $cmcrpr_replacedTerms;
//			}
			foreach ( $itemsToDisplay as $replacedItemKey => $replacedItemArr ) {
				$singleItemsContent = self::displaySingleItem( $replacedItemArr );
				$itemsContent .= apply_filters( 'cmcrpr_single_item_display', $singleItemsContent, $replacedItemKey );
			}
			$itemsContent .= '</div>';
			$itemsContent .= do_shortcode('[cminds_free_author id="cmcrpr"]');
			$itemsContent .= '</div>';
		}

		return $itemsContent;
	}

	public static function displaySingleItem( $itemArr ) {

		$itemDisplayTitle		 = CMCRPR_Base::_getOptions( 'showTitle' );
		$itemDisplayDescription	 = CMCRPR_Base::_getOptions( 'showDescription' );
		$showTitleAttribute		 = CMCRPR_Base::_getOptions( 'showTitleAttribute' );
		$openInNewTab			 = CMCRPR_Base::_getOptions( 'openInNewTab' );

		$item				 = $itemArr[ 'post' ];
		$itemsIndexNumber	 = $itemArr[ 'index' ];
		$itemId				 = 'cmcrpr_item_' . $itemsIndexNumber;

		$itemsContent	 = apply_filters( 'cmcrpr_item_content', '', $item );
		/*
		 * Apply filters for 3rd party widgets additions
		 */
		$itemsContent	 = apply_filters( 'cmcrpr_3rdparty_item_content', $itemsContent, $item );
		/*
		 * Add filter to change the item content on the list
		 */
		$itemsContent	 = apply_filters( 'cmcrpr_item_content_add', $itemsContent, $item );

		$itemsContent .= '<div id="' . $itemId . '" class="cmcrpr_item_row">';

		$target	 = $openInNewTab ? '_blank' : '';
		$title	 = $showTitleAttribute ? $item->post_title : '';
		$itemsContent .= '<a href="' . $item->url . '" target="' . $target . '" title="' . $title . '" class="cmcrpr_item_link">';

		$itemsContent .= '<img src="' . $item->image . '" class="cmcrpr_item_image">';

		if ( $itemDisplayTitle ) {
			$itemsContent .= '<div class="cmcrpr_item_title">' . $item->post_title . '</div>';
		}

		if ( $itemDisplayDescription ) {
			$limitDescriptionLength	 = CMCRPR_Base::_getOptions( 'limitDescriptionLength' );
			$description			 = $item->description;
			if ( $limitDescriptionLength ) {
				$description = cminds_truncate( $description, $limitDescriptionLength, '...', TRUE );
			}
			$itemsContent .= '<div class="cmcrpr_item_description">' . $description . '</div>';
		}
		$itemsContent .= '</a>';
		$itemsContent .= '</div>';

		return $itemsContent;
	}

	/**
	 * Returns TRUE if the shortcode was found
	 * @staticvar boolean $found
	 * @param type $setFound
	 * @return type
	 */
	public static function wasShortcodeFound( $setFound = FALSE ) {
		static $found = FALSE;
		if ( $setFound ) {
			$found = $setFound;
		}
		return $found;
	}

	public static function outputItems( $content ) {
		$contentWithItems = do_shortcode( $content );

		$shortcodeWasFound = self::wasShortcodeFound();
		if ( !$shortcodeWasFound ) {
			$itemsContent = self::getItemsContent();
			self::wasShortcodeFound( TRUE );

			$contentWithItems = $contentWithItems . $itemsContent;
		}

		return $contentWithItems;
	}

	/**
	 * Add the prefix before the title on the Term page
	 * @global type $wp_query
	 * @param string $title
	 * @param type $id
	 * @return string
	 */
	public static function addTitlePrefix( $title = '', $id = null ) {
		global $wp_query;

		if ( $id ) {
			$postItem = get_post( $id );
			if ( $postItem && CMCRPR_Base::POST_TYPE == $postItem->post_type && $wp_query->is_single && isset( $wp_query->query[ 'post_type' ] ) && CMCRPR_Base::POST_TYPE == $wp_query->query[ 'post_type' ] ) {
				$prefix = CMCRPR_Base::_getOptions( 'postTitlePrefix' );
				if ( !empty( $prefix ) ) {
					$title = $prefix . $title;
				}
			}
		}

		return $title;
	}

	/**
	 * Get the base of the Content on Index Page
	 * @param type $content
	 * @param type $item
	 * @return type
	 */
	public static function addItemsDescriptionFold( $content, $item ) {
		$foldCharacters	 = (int) CMCRPR_Base::_getOptions( 'limitDescriptionLength' );
		$contentLength	 = strlen( $content );

		if ( '0' == $foldCharacters || $contentLength < $foldCharacters ) {
			return $content;
		}

		$shortContent = cminds_truncate( $content, $foldCharacters, '', false );

		if ( $shortContent < $content ) {
			$shortWrappedContent = '<div class="cmcrpr_item_short">' . $shortContent . '</div>';
			$longWrappedContent	 = '<div class="cmcrpr_item_full">' . $content . '</div>';

			$content = $shortWrappedContent . $longWrappedContent;
		}

		return $content;
	}

	/**
	 * Get the base of the Items Content on Index Page
	 * @param type $content
	 * @param type $item
	 * @return type
	 */
	public static function getItemsContentBase( $content, $item ) {
		$content = (CMCRPR_Base::_getOptions( 'useExcerpt' ) && $item->post_excerpt) ? $item->post_excerpt : $item->post_content;
		return $content;
	}

	/**
	 * Function adds the editlink
	 * @return string
	 */
	public static function addEditlinkToItem( $itemContent, $item ) {
		$showTitle = CMCRPR_Base::_getOptions( 'addTermEditLink' );

		if ( 1 == $showTitle && current_user_can( 'edit_posts' ) ) {
			$link			 = '<a href=&quot;' . get_edit_post_link( $item->ID ) . '&quot;>Edit term</a>';
			$itemEditLink	 = '<div class=cmcrpr_itemEditlink>' . $link . '</div>';
			/*
			 * Add the editlink
			 */
			$itemContent	 = $itemEditLink . $itemContent;
		}

		return $itemContent;
	}

	/**
	 * Add the social share buttons
	 * @param string $content
	 * @return string
	 */
	public static function addShareBox( $content = '' ) {
		if ( !defined( 'DOING_AJAX' ) ) {
			ob_start();
			require CMCRPR_PLUGIN_DIR . 'views/frontend/social_share.phtml';
			$preContent = ob_get_clean();

			$content = $preContent . $content;
		}

		return $content;
	}

	/**
	 * BuddyPress record custom post type comments
	 * @param array $post_types
	 * @return string
	 */
	public static function addPostTypeToBPComments( $post_types ) {
		$post_types[] = CMCRPR_Base::POST_TYPE;
		return $post_types;
	}

	/**
	 * Function strips the shortcodes if the option is set
	 * @param type $content
	 * @return type
	 */
	public static function stripShortcodesFromContent( $content, $item ) {
		if ( CMCRPR_Base::_getOptions( 'stripShortcode' ) == 1 ) {
			$content = strip_shortcodes( $content );
		} else {
			$content = do_shortcode( $content );
		}

		return $content;
	}

}
