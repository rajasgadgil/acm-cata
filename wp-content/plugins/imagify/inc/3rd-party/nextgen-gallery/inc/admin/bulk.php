<?php
defined( 'ABSPATH' ) || die( 'Cheatin\' uh?' );

add_filter( 'imagify_bulk_page_data', 'imagify_ngg_bulk_page_data' );
/**
 * Filter the data to use on the bulk optimization page.
 *
 * @since  1.7
 * @author Grégory Viguier
 *
 * @param  array $data The data to use.
 * @return array
 */
function imagify_ngg_bulk_page_data( $data ) {
	if ( empty( $_GET['page'] ) || imagify_get_ngg_bulk_screen_slug() !== $_GET['page'] ) { // WPCS: CSRF ok.
		return $data;
	}

	add_filter( 'imagify_get_folder_type_data'          , 'imagify_ngg_get_folder_type_data', 10, 2 );
	add_filter( 'imagify_count_attachments'             , 'imagify_ngg_count_attachments' );
	add_filter( 'imagify_count_optimized_attachments'   , 'imagify_ngg_count_optimized_attachments' );
	add_filter( 'imagify_count_error_attachments'       , 'imagify_ngg_count_error_attachments' );
	add_filter( 'imagify_count_unoptimized_attachments' , 'imagify_ngg_count_unoptimized_attachments' );
	add_filter( 'imagify_percent_optimized_attachments' , 'imagify_ngg_percent_optimized_attachments' );
	add_filter( 'imagify_count_saving_data'             , 'imagify_ngg_count_saving_data', 8 );

	$total_saving_data = imagify_count_saving_data();

	// Global chart.
	$data['total_attachments']             += imagify_ngg_count_attachments();
	$data['unoptimized_attachments']       += imagify_ngg_count_unoptimized_attachments();
	$data['optimized_attachments']         += imagify_ngg_count_optimized_attachments();
	$data['errors_attachments']            += imagify_ngg_count_error_attachments();
	// Stats block.
	$data['already_optimized_attachments'] += $total_saving_data['count'];
	$data['original_size']                 += $total_saving_data['original_size'];
	$data['optimized_size']                += $total_saving_data['optimized_size'];
	// Limits.
	$data['unoptimized_attachment_limit']  += imagify_get_unoptimized_attachment_limit();
	// Group.
	$data['groups']['NGG'] = array(
		/**
		 * The group_id corresponds to the file names like 'part-bulk-optimization-results-row-{$group_id}'.
		 * It is also used in get_imagify_localize_script_translations() and imagify_get_folder_type_data().
		 */
		'group_id' => 'library',
		'context'  => 'NGG',
		'title'    => __( 'NextGen Galleries', 'imagify' ),
		/* translators: 1 is the opening of a link, 2 is the closing of this link. */
		'footer'   => sprintf( __( 'You can also re-optimize your images more finely directly in each %1$sgallery%2$s.', 'imagify' ), '<a href="' . esc_url( admin_url( 'admin.php?page=nggallery-manage-gallery' ) ) . '">', '</a>' ),
	);

	return $data;
}

/**
 * Provide custom folder type data.
 *
 * @since  1.7
 * @author Grégory Viguier
 *
 * @param  array  $data        An array with keys corresponding to cell classes, and values formatted with HTML.
 * @param  string $folder_type A folder type.
 * @return array
 */
function imagify_ngg_get_folder_type_data( $data, $folder_type ) {
	if ( 'NGG' !== $folder_type ) {
		return $data;
	}

	// Already filtered in imagify_ngg_bulk_page_data().
	$total_saving_data = imagify_count_saving_data();

	return array(
		'images-optimized' => imagify_ngg_count_optimized_attachments(),
		'errors'           => imagify_ngg_count_error_attachments(),
		'optimized'        => $total_saving_data['optimized_size'],
		'original'         => $total_saving_data['original_size'],
		'errors_url'       => admin_url( 'admin.php?page=nggallery-manage-gallery' ),
	);
}
