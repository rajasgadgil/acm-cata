<?php

class CMCRPR_Metabox {

	public static function init() {

		global $cmcrpr_isLicenseOk;

		add_action( 'add_meta_boxes', array( __CLASS__, 'cmcrpr_RegisterBoxes' ) );
		add_action( 'save_post', array( __CLASS__, 'saveMetaboxValues' ) );

		if ( $cmcrpr_isLicenseOk ) {

		}
	}

	/**
	 * Registers the metaboxes
	 */
	public static function cmcrpr_RegisterBoxes() {

		add_meta_box( CMCRPR_SLUG_NAME . '-properties-box', CMCRPR_SHORTNAME . ' - Properties', array( __CLASS__, 'renderMetabox' ), CMCRPR_Base::POST_TYPE, 'normal', 'high', array( 'id' => 'properties' ) );

		$defaultPostTypes	 = CMCRPR_Base::_getOptions( 'exludeMetaboxOnAllPostTypes' ) ? get_post_types() : array( 'post', 'page' );
		$connectedPostTypes	 = apply_filters( 'cmcrpr_disable_metabox_posttypes', $defaultPostTypes );

		foreach ( $connectedPostTypes as $postType ) {
//			add_meta_box( CMCRPR_SLUG_NAME . '-disable-box', CMCRPR_SHORTNAME . ' - Options', array( __CLASS__, 'renderMetabox' ), $postType, 'side', 'high', array( 'id' => 'excludes' ) );
		}

		do_action( 'cmcrpr_register_boxes' );
	}

	public static function getMetaboxFields( $metaboxID = null ) {

		$fields = array(
			'properties' => array(
				'cmcrpr_url'		 => array( 'label' => CMCRPR_Base::__( 'URL' ), 'html_atts' => 'size="30"' ),
			)
		);

		if ( null !== $metaboxID ) {
			$defaultFields = isset( $fields[ $metaboxID ] ) ? $fields[ $metaboxID ] : array();
		} else {
			$defaultFields = array();
			foreach ( $fields as $value ) {
				$defaultFields = array_merge( $defaultFields, $value );
			}
		}

		$metaBoxFields = apply_filters( 'cmcrpr_' . $metaboxID . '_metabox', $defaultFields );
		return $metaBoxFields;
	}

	public static function renderMetabox( $post, $args = null ) {
		$result = array();

		$metaboxID = isset( $args[ 'args' ][ 'id' ] ) ? $args[ 'args' ][ 'id' ] : 'properties';

		foreach ( self::getMetaboxFields( $metaboxID ) as $key => $fieldValueArr ) {
			$optionContent	 = '<p><label for="' . $key . '" class="blocklabel">';
			$fieldValue		 = get_post_meta( $post->ID, '_' . $key, true );

			if ( $fieldValue === '' && !empty( $fieldValueArr[ 'default' ] ) ) {
				$fieldValue = $fieldValueArr[ 'default' ];
			}

			if ( is_string( $fieldValueArr ) ) {
				$label = $fieldValueArr;
				$optionContent .= '<input type="checkbox" name="' . $key . '" id="' . $key . '" value="1" ' . checked( '1', $fieldValue, false ) . '>';
			} elseif ( is_array( $fieldValueArr ) ) {
				$label = isset( $fieldValueArr[ 'label' ] ) ? $fieldValueArr[ 'label' ] : CMCRPR_Base::__( 'No label' );

				if ( array_key_exists( 'options', $fieldValueArr ) ) {
					$options = isset( $fieldValueArr[ 'options' ] ) ? $fieldValueArr[ 'options' ] : array( '' => CMCRPR_Base::__( '-no options-' ) );
					$optionContent .= '<select name="' . $key . '" id="' . $key . '">';
					foreach ( $options as $optionKey => $optionLabel ) {
						$optionContent .= '<option value="' . $optionKey . '" ' . selected( $optionKey, $fieldValue, false ) . '>' . $optionLabel . '</option>';
					}
					$optionContent .= '</select>';
				} else if ( array_key_exists( 'callback', $fieldValueArr ) ) {
					$optionContent .= call_user_func( $fieldValueArr[ 'callback' ], $key, $fieldValueArr, $post );
				} else {
					$type		 = isset( $fieldValueArr[ 'type' ] ) ? $fieldValueArr[ 'type' ] : 'text';
					$htmlAtts	 = isset( $fieldValueArr[ 'html_atts' ] ) ? $fieldValueArr[ 'html_atts' ] : '';
					$optionContent .= '<input type="' . $type . '" name="' . $key . '" id="' . $key . '" value="' . $fieldValue . '" ' . $htmlAtts . '>';
				}
			}

			if ( !empty( $label ) ) {
				$optionContent .= '&nbsp;&nbsp;&nbsp;' . $label . '</label>';
			}

			$optionContent .= '</p>';

			$result[] = $optionContent;
		}

		$result = apply_filters( 'cmcrpr_edit_properties_metabox_array', $result );

		echo implode( '', $result );
	}

	/**
	 * Function for saving the metabox options
	 * @param type $post_id
	 * @return type
	 */
	public static function saveMetaboxValues( $post_id ) {
		$post		 = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );
		$postType	 = isset( $post[ 'post_type' ] ) ? $post[ 'post_type' ] : '';

		do_action( 'cmcrpr_on_' . CMCRPR_Base::POST_TYPE . '_item_save_before', $post_id, $post );

		$saveOnPostTypes = apply_filters( 'cmcrpr_disable_metabox_posttypes', array( 'post', 'page' ) );
		if ( !in_array( CMCRPR_Base::POST_TYPE, $saveOnPostTypes ) ) {
			$saveOnPostTypes[] = CMCRPR_Base::POST_TYPE;
		}

		if ( !in_array( $postType, $saveOnPostTypes ) ) {
			return;
		}

		do_action( 'cmcrpr_on_' . CMCRPR_Base::POST_TYPE . '_item_save', $post_id, $post );

		/*
		 * Invalidate the list of all plugin items stored in cache
		 */
		delete_transient( CMCRPR_TRANSIENT_ALL_ITEMS_KEY );

		/*
		 * Part for "plugin post type" items only starts here
		 */
		foreach ( array_keys( self::getMetaboxFields() ) as $value ) {
			if ( !isset( $post[ $value ] ) ) {
				continue;
			}
			$metaValue = $post[ $value ];
			if ( is_array( $metaValue ) ) {
				delete_post_meta( $post_id, '_' . $value );
				$metaValue = array_filter( $metaValue );
			}
			update_post_meta( $post_id, '_' . $value, $metaValue );
		}
	}

	public static function renderCategorySelector( $key, $atts, $post ) {

		$fieldValue = get_post_meta( $post->ID, '_' . $key, true );

		$args	 = array(
			'show_option_none'	 => '-All-',
			'option_none_value'	 => '',
			'orderby'			 => 'ID',
			'order'				 => 'ASC',
			'show_count'		 => 0,
			'hide_empty'		 => 1,
			'child_of'			 => 0,
			'exclude'			 => '',
			'echo'				 => 0,
			'selected'			 => $fieldValue,
			'hierarchical'		 => 0,
			'name'				 => $key,
			'id'				 => '',
			'class'				 => 'postform',
			'depth'				 => 0,
			'tab_index'			 => 0,
			'taxonomy'			 => CMCRPR_Base::TAXONOMY,
			'hide_if_empty'		 => false,
			'value_field'		 => 'term_id',
		);
		$result	 = wp_dropdown_categories( $args );
		return $result;
	}

	public static function renderPageSelector( $key, $atts, $post ) {
		$innterContent = '';

		$fieldValue = get_post_meta( $post->ID, '_' . $key, true );

		if ( !empty( $fieldValue ) ) {
			if ( !is_array( $fieldValue ) ) {
				$fieldValue = array( $fieldValue );
			}

			foreach ( $fieldValue as $fieldIndex => $fieldPageId ) {
				$dropdownArgs = array(
					'echo'		 => 0,
					'name'		 => $key . '[]',
					'selected'	 => $fieldPageId,
					'id'		 => $key . '_' . $fieldIndex
				);
				$innterContent .= '<div class="selectedPageRow">' . cmcrpr_cminds_dropdown( $dropdownArgs ) . '<a class="remove button-secondary">' . self::__( 'Remove' ) . '</a></div>';
			}
		}

		$dropdownArgs = array(
			'echo'				 => 0,
			'name'				 => $key . '[]',
			'show_option_none'	 => self::__( '-None-' ),
			'id'				 => ''
		);
		$innterContent .= '<div class="selectedPageRow toAdd" style="display:none">' . cmcrpr_cminds_dropdown( $dropdownArgs ) . '<a class="remove button-secondary">' . self::__( 'Remove' ) . '</a></div>';
		$innterContent .= '<p><a class="add button-primary">' . self::__( 'Add' ) . '</a></p>';
		ob_start();
		?>
		<script>
		    ( function ( $ ) {

		        $( document ).ready( function () {
		            $( 'div.cmcrpr_PageSelector' ).on( 'click', 'a.remove', function () {
		                var rowToRemove = $( this ).parents( 'div.selectedPageRow' );
		                rowToRemove.remove();
		                return false;
		            } );

		            $( 'div.cmcrpr_PageSelector a.add' ).on( 'click', function () {
		                var rowToAdd = $( this ).parents( 'div.cmcrpr_PageSelector' ).find( 'div.selectedPageRow.toAdd' );
		                var clone = rowToAdd.clone().removeClass( 'toAdd' ).show();

		                rowToAdd.before( clone );
		                return false;
		            } );

		            $( 'select#cmcrpr_display_mode' ).on( 'change', function () {
		                var val = $( this ).val();
		                var pageSelector = $( '.cmcrpr_PageSelector' );

		                if ( val === '0' || val === '1' )
		                {
		                    pageSelector.hide();
		                }
		                else
		                {
		                    pageSelector.show();
		                }
		            } ).trigger( 'change' );
		        } );

		    }( jQuery ) );
		</script>
		<?php

		$scriptContent	 = ob_get_clean();
		$content		 = '<div class="cmcrpr_PageSelector"><h4>Selected pages</h4>' . $innterContent . $scriptContent . '</div>';

		return $content;
	}

}
