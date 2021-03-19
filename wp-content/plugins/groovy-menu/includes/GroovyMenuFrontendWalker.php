<?php defined( 'ABSPATH' ) || die( 'This script cannot be accessed directly.' );

/**
 * Class GroovyMenuFrontendWalker
 */
class GroovyMenuFrontendWalker extends GroovyMenuWalkerNavMenu {

	protected $currentLvl            = 0;
	protected $isMegaMenu            = false;
	protected $megaMenuCnt           = 0;
	protected $megaMenuColStarted    = false;
	protected $megaMenuCols          = 5;
	protected $megaMenuPost          = null;
	protected $megaMenuPostNotMobile = null;
	protected $currentItem;


	/**
	 * @param string $output
	 * @param int    $depth
	 * @param array  $args
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		$this->currentLvl ++;
		$classes = '';
		$styles  = '';

		if ( ! $this->isMegaMenu || ( $this->isMegaMenu && 2 !== $this->currentLvl ) ) {
			$classes = "gm-dropdown-menu gm-dropdown-menu--lvl-{$this->currentLvl}";

			if ( $this->getBackgroundId( $this->currentItem ) ) {
				$size     = $this->getBackgroundSize( $this->currentItem );
				$styles  .= 'background-image: url(' . $this->getBackgroundUrl( $this->currentItem, $size ) . ');';
				$styles  .= 'background-repeat: ' . $this->getBackgroundRepeat( $this->currentItem ) . ';';
				$styles  .= 'background-position: ' . $this->getBackgroundPosition( $this->currentItem ) . ';';
				$classes .= " gm-dropdown-menu--background";
			}
		}

		$output .= "\n$indent<ul class='{$classes}' style='{$styles}'>\n";
	}


	/**
	 * @param string $output
	 * @param int    $depth
	 * @param array  $args
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {

		$show_in_mobile = ( isset( $args->gm_navigation_mobile ) && $args->gm_navigation_mobile );

		if ( 1 === $this->currentLvl && $this->isMegaMenu && ! $show_in_mobile ) {
			$this->megamenuWrapperEnd( $output );
			$this->megaMenuCnt = 0;
		}
		$indent  = str_repeat( "\t", $depth );
		$output .= "$indent</ul>\n";
		$this->currentLvl --;

	}


	/**
	 * Begin of element
	 *
	 * @param string  $output
	 * @param WP_Post $item
	 * @param int     $depth
	 * @param array   $args
	 * @param int     $id
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		global $groovyMenuSettings;
		$item_output = '';

		$this->currentItem = $item;
		$indent            = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$show_in_mobile = ( isset( $args->gm_navigation_mobile ) && $args->gm_navigation_mobile );

		if ( ! $show_in_mobile ) {
			$this->megamenuWrapperStart( $output, $item );
		}

		$postContent                 = '';
		$this->megaMenuPost          = $this->megaMenuPost( $item );
		$this->megaMenuPostNotMobile = $this->megaMenuPostNotMobile( $item ) && $show_in_mobile;
		if ( $this->megaMenuPost && ! $this->megaMenuPostNotMobile ) {
			$postContent = $this->getMenuBlockPostContent( $this->megaMenuPost );
			if ( function_exists( 'groovy_menu_add_custom_styles' ) ) {
				groovy_menu_add_custom_styles( $this->megaMenuPost );
			}
		}

		$gm_menu_block = false;
		if ( isset( $item->object ) && 'gm_menu_block' === $item->object ) {
			$gm_menu_block = true;
		}


		$gm_thumb_settings = $this->gmGetThumbSettings( $item );


		if ( 1 === $depth && $this->isMegaMenu && ! $show_in_mobile ) {

			global $groovyMenuSettings;
			$styles          = new GroovyMenuStyle();
			$headerStyle     = intval( $groovyMenuSettings['header']['style'] );
			$is_title_as_url = $groovyMenuSettings['megamenuTitleAsLink'];

			if ( $headerStyle && in_array( $headerStyle, array( 2, 3 ), true ) ) {

				$gridClass = 'mobile-grid-100 grid-100';

			} else {

				if ( is_numeric( $this->megaMenuCols ) ) {
					if ( intval( $this->megaMenuCols ) > 0 ) {
						$colNumder = ( (int) ( 100 / intval( $this->megaMenuCols ) ) );
					}
				} else {
					$_colsElements  = explode( '-', $this->megaMenuCols );
					$_colsElemCount = count( $_colsElements );
					$_counter       = $this->megaMenuCnt;
					$maximus        = 100;

					if ( is_array( $_colsElements ) && ! empty( $_colsElements ) ) {

						while ( empty( $colNumder ) && $maximus > 0 ) {

							if ( $_counter > $_colsElemCount ) {
								$_counter = $_counter - $_colsElemCount;
							}

							if ( ! empty( $_colsElements[ ( $_counter - 1 ) ] ) ) {
								$colNumder = $_colsElements[ ( $_counter - 1 ) ];
							}

							$maximus --;
						}
					}
				}

				if ( empty( $colNumder ) ) {
					$colNumder = '20'; // 20 by default. 5 cols
				}

				$gridClass = 'mobile-grid-100 grid-' . $colNumder;
			}

			$output .= '<div class="gm-mega-menu__item ' . $gridClass . '">';

			if ( $gm_thumb_settings['display'] && 'above' === $gm_thumb_settings['position'] ) {
				$output .= $gm_thumb_settings['html'];
			}

			if ( ! $this->doNotShowTitle( $item ) ) {

				$item_link  = '';
				$item_title = '';

				if ( $is_title_as_url ) {
					$atts           = array();
					$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
					$atts['target'] = ! empty( $item->target ) ? $item->target : '';
					$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
					$atts['href']   = ! empty( $item->url ) ? $item->url : '';
					$atts['class']  = 'gm-anchor';

					$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

					$attributes = '';
					foreach ( $atts as $attr => $value ) {
						if ( ! empty( $value ) ) {
							if ( 'href' === $attr ) {
								$value = esc_url( $value );
								if ( $gm_menu_block ) {
									$value = $this->menuBlockURL( $item, $value );
								}
							} else {
								$value = esc_attr( $value );
							}
							$attributes .= ' ' . $attr . '="' . $value . '"';
						}
					}

					$item_link .= '<a' . $attributes . '>';
					if ( $this->getIcon( $item ) ) {
						$item_link .= '<span class="gm-menu-item__icon ' . $this->getIcon( $item ) . '"></span>';
					}

					$badge = array(
						'left'  => '',
						'right' => '',
					);

					$badge_enable = $this->getBadgeEnable( $item );
					if ( ! empty( $badge_enable ) && $badge_enable ) {

						$attr                    = 'style';
						$badge_type              = $this->getBadgeType( $item );
						$badge_placement         = $this->getBadgePlacement( $item );
						$badge_position          = $this->getBadgeGeneralPosition( $item );
						$badge_y_position        = $this->getBadgeYPosition( $item ) ? : 0;
						$badge_x_position        = $this->getBadgeXPosition( $item ) ? : 0;
						$badge_container_radius  = $this->getBadgeContainerRadius( $item );
						$badge_container_padding = $this->getBadgeContainerPadding( $item );
						$badge_container_bg      = $this->getBadgeContainerBg( $item );
						$badge_in_style          = '';
						$badge_out_style         = '';

						if ( ! empty( $badge_position ) ) {
							$badge_out_style .= 'position: ' . $badge_position . ';';
						}
						if ( ! empty( $badge_y_position ) || ! empty( $badge_x_position ) ) {
							$badge_out_style .= 'transform: translate(' . $badge_x_position . ', ' . $badge_y_position . ');';
						}

						if ( ! empty( $badge_container_bg ) ) {
							$badge_in_style .= 'background-color: ' . $badge_container_bg . ';';
						}
						if ( ! empty( $badge_container_padding ) ) {
							$badge_in_style .= 'padding: ' . $badge_container_padding . ';';
						}
						if ( ! empty( $badge_container_radius ) ) {
							$badge_in_style .= 'border-radius: ' . $badge_container_radius . ';';
						}


						switch ( $badge_type ) {
							case 'image':
								$badge_image       = $this->getBadgeImage( $item );
								$badge_image_sizes = $this->getBadgeImageWidthHeight( $item );

								if ( ! empty( $badge_in_style ) ) {
									$badge_in_style = $attr . '="' . $badge_in_style . '" ';
								}

								if ( ! empty( $badge_image ) ) {
									$badge_html = '<span ' . $badge_in_style . '><img src="' . $this->getBadgeImage( $item ) . '" alt="" ' . $badge_image_sizes . '></span>';
								}
								break;

							case 'icon':
								$badge_icon       = $this->getBadgeIcon( $item );
								$badge_icon_size  = $this->getBadgeIconSize( $item );
								$badge_icon_color = $this->getBadgeIconColor( $item );

								if ( ! empty( $badge_icon_color ) ) {
									$badge_in_style .= 'color: ' . $badge_icon_color . ';';
								}
								if ( ! empty( $badge_icon_size ) ) {
									$badge_in_style .= 'font-size: ' . $badge_icon_size . 'px;';
								}

								if ( ! empty( $badge_icon ) ) {
									if ( ! empty( $badge_in_style ) ) {
										$badge_in_style = $attr . '="' . $badge_in_style . '" ';
									}
									$badge_html = '<span ' . $badge_in_style . '><i class="' . $badge_icon . '"></i></span>';
								}
								break;


							case 'text':
								$badge_text         = $this->getBadgeText( $item );
								$badge_text_family  = $this->getBadgeTextFontFamily( $item );
								$badge_text_variant = $this->getBadgeTextFontVariant( $item );
								$badge_text_size    = $this->getBadgeTextFontSize( $item );
								$badge_text_color   = $this->getBadgeTextFontColor( $item );
								if ( ! empty( $badge_text_family ) ) {
									$fontClass           = new GroovyMenuGFonts();
									$common_font_variant = $badge_text_variant;
									if ( 'inherit' === $common_font_variant ) {
										$common_font_variant = 'regular';
									}
									$fontClass->add_gfont_face_simple( $badge_text_family, $common_font_variant, true );

									$badge_in_style .= 'font-family: \'' . $badge_text_family . '\';';
								}
								if ( ! empty( $badge_text_variant ) ) {
									$common_font_variant = intval( $badge_text_variant );
									if ( empty( $common_font_variant ) || 'regular' === $badge_text_variant || 'italic' === $badge_text_variant ) {
										$common_font_variant = 400;
									}
									$badge_in_style .= 'font-weight: ' . $common_font_variant . ';';
									$pos             = strpos( $badge_text_variant, 'italic' );
									if ( false !== $pos ) {
										$badge_in_style .= 'font-style: italic;';
									}
								}
								if ( ! empty( $badge_text_color ) ) {
									$badge_in_style .= 'color: ' . $badge_text_color . ';';
								}
								if ( ! empty( $badge_text_size ) ) {
									$badge_in_style .= 'font-size: ' . $badge_text_size . 'px;';
								}

								if ( ! empty( $badge_text ) ) {
									if ( ! empty( $badge_in_style ) ) {
										$badge_in_style = $attr . '="' . $badge_in_style . '" ';
									}
									$badge_html = '<span ' . $badge_in_style . '>' . $badge_text . '</span>';
								}
								break;
						}


						if ( ! empty( $badge_out_style ) ) {
							$badge_out_style = $attr . '="' . $badge_out_style . '" ';
						}

						if ( ! empty( $badge_placement ) && ! empty( $badge_html ) ) {
							$badge[ $badge_placement ] = '<span class="gm-badge" ' . $badge_out_style . '>' . $badge_html . '</span>';
						}
					}

					$item_link .= '<span class="gm-menu-item__txt-wrapper">';
					$item_link .= $badge['left'];
					$item_link .= '<span class="gm-menu-item__txt">';
					$item_link .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
					$item_link .= '</span>'; // .gm-menu-item__txt
					$item_link .= $badge['right'];
					$item_link .= '</span>'; // .gm-menu-item__txt-wrapper
					$item_link .= '</a>';


					$item_title .= $item_link;

				} else {

					$item_title .= apply_filters( 'the_title', $item->title, $item->ID );

				}

				$output .= '<div class="gm-mega-menu__item__title">' . $item_title . '</div>';

			}

			if ( $postContent ) {
				$output .= $postContent;
			}

		} else {

			if ( $gm_thumb_settings['display'] && 'above' === $gm_thumb_settings['position'] ) {
				$output .= $gm_thumb_settings['html'];
			}

			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			$thumb   = null;
			if ( $depth > 0 && $this->isShowFeaturedImage( $item ) ) {
				$previewWidth  = $groovyMenuSettings['previewWidth'];
				$previewHeight = $groovyMenuSettings['previewHeight'];

				if ( get_post_thumbnail_id( $item->object_id ) ) {
					$thumb = wp_get_attachment_image( get_post_thumbnail_id( $item->object_id ), array(
						$previewWidth,
						$previewHeight,
					), false, array( 'class' => 'attachment-menu-thumb size-menu-thumb' ) );
					if ( $thumb ) {
						$classes[] = 'has-attachment-thumbnail';
					}
				}
			}

			$classes[] = 'gm-menu-item';
			if ( $this->hasChildren( $classes ) ) {
				$classes[] = 'gm-dropdown';
			}
			if ( $this->hasParents() && $this->hasChildren( $classes ) ) {
				$classes[] = 'gm-dropdown-submenu';
			}

			if ( 0 === $depth && $this->isMegaMenu( $item ) && ! $show_in_mobile ) {
				$this->megaMenuCols = $this->megaMenuCols( $item );
				$classes[]          = 'mega-gm-dropdown';
				$this->isMegaMenu   = true;
			}

			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
			$class_names = trim( $class_names ) ? ' class="' . esc_attr( $class_names ) . '"' : '';

			$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

			$output        .= $indent . '<li' . $id . $class_names . '>';
			$atts           = array();
			$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
			$atts['target'] = ! empty( $item->target ) ? $item->target : '';
			$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
			$atts['href']   = ! empty( $item->url ) ? $item->url : '';
			$atts['class']  = 'gm-anchor';
			if ( $this->hasChildren( $classes ) ) {
				$atts['class'] .= ' gm-dropdown-toggle';
			}
			if ( $this->hasParents() ) {
				$atts['class'] .= ' gm-menu-item__link';
			}

			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					if ( 'href' === $attr ) {
						$value = esc_url( $value );
						if ( $gm_menu_block ) {
							$value = $this->menuBlockURL( $item, $value );
						}
					} else {
						$value = esc_attr( $value );
					}
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			$item_output .= $args->before;
			if ( ! $this->doNotShowTitle( $item ) ) {
				$item_output .= '<a' . $attributes . '>';
				if ( $this->getIcon( $item ) ) {
					$item_output .= '<span class="gm-menu-item__icon ' . $this->getIcon( $item ) . '"></span>';
				}

				$badge = array(
					'left'  => '',
					'right' => '',
				);

				$badge_enable = $this->getBadgeEnable( $item );
				if ( ! empty( $badge_enable ) && $badge_enable ) {

					$attr                    = 'style';
					$badge_type              = $this->getBadgeType( $item );
					$badge_placement         = $this->getBadgePlacement( $item );
					$badge_position          = $this->getBadgeGeneralPosition( $item );
					$badge_y_position        = $this->getBadgeYPosition( $item ) ? : 0;
					$badge_x_position        = $this->getBadgeXPosition( $item ) ? : 0;
					$badge_container_radius  = $this->getBadgeContainerRadius( $item );
					$badge_container_padding = $this->getBadgeContainerPadding( $item );
					$badge_container_bg      = $this->getBadgeContainerBg( $item );
					$badge_in_style          = '';
					$badge_out_style         = '';

					if ( ! empty( $badge_position ) ) {
						$badge_out_style .= 'position: ' . $badge_position . ';';
					}
					if ( ! empty( $badge_y_position ) || ! empty( $badge_x_position ) ) {
						$badge_out_style .= 'transform: translate(' . $badge_x_position . ', ' . $badge_y_position . ');';
					}

					if ( ! empty( $badge_container_bg ) ) {
						$badge_in_style .= 'background-color: ' . $badge_container_bg . ';';
					}
					if ( ! empty( $badge_container_padding ) ) {
						$badge_in_style .= 'padding: ' . $badge_container_padding . ';';
					}
					if ( ! empty( $badge_container_radius ) ) {
						$badge_in_style .= 'border-radius: ' . $badge_container_radius . ';';
					}


					switch ( $badge_type ) {
						case 'image':
							$badge_image       = $this->getBadgeImage( $item );
							$badge_image_sizes = $this->getBadgeImageWidthHeight( $item );

							if ( ! empty( $badge_in_style ) ) {
								$badge_in_style = $attr . '="' . $badge_in_style . '" ';
							}

							if ( ! empty( $badge_image ) ) {
								$badge_html = '<span ' . $badge_in_style . '><img src="' . $this->getBadgeImage( $item ) . '" alt="" ' . $badge_image_sizes . '></span>';
							}
							break;

						case 'icon':
							$badge_icon       = $this->getBadgeIcon( $item );
							$badge_icon_size  = $this->getBadgeIconSize( $item );
							$badge_icon_color = $this->getBadgeIconColor( $item );

							if ( ! empty( $badge_icon_color ) ) {
								$badge_in_style .= 'color: ' . $badge_icon_color . ';';
							}
							if ( ! empty( $badge_icon_size ) ) {
								$badge_in_style .= 'font-size: ' . $badge_icon_size . 'px;';
							}

							if ( ! empty( $badge_icon ) ) {
								if ( ! empty( $badge_in_style ) ) {
									$badge_in_style = $attr . '="' . $badge_in_style . '" ';
								}
								$badge_html = '<span ' . $badge_in_style . '><i class="' . $badge_icon . '"></i></span>';
							}
							break;


						case 'text':
							$badge_text         = $this->getBadgeText( $item );
							$badge_text_family  = $this->getBadgeTextFontFamily( $item );
							$badge_text_variant = $this->getBadgeTextFontVariant( $item );
							$badge_text_size    = $this->getBadgeTextFontSize( $item );
							$badge_text_color   = $this->getBadgeTextFontColor( $item );
							if ( ! empty( $badge_text_family ) ) {
								$fontClass           = new GroovyMenuGFonts();
								$common_font_variant = $badge_text_variant;
								if ( 'inherit' === $common_font_variant ) {
									$common_font_variant = 'regular';
								}
								$fontClass->add_gfont_face_simple( $badge_text_family, $common_font_variant, true );

								$badge_in_style .= 'font-family: \'' . $badge_text_family . '\';';
							}
							if ( ! empty( $badge_text_variant ) ) {
								$common_font_variant = intval( $badge_text_variant );
								if ( empty( $common_font_variant ) || 'regular' === $badge_text_variant || 'italic' === $badge_text_variant ) {
									$common_font_variant = 400;
								}
								$badge_in_style .= 'font-weight: ' . $common_font_variant . ';';
								$pos             = strpos( $badge_text_variant, 'italic' );
								if ( false !== $pos ) {
									$badge_in_style .= 'font-style: italic;';
								}
							}
							if ( ! empty( $badge_text_color ) ) {
								$badge_in_style .= 'color: ' . $badge_text_color . ';';
							}
							if ( ! empty( $badge_text_size ) ) {
								$badge_in_style .= 'font-size: ' . $badge_text_size . 'px;';
							}

							if ( ! empty( $badge_text ) ) {
								if ( ! empty( $badge_in_style ) ) {
									$badge_in_style = $attr . '="' . $badge_in_style . '" ';
								}
								$badge_html = '<span ' . $badge_in_style . '>' . $badge_text . '</span>';
							}
							break;
					}


					if ( ! empty( $badge_out_style ) ) {
						$badge_out_style = $attr . '="' . $badge_out_style . '" ';
					}

					if ( ! empty( $badge_placement ) && ! empty( $badge_html ) ) {
						$badge[ $badge_placement ] = '<span class="gm-badge" ' . $badge_out_style . '>' . $badge_html . '</span>';
					}
				}

				$item_output .= '<span class="gm-menu-item__txt-wrapper">';
				$item_output .= $badge['left'];
				$item_output .= '<span class="gm-menu-item__txt">';
				$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
				$item_output .= '</span>'; // .gm-menu-item__txt
				$item_output .= $badge['right'];
				$item_output .= '</span>'; // .gm-menu-item__txt-wrapper
				if ( $this->hasParents() && $this->hasChildren( $classes ) ) {
					$item_output .= '<span class="gm-caret"><i class="fa fa-fw fa-angle-right"></i></span>';
				} elseif ( $this->hasChildren( $classes ) ) {
					$item_output .= '<span class="gm-caret"><i class="fa fa-fw fa-angle-down"></i></span>';
				}
				$item_output .= $thumb;
				$item_output .= '</a>';
			} else {
				if ( $this->hasParents() && $this->hasChildren( $classes ) ) {
					$item_output .= '<span class="gm-caret ' . $atts['class'] . '"><i class="fa fa-fw fa-angle-right"></i></span>';
				} elseif ( $this->hasChildren( $classes ) ) {
					$item_output .= '<span class="gm-caret ' . $atts['class'] . '"><i class="fa fa-fw fa-angle-down"></i></span>';
				}
				$item_output .= $thumb;
			}
			$item_output .= $postContent;
			$item_output .= $args->after;
		}
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}


	/**
	 * @param string  $output
	 * @param WP_Post $item
	 * @param int     $depth
	 * @param array   $args
	 */
	public function end_el( &$output, $item, $depth = 0, $args = array() ) {

		$show_in_mobile = ( isset( $args->gm_navigation_mobile ) && $args->gm_navigation_mobile );

		$gm_thumb_settings = $this->gmGetThumbSettings( $item );

		if ( 1 === $depth && $this->isMegaMenu && ! $show_in_mobile ) {

			if ( $gm_thumb_settings['display'] && 'under' === $gm_thumb_settings['position'] ) {
				$output .= $gm_thumb_settings['html'];
			}

			$output .= '</div>';
		} else {

			if ( $gm_thumb_settings['display'] && 'under' === $gm_thumb_settings['position'] ) {
				$output .= $gm_thumb_settings['html'];
			}

			parent::end_el( $output, $item, $depth, $args );
		}

		$this->megaMenuPost = '';

	}


	/**
	 * @param $classes
	 *
	 * @return bool
	 */
	protected function hasChildren( $classes ) {
		return in_array( 'menu-item-has-children', $classes, true );
	}


	/**
	 * @return bool
	 */
	protected function hasParents() {
		return $this->currentLvl > 0;
	}


	/**
	 * @param $output
	 * @param $item
	 */
	protected function megamenuWrapperStart( &$output, $item ) {

		if ( $this->isMegaMenu ) {
			if ( 1 === $this->currentLvl ) {
				$this->megaMenuCnt ++;

				if ( 1 === $this->megaMenuCnt ) {
					$styles = '';
					$class  = 'gm-mega-menu-wrapper';

					$output .= '<li><div style="' . $styles . '" class="' . $class . '"><div class="gm-grid-container"><div class="gm-grid-row">';
				}
			}
		}
	}


	/**
	 * @param $output
	 */
	protected function megamenuWrapperEnd( &$output ) {

		$output .= '</div></div></div></li>';

		$this->isMegaMenu   = false;
		$this->megaMenuPost = '';

	}


	/**
	 * @param $item
	 *
	 * @return array
	 */
	protected function gmGetThumbSettings( $item ) {

		$gm_thumb_settings = array(
			'display'      => false,
			'menu_item_id' => $this->getId( $item ),
			'object_id'    => $item->object_id,
			'position'     => 'above',
			'max_height'   => '128',
			'image'        => '',
			'html'         => '',
		);
		if ( $this->getThumbEnable( $item ) ) {
			$gm_thumb_settings['display']    = true;
			$gm_thumb_settings['position']   = $this->getThumbPosition( $item ) ? esc_attr( $this->getThumbPosition( $item ) ) : 'above';
			$gm_thumb_settings['max_height'] = $this->getThumbMaxHeight( $item ) ? esc_attr( $this->getThumbMaxHeight( $item ) ) : '128';
			$gm_thumb_settings['image']      = $this->getThumbImage( $item ) ? esc_attr( $this->getThumbImage( $item ) ) : '';

			$gm_thumb_html = '';

			if ( ! empty( $gm_thumb_settings['image'] ) ) {
				$thumb_css = $gm_thumb_settings['max_height'] ? ' style' : '';
				if ( $thumb_css ) {
					$thumb_css .= '="max-height:' . $gm_thumb_settings['max_height'] . 'px;" ';
				}

				$thumb_classes = array(
					'gm-thumb-menu-item-wrapper',
					'gm-thumb-menu-item-position--' . $gm_thumb_settings['position'],
				);

				$gm_thumb_html .= '<div class="' . implode( ' ', $thumb_classes ) . '"' . $thumb_css . '>';
				$gm_thumb_html .= '<img class="gm-thumb-menu-item" src="' . $gm_thumb_settings['image'] . '">';
				$gm_thumb_html .= '</div>';
			}

			$gm_thumb_settings['html'] = strval( apply_filters( 'groovy_menu_item_thumb_html', $gm_thumb_html, $gm_thumb_settings ) );

		}

		$gm_thumb_settings = apply_filters( 'groovy_menu_item_thumb_settings', $gm_thumb_settings );

		// if not array - set thumb disabled.
		if ( ! is_array( $gm_thumb_settings ) ) {
			$gm_thumb_settings = array( 'display' => false );
		}

		return $gm_thumb_settings;
	}

}
