<?php defined( 'ABSPATH' ) || die( 'This script cannot be accessed directly.' );

global $gm_supported_module;

if ( ! function_exists( 'groovy_menu_support_elementor_post_types' ) ) {

	/**
	 * Add Elementor post types for Groovy Menu.
	 *
	 * @param $post_types array Post types list.
	 *
	 * @return array
	 */
	function groovy_menu_support_elementor_post_types( $post_types ) {

		if ( defined( 'ELEMENTOR_VERSION' ) && is_array( $post_types ) && ! in_array( 'elementor_library', $post_types, true ) ) {
			$post_types[] = 'elementor_library';
		}

		return $post_types;
	}
}

add_filter( 'groovy_menu_single_post_add_meta_box_post_types', 'groovy_menu_support_elementor_post_types', 10, 1 );
