<?php

/*
    The purpose of this class is to handle ajax requests for saving large amounts
    of data in the WP database, ignoring the character limit of a POST request.
*/

if (!class_exists('WCPMegaSave')) {
	class WCPMegaSave {
        function __construct($admin_options_name) {
			$this->fragment_size = 4096;
			$this->admin_options_name = $admin_options_name;
			$this->options_defaults = array(
				"saves" => array(),
			);
			$this->save_defaults = array(
				"id" => array(),
				"meta" => array(),
				"fragments" => array()
			);
			// $this->wipe();
			// $options = $this->get_admin_options();
			// print_r($options);
			// die();
        }

		// Utility
		function wipe() {
			error_log('wipe');
			update_option($this->admin_options_name, $this->options_defaults);
		}
		function get_admin_options() {
			$options = get_option($this->admin_options_name);

			if (!$options) {
				$options = $this->options_defaults;
				update_option($this->admin_options_name, $options);
			}

			return $options;
		}
		function get_max_fragment_size() {
			return $this->fragment_size;
		}

		// Get
		function get_saves_list() {
			$options = $this->get_admin_options();
			$list = array();

			foreach ($options['saves'] as $id => $save) {
				$list_item = array(
					"id" => $id,
					"meta" => $save['meta'],
					"fragments" => count($save['fragments'])
				);

				array_push($list, $list_item);
			}

			return $list;
		}
        function get_number_of_fragments_for_save($save_id) {
			$options = $this->get_admin_options();
			return count($options['saves'][$save_id]['fragments']);
        }
        function get_save_fragment($save_id, $fragment_index) {
			$options = $this->get_admin_options();
			return $options['saves'][$save_id]['fragments'][$fragment_index];
        }
		function get_save($save_id) {
			$options = $this->get_admin_options();
			return $options['saves'][$save_id];
		}
		function get_all_saves() {
			$options = $this->get_admin_options();
			return $options['saves'];
		}

		// Store
		function store_save_bulk($id, $meta, $data) {
			$options = $this->get_admin_options();

			$save = $this->save_defaults;

			$save['id'] = $id;
			$save['meta'] = $meta;

			$encodedData = json_encode($data);

			// Sanitize
			$encodedData = str_replace("\n", "<br>", $encodedData); // Replace new line characters with <br>
			$encodedData = str_replace("\\n", "<br>", $encodedData); // Replace new line characters with <br>
			$encodedData = str_replace("\\'", "'", $encodedData); // Replace \' with '

			$fragments = str_split($encodedData, $this->fragment_size);

			for ($i=0; $i<count($fragments); $i++) {
				$save['fragments'][$i] = $fragments[$i];
			}

			$options['saves'][$id] = $save;

			update_option($this->admin_options_name, $options);
		}
		function store_save_meta($save_id, $meta_data) {
			$options = $this->get_admin_options();
			$options['saves'][$save_id]['meta_tmp'] = $meta_data;
			update_option($this->admin_options_name, $options);
		}
        function store_save_fragment($save_id, $fragment_index, $fragment_data, $done) {
			// Save temporarily
			update_option($save_id . '-' . $fragment_index, $fragment_data);
        }
		function store_save_complete($save_id, $fragments_length) {
			// Reconstruct the array of fragments
			$fragments = array();

			for ($i=0; $i<$fragments_length; $i++) {
				$data = get_option($save_id . '-' . $i);
				array_push($fragments, $data);
				delete_option($save_id . '-' . $i);
			}

			// Save the new array
			$options = $this->get_admin_options();
			$options['saves'][$save_id]['fragments'] = $fragments;

			// Save the meta permanently
			$options['saves'][$save_id]['meta'] = $options['saves'][$save_id]['meta_tmp'];

			update_option($this->admin_options_name, $options);
		}

		// Other
		function delete_save($save_id) {
			$options = $this->get_admin_options();
			unset($options['saves'][$save_id]);
			update_option($this->admin_options_name, $options);
		}
		function clear_fragments_for_save($save_id) {
			$options = $this->get_admin_options();
			$options['saves'][$save_id]['fragments'] = array();
			update_option($this->admin_options_name, $options);
		}
    }
}
