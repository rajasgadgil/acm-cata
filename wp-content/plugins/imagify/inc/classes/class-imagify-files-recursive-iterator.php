<?php
defined( 'ABSPATH' ) || die( 'Cheatin\' uh?' );

/**
 * Class allowing to filter RecursiveDirectoryIterator, to return only files that Imagify can optimize.
 * It also allows to remove forbidden folders.
 *
 * @since  1.7
 * @author Grégory Viguier
 */
class Imagify_Files_Recursive_Iterator extends RecursiveFilterIterator {

	/**
	 * Class version.
	 *
	 * @var    string
	 * @since  1.7
	 * @author Grégory Viguier
	 */
	const VERSION = '1.0';

	/**
	 * Check whether the current element of the iterator is acceptable.
	 *
	 * @since  1.7
	 * @access public
	 * @author Grégory Viguier
	 *
	 * @return bool Returns whether the current element of the iterator is acceptable through this filter.
	 */
	public function accept() {
		static $extensions, $has_extension_method;

		// Forbidden file/folder paths and names.
		$file_path = $this->current()->getPathname();

		if ( Imagify_Files_Scan::is_path_forbidden( $file_path ) ) {
			return false;
		}

		// OK for folders.
		if ( $this->hasChildren() ) {
			return true;
		}

		// Only files.
		if ( ! $this->current()->isFile() ) {
			return false;
		}

		// Only files with the required extension.
		if ( ! isset( $extensions ) ) {
			$extensions = array_keys( imagify_get_mime_types() );
			$extensions = implode( '|', $extensions );
		}

		if ( ! isset( $has_extension_method ) ) {
			// This method was introduced in php 5.3.6.
			$has_extension_method = method_exists( $this->current(), 'getExtension' );
		}

		if ( $has_extension_method ) {
			$file_extension = strtolower( $this->current()->getExtension() );
		} else {
			$file_extension = strtolower( pathinfo( $file_path, PATHINFO_EXTENSION ) );
		}

		return preg_match( '@^' . $extensions . '$@', $file_extension );
	}
}
