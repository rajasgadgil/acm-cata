<?php

include('includes/wcp-mega-save.php');

/*
Plugin Name: Image Map Pro
Plugin URI: http://www.webcraftplugins.com/
Version: 3.0.20
Author: Nikolay Dyankov
Description: The most advanced image map builder for WordPress
*/

if (!class_exists('ImageMapPro')) {
	class ImageMapPro {
		function __construct() {
			$this->admin_options_name = 'image-map-pro-wordpress-admin-options';
			$this->default_settings = array(
				"saves" => array(),
				"last_save" => ""
			);
			$this->pagename = 'image-map-pro-wordpress';
			$this->new_pagename = 'new_image-map-pro-wordpress';
		}
		function get_admin_options() {
			$admin_options = array(
				"saves" => array(),
				"last_save" => ""
			);
			// update_option($this->admin_options_name, $admin_options);

			$loaded_options = get_option($this->admin_options_name);

			if (!empty($loaded_options)) {
				foreach ($loaded_options as $key => $option) {
					$admin_options[$key] = $option;
				}
			} else {
				$loaded_options = $this->default_settings;
			}

			update_option($this->admin_options_name, $admin_options);
			return $admin_options;
		}
		function init_pages() {
			add_menu_page("Image Map Pro", "Image Map Pro", "manage_options", $this->pagename, array($this, "print_options_page"));
		}

		function register_admin_includes() {
			wp_register_style('image-map-pro-wcp-tour-css', plugins_url('/css/wcp-tour.css', __FILE__), false, '3.0.20', false);
			wp_register_script('image-map-pro-wcp-tour-js', plugins_url('/js/wcp-tour.js', __FILE__), false, '3.0.20', true);

			wp_register_style('image-map-pro-wcp-editor-css', plugins_url('/css/wcp-editor.css', __FILE__), false, '3.0.20', false);
			wp_register_script('image-map-pro-wcp-editor-js', plugins_url('/js/wcp-editor.js', __FILE__), false, '3.0.20', true);
			wp_register_script('image-map-pro-wcp-compress-js', plugins_url('/js/wcp-compress.js', __FILE__), false, '3.0.20', true);
			wp_register_style('image-map-pro-wcp-editor-controls-css', plugins_url('/css/wcp-editor-controls.css', __FILE__), false, '3.0.20', false);
			wp_register_script('image-map-pro-wcp-editor-controls-js', plugins_url('/js/wcp-editor-controls.js', __FILE__), false, '3.0.20', true);
			wp_register_script('image-map-pro-wcp-wp-media-js', plugins_url('/js/wcp-wp-media.js', __FILE__), false, '3.0.20', true);

			wp_register_script('image-map-pro-squares-js', plugins_url('/js/squares.js', __FILE__), false, '3.0.20', true);
			wp_register_script('image-map-pro-squares-elements-js', plugins_url('/js/squares-elements-wp.js', __FILE__), false, '3.0.20', true);
			wp_register_script('image-map-pro-squares-controls-js', plugins_url('/js/squares-controls.js', __FILE__), false, '3.0.20', true);
			wp_register_script('image-map-pro-frontend-squares-js-renderer', plugins_url('/js/squares-renderer.js', __FILE__), array('jquery'), '3.0.20', true);
			wp_register_style('image-map-pro-squares-css', plugins_url('/css/squares.css', __FILE__), false, '3.0.20', false);
			wp_register_style('image-map-pro-squares-editor-css', plugins_url('/css/squares-editor.css', __FILE__), false, '3.0.20', false);
			wp_register_style('image-map-pro-squares-controls-css', plugins_url('/css/squares-controls.css', __FILE__), false, '3.0.20', false);

			wp_register_style('image-map-pro-wordpress-css', plugins_url('/css/image-map-pro.css', __FILE__), false, '3.0.20', false);
			wp_register_script('image-map-pro-wordpress-js', plugins_url('/js/image-map-pro.js', __FILE__), false, '3.0.20', true);

			wp_register_style('image-map-pro-wordpress-editor-css', plugins_url('/css/image-map-pro-editor.css', __FILE__), false, '3.0.20', false);
			wp_register_script('image-map-pro-wordpress-editor-js', plugins_url('/js/image-map-pro-editor.js', __FILE__), false, '3.0.20', true);
			wp_register_script('image-map-pro-wordpress-editor-init-js', plugins_url('/js/image-map-pro-editor-init-wp.js', __FILE__), false, '3.0.20', true);

			wp_register_script('image-map-pro-wordpress-editor-content-js', plugins_url('/js/image-map-pro-editor-content.js', __FILE__), false, '3.0.20', true);
			wp_register_script('image-map-pro-wordpress-editor-wp-storage-js', plugins_url('/js/image-map-pro-editor-wp-storage.js', __FILE__), false, '3.0.20', true);
			wp_register_script('image-map-pro-webcraft-icons-js', plugins_url('/js/webcraft-icons.js', __FILE__), false, '3.0.20', true);

			wp_register_style('image-map-pro-wordpress-admin-css', plugins_url('/css/admin.css', __FILE__), false, '3.0.20', false);
			wp_register_script('image-map-pro-wordpress-admin-js', plugins_url('/js/admin.js', __FILE__), false, '3.0.20', true);
		}
		function register_client_includes() {
			wp_register_style('image-map-pro-frontend-squares-css', plugins_url('/css/squares.css', __FILE__), false, '3.0.20', false);
			wp_register_style('image-map-pro-frontend-wordpress-css', plugins_url('/css/image-map-pro.css', __FILE__), false, '3.0.20', false);
			wp_register_script('image-map-pro-frontend-wordpress-js', plugins_url('/js/image-map-pro.js', __FILE__), array('jquery'), '3.0.20', true);
			wp_register_script('image-map-pro-frontend-squares-js-renderer', plugins_url('/js/squares-renderer.js', __FILE__), array('jquery'), '3.0.20', true);
			wp_register_script('image-map-pro-squares-elements-js', plugins_url('/js/squares-elements-wp.js', __FILE__), false, '3.0.20', true);
		}
		function enqueue_admin_includes() {
			wp_enqueue_script('jquery');
			wp_enqueue_media();

			wp_enqueue_style('image-map-pro-wcp-tour-css');
			wp_enqueue_script('image-map-pro-wcp-tour-js');

			wp_enqueue_style('image-map-pro-wcp-editor-css');
			wp_enqueue_script('image-map-pro-wcp-editor-js');
			wp_enqueue_script('image-map-pro-wcp-compress-js');
			wp_enqueue_style('image-map-pro-wcp-editor-controls-css');
			wp_enqueue_script('image-map-pro-wcp-editor-controls-js');
			wp_enqueue_script('image-map-pro-wcp-wp-media-js');

			wp_enqueue_script('image-map-pro-squares-js');
			wp_enqueue_script('image-map-pro-squares-controls-js');
			wp_enqueue_script('image-map-pro-frontend-squares-js-renderer');
			wp_enqueue_script('image-map-pro-squares-elements-js');
			wp_enqueue_style('image-map-pro-squares-css');
			wp_enqueue_style('image-map-pro-squares-editor-css');
			wp_enqueue_style('image-map-pro-squares-controls-css');

			wp_enqueue_style('image-map-pro-wordpress-css');
			wp_enqueue_script('image-map-pro-wordpress-js');

			wp_enqueue_style('image-map-pro-wordpress-editor-css');
			wp_enqueue_script('image-map-pro-wordpress-editor-js');
			wp_enqueue_script('image-map-pro-wordpress-editor-init-js');

			wp_enqueue_script('image-map-pro-wordpress-editor-content-js');
			wp_enqueue_script('image-map-pro-wordpress-editor-wp-storage-js');
			wp_enqueue_script('image-map-pro-webcraft-icons-js');

			wp_enqueue_style('image-map-pro-wordpress-admin-css');
			wp_enqueue_script('image-map-pro-wordpress-admin-js');
		}
		function enqueue_client_includes() {
			wp_enqueue_script('jquery');
			wp_enqueue_style('image-map-pro-frontend-squares-css');
			wp_enqueue_style('image-map-pro-frontend-wordpress-css');
			wp_enqueue_script('image-map-pro-frontend-wordpress-js');
			wp_enqueue_script('image-map-pro-frontend-squares-js-renderer');
			wp_enqueue_script('image-map-pro-squares-elements-js');
		}
		function shortcodes() {
			$saves = $this->mega_saver->get_all_saves();

			foreach ($saves as $save) {
				if (isset($save['meta']['shortcode']) && strlen($save['meta']['shortcode']) > 0) {
					add_shortcode($save['meta']['shortcode'], array($this, 'print_shortcode'));
				}
			}
		}
		function print_shortcode($a, $b, $shortcode) {
			$saves = $this->mega_saver->get_all_saves();
			$result = false;

			foreach ($saves as $id => $save) {
				if (isset($save['meta']['shortcode']) && $save['meta']['shortcode'] == $shortcode) {
					$result = '<div id="image-map-pro-'. $id .'"></div>';
				}
			}

			if ($result) {
				add_action('wp_footer', array($this, 'call_plugin'));
			}

			return $result;
		}
		function call_plugin() {
			$this->enqueue_client_includes();

			$saves = $this->mega_saver->get_all_saves();

			foreach ($saves as $id => $save_master) {
				?>
				<script>
				;(function ($, window, document, undefined ) {
					$(document).ready(function() {
						<?php

						$print = '';
						$save = implode('', $save_master['fragments']);

						$save = str_replace("\n", "<br>", $save); // Replace new line characters with <br>
						// $save = str_replace('\\"', '"', $save); // top-level JSON
						// $save = str_replace('\\\\"', '\"', $save); // HTML inside top level JSON
						// $save = str_replace('\\\\\\\\\\"', '\\\\\"', $save); // HTML inside second level JSON

						// $save = str_replace("\\n", "<br>", $save); // Replace new line characters with <br>
						// $save = str_replace('\\\\\\\\\\\"', '\\\\"', $save); // Replace \" with "
						// $save = str_replace('\\\\"', '\\"', $save); // Replace \" with "
						// $save = str_replace('\\"', '"', $save); // Replace \" with "
						// $save = str_replace("\\'", "'", $save); // Replace \' with '

						// $save = utf8_decode($save);

						// Shortcode
						$save = do_shortcode($save);
						$save = str_replace("\n", "<br>", $save); // Replace new line characters with <br>

						echo 'var settings = JSON.parse("' . $save . '");' . "\n";
						echo "$('#image-map-pro-". $id . "').imageMapPro(settings);";

						?>
					});
				})(jQuery, window, document);
				</script>
				<?php
			}
		}
		function print_options_page() {
			$options = $this->get_admin_options();
			$this->enqueue_admin_includes();
			?>
			<div id="instance-options-wrap">
				<div id="wcp-editor"></div>
			</div>
			<?php
		}

		// Temporary code, will be removed in a future version
		function migrate_saves() {
			$options = $this->get_admin_options();

			// If saves are already migrated, return
			if (isset($options['migrated-saves-to-WCPMega-Save'])) return;

			$saves = $options['saves'];

			for ($i=0; $i<count($saves); $i++) {
				$meta = array( "name" => $saves[$i]['general']['name'], "shortcode" => $saves[$i]['general']['shortcode'] );
				$this->mega_saver->store_save_bulk($saves[$i]['id'], $meta, $saves[$i]);
			}

			// Migrate last save
			if (isset($options['last_save'])) {
				$options['last_save_id'] = $options['last_save']['id'];
			}

			$options['migrated-saves-to-WCPMega-Save'] = true;
			update_option($this->admin_options_name, $options);
		}

		// AJAX
		function ajax_get_saves_list() {
			$saves_list = $this->mega_saver->get_saves_list();

			die(json_encode($saves_list));
		}
		function ajax_get_number_of_fragments_for_save() {
			$n = $this->mega_saver->get_number_of_fragments_for_save($_POST['saveID']);
			echo $n;
			die();
		}
		function ajax_get_save_fragment() {
			$fragment = $this->mega_saver->get_save_fragment($_POST['saveID'], $_POST['index']);
			$result = array(
				"fragment" => $fragment,
				"index" => $_POST['index']
			);
			die(json_encode($result));
		}
		function ajax_get_max_fragment_size() {
			$n = $this->mega_saver->get_max_fragment_size();
			echo $n;
			die();
		}
		function ajax_store_save_fragment() {
			$this->mega_saver->store_save_fragment($_POST['saveID'], $_POST['index'], $_POST['fragment'], $_POST['done']);
			die();
		}
		function ajax_store_save_complete() {
			$this->mega_saver->store_save_complete($_POST['saveID'], $_POST['fragmentsLength']);
			die();
		}
		function ajax_store_save_meta() {
			$this->mega_saver->store_save_meta($_POST['saveID'], $_POST['meta']);
			die();
		}
		function ajax_clear_fragments_for_save() {
			$this->mega_saver->clear_fragments_for_save($_POST['saveID']);
			die();
		}
		function ajax_delete_save() {
			$this->mega_saver->delete_save($_POST['saveID']);
			$options = $this->get_admin_options();

			if ($_POST['saveID'] == $options['last_save_id']) {
				unset($options['last_save_id']);
				update_option($this->admin_options_name, $options);
			}

			die();
		}
		function ajax_get_last_save() {
			$options = $this->get_admin_options();

			if (isset($options['last_save_id'])) {
				echo $options['last_save_id'];
			}

			die();
		}
		function ajax_set_last_save() {
			$options = $this->get_admin_options();

			$options['last_save_id'] = $_POST['saveID'];
			update_option($this->admin_options_name, $options);

			die();
		}
	}
}

if (class_exists('WCPMegaSave')) {
	$wcpMegaSave = new WCPMegaSave('image-map-pro-fragmented-saves');
}

if (class_exists('ImageMapPro')) {
	$instance = new ImageMapPro();
	$instance->mega_saver = $wcpMegaSave;
	$instance->shortcodes();

	// Temporary code, will be removed in a future version
	// Migrates all pre-3.0 saves to WCPMegaSave
	$instance->migrate_saves();
}

add_action('admin_menu', array($instance, 'init_pages'));

add_action('admin_enqueue_scripts', array($instance, 'register_admin_includes'));
add_action('wp_enqueue_scripts', array($instance, 'register_client_includes'));

// Reworked
add_action('wp_ajax_image_map_pro_get_saves_list', array($instance, 'ajax_get_saves_list'));
add_action('wp_ajax_image_map_pro_get_number_of_fragments_for_save', array($instance, 'ajax_get_number_of_fragments_for_save'));
add_action('wp_ajax_image_map_pro_get_save_fragment', array($instance, 'ajax_get_save_fragment'));
add_action('wp_ajax_image_map_pro_get_max_fragment_size', array($instance, 'ajax_get_max_fragment_size'));
add_action('wp_ajax_image_map_pro_clear_fragments_for_save', array($instance, 'ajax_clear_fragments_for_save'));
add_action('wp_ajax_image_map_pro_store_save_fragment', array($instance, 'ajax_store_save_fragment'));
add_action('wp_ajax_image_map_pro_store_save_complete', array($instance, 'ajax_store_save_complete'));
add_action('wp_ajax_image_map_pro_store_save_meta', array($instance, 'ajax_store_save_meta'));
add_action('wp_ajax_image_map_pro_delete_save', array($instance, 'ajax_delete_save'));
add_action('wp_ajax_image_map_pro_get_last_save', array($instance, 'ajax_get_last_save'));
add_action('wp_ajax_image_map_pro_set_last_save', array($instance, 'ajax_set_last_save'));
