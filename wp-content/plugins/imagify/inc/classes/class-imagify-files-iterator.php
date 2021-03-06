<?php
defined( 'ABSPATH' ) || die( 'Cheatin\' uh?' );

/**
 * Class allowing to filter DirectoryIterator, to return only files that Imagify can optimize and folders.
 * It also allows to remove forbidden folders.
 *
 * @since  1.7
 * @author Grégory Viguier
 */
class Imagify_Files_Iterator extends FilterIterator {

	/**
	 * Class version.
	 *
	 * @var    string
	 * @since  1.7
	 * @author Grégory Viguier
	 */
	const VERSION = '1.0';

	/**
	 * Tell if the iterator will return both folders and images, or only images.
	 *
	 * @var   bool
	 * @since 1.7
	 */
	protected $include_folders;

	/**
	 * Check whether the current element of the iterator is acceptable.
	 *
	 * @since  1.7
	 * @access public
	 * @author Grégory Viguier
	 *
	 * @param object $iterator        The iterator that is being filtered.
	 * @param bool   $include_folders True to return both folders and images. False to return only images.
	 */
	public function __construct( $iterator, $include_folders = true ) {
		parent::__construct( $iterator );
		$this->include_folders = (bool) $include_folders;
	}

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
		if ( $this->include_folders && $this->isDir() ) {
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
