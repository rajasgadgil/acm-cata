<?php
/**
 * Badge Styles
 *
 * @var string $type              The badge type.
 * @var string $image_url         Image URL
 * @var string $txt_color         The text color
 * @var string $txt_color_default The text color default value
 * @var string $bg_color          The background color
 * @var string $bg_color_default  The background color default value
 * @var string $width             The width
 * @var string $height            The height
 * @var string $position          The badge position
 * @var int    $id_badge          The badge ID
 * @package YITH WooCommerce Badge Management
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

switch ( $type ) {
	case 'text':
	case 'custom':
		?>
		.yith-wcbm-badge-<?php echo absint( $id_badge ); ?>
		{
		color: <?php echo esc_html( $txt_color ); ?>;
		background-color: <?php echo esc_html( $bg_color ); ?>;
		width: <?php echo esc_html( $width ); ?>px;
		height: <?php echo esc_html( $height ); ?>px;
		line-height: <?php echo esc_html( $height ); ?>px;
		<?php echo esc_html( $position_css ); ?>
		}
		<?php
		break;

	case 'image':
		?>
		.yith-wcbm-badge-<?php echo absint( $id_badge ); ?>
		{
		<?php echo esc_html( $position_css ); ?>
		}
		<?php
		break;
}
