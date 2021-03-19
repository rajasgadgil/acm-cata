<?php

class CMCRPR_Base {

	protected static $cssPath		 = '';
	protected static $jsPath		 = '';
	public static $lastQueryDetails	 = array();

	const POST_TYPE				 = 'cm_product_recommend';
	const TAXONOMY				 = 'product_recommend_cat';
	const DISPLAY_NOWHERE			 = 0;
	const DISPLAY_EVERYWHERE		 = 1;
	const DISPLAY_ONLY_ON_PAGES	 = 2;
	const DISPLAY_EXCEPT_ON_PAGES	 = 3;

	public static function init() {
		global $cmcrpr_isLicenseOk;

		self::setupConstants();

		self::includeFiles();

		$cmcrpr_isLicenseOk = TRUE;

		self::initFiles();

		self::addOptions();

		self::$cssPath	 = CMCRPR_PLUGIN_URL . 'assets/css/';
		self::$jsPath	 = CMCRPR_PLUGIN_URL . 'assets/js/';

		add_action( 'init', array( __CLASS__, 'cmcrpr_create_post_types' ) );
		add_action( 'admin_menu', array( __CLASS__, 'cmcrpr_admin_menu' ) );

		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'cmcrpr_admin_settings_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'cmcrpr_admin_edit_scripts' ) );

		add_action( 'restrict_manage_posts', array( __CLASS__, 'cmcrpr_restrict_manage_posts' ) );

		add_action( 'wp_print_styles', array( __CLASS__, 'frontendCSS' ) );

		add_action( 'admin_notices', array( __CLASS__, 'cmcrpr_admin_notice_wp33' ) );
		add_action( 'admin_notices', array( __CLASS__, 'cmcrpr_admin_notice_mbstring' ) );

		add_filter( 'cmcrpr_options_before_save', array( __CLASS__, 'sanitizeOptions' ) );

		add_action( 'admin_menu', array( __CLASS__, 'update_menu' ), 21 );
	}

	public static function update_menu() {
		global $submenu;

		$pluginMenu = 'edit.php?post_type=' . CMCRPR_Base::POST_TYPE;
	}

	/**
	 * Include the files
	 */
	public static function includeFiles() {
		do_action( 'cmcrpr_include_files_before' );

		include_once CMCRPR_PLUGIN_DIR . "package/cminds-free.php";
		include_once CMCRPR_PLUGIN_DIR . "classes/ProductRecommendationsPro.php";
		include_once CMCRPR_PLUGIN_DIR . "classes/Display.php";
		include_once CMCRPR_PLUGIN_DIR . "classes/Parser.php";
		include_once CMCRPR_PLUGIN_DIR . "classes/Metabox.php";
		include_once CMCRPR_PLUGIN_DIR . "functions.php";

		do_action( 'cmcrpr_include_files_after' );
	}

	/**
	 * Initialize the files
	 */
	public static function initFiles() {
		do_action( 'cmcrpr_init_files_before' );

		CMCRPR_Pro::init();
		CMCRPR_Display::init();
		CMCRPR_Parser::init();
		CMCRPR_Metabox::init();

//		CMCRPR_Related::init();

		do_action( 'cmcrpr_init_files_after' );
	}

	/**
	 * Adds options
	 */
	public static function addOptions() {
		$defaultOptions = array(
			'showOnPostTypes'				 => array( 'post', 'page' ), //Default post types where the terms are highlighted
			/*
			 * General settings
			 */
			'showOnMainQuery'				 => 1, //Show on Main Query only
			'showOnlyOnSingle'				 => 1, //Show on Home and Category Pages or just single post pages?
			'firstOccuranceOnly'			 => 0, //Search for all occurances in a post or only one?
			'exludeMetaboxOnAllPostTypes'	 => 0, //show disable metabox for all post types
			'showTitleAttribute'			 => 0, //show HTML title attribute
			'notParseProtectedTags'			 => 1, //Do not parse in protected tags
			'termsCaseSensitive'			 => 0, //Case sensitive?
			'termsWithLinks'				 => 0, //Add links?
			'enableCaching'					 => 0, //Enable caching?
			'widgetLabel'					 => CMCRPR_SHORTNAME, //Widget Label
			'numberOfItemsDisplayed'		 => '3',
			'numberOfLinesDisplayed'		 => '1',
			'sizeOfItem'					 => 'medium',
			'openInNewTab'					 => 1,
			'showTitle'						 => 1,
			'titleFontSize'					 => '13px',
			'titleFontColor'				 => '#000000',
			'showDescription'				 => 1,
			'limitDescriptionLength'		 => 0,
			'descriptionFontSize'			 => '10px',
			'descriptionFontColor'			 => '#000000',
			'postTitlePrefix'				 => '', //Text which shows up before the title on the term page
		);

		/*
		 * TODO: REMOVE this
		 */
//		delete_option('cmcrpr_options');
		add_option( 'cmcrpr_options', apply_filters( 'cmcrpr_default_options', $defaultOptions ) );

		do_action( 'cmcrpr_add_options' );
	}

	/**
	 * Setup plugin constants
	 *
	 * @access private
	 * @since 1.1
	 * @return void
	 */
	public static function setupConstants() {
		/**
		 * Define Plugin Directory
		 *
		 * @since 1.0
		 */
		if ( !defined( 'CMCRPR_PLUGIN_DIR' ) ) {
			define( 'CMCRPR_PLUGIN_DIR', plugin_dir_path( CMCRPR_PLUGIN_FILE ) );
		}

		/**
		 * Define Plugin URL
		 *
		 * @since 1.0
		 */
		if ( !defined( 'CMCRPR_PLUGIN_URL' ) ) {
			define( 'CMCRPR_PLUGIN_URL', plugin_dir_url( CMCRPR_PLUGIN_FILE ) );
		}

		/**
		 * Define Plugin Slug name
		 *
		 * @since 1.0
		 */
		if ( !defined( 'CMCRPR_SLUG_NAME' ) ) {
			define( 'CMCRPR_SLUG_NAME', 'cm-context-related-product-recommendations' );
		}

		/**
		 * Define Plugin Slug name
		 *
		 * @since 1.0
		 */
		if ( !defined( 'CMCRPR_SHORT_SLUG_NAME' ) ) {
			define( 'CMCRPR_SHORT_SLUG_NAME', 'product-recommendations' );
		}


		/**
		 * Define Plugin basename
		 *
		 * @since 1.0
		 */
		if ( !defined( 'CMCRPR_PLUGIN' ) ) {
			define( 'CMCRPR_PLUGIN', plugin_basename( CMCRPR_PLUGIN_FILE ) );
		}

		if ( !defined( 'CMCRPR_MENU_OPTION' ) ) {
			define( 'CMCRPR_MENU_OPTION', 'cmcrpr_menu_options' );
		}

		define( 'CMCRPR_ABOUT_OPTION', 'cmcrpr_about' );
		define( 'CMCRPR_SETTINGS_OPTION', 'cmcrpr_settings' );
		define( 'CMCRPR_TRANSIENT_ALL_ITEMS_KEY', 'cmcrpr_index_all_items' );

		do_action( 'cmcrpr_setup_constants_after' );
	}

	/**
	 * Create custom post type
	 */
	public static function cmcrpr_create_post_types() {
		$postTypeArgs = array(
			'label'					 => CMCRPR_SHORTNAME,
			'labels'				 => array(
				'add_new_item'	 => 'Add New Product Recommendation',
				'add_new'		 => 'Add New',
				'edit_item'		 => 'Edit Product Recommendation',
				'view_item'		 => 'View Product Recommendation',
				'singular_name'	 => 'Product Recommendation',
				'name'			 => CMCRPR_SHORTNAME,
				'menu_name'		 => CMCRPR_SHORTNAME
			),
			'description'			 => '',
			'map_meta_cap'			 => true,
			'publicly_queryable'	 => true,
			'exclude_from_search'	 => false,
			'public'				 => false,
			'show_ui'				 => true,
			'show_in_admin_bar'		 => true,
			'_builtin'				 => false,
			'capability_type'		 => 'post',
			'hierarchical'			 => false,
			'has_archive'			 => false,
			'rewrite'				 => array( 'slug' => CMCRPR_Base::POST_TYPE, 'with_front' => false, 'feeds' => true, 'feed' => true ),
			'query_var'				 => true,
			'supports'				 => array( 'title', 'post-thumbnails', 'thumbnail' ),
		);

		register_post_type( CMCRPR_Base::POST_TYPE, apply_filters( 'cmcrpr_post_type_args', $postTypeArgs ) );

		global $wp_rewrite;
		$args = (object) $postTypeArgs;

		$post_type		 = CMCRPR_Base::POST_TYPE;
		$archive_slug	 = $args->rewrite[ 'slug' ];
		if ( $args->rewrite[ 'with_front' ] ) {
			$archive_slug = substr( $wp_rewrite->front, 1 ) . $archive_slug;
		} else {
			$archive_slug = $wp_rewrite->root . $archive_slug;
		}
		if ( $args->rewrite[ 'feeds' ] && $wp_rewrite->feeds ) {
			$feeds = '(' . trim( implode( '|', $wp_rewrite->feeds ) ) . ')';
			add_rewrite_rule( "{$archive_slug}/feed/$feeds/?$", "index.php?post_type=$post_type" . '&feed=$matches[1]', 'top' );
			add_rewrite_rule( "{$archive_slug}/$feeds/?$", "index.php?post_type=$post_type" . '&feed=$matches[1]', 'top' );
		}
	}

	public static function cmcrpr_admin_menu() {
		global $submenu;
//		add_menu_page( CMCRPR_NAME, CMCRPR_NAME, 'edit_posts', CMCRPR_SLUG_NAME, 'edit.php?post_type=' . CMCRPR_Base::POST_TYPE, CMCRPR_PLUGIN_URL . 'assets/css/images/cm-plugin-icon.png' );

		/*
		 * Change the menu name to "Products"
		 */
		$submenu[ 'edit.php?post_type=' . CMCRPR_Base::POST_TYPE ][ 5 ][ 0 ] = 'Products';
		do_action( 'cmcrpr_add_admin_menu_after_new' );
		add_submenu_page( 'edit.php?post_type=' . CMCRPR_Base::POST_TYPE, 'Options', 'Settings', 'manage_options', CMCRPR_SETTINGS_OPTION, array( __CLASS__, 'outputOptions' ) );

		$pluginsItemsPerPage = get_user_meta( get_current_user_id(), 'edit_' . CMCRPR_Base::POST_TYPE . '_per_page', true );
		if ( $pluginsItemsPerPage && intval( $pluginsItemsPerPage ) > 100 ) {
			update_user_meta( get_current_user_id(), 'edit_' . CMCRPR_Base::POST_TYPE . '_per_page', 100 );
		}

		add_filter( 'views_edit-' . CMCRPR_Base::POST_TYPE, array( __CLASS__, 'cmcrpr_filter_admin_nav' ), 10, 1 );
	}

	/**
	 * Function enqueues the scripts and styles for the admin Settings view
	 * @global type $parent_file
	 * @return type
	 */
	public static function cmcrpr_admin_settings_scripts() {
		global $parent_file;

		if ( 'edit.php?post_type=' . CMCRPR_Base::POST_TYPE !== $parent_file ) {
			return;
		}

		wp_enqueue_style( 'jqueryUIStylesheet', self::$cssPath . 'jquery-ui-1.10.3.custom.css' );
		wp_enqueue_style( CMCRPR_SLUG_NAME . '-admin-css', self::$cssPath . 'admin.css' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( CMCRPR_SLUG_NAME . '-admin-js', self::$jsPath . 'admin.js', array( 'jquery', 'wp-color-picker' ) );

		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-tooltip' );
		wp_enqueue_script( 'jquery-ui-tabs' );

		$adminScriptData[ 'ajaxurl' ] = admin_url( 'admin-ajax.php' );
		wp_localize_script( CMCRPR_SLUG_NAME . '-admin-js', 'cmcrpr_data', $adminScriptData );
	}

	/**
	 * Function outputs the scripts and styles for the edit views
	 * @global type $typenow
	 * @return type
	 */
	public static function cmcrpr_admin_edit_scripts() {
		global $typenow;

		$defaultPostTypes			 = CMCRPR_Base::_getOptions( 'cmcrpr_allowed_terms_metabox_all_post_types' ) ? get_post_types() : array( 'post', 'page' );
		$allowedTermsBoxPostTypes	 = apply_filters( 'cmcrpr_allowed_terms_metabox_posttypes', $defaultPostTypes );

		if ( !in_array( $typenow, $allowedTermsBoxPostTypes ) ) {
			return;
		}

		wp_enqueue_style( CMCRPR_SLUG_NAME . '-admin-css', self::$cssPath . 'admin.css' );
		wp_enqueue_script( CMCRPR_SLUG_NAME . '-admin-js', self::$jsPath . 'admin.js', array( 'jquery', 'wp-color-picker' ) );

		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-tooltip' );
	}

	/**
	 * Filters admin navigation menus to show horizontal link bar
	 * @global string $submenu
	 * @global type $plugin_page
	 * @param type $views
	 * @return string
	 */
	public static function cmcrpr_filter_admin_nav( $views ) {
		global $submenu, $plugin_page;
		$scheme		 = is_ssl() ? 'https://' : 'http://';
		$adminUrl	 = str_replace( $scheme . $_SERVER[ 'HTTP_HOST' ], '', admin_url() );
		$currentUri	 = str_replace( $adminUrl, '', $_SERVER[ 'REQUEST_URI' ] );
		$submenus	 = array();
		if ( isset( $submenu[ CMCRPR_SLUG_NAME ] ) ) {
			$thisMenu = $submenu[ CMCRPR_SLUG_NAME ];

			$firstMenuItem = $thisMenu[ 0 ];
			unset( $thisMenu[ 0 ] );

			$secondMenuItem = array( 'Trash', 'edit_posts', 'edit.php?post_status=trash&post_type=' . CMCRPR_Base::POST_TYPE, 'Trash' );

			array_unshift( $thisMenu, $firstMenuItem, $secondMenuItem );

			foreach ( $thisMenu as $item ) {
				$slug					 = $item[ 2 ];
				$isCurrent				 = ($slug == $plugin_page || strpos( $item[ 2 ], '.php' ) === strpos( $currentUri, '.php' ));
				$isExternalPage			 = strpos( $item[ 2 ], 'http' ) !== FALSE;
				$isNotSubPage			 = $isExternalPage || strpos( $item[ 2 ], '.php' ) !== FALSE;
				$url					 = $isNotSubPage ? $slug : get_admin_url( null, 'admin.php?page=' . $slug );
				$target					 = $isExternalPage ? '_blank' : '';
				$submenus[ $item[ 0 ] ]	 = '<a href="' . $url . '" target="' . $target . '" class="' . ($isCurrent ? 'current' : '') . '">' . $item[ 0 ] . '</a>';
			}
		}
		return $submenus;
	}

	public static function cmcrpr_restrict_manage_posts() {
		global $typenow;
		if ( $typenow == CMCRPR_Base::POST_TYPE ) {
			$status	 = get_query_var( 'post_status' );
			$options = apply_filters( 'cmcrpr_' . CMCRPR_Base::POST_TYPE . '_restrict_manage_posts', array( 'published' => 'Published', 'trash' => 'Trash' ) );

			echo '<select name="post_status">';
			foreach ( $options as $key => $label ) {
				echo '<option value="' . $key . '" ' . selected( $key, $status ) . '>' . CMCRPR_Base::_e( $label ) . '</option>';
			}
			echo '</select>';
		}
	}

	/**
	 * Displays the horizontal navigation bar
	 * @global string $submenu
	 * @global type $plugin_page
	 */
	public static function cmcrpr_showNav() {
		global $submenu, $plugin_page;
		$submenus	 = array();
		$scheme		 = is_ssl() ? 'https://' : 'http://';
		$adminUrl	 = str_replace( $scheme . $_SERVER[ 'HTTP_HOST' ], '', admin_url() );
		$currentUri	 = str_replace( $adminUrl, '', $_SERVER[ 'REQUEST_URI' ] );

		if ( isset( $submenu[ CMCRPR_SLUG_NAME ] ) ) {
			$thisMenu = $submenu[ CMCRPR_SLUG_NAME ];
			foreach ( $thisMenu as $item ) {
				$slug			 = $item[ 2 ];
				$isCurrent		 = ($slug == $plugin_page || strpos( $item[ 2 ], '.php' ) === strpos( $currentUri, '.php' ));
				$isExternalPage	 = strpos( $item[ 2 ], 'http' ) !== FALSE;
				$isNotSubPage	 = $isExternalPage || strpos( $item[ 2 ], '.php' ) !== FALSE;
				$url			 = $isNotSubPage ? $slug : get_admin_url( null, 'admin.php?page=' . $slug );
				$submenus[]		 = array(
					'link'		 => $url,
					'title'		 => $item[ 0 ],
					'current'	 => $isCurrent,
					'target'	 => $isExternalPage ? '_blank' : ''
				);
			}
			include CMCRPR_PLUGIN_DIR . 'views/backend/admin_nav.php';
		}
	}

	/**
	 * Add the dynamic CSS to reflect the styles set by the options
	 * @return type
	 */
	public static function frontendDynamicCSS() {
		ob_start();
		echo apply_filters( 'frontendDynamicCSS_before', '' );
		?>
		<?php
		echo apply_filters( 'frontendDynamicCSS_after', '' );
		$content = ob_get_clean();
		return trim( $content );
	}

	/**
	 * Outputs the frontend CSS
	 */
	public static function frontendCSS() {

		wp_enqueue_style( CMCRPR_SLUG_NAME . '-frontend', self::$cssPath . 'frontend.css' );

		$fontName = CMCRPR_Base::_getOptions( 'customFontFamily', 'default' );
		if ( is_string( $fontName ) && 'default' !== $fontName ) {
			wp_enqueue_style( CMCRPR_SLUG_NAME . '-google-font', '//fonts.googleapis.com/css?family=' . $fontName );
		}

		/*
		 * It's WP 3.3+ function
		 */
		if ( function_exists( 'wp_add_inline_style' ) ) {
			wp_add_inline_style( CMCRPR_SLUG_NAME . '-frontend', self::frontendDynamicCSS() );
		}
	}

	/**
	 * Adds a notice about wp version lower than required 3.3
	 * @global type $wp_version
	 */
	public static function cmcrpr_admin_notice_wp33() {
		global $wp_version;

		if ( version_compare( $wp_version, '3.3', '<' ) ) {
			$message = sprintf( CMCRPR_Base::__( '%s requires Wordpress version 3.3 or higher to work properly.' ), CMCRPR_NAME );
			cminds_show_message( $message, true );
		}
	}

	/**
	 * Adds a notice about mbstring not being installed
	 * @global type $wp_version
	 */
	public static function cmcrpr_admin_notice_mbstring() {
		$mb_support = function_exists( 'mb_strtolower' );

		if ( !$mb_support ) {
			$message = sprintf( CMCRPR_Base::__( '%s since version 2.6.0 requires "mbstring" PHP extension to work! ' ), CMCRPR_NAME );
			$message .= '<a href="http://www.php.net/manual/en/mbstring.installation.php" target="_blank">(' . CMCRPR_Base::__( 'Installation instructions.' ) . ')</a>';
			cminds_show_message( $message, true );
		}
	}

	/**
	 * Sanitizes the options array
	 * @param type $optionsArr
	 * @return type
	 */
	public static function sanitizeOptions( $optionsArr ) {
		if ( !empty( $optionsArr ) ) {
			$baseOptionsArr = get_option( 'cmcrpr_options' );
			foreach ( $optionsArr as $optionName => $optionValue ) {
				$optionValue				 = is_array( $optionValue ) ? $optionValue : trim( $optionValue );
				$optionsArr[ $optionName ]	 = $optionValue;
			}

			$optionsArr = array_merge( $baseOptionsArr, $optionsArr );
		}
		return $optionsArr;
	}

	/**
	 * Function responsible for saving the options
	 */
	public static function saveOptions() {
		$post = array_map( 'stripslashes_deep', $_POST );

		if ( isset( $post[ "cmcrpr_optionsSave" ] ) ) {
			do_action( 'cmcrpr_save_options_before', $post );

			if ( apply_filters( 'cmcrpr_enqueueFlushRules', FALSE, $post ) ) {
				self::_flush_rewrite_rules();
			}

			$optionsArr			 = !empty( $post[ 'cmcrpr_options' ] ) ? $post[ 'cmcrpr_options' ] : array();
			$optionsArrFiltered	 = apply_filters( 'cmcrpr_options_before_save', $optionsArr );

			if ( !empty( $optionsArrFiltered ) ) {
				update_option( 'cmcrpr_options', $optionsArrFiltered );
			}

			self::_getOptions( NULL, NULL, TRUE );

			do_action( 'cmcrpr_save_options_after_on_save', $post );
		}

		do_action( 'cmcrpr_save_options_after', $post );

		if ( isset( $post[ 'cmcrpr_pluginCleanup' ] ) ) {
			self::_cleanup();
			self::addMessage( CMCRPR_NAME . ' data (posts, options) have been removed from the database.' );
		}

		return array( 'messages' => self::addMessage() );
	}

	public static function addMessage( $newMessage = null ) {
		static $message = null;
		if ( !empty( $newMessage ) ) {
			$message = $newMessage;
		}
		return $message;
	}

	/**
	 * Displays the options screen
	 */
	public static function outputOptions() {
		$result		 = self::saveOptions();
		$messages	 = $result[ 'messages' ];

		ob_start();
		include CMCRPR_PLUGIN_DIR . 'views/backend/admin_settings.php';
		$content = ob_get_contents();
		ob_end_clean();
		include CMCRPR_PLUGIN_DIR . 'views/backend/admin_template.php';
	}

	/**
	 * Returns items by custom id
	 * @staticvar array $resultsCache
	 * @param type $id
	 * @return type
	 */
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
					'key'	 => '_cmcrpr_custom_id',
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
	 * Function renders (default) or returns the setttings tabs
	 *
	 * @param type $return
	 * @return string
	 */
	public static function renderSettingsTabs( $return = false ) {
		$content				 = '';
		$settingsTabsArrayBase	 = array();

		$settingsTabsArray = apply_filters( 'cmf-settings-tabs-array', $settingsTabsArrayBase );

		if ( $settingsTabsArray ) {
			foreach ( $settingsTabsArray as $tabKey => $tabLabel ) {
				$filterName = 'cmf-custom-settings-tab-content-' . $tabKey;

				$content .= '<div id="tabs-' . $tabKey . '">';
				$tabContent = apply_filters( $filterName, '' );
				$content .= $tabContent;
				$content .= '</div>';
			}
		}

		if ( $return ) {
			return $content;
		}
		echo $content;
	}

	/**
	 * Function renders (default) or returns the setttings tabs
	 *
	 * @param type $return
	 * @return string
	 */
	public static function renderSettingsTabsControls( $return = false ) {
		$content				 = '';
		$settingsTabsArrayBase	 = array(
			'0'	 => 'Upgrade',
			'1'	 => 'General Settings',
			'2'	 => 'Widget Display',
			'88' => 'Shortcodes',
			'99' => 'Installation Instructions',
		);

		$settingsTabsArray = apply_filters( 'cmf-settings-tabs-array', $settingsTabsArrayBase );

		ksort( $settingsTabsArray );

		if ( $settingsTabsArray ) {
			$content .= '<ul>';
			foreach ( $settingsTabsArray as $tabKey => $tabLabel ) {
				$content .= '<li><a href="#tabs-' . $tabKey . '">' . $tabLabel . '</a></li>';
			}
			$content .= '</ul>';
		}

		if ( $return ) {
			return $content;
		}
		echo $content;
	}

	/**
	 * Returns the list of sorted plugins items
	 * @staticvar array $itemsIndexFullSorted
	 * @param type $args
	 * @return type
	 */
	public static function getPostTypeItemsSorted() {
		static $itemsIndexFullSorted = array();

		if ( $itemsIndexFullSorted === array() ) {
			$itemsIndex				 = self::getPostTypeItems();
			$itemsIndexFullSorted	 = $itemsIndex;
			uasort( $itemsIndexFullSorted, array( __CLASS__, '_sortByWPQueryObjectTitleLength' ) );
		}

		return $itemsIndexFullSorted;
	}

	/**
	 * Returns the cachable array of all Plugin Items, either sorted by title, or by title length
	 *
	 * @param type $args
	 * @return type
	 */
	public static function getPostTypeItems( $args = array() ) {
		static $itemsIndexCache = array();

		$pluginsItems	 = array();
		$pluginsItemsArr = array();

		$argsKey = 'cmcrpr_' . md5( 'args' . json_encode( $args ) );

		if ( !isset( $itemsIndexCache[ $argsKey ] ) ) {
			if ( !CMCRPR_Base::_getOptions( 'enableCaching', TRUE ) ) {
				delete_transient( $argsKey );
			}
			if ( false === ($pluginsItems = get_transient( $argsKey ) ) ) {
				$defaultArgs = array(
					'post_type'				 => CMCRPR_Base::POST_TYPE,
					'post_status'			 => 'publish',
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
					'suppress_filters'		 => false,
				);

				$queryArgs = array_merge( $defaultArgs, $args );

				$nopaging_args					 = $queryArgs;
				$nopaging_args[ 'nopaging' ]	 = true;
				$nopaging_args[ 'numberposts' ]	 = -1;

				if ( $args === array() ) {
					$queryArgs = $nopaging_args;
				}

				$query		 = new WP_Query;
				$itemsIndex	 = $query->query( $queryArgs );

				foreach ( $itemsIndex as $post ) {
					$obj				 = new stdClass();
					$obj->ID			 = $post->ID;
					$obj->post_title	 = $post->post_title;
					$obj->post_content	 = $post->post_content;
					$obj->post_excerpt	 = $post->post_excerpt;
					$obj->post_date		 = $post->post_date;

					$newObj				 = apply_filters( 'cmcrpr_get_all_plugin_items_single', $obj, $post );
					$pluginsItemsArr[]	 = $newObj;
				}

				$pluginsItems[ 'index' ]		 = $pluginsItemsArr;
				$pluginsItems[ 'query' ]		 = $query;
				$pluginsItems[ 'args' ]			 = $queryArgs;
				$pluginsItems[ 'nopaging_args' ] = $nopaging_args;

				if ( CMCRPR_Base::_getOptions( 'enableCaching', TRUE ) ) {
					set_transient( $argsKey, $pluginsItems, 1 * MINUTE_IN_SECONDS );
				}
			}

			$pluginsItemsArr		 = $pluginsItems[ 'index' ];
			/*
			 * Save statically
			 */
			self::$lastQueryDetails	 = $pluginsItems;
		}

		return $pluginsItemsArr;
	}

	public static function outputCustomPostTypesList() {
		$content = '<input type="hidden" name="showOnPostTypes" value="0" />';

		$post_types			 = array('post' => 'Post','page' => 'Page');
		$selected_post_types = CMCRPR_Base::_getOptions( 'showOnPostTypes' );

		if ( !is_array( $selected_post_types ) ) {
			$selected_post_types = array();
		}

		foreach ( $post_types as $post_type_key => $post_type_name ) {
			$label	 = $post_type_name . ' (' . $post_type_key . ')';
			$name	 = $post_type_key;

			$content .= '<div><label><input type="checkbox" name="showOnPostTypes[]" ' . checked( true, in_array( $name, $selected_post_types ), false ) . ' value="' . $name . '" />' . $label . '</label></div>';
		}
		return $content;
	}

	/*
	 *  Sort longer titles first, so if there is collision between terms
	 * (e.g., "essential fatty acid" and "fatty acid") the longer one gets created first.
	 */

	public static function _sortByWPQueryObjectTitleLength( $a, $b ) {
		$sortVal = 0;
		if ( property_exists( $a, 'post_title' ) && property_exists( $b, 'post_title' ) ) {
			$sortVal = strlen( $b->post_title ) - strlen( $a->post_title );
		}
		return $sortVal;
	}

	/**
	 * Strips just one tag
	 * @param type $str
	 * @param type $tags
	 * @param type $stripContent
	 * @return type
	 */
	public static function _strip_only( $str, $tags, $stripContent = false ) {
		$content = '';
		if ( !is_array( $tags ) ) {
			$tags = (strpos( $str, '>' ) !== false ? explode( '>', str_replace( '<', '', $tags ) ) : array( $tags ));
			if ( end( $tags ) == '' ) {
				array_pop( $tags );
			}
		}
		foreach ( $tags as $tag ) {
			if ( $stripContent ) {
				$content = '(.+</' . $tag . '[^>]*>|)';
			}
			$str = preg_replace( '#</?' . $tag . '[^>]*>' . $content . '#is', '', $str );
		}
		return $str;
	}

	/**
	 * Function cleans up the plugin, removing the terms, resetting the options etc.
	 *
	 * @return string
	 */
	protected static function _cleanup( $force = true ) {
		$pluginsItems = self::getPostTypeItems();

		/*
		 * Remove the plugins items
		 */
		foreach ( $pluginsItems as $post ) {
			wp_delete_post( $post->ID, $force );
		}

		/*
		 * Invalidate the list of all items stored in cache
		 */
		delete_transient( CMCRPR_TRANSIENT_ALL_ITEMS_KEY );

		/*
		 * Remove the data from the other tables
		 */
		do_action( 'cmcrpr_do_cleanup' );

		/*
		 * Remove the options
		 */
		$optionNames = wp_load_alloptions();

		function cmcrpr_get_the_option_names( $k ) {
			return strpos( $k, 'cmcrpr_' ) === 0;
		}

		$options_names = array_filter( array_keys( $optionNames ), 'cmcrpr_get_the_option_names' );
		foreach ( $options_names as $optionName ) {
			delete_option( $optionName );
		}
	}

	/**
	 * Plugin activation
	 */
	protected static function _activate() {
		do_action( 'cmcrpr_do_activate' );
	}

	/**
	 * Plugin installation
	 *
	 * @global type $wpdb
	 * @param type $networkwide
	 * @return type
	 */
	public static function _install( $networkwide ) {
		global $wpdb;

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			// check if it is a network activation - if so, run the activation function for each blog id
			if ( $networkwide ) {
				$old_blog	 = $wpdb->blogid;
				// Get all blog ids
				$blogids	 = $wpdb->get_col( $wpdb->prepare( "SELECT blog_id FROM {$wpdb->blogs}" ) );
				foreach ( $blogids as $blog_id ) {
					switch_to_blog( $blog_id );
					self::_activate();
				}
				switch_to_blog( $old_blog );
				return;
			}
		}

		self::_activate();
	}

	/**
	 * Flushes the rewrite rules to reflect the permalink changes automatically (if any)
	 *
	 * @global type $wp_rewrite
	 */
	public static function _flush_rewrite_rules() {
		global $wp_rewrite;
		// First, we "add" the custom post type via the above written function.

		self::cmcrpr_create_post_types();

		do_action( 'cmcrpr_flush_rewrite_rules' );

		// Clear the permalinks
		flush_rewrite_rules();

		//Call flush_rules() as a method of the $wp_rewrite object
		$wp_rewrite->flush_rules();
	}

	/**
	 * Returns the table of options
	 * @staticvar array $_options
	 * @param type $optionKey
	 * @return boolean
	 */
	public static function _getOptions( $optionKey = NULL, $defaultValue = FALSE, $resetCache = FALSE ) {
		static $_options = array();

		if ( empty( $_options ) || $resetCache ) {
			$_options = get_option( 'cmcrpr_options' );
		}
		if ( !empty( $optionKey ) ) {
			if ( !empty( $_options[ $optionKey ] ) ) {
				return $_options[ $optionKey ];
			} else {
				return $defaultValue;
			}
		}
		return $_options;
	}

	/**
	 * Scoped i18n function
	 * @param type $message
	 * @return type
	 */
	public static function __( $message ) {
		return __( $message, CMCRPR_SLUG_NAME );
	}

	/**
	 * Scoped i18n function
	 * @param type $message
	 * @return type
	 */
	public static function _e( $message ) {
		return _e( $message, CMCRPR_SLUG_NAME );
	}

}
