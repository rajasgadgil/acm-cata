<?php
class ASG_Instagram_Source extends ASG_Http_Source {

	function __construct($options) {
		$this->slug = 'instagram';
		$this->name = 'Instagram';
		$this->sequential = true;
		parent::__construct($options);
	}

	function get_image_url($data, $options){
		$largest = $this->get_largest_resolution($data);
		return $largest['url'];
	}

	function fetch_image_size($item, $url, $options){
		$largest = $this->get_largest_resolution($item);
		return array('width' => $largest['width'], 'height' => $largest['height']);
	}

	function get_permalink($data, $options){
		return $data['link'];
	}

	function get_tags($data, $options){
		return $data['tags'];
	}


	function get_largest_resolution($image_data) {
		$width = 0;
		$largest = null;
		foreach ($image_data['images'] as $name => $data) {
			if ((int)$data['width'] > $width) {
				$largest = $data;
				$width = $data['width'];
			}
		}
		return $largest;
	}

	function get_caption($source, $image_data) {
		$source = $this->source[$source];
		switch ($source) {
			case 'none':
				return '';
			case 'login':
				return $image_data['caption']['from']['username'];
			case 'fullname':
				return $image_data['caption']['from']['full_name'];
			default:
				return $image_data['caption']['text'];
				break;
		}
	}

	function get_sig($endpoint, $params, $secret) {
		$sig = $endpoint;
	  ksort($params);
	  foreach ($params as $key => $val) {
	    $sig .= "|$key=$val";
	  }
	  return hash_hmac('sha256', $sig, $secret, false);
	}

	function fetch($endpoint, $param) {
		$prefix = 'https://api.instagram.com/v1';
		$param['access_token'] = $this->source['access_token'];
		$param['sig'] = $this->get_sig($endpoint, $param, $this->source['client_secret']);
		$url = add_query_arg($param, $prefix . $endpoint);
		return $this->http_get_cached($url);
	}

  function get_endpoint($options) {
		switch ($this->source['feed_type']){
			case 'my-feed':
				return '/users/self/media/recent';
			case 'liked':
				return '/users/self/media/liked';
			case 'hashtag':
				$tag = strtolower(preg_replace('/^#/', '', $this->source['hashtag']));
			  return  "/tags/" . $tag . "/media/recent";
			default:
				$user_id = $this->get_user_id();
				if (is_wp_error($user_id))
					return array($user_id, null);
				return "/users/{$user_id}/media/recent";
		}
  }

	function fetch_raw_images($page, $limit, $max_id = null, $options = null) {
    $endpoint = $this->get_endpoint($options);
		$param = array();
		if ($limit)
			$param['count'] = $limit;
		if ($max_id)
			$param['max_id'] = $max_id;
		$result = $this->fetch($endpoint, $param);
		if (is_wp_error($result))
			return array($result, null);
		$result = json_decode($result, true);
		return array($result['data'], isset($result['pagination']['next_max_id']) ? $result['pagination']['next_max_id'] : null);
	}

	function get_user_id() {
		$url = add_query_arg(urlencode_deep(array(
			'q' => strtolower($this->source['other_user_login']),
			'access_token' => $this->source['access_token']
		)), 'https://api.instagram.com/v1/users/search');
		$response = $this->http_get_cached($url);
		if (is_wp_error($response))
			return $response;
		$users = json_decode($response);
		foreach ($users->data as $user) {
			if ($user->username == $this->source['other_user_login']) {
				return $user->id;
			}
		}
		return new WP_Error(-1, 'User not found');
	}
}

global $asg_sources;
$asg_sources['instagram'] = 'ASG_Instagram_Source';
