<?php

class CMCRPR_Exception extends Exception {

}

class CMCRPR_Parser {

	public static function init() {

		global $cmcrpr_isLicenseOk;

		if ( $cmcrpr_isLicenseOk ) {

			/*
			 * FILTERS
			 */

			/*
			 * Make sure parser runs before the post or page content is outputted
			 */
			add_filter( 'the_content', array( __CLASS__, 'parseContent' ), 9999 );

			/*
			 * Fix for SEO by Yoast
			 */
			add_filter( 'get_the_excerpt', array( __CLASS__, 'disableParsing' ), 1 );
			add_filter( 'wpseo_opengraph_desc', array( __CLASS__, 'reenableParsing' ), 1 );

			/*
			 * Fix for BuddyPress
			 */
			add_action( 'bp_before_create_group', array( __CLASS__, 'outputExcludeStart' ) );
			add_action( 'bp_before_group_admin_content', array( __CLASS__, 'outputExcludeStart' ), 50 );
			add_action( 'bp_after_create_group', array( __CLASS__, 'outputExcludeEnd' ) );
			add_action( 'bp_after_group_admin_content', array( __CLASS__, 'outputExcludeEnd' ), 50 );

			/*
			 * SHORTCODES
			 */
			/*
			 * Custom item shortcode
			 */
			add_shortcode( 'cmcrpr_custom_item', array( __CLASS__, 'customItemShortcode' ) );
		}
	}


	public static function outputExcludeStart() {
		echo '[cmcrpr_exclude]';
	}

	public static function outputExcludeEnd() {
		echo '[/cmcrpr_exclude]';
	}

	/**
	 * Function returns TRUE if the given post should be parsed
	 * @param type $post
	 * @param type $force
	 * @return boolean
	 */
	public static function isParsingRequired( $post, $force = false, $from_cache = false ) {
		static $requiredAtLeastOnce = false;
		if ( $from_cache ) {
			/*
			 * Could be used to load JS/CSS in footer only when needed
			 */
			return $requiredAtLeastOnce;
		}

		/*
		 *  Skip parsing for excluded pages and posts
		 */
		$parsingDisabled = get_post_meta( $post->ID, '_disable_for_page', true ) == 1;
		if ( $parsingDisabled ) {
			return FALSE;
		}

		if ( $force ) {
			return TRUE;
		}

		if ( !is_object( $post ) ) {
			return FALSE;
		}

		$currentPostType			 = get_post_type( $post );
		$showOnPostTypes			 = CMCRPR_Base::_getOptions( 'showOnPostTypes' );
		$showOnHomepageAuthorpageEtc = (!is_page( $post ) && !is_single( $post ) && CMCRPR_Base::_getOptions( 'showOnlyOnSingle' ) == 0);
		$onMainQueryOnly			 = (CMCRPR_Base::_getOptions( 'showOnMainQuery' ) == 1 ) ? is_main_query() : TRUE;

		if ( !is_array( $showOnPostTypes ) ) {
			$showOnPostTypes = array();
		}
		$showOnSingleCustom	 = (is_singular( $post ) && in_array( $currentPostType, $showOnPostTypes ));
		$condition			 = ( $showOnHomepageAuthorpageEtc || $showOnSingleCustom );

		$result = $onMainQueryOnly && $condition;
		if ( $result ) {
			$requiredAtLeastOnce = TRUE;
		}
		return $result;
	}

	/**
	 * Prepare the data for the parser
	 *
	 * @global type $cmcrpr_itemsIndexArr
	 * @global type $cmcrpr_searchStringArr
	 * @global type $cmcrpr_onlySynonyms
	 */
	public static function prepareParsingData() {
		static $runOnce = FALSE;

		if ( $runOnce ) {
			return;
		}

		global $cmcrpr_itemsIndexArr, $cmcrpr_searchStringArr, $cmcrpr_onlySynonyms;
		/*
		 * Initialize $cmcrpr_searchStringArr as empty array
		 */
		$cmcrpr_searchStringArr	 = array();
		$cmcrpr_onlySynonyms	 = array();

		$itemsIndex = CMCRPR_Base::getPostTypeItemsSorted();

		/*
		 * the tag:[cmcrpr_exclude]+[/cmcrpr_exclude] can be used to mark text will not be taken into account by the parser
		 */
		if ( $itemsIndex ) {
			$caseSensitive = CMCRPR_Base::_getOptions( 'termsCaseSensitive', 0 );

			/*
			 * The loops prepares the search query for the replacement
			 */
			foreach ( $itemsIndex as $item ) {
				$itemTitle = preg_quote( str_replace( '\'', '&#39;', htmlspecialchars( trim( $item->post_title ), ENT_QUOTES, 'UTF-8' ) ), '/' );

				$addition					 = '';
				$synonyms					 = array();
				$onlySynonyms[ $item->ID ]	 = $synonyms;
				$synonyms2					 = array();

				if ( !empty( $synonyms ) && count( $synonyms ) > 0 ) {
					foreach ( $synonyms as $val ) {
						$val = str_replace( '&#039;', '’', preg_quote( htmlspecialchars( trim( $val ), ENT_QUOTES, 'UTF-8' ), '/' ) );
						if ( !empty( $val ) ) {
							$synonyms2[] = $val;
						}
					}
					if ( !empty( $synonyms2 ) ) {
						$addition = '|' . implode( '|', $synonyms2 );
					}
				}
				$synonyms	 = null;
				$synonyms2	 = null;

				$additionFiltered = apply_filters( 'cmcrpr_parse_addition_add', $addition, $item );

				$itemIndexArrKey = $itemTitle . $additionFiltered;
				if ( !$caseSensitive ) {
					$itemIndexArrKey = mb_strtolower( $itemIndexArrKey );
				}

				$ignore = get_post_meta( $item->ID, '_cmcrpr_not_parsed', true );
				if ( !$ignore ) {
					$cmcrpr_searchStringArr[]					 = $itemTitle . $additionFiltered;
					$cmcrpr_itemsIndexArr[ $itemIndexArrKey ]	 = $item;
				} else {
					$customKey = self::getCustomKey( get_post_meta( $item->ID, '_cmcrpr_custom_id', true ) );
					if ( !empty( $customKey ) ) {
						global $cmcrpr_specialReplaceRules;

						if ( !empty( $cmcrpr_specialReplaceRules ) ) {
							foreach ( $cmcrpr_specialReplaceRules as $customItem ) {
								if ( $customItem[ 'cmcrpr_itemCustomKey' ] !== $customKey ) {
									continue;
								}
								$replaceFrom = $customItem[ 'replaceFrom' ];

								$itemIndexArrKey = !$caseSensitive ? mb_strtolower( $replaceFrom ) : $replaceFrom;

								$cmcrpr_itemsIndexArr[ $itemIndexArrKey ]	 = $item;
								$cmcrpr_searchStringArr[]					 = $replaceFrom;
							}
						}
					}
				}
			}
		}

		$runOnce = TRUE;
	}

	/**
	 * Get's the custom key with the prefix and suffix
	 * @param type $key
	 * @return type
	 */
	public static function getCustomKey( $key ) {
		$customKey = !empty( $key ) ? '__' . $key . '__' : FALSE;
		return $customKey;
	}

	/**
	 * Main parser function
	 *
	 * @global type $post
	 * @global type $wp_query
	 * @global type $cmcrpr_searchStringArr
	 * @global array $cmcrpr_replacedTerms
	 * @param type $content
	 * @param type $force
	 * @return type
	 */
	public static function parseContent( $content, $force = false ) {
		global $post, $wp_query;

		if ( $post === NULL ) {
			return $content;
		}

		if ( !is_object( $post ) ) {
			$post = $wp_query->post;
		}

		$seo = doing_action( 'wpseo_opengraph' );
		if ( $seo ) {
			return $content;
		}

		$runParser = self::isParsingRequired( $post, $force );
		if ( !$runParser ) {
			/*
			 * Returns empty string
			 */
			add_shortcode( CMCRPR_Base::POST_TYPE, '__return_empty_string' );
			$removeShortcodeContent = do_shortcode( $content );
			return $removeShortcodeContent;
		}

		/*
		 * Check the cache
		 */
		$contentHash = 'cmcrpr_content' . sha1( $post->ID );
		if ( !$force ) {
			if ( !CMCRPR_Base::_getOptions( 'enableCaching', TRUE ) ) {
				delete_transient( $contentHash );
			}
			$result = get_transient( $contentHash );
			if ( $result !== false ) {
				return $result;
			}
		}

		/*
		 * Prepare the parsing data
		 */
		self::prepareParsingData();

		global $cmcrpr_searchStringArr, $cmcrpr_replacedTerms;

		if ( !empty( $cmcrpr_searchStringArr ) && is_array( $cmcrpr_searchStringArr ) ) {
			$caseSensitive = CMCRPR_Base::_getOptions( 'termsCaseSensitive', 0 );

			$excludeRegex = '/\\['   // Opening bracket
			. '(\\[?)'   // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
			. "(cmcrpr_exclude)"   // 2: Shortcode name
			. '\\b'   // Word boundary
			. '('  // 3: Unroll the loop: Inside the opening shortcode tag
			. '[^\\]\\/]*' // Not a closing bracket or forward slash
			. '(?:'
			. '\\/(?!\\])'   // A forward slash not followed by a closing bracket
			. '[^\\]\\/]*'   // Not a closing bracket or forward slash
			. ')*?'
			. ')'
			. '(?:'
			. '(\\/)'   // 4: Self closing tag ...
			. '\\]'  // ... and closing bracket
			. '|'
			. '\\]'  // Closing bracket
			. '(?:'
			. '('   // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
			. '[^\\[]*+' // Not an opening bracket
			. '(?:'
			. '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
			. '[^\\[]*+'   // Not an opening bracket
			. ')*+'
			. ')'
			. '\\[\\/\\2\\]' // Closing shortcode tag
			. ')?'
			. ')'
			. '(\\]?)/s';

			$excludedItemsArr = array();

			/*
			 * Replace exclude tags and content between them in purpose to save the original text as is
			 * before plugins parser go over the content and adds its code
			 * (later will be returned to the marked places in content)
			 */
			$excludeTagsCount	 = preg_match_all( $excludeRegex, $content, $excludedItemsArr, PREG_PATTERN_ORDER );
			$i					 = 0;

			if ( $excludeTagsCount > 0 ) {
				foreach ( $excludedItemsArr[ 0 ] as $excludeStr ) {
					$content = preg_replace( $excludeRegex, '#' . $i . 'cmcrpr_excludeItem', $content, 1 );
					$i++;
				}
			}

			$itemsArrayChunkSize = apply_filters( 'cmcrpr_parse_array_chunk_size', 75 );
			$spaceSeparated		 = apply_filters( 'cmcrpr_parse_space_separated_only', 1 );

			if ( !is_array( $cmcrpr_replacedTerms ) ) {
				$cmcrpr_replacedTerms = array();
			}

			if ( count( $cmcrpr_searchStringArr ) > $itemsArrayChunkSize ) {
				$cmcrpr_chunkedSearchStringArr = array_chunk( $cmcrpr_searchStringArr, $itemsArrayChunkSize, TRUE );

				foreach ( $cmcrpr_chunkedSearchStringArr as $searchStringArrChunk ) {
					$regexSearchString	 = '/' . (($spaceSeparated) ? '(?<=\P{L}|^)(?<!(\p{N}))' : '') . '(?!(<|&lt;))(' . (!$caseSensitive ? '(?i)' : '') . implode( '|', $searchStringArrChunk ) . ')(?!(>|&gt;))' . (($spaceSeparated) ? '(?=\P{L}|$)(?!(\p{N}))' : '') . '/u';
					$content			 = self::strReplaceDOM( $content, $regexSearchString );
				}
			} else {
				$regexSearchString	 = '/' . (($spaceSeparated) ? '(?<=\P{L}|^)(?<!(\p{N}))' : '') . '(?!(<|&lt;))(' . (!$caseSensitive ? '(?i)' : '') . implode( '|', $cmcrpr_searchStringArr ) . ')(?!(>|&gt;))' . (($spaceSeparated) ? '(?=\P{L}|$)(?!(\p{N}))' : '') . '/u';
				$content			 = self::strReplaceDOM( $content, $regexSearchString );
			}

			if ( $excludeTagsCount > 0 ) {
				$i = 0;
				foreach ( $excludedItemsArr[ 0 ] as $excludeStr ) {
					$content = str_replace( '#' . $i . 'cmcrpr_excludeItem', $excludeStr, $content );
					$i++;
				}
				//remove all the exclude signs
				$content = str_replace( array( '[cmcrpr_exclude]', '[/cmcrpr_exclude]' ), array( '', '' ), $content );
			}
		}

		/*
		 * Adding the shortcode etc.
		 */
		do_action( 'cmcrpr_before_parse_end' );

		$content = apply_filters( 'cmcrpr_parse_end', $content );

		if ( CMCRPR_Base::_getOptions( 'enableCaching', TRUE ) ) {
			$result = set_transient( $contentHash, $content, 1 * MINUTE_IN_SECONDS );
		}

		return $content;
	}

	/**
	 * Disable the parsing for some reason
	 * @global type $wp_query
	 * @param type $smth
	 * @return type
	 */
	public static function disableParsing( $smth ) {
		global $wp_query;
		if ( $wp_query->is_main_query() && !$wp_query->is_singular ) {  // to prevent conflict with Yost SEO
			remove_filter( 'the_content', array( __CLASS__, 'parseContent' ), 9999 );
			do_action( 'cmcrpr_disable_parsing' );
		}
		return $smth;
	}

	/**
	 * Reenable the parsing for some reason
	 * @global type $wp_query
	 * @param type $smth
	 * @return type
	 */
	public static function reenableParsing( $smth ) {
		add_filter( 'the_content', array( __CLASS__, 'parseContent' ), 9999 );
		do_action( 'cmcrpr_reenable_parsing' );
		return $smth;
	}

	public static function getItemByCustomId( $id ) {
		static $resultsCache = array();

		if ( !isset( $resultsCache[ $id ] ) ) {
			$args = array(
				'post_type'				 => CMCRPR_Base::POST_TYPE,
				'post_status'			 => 'publish',
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'suppress_filters'		 => false,
				'numberposts'			 => 1,
			);

			$metaQueryArgs			 = array(
				array(
					'key'	 => '_cmf_custom_id',
					'value'	 => $id,
				)
			);
			$args[ 'meta_query' ]	 = $metaQueryArgs;

			$query = new WP_Query( $args );

			$posts	 = $query->get_posts();
			$result	 = !empty( $posts );

			$resultsCache[ $id ] = $result;
		} else {
			$result = $resultsCache[ $id ];
		}

		return $result;
	}

	/**
	 * Adds the support for the custom items
	 * [cmcrpr_custom_item content="text"]term[/cmcrpr_custom_item]
	 */
	public static function customItemShortcode( $atts, $text = '' ) {
		global $post, $wp_query;

		if ( $post === NULL ) {
			return $text;
		}

		if ( !is_object( $post ) ) {
			$post = $wp_query->post;
		}

		$parsingRequired = self::isParsingRequired( $post );
		if ( !$parsingRequired ) {
			return $text;
		}

		$id = CMCRPR_Base::__( 'Use the &quot;id&quot; attribute to select the item!' );
		extract( shortcode_atts( array( 'id' => $id ), $atts ) );

		$hasItem = self::getItemByCustomId( $id );
		if ( !$hasItem ) {
			return $text;
		}

		$cmcrpr_itemCustomKey = self::getCustomKey( $id );

		global $cmcrpr_specialReplaceRules;

		$replacementKey = uniqid() . $text . $cmcrpr_itemCustomKey;

		$caseSensitive				 = CMCRPR_Base::_getOptions( 'termsCaseSensitive', 0 );
		$normalizedKey				 = preg_quote( str_replace( '\'', '&#39;', htmlspecialchars( trim( $replacementKey ), ENT_QUOTES, 'UTF-8' ) ), '/' );
		$normalizedReplacementKey	 = (!$caseSensitive) ? mb_strtolower( $normalizedKey ) : $normalizedKey;

		$cmcrpr_specialReplaceRules[ $normalizedReplacementKey ] = array(
			'cmcrpr_itemCustomKey'	 => $cmcrpr_itemCustomKey,
			'replaceFrom'			 => $replacementKey,
			'replaceTo'				 => $text,
			'verbose'				 => TRUE
		);

		$item = $replacementKey;
		return $item;
	}

	public static function getItemsSymbol( $itemsData ) {
		$symbol = $itemsData[ 'index' ];
		return $symbol;
	}

	/**
	 * Function which prepares the templates for the words found in text
	 *
	 * @param string $title replacement text
	 * @return array|string
	 */
	public static function prepareReplaceTemplate( $title, $returnTitle = NULL, $verbose = FALSE, $setCommonKey = NULL ) {
		static $cmcrpr_itemIndex = 0;

		$returnTitle = (NULL !== $returnTitle) ? $returnTitle : $title;
		$commonKey	 = (NULL !== $setCommonKey) ? $setCommonKey : $title;

		/*
		 * Placeholder for the title
		 */
		$titlePlaceholder = '##TITLE_GOES_HERE##';

		/*
		 * Array of items, settings etc
		 */
		global $cmcrpr_itemsIndexArr, $caseSensitive, $templatesArr, $cmcrpr_replacedTerms, $post;

		/*
		 *  Checks whether to show items on this page or not
		 */
		$disabledOnCurrentPage = get_post_meta( $post->ID, '_cmcrpr_disable_parsing_for_page', true ) == 1;

		/*
		 *  Checks whether to show links to item pages or not
		 */
		$linksDisabled = get_post_meta( $post->ID, '_cmcrpr_disable_links_for_page', true ) == 1;

		/*
		 * If FALSE then the links to item pages are exchanged with spans
		 */
		$addLinksToTerms = (CMCRPR_Base::_getOptions( 'termsWithLinks' ) == 1 && !$linksDisabled);

		/*
		 * If "Highlight first occurance only" option is set
		 */
		$highlightFirstOccuranceOnly = (CMCRPR_Base::_getOptions( 'firstOccuranceOnly' ) == 1);

		/*
		 * If it's case insensitive, then the term keys are stored as lowercased
		 */
		$normalizedTitle = preg_quote( str_replace( '\'', '&#39;', htmlspecialchars( trim( $title ), ENT_QUOTES, 'UTF-8' ) ), '/' );
		$titleIndex		 = (!$caseSensitive) ? mb_strtolower( $normalizedTitle ) : $normalizedTitle;

		try {
			do_action( 'cmcrpr_replace_template_before_synonyms', $titleIndex, $title );
		} catch ( CMCRPR_Exception $ex ) {
			/*
			 * Trick to stop the execution
			 */
			$message = $ex->getMessage();
			return $message;
		}

		/*
		 * Upgrade to make it work with synonyms
		 */
		if ( $cmcrpr_itemsIndexArr ) {
			/*
			 * First - look for exact keys
			 */
			if ( array_key_exists( $titleIndex, $cmcrpr_itemsIndexArr ) ) {
				$currentItem = $cmcrpr_itemsIndexArr[ $titleIndex ];
			} else {
				/*
				 * If not found - try the synonyms
				 */
				foreach ( $cmcrpr_itemsIndexArr as $key => $value ) {
					/*
					 * If we find the term we make sure it's a synonym and not a part of some other term
					 */
					if ( strstr( $key, '|' ) && strstr( $key, $titleIndex ) ) {
						$synonymsArray = explode( '|', $key );
						if ( in_array( $titleIndex, $synonymsArray ) ) {
							/*
							 * $replace = WP Post
							 */
							$currentItem = $value;
							break;
						}
					}
				}
			}
		}

		try {
			do_action( 'cmcrpr_replace_template_after_synonyms', $currentItem, $titleIndex, $title );
		} catch ( CMCRPR_Exception $ex ) {
			/*
			 * Trick to stop the execution
			 */
			$message = $ex->getMessage();
			return $message;
		}

		/*
		 * Error checking
		 */
		if ( empty( $currentItem ) || !is_object( $currentItem ) ) {
			if ( !$verbose && defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				throw new CMCRPR_Exception( 'Error! Post not found for word:' . $titleIndex );
			}
			return $returnTitle;
		}

		$id = $currentItem->ID;

		/**
		 *  If "Highlight first occurance only" option is set, we check if the post has already been highlighted
		 */
		if ( $highlightFirstOccuranceOnly && is_array( $cmcrpr_replacedTerms ) && !empty( $cmcrpr_replacedTerms ) ) {
			foreach ( $cmcrpr_replacedTerms as $replacedTerm ) {
				if ( $replacedTerm[ 'postID' ] == $id ) {
					/*
					 * If the post has already been highlighted
					 */
					return $returnTitle;
				}
			}
		}

		$excludeItem = get_post_meta( $id, '_cmcrpr_exclude_item', true ) || $disabledOnCurrentPage;
		if ( $excludeItem ) {
			return $returnTitle;
		}

		/*
		 * Save the post item to the global array so it can be used to generate "Related Terms" list
		 */
		$cmcrpr_replacedTerms[ $commonKey ][ 'post' ] = $currentItem;

		/*
		 * Save the post item ID to the global array so it's easy to find out if it has been highlighted in text or not
		 */
		$cmcrpr_replacedTerms[ $commonKey ][ 'postID' ] = $id;

		/*
		 * Replacement is already cached - use it
		 */
		if ( $returnTitle === NULL && !empty( $templatesArr[ $id ] ) ) {
			$templateReplaced = str_replace( $titlePlaceholder, $title, $templatesArr[ $id ] );
			return $templateReplaced;
		}

		if ( !isset( $cmcrpr_replacedTerms[ $commonKey ][ 'index' ] ) ) {
			/*
			 * Index of the items
			 */
			$cmcrpr_replacedTerms[ $commonKey ][ 'index' ] = ++$cmcrpr_itemIndex;
		}

		$currentIndexNumber = $cmcrpr_replacedTerms[ $commonKey ][ 'index' ];

		$cmcrpr_replacedTerms[ $commonKey ][ 'symbol' ] = self::getItemsSymbol( $cmcrpr_replacedTerms[ $commonKey ] );

		$additionalClass = apply_filters( 'cmcrpr_term_item_additional_class', '', $currentItem );
		$permalink		 = apply_filters( 'cmcrpr_term_item_permalink', get_permalink( $currentItem->ID ), $currentItem );

		/*
		 * Open in new window
		 */
		$windowTarget	 = (CMCRPR_Base::_getOptions( 'openInNewTab' ) == 1) ? ' target="_blank" ' : '';
		$titleAttr		 = (CMCRPR_Base::_getOptions( 'showTitleAttribute' ) == 1) ? ' title=" ' . esc_attr( $currentItem->post_title ) . '" ' : '';

		$itemId = 'cmcrpr_item_' . $currentIndexNumber;

		$itemData[ 'index' ] = $currentIndexNumber;
		$itemSymbol			 = self::getItemsSymbol( $itemData );

		if ( $addLinksToTerms ) {
			$href = $currentItem->url;
			$link_replace	 = '<a href="'.$href.'"  ' . $titleAttr . ' class="cmcrpr_has_item ' . $additionalClass . '">' . $titlePlaceholder . '</a>';
		} else {
			$link_replace	 = '<span  ' . $titleAttr . ' class="cmcrpr_has_item ' . $additionalClass . '">' . $titlePlaceholder . '</span>';
		}

		/*
		 * Save with $titlePlaceholder - for the synonyms
		 */
		$templatesArr[ $id ] = $link_replace;

		/*
		 * Replace it with title to show correctly for the first time
		 */
		$link_replace = str_replace( $titlePlaceholder, $returnTitle, $link_replace );
		return $link_replace;
	}

	/**
	 * Replaces the matches
	 * @global array $cmcrpr_replacedTerms
	 * @param type $match
	 * @return type
	 */
	public static function replaceMatches( $match ) {
		if ( !empty( $match[ 0 ] ) ) {
			$matchedTerm = $match[ 0 ];

			global $cmcrpr_specialReplaceRules, $cmcrpr_replacedTerms, $caseSensitive;

			$returnTitle	 = NULL;
			$verbose		 = FALSE;
			$setCommonKey	 = NULL;

			$normalizedKey				 = preg_quote( str_replace( '\'', '&#39;', htmlspecialchars( trim( $matchedTerm ), ENT_QUOTES, 'UTF-8' ) ), '/' );
			$normalizedReplacementKey	 = (!$caseSensitive) ? mb_strtolower( $normalizedKey ) : $normalizedKey;

			if ( isset( $cmcrpr_specialReplaceRules[ $normalizedReplacementKey ] ) ) {
				$returnTitle	 = isset( $cmcrpr_specialReplaceRules[ $normalizedReplacementKey ][ 'replaceTo' ] ) ? $cmcrpr_specialReplaceRules[ $normalizedReplacementKey ][ 'replaceTo' ] : $returnTitle;
				$verbose		 = isset( $cmcrpr_specialReplaceRules[ $normalizedReplacementKey ][ 'verbose' ] ) ? $cmcrpr_specialReplaceRules[ $normalizedReplacementKey ][ 'verbose' ] : $verbose;
				$setCommonKey	 = isset( $cmcrpr_specialReplaceRules[ $normalizedReplacementKey ][ 'cmcrpr_itemCustomKey' ] ) ? $cmcrpr_specialReplaceRules[ $normalizedReplacementKey ][ 'cmcrpr_itemCustomKey' ] : $setCommonKey;
			}

			$replacementText = self::prepareReplaceTemplate( htmlspecialchars_decode( $matchedTerm, ENT_COMPAT ), $returnTitle, $verbose, $setCommonKey );
			return $replacementText;
		}
	}

	/**
	 * New function to search the terms in the content
	 *
	 * @param strin $html
	 * @param string $itemSearchString
	 * @since 2.3.1
	 * @return type
	 */
	public static function strReplaceDOM( $html, $itemSearchString ) {
		global $cmWrapItUp;

		if ( !empty( $html ) && is_string( $html ) ) {
			if ( $cmWrapItUp ) {
				$html = '<span>' . $html . '</span>';
			}
			$dom = new DOMDocument();
			/*
			 * loadXml needs properly formatted documents, so it's better to use loadHtml, but it needs a hack to properly handle UTF-8 encoding
			 */
			libxml_use_internal_errors( true );
			if ( !$dom->loadHtml( mb_convert_encoding( $html, 'HTML-ENTITIES', "UTF-8" ) ) ) {
				libxml_clear_errors();
			}
			$xpath = new DOMXPath( $dom );

			/*
			 * Base query NEVER parse in scripts
			 */
			$query = '//text()[not(ancestor::script)][not(ancestor::style)]';
			if ( CMCRPR_Base::_getOptions( 'notParseProtectedTags' ) == 1 ) {
				$query .= '[not(ancestor::header)][not(ancestor::a)][not(ancestor::pre)][not(ancestor::object)][not(ancestor::h1)][not(ancestor::h2)][not(ancestor::h3)][not(ancestor::h4)][not(ancestor::h5)][not(ancestor::h6)][not(ancestor::textarea)]';
			}
			/*
			 * Parsing of the Index Page
			 */
			if ( CMCRPR_Base::_getOptions( 'notParseOnIndex', 1 ) == 1 ) {
				$query .= '[not(ancestor::div[@class=\'' . CMCRPR_Base::POST_TYPE . '\'])]';
			}

			foreach ( $xpath->query( $query ) as $node ) {
				/* @var $node DOMText */
				$replaced = preg_replace_callback( $itemSearchString, array( __CLASS__, 'replaceMatches' ), htmlspecialchars( $node->wholeText, ENT_COMPAT ) );
				if ( !empty( $replaced ) ) {
					$newNode			 = $dom->createDocumentFragment();
					$replacedShortcodes	 = strip_shortcodes( $replaced );
					$result				 = $newNode->appendXML( '<![CDATA[' . $replacedShortcodes . ']]>' );

					if ( $result !== false ) {
						$node->parentNode->replaceChild( $newNode, $node );
					}
				}
			}

			do_action( 'cmcrpr_xpath_main_query_after', $xpath, $itemSearchString, $dom );

			/*
			 *  get only the body tag with its contents, then trim the body tag itself to get only the original content
			 */
			$bodyNode = $xpath->query( '//body' )->item( 0 );

			if ( $bodyNode !== NULL ) {
				$newDom = new DOMDocument();
				$newDom->appendChild( $newDom->importNode( $bodyNode, TRUE ) );

				$intermalHtml	 = $newDom->saveHTML();
				$html			 = mb_substr( trim( $intermalHtml ), 6, (mb_strlen( $intermalHtml ) - 14 ), "UTF-8" );
				/*
				 * Fixing the self-closing which is lost due to a bug in DOMDocument->saveHtml() (caused a conflict with NextGen)
				 */
				$html			 = preg_replace( '#(<img[^>]*[^/])>#Ui', '$1/>', $html );
			}
		}

		if ( $cmWrapItUp ) {
			$html = mb_substr( trim( $html ), 6, (mb_strlen( $html ) - 13 ), "UTF-8" );
		}

		return $html;
	}

}
