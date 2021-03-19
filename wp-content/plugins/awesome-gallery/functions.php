<?php
require(dirname(__FILE__) . "/presets.php");
add_action('asg_refresh_gallery', 'asg_refresh_gallery');


function asg_enqueue_styles(){
	wp_enqueue_style('awesome-gallery',
		ASG_URL . "assets/css/awesome-gallery.css", array(), ASG_VERSION);
}

function asg_fix_image_url($url){
	if (is_multisite() && !defined('SUBDOMAIN_INSTALL') || defined('SUBDOMAIN_INSTALL') && !SUBDOMAIN_INSTALL)
		return preg_replace("%^" . preg_quote(site_url()) . "%", '', $url);
	return $url;
}


function asg_get_gallery($post, $override = array()) {
	if (!is_object($post))
		$post = get_post($post);
	$data = get_post_meta($post->ID, '_asg_json', true);
	if ($data){
		$data = json_decode($data, true);
	} else
		$data = get_post_meta($post->ID, '_asg', true);
	if (!$data)
		$data = array();
	$data = asg_parse_args(asg_parse_args(asg_get_gallery_defaults(), $data), $override);
	return $data;
}

function awesome_gallery($id, $attributes = array()) {
	$gallery = new ASG_Gallery($id, $attributes);
	$gallery->ping();
	$gallery->render();
}

function asg_get_builtin_image($file, $option_name){
	if (false === ($image = get_option($option_name))){
		$id = asg_add_image($file);
		if (is_wp_error($id))
			return null;
		update_option($option_name, $id);
		return $id;
	}
	if (false !== wp_get_attachment_image_src($image))
		return $image;
	$id = asg_add_image($file);
	if (is_wp_error($id))
		return null;
	update_option($option_name, $id);
	return $id;
}


function asg_get_plus_image(){
	return asg_get_builtin_image(ASG_PATH . "assets/images/plus.png", 'asg_plus_image');
}

function asg_add_image($path){
	$upload_dir = wp_upload_dir();
	$file = wp_unique_filename($upload_dir['path'], basename($path));
	$file_path = $upload_dir['path'] . "/" . $file;
	$plus_contents = file_get_contents($path);
	file_put_contents($file_path, $plus_contents);
	$attachment = array(
		'post_mime_type' => 'image/png',
		'guid' => $upload_dir['url'] . "/" . $file,
		'post_title' => __('Plus', 'asg'),
		'post_content' => '',
	);
	$id = wp_insert_attachment($attachment, $file_path);

	if ( !is_wp_error($id) ) {
		wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $file_path ) );
	}
	return $id;
}


function asg_refresh_gallery($options = array()) {
	if (!isset($options['id']))
		return;
	$id = $options['id'];
	$gallery = new ASG_Gallery($id, asg_get_gallery($id));
	$gallery->get_images(true);
}

function asg_save_gallery($id, $gallery) {
	update_post_meta($id, '_asg', $gallery);
}

function asg_parse_args( $array1, $array2 ) {
	$merged = $array1;
	foreach ($array2 as $key => &$value) {

		if (is_array($value) && isset( $merged[ $key ] ) && is_array( $merged[ $key ] ) ) {
			$merged[$key] = asg_parse_args( $merged[ $key ], $value );
		} else {
			$merged[$key] = $value;
		}
	}
	return $merged;
}

function asg_get_gallery_defaults() {
	global $asg_source_editors;
	$keys = array_keys($asg_source_editors);
	$default_source = $asg_source_editors[$keys[0]];
	return array(
		'source' => $default_source->slug,
		'sources' => asg_get_sources_defaults(),
		'layout' => array(
			'mode' => 'horizontal-flow',
			'width' => 240,
			'height' => 190,
			'gap' => 5,
			'hanging' => 'show',
			'align' => 'center'
		),
		'filters' => array(
			'enabled' => false,
			'align' => 'center',
			'sort' => true,
			'style' => 'tabs',
			'all' => __('All', 'asg'),
			'color' => '#FFFFFF',
			'background_color' => '#222222',
			'accent_color' => '#FFFFFF',
			'accent_background_color' => '#444444',
			'border_radius' => 0,
			'list' => ''
		),
		'shadow' => array(
			'mode' => 'off',
			'color' => '#000000',
			'opacity' => 0.2,
			'radius' => 0,

			'x' => 0,
			'y' => 0
		),
		'overlay' => array(
			'mode' => 'on-hover',
			'color' => '#000',
			'opacity' => 0.3,
			'effect' => 'fade',
			'image' => ''
		),
		'image' => array(
			'blur' => 'off',
			'bw' => 'off'
		),
		'caption' => array(
			'mode' => 'on-hover',
			'color' => '#FFFFFF',
			'color2' => '#FFFFFF',
			'background_color' => '#000',
			'opacity' => 0.8,
			'effect' => 'fade',
			'align' => 'center',
			'position' => 'bottom',
			'font1' => array('family' => '', 'style' => '', 'size' => 14),
			'font2' => array('family' => '', 'style' => '', 'size' => 14)
		),
		'border' => array(
			'width' => 0,
			'color' => '#ddd',
			'radius' => 0,
			'width2' => 0,
			'color2' => '#eee'
		),
		'load_more' => array(
			'style' => 'load-more',
			'page_size' => 35,
			'loading_text' => __('Loading...', 'asg'),
			'load_more_text' => __('Load more', 'asg'),
			'all_images_loaded' => __('All images loaded', 'asg'),
			'width' => 'full',
			'color' => '#FFFFFF',
			'color_loaded' => '#CCCCCC',
			'background_color' => '#222',
			'background_color_loaded' => '#888888',
			'shadow_width' => 3,
			'shadow_color' => '#EEE',
			'shadow_color_loaded' => '#BBBBBB',
			'border_radius' => 0,
			'vertical_padding' => 12,
			'horizontal_padding' => 30
		),
		'custom_css' => array(
			'image' => '',
			'image_hover' => '',
			'caption' => '',
			'caption_hover' => '',
			'caption1' => '',
			'caption1_hover' => '',
			'caption2' => '',
			'caption2_hover' => '',
			'filters' => '',
			'filter' => '',
			'filter_hover' => '',
			'load_more_wrapper' => '',
			'load_more_button' => '',
			'load_more_button_hover' => ''
		),
		'caching' => array(
			'duration' => 600
		)

	);
}
function asg_get_custom_css_sections(){
	return  array(
		__('Image', 'asg') => array(
			'image' =>   array(
				'title' => __('Image custom CSS', 'asg'),
				'selector' => '.asg-image'
			),
			'image_hover' => array(
				'title' => __('On-hover custom CSS', 'asg'),
				'selector' => '.asg-image:hover'
			)
		),
		__('Image caption', 'asg') => array(
			'caption' => array(
				'title' => __('Whole caption custom CSS properties', 'asg'),
				'selector' => '.asg-image-caption-wrapper'
			),
			'caption_hover' => array(
				'title' => __('On-hover custom CSS properties', 'asg'),
				'selector' => '.asg-image-caption-wrapper:hover'
			),
			'caption1' => array(
				'title' => __('Caption line 1 custom CSS properties', 'asg'),
				'selector' => '.asg-image-caption1'
			),
			'caption1_hover' => array(
				'title' => __('Caption line 1 on-hover custom CSS properties', 'asg'),
				'selector' => '.asg-image:hover .asg-image-caption1'
			),
			'caption2' => array(
				'title' => __('Caption line 2 custom CSS properties', 'asg'),
				'selector' => '.asg-image .asg-image-caption2'
			),
			'caption2_hover' => array(
				'title' => __('Caption line 2 on-hover custom CSS properties', 'asg'),
				'selector' => '.asg-image:hover .asg-image-caption2'
			)
		),
		__('Filters', 'asg') => array(
			'filters' => array(
				'title' => __('Filters wrapper custom CSS properties', 'asg'),
				'selector' => '.asg-filters'
			),
			'filter' => array(
				'title' => __('Filter item custom CSS', 'asg'),
				'selector' => '.asg-filters .asg-filter a'
			),
			'filter_hover' => array(
				'title' => __('Filter item on-hover custom CSS properties', 'asg'),
				'selector' => '.asg-filters .asg-filter:hover a'
			)
		),
		__('Load More', 'asg') => array(
			'load_more_wrapper' => array(
				'title' => __('Wrapper custom CSS properties', 'asg'),
				'selector' => '.asg-bottom'
			),
			'load_more_button' => array(
				'title' => __('Button custom CSS properties', 'asg'),
				'selector' => '.asg-bottom .asg-load-more'
			),
			'load_more_button_hover' => array(
				'title' => __('Button on-hover custom CSS properties', 'asg'),
				'selector' => '.asg-bottom .asg-load-more:hover'
			)
		)

	);
}

function asg_get_sources_defaults() {
	global $asg_source_editors;
	$defaults = array();
	foreach ($asg_source_editors as $slug => $source) {
		$defaults[$slug] = $source->get_defaults();
	}
	return $defaults;
}


function asg_get_wp_image_src($image, $size = 'awesome-gallery') {
	$img = wp_get_attachment_image_src($image, $size);
	return $img[0];

}

function asg_enqueue_script($slug, $path, $deps = array(), $footer = false) {
	wp_enqueue_script($slug, ASG_URL . $path . '.js', $deps, ASG_VERSION, $footer);
}


function asg_enqueue_style($slug, $path, $deps = array()) {
	wp_enqueue_style("asg-" . $slug, ASG_URL . $path . ".css", $deps, ASG_VERSION);
}


function asg_remote_get($url, $args = array()) {
	return wp_remote_get($url, wp_parse_args($args, array('timeout' => 30, 'sslverify' => false)));
}

function asg_remote_post($url, $args = array()) {
	return wp_remote_post($url, wp_parse_args($args, array('timeout' => 30, 'sslverify' => false)));
}


function asg_hex2rgba($hex, $opacity = 1) {
	if (empty($hex))
		return 'transparent';
	$hex = preg_replace("/^#/", "", trim($hex));
	$color = array();
	if (strlen($hex) == 3) {
		$color['r'] = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
		$color['g'] = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
		$color['b'] = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
	} else if (strlen($hex) == 6) {
		$color['r'] = hexdec(substr($hex, 0, 2));
		$color['g'] = hexdec(substr($hex, 2, 2));
		$color['b'] = hexdec(substr($hex, 4, 2));
	}
	return "rgba(" . implode(', ', $color) . ", " . $opacity . ")";
}

function asg_color($hex, $opacity = 1.0) {
	$hex = strtolower($hex);
	if ((float)$opacity < 1.0)
		return asg_hex2rgba($hex, $opacity);
	if (preg_match("/^#(\d|[abcdef]){3,6}$/i", $hex))
		return $hex;
	if (preg_match("/^(\d|[abcdef]){3,6}$/i", $hex))
		return "#" . $hex;
	if ('transparent' == $hex)
		return $hex;
	return null;
}

function asg_http_get_cached($url, $options = array()){
	srand();
	$options = wp_parse_args($options, array(
		'timeout' => 30000
	));
	$transient_name = "http_" . md5($url);
	if ($response = get_transient($transient_name)) {
		return $response;
	}
	/*if (ASG_USE_FILE) {
		$response = file_get_contents($url);
		if (!$response) {
			return new WP_Error('Error connecting with file_get_contents');
		}
	} else {*/
		$response = asg_remote_get($url, $options);
		if (is_wp_error($response))
			return $response;
		if ($response['response']['code'] != 200 && $response['response']['code'] != 206)
			return new WP_Error($response['response']['code'], $response['body']);
		$response = $response['body'];
	/*} */
	set_transient($transient_name, $response, $options['timeout']);
	return $response;
}
