<?php
/**
 * Badge Content
 *
 * @package YITH WooCommerce Badge Management
 * @var string $position  The badge position.
 * @var string $image_url The badge image URL.
 * @var string $type      The badge type.
 * @var int    $id_badge  The id of the badge.
 */

defined( 'YITH_WCBM' ) || exit; // Exit if accessed directly.

$position_css = '';
if ( 'top-left' === $position ) {
	$position_css = 'top: 0; left: 0;';
} elseif ( 'top-right' === $position ) {
	$position_css = 'top: 0; right: 0;';
} elseif ( 'bottom-left' === $position ) {
	$position_css = 'bottom: 0; left: 0;';
} elseif ( 'bottom-right' === $position ) {
	$position_css = 'bottom: 0; right: 0;';
}

// WPML integration.
$text = yith_wcbm_wpml_string_translate( 'yith-woocommerce-badges-management', sanitize_title( $text ), $text );

if ( 'text' !== $type ) {
	// Image Badge.
	$image_url = YITH_WCBM_ASSETS_URL . '/images/' . $image_url;
	$text      = '<img src="' . $image_url . '" />';
}
?>
<div class='yith-wcbm-badge yith-wcbm-badge-custom yith-wcbm-badge-<?php echo absint( $id_badge ); ?>'><?php echo wp_kses_post( $text ); ?></div><!--yith-wcbm-badge-->
