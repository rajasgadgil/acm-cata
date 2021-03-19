<?php
/**
 * Class contains methods/helper functions related to Wordpress SEO plugin integration.
 * Fixes canonical urls for tours archive page and tours archive page title.
 *
 * @author    Themedelight
 * @package   Themedelight/AdventureTours
 * @version   3.2.0
 */

class AtWordpressSEOIntegrationHelper extends TdComponent {
	/**
	 * If canoncal urls for tours archive page should be fixed.
	 *
	 * @var boolean
	 */
	public $fix_tour_archive_canonical_urls = true;

	/**
	 * If page title for tours archive page should be fixed.
	 *
	 * @var boolean
	 */
	public $fix_tour_archive_page_title = true;

	/**
	 * If meta description text should be fixed ( used from tours archive page ) instead of the products post type archive.
	 *
	 * @var boolean
	 *
	 * @deprecated since version 3.2.0
	 */
	public $fix_tour_archive_seo_description = true;

	protected $tours_page_url;

	public function init() {
		if ( ! parent::init() ) {
			return false;
		}

		if ( $this->fix_tour_archive_page_title ) {
			add_filter( 'wpseo_frontend_page_type_simple_page_id', array( $this, 'filter_tours_page_id' ), 11 );
			add_filter( 'wpseo_replacements', array( &$this, 'wpseo_replacements_fix_plular_for_tours_archive' ) );
		}

		if( $this->fix_tour_archive_canonical_urls ) {
			add_action( 'template_redirect', array( &$this, 'on_template_redirect' ) );
		}

		return true;
	}

	public function filter_tours_page_id( $page_id ) {
		if ( ! adventure_tours_check( 'is_tour_search' ) ) {
			return $page_id;
		}

		$tours_page_id = adventure_tours_get_option( 'tours_page' );
		return $tours_page_id ? $tours_page_id : $page_id;
	}

	public function on_template_redirect() {
		if ( ! adventure_tours_check( 'is_tour_search' ) ) {
			return;
		}

		$tours_page_id = adventure_tours_get_option( 'tours_page' );
		$tours_post = $tours_page_id ? get_post( $tours_page_id ) : null;
		$this->tours_page_url = $tours_post ? get_permalink( $tours_post ) : '';

		if ( $this->fix_tour_archive_canonical_urls && $this->tours_page_url ) {
			add_action( 'wpseo_head', array( &$this, 'activate_tour_type_filter' ), 19 );
			add_action( 'wpseo_head', array( &$this, 'deactivate_tour_type_filter' ), 21 );
		}
	}

	public function activate_tour_type_filter() {
		$this->switch_post_type_filter( true );
	}

	public function deactivate_tour_type_filter() {
		$this->switch_post_type_filter( false );
	}

	protected function switch_post_type_filter( $enable ) {
		$callback = array( &$this, 'post_type_filter' );
		$priority = 20;
		if ( $enable ) {
			add_filter( 'post_type_archive_link', $callback, $priority, 2 );
		} else {
			remove_filter( 'post_type_archive_link', $callback, $priority, 2 );
		}
	}

	public function post_type_filter( $url, $post_type ) {
		if ( 'product' == $post_type && $this->tours_page_url ) {
			return $this->tours_page_url;
		}
		return $url;
	}

	public function wpseo_replacements_fix_plular_for_tours_archive( $replacements ){
		if ( isset( $replacements['%%pt_plural%%'] ) ) {
			$tours_page_id = adventure_tours_get_option( 'tours_page' );
			$replacements['%%pt_plural%%'] = $tours_page_id ? get_the_title( $tours_page_id ) : __( 'Tours', 'adventure_tours' );
		}
		return $replacements;
	}
}

