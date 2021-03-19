<?php
/*
Plugin Name: windria
Plugin URI: http://wordpress.org/plugins/windria
Description: Show your visitors wind/weather/waves/currents/rain etc. on a precise, beautiful, interactive map! To embed Windria, simply use this shortcode: [windria url="brighton-gb" width="800" height="500"]
Version: 1.0.1
Author: windria
Author URI: https://windria.net
License: GPLv3
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function windria_embed_shortcode( $atts, $content = null ) {
	$defaults = array(
    'url' => 'map', // using geolocation (default behavior of no url is provided)
		'width' => '800',
		'height' => '500',
    'frameborder' => '0'
	);

	foreach ( $defaults as $default => $value ) { // add defaults
		if ( ! @array_key_exists( $default, $atts ) ) { // mute warning with "@" when no params at all
			$atts[$default] = $value;
		}
	}

	$html .= '<iframe src="https://windria.net/'.$atts["url"].'" style="width:100%;border:0;margin-bottom:0;" height="'.$atts["height"].'" frameborder="'.$atts["frameborder"].'"></iframe><div style="width:100%;text-align:right;">';

	return $html;
}
add_shortcode( 'windria', 'windria_embed_shortcode' );


function windria_plugin_meta( $links, $file ) {
	if ( strpos( $file, 'iframe.php' ) !== false ) {
		$links = array_merge( $links, array( '<a href="https://www.windria.net/get_widget" title="Iframe Builder">Map Builder</a>' ) );
		$links = array_merge( $links, array( '<a href="https://www.windria.net/" title="Wind Map">Wind Map</a>' ) );
	}
	return $links;
}
add_filter( 'plugin_row_meta', 'windria_plugin_meta', 10, 2 );
