<?php
function asg_filter($term) {
	return array('text' => $term, 'slug' => sanitize_title($term));
}
function asg_sanitize_image($image){
	$image->tags = array_map('asg_filter', $image->tags);
	return $image;
}
function asg_sanitize_image_tags($images) {
	return array_map('asg_sanitize_image', $images);
}
class ASG_Gallery extends ASG_VisualElement {
	private $source;
	private $gallery;
	private $options;
	private $id;
	static $grid_count = 0;
	function __construct($id, $attr = array()) {
		$this->id = $id;
		$post = get_post($id);
		$this->slug = $post->post_name;
		$this->gallery = $this->options = asg_parse_args(asg_get_gallery($post), wp_parse_args($attr, array('id' => $this->id)));
		$this->source = $this->create_source();
	}


	function create_source(){
		global $asg_sources;
		$source_class = $asg_sources[$this->gallery['source']];
		return new $source_class($this->gallery);
	}
	function str_rand($length = 8){
		$alphabet='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
	}

	function parse_font_weight($style){
		if (in_array($style, array('', 'regular', 'italic')))
			return '400';
		if ($style == 'bold')
			return '700';
		if (preg_match('/^(\d{3,4})/', $style, $matches))
			return $matches[0];
		return '400';
	}

	function parse_font_style($style){
		if (in_array($style, array('', 'italic')))
			return $style;

		if (preg_match('/^(\d{3,4}.+)/', $style, $matches))
			return preg_replace('/^(\d{3,4})/', '', $style);
		return 'normal';
	}
	function get_font_families(){
		return array_unique(array_filter(array($this->gallery['caption']['font1']['family'],
			$this->gallery['caption']['font2']['family'])));
	}
	function render($preview = false) {
		global $wp;
		$gallery = $this->gallery;
		$classes = array();
		if ($this->gallery['image']['blur'] != 'off')
			$classes []= "asg-" . $this->gallery['image']['blur'] . "-blur";
		if ($this->gallery['image']['bw'] != 'off')
			$classes []= "asg-" . $this->gallery['image']['bw'] . "-bw";
		if ((isset($this->gallery['layout']['align']) && $this->gallery['layout']['align']))
			$classes []= 'asg-align-' . $this->gallery['layout']['align'];
		$font_families = $this->get_font_families();
		if ($font_families){
			echo '<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=' . urlencode(implode('|', $font_families)) . '"></link>';
		}
		$rand = ++ ASG_Gallery::$grid_count;
		require(ASG_PATH . "stylesheet.css.php");
		$wrapper_id = "asg-wrapper-" . $this->id . "-" . $rand;
		?>
		<?php if (current_user_can('manage_options') && !$preview && !get_option('asg_hide_buttons', false)): ?>
			<div class="awesome-gallery-edit-wrapper">
				<a href="<?php echo admin_url("post.php?post={$this->id}&action=edit") ?>"
				   class="edit-grid"><?php _e('Edit gallery', 'asg') ?></a>

				<a href="<?php echo admin_url('edit.php?post_type=awesome-gallery&page=support&url=http://' .
				$_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']) ?>"><?php _e('Ask for support',
						'asg') ?></a>
			</div>
		<?php endif ?>
		<div class="asg-wrapper" id="<?php echo $wrapper_id ?>"></div>
		<script>
			(function(){
			    var id = '<?php echo $wrapper_id; ?>';
			    var el = document.getElementById(id);
			    var options = <?php echo json_encode($this->get_settings($preview, $rand)); ?>;
			    function isVisible() {
			        return el.offsetWidth !== 0 || el.offsetHeight !== 0; }
			    function runWhen(condition, callback) {
			        function run() {
			            if (condition()) {
			                callback();
			            } else {
			                setTimeout(run, 500);
			            }
			        }
			        run()
			    }
			    function show() { window.awesomeGallery(el, options); }
			    function ready(callback) {
			        if (document.readyState == 'complete') {
			            setTimeout(callback, 0);
			        } else {
			            document.addEventListener("DOMContentLoaded", callback);
			        }
			    }
			    function loadScript(callback, url, id){
			        var script = document.getElementById(id);
			        if (!script) {
			            script = document.createElement('SCRIPT')
			            script.setAttribute('src', url);
			            script.id = id;
			            script.addEventListener('load', callback);
			            document.documentElement.appendChild(script)
			        } else {
			            script.addEventListener('load', callback);
			        }
			    }
			    function run() { runWhen(isVisible, show); }
			    ready(function(){
			        if (window.awesomeGallery) {
			            run()
			        } else {
			            loadScript(run, options.scriptUrl, 'awesome-gallery-script');
			        }
			    });
			})();
		</script>
	<?php
	}

	function ping() {
		$transient = "asg_ping_" . $this->id;
		if (!get_transient($transient)){
			set_transient($transient, true, 400);
			wp_schedule_single_event(time() + 200, 'asg_refresh_gallery', array('id' => $this->id));
		}
	}


	function get_settings($preview = false, $rand = 0) {
		$error = null;
		if ($binder = get_option('asg_scroll_binder'))
			$load_more['binder'] = $binder;
		$images = $this->get_images();
		return array(
			'id' => $this->id,
			'scriptUrl' => ASG_URL .
				'assets/js/awesome-gallery.js?ver=' . ASG_VERSION,
			'gallerySlug' => "{$this->id}-{$this->slug}",
			'link' => $this->source->source['link'],
			'rand' => $rand,
			'layout' => array(
				'mode' => $this->gallery['layout']['mode'],
				'width' => (int)$this->gallery['layout']['width'],
				'height' => (int)$this->gallery['layout']['height'],
				'gap' => (int)$this->gallery['layout']['gap'],
				'border' => (int)$this->gallery['border']['width'],
				'allowHanging' => $this->gallery['layout']['hanging'] == 'show',
			),
			'image' => $this->gallery['image'],
			'caption' => $this->gallery['caption'],
			'overlay' => $this->gallery['overlay'],
			'filters' => $this->get_filters($images),
			'loadMore' => $this->gallery['load_more'],
			'images' => asg_sanitize_image_tags($images),
		);
	}
	function _not_empty($item) {
		return !empty($item);
	}

	function get_filters($images) {
		return array_merge($this->gallery['filters'], array(
			'list' => array_map('asg_filter',
				$this->get_filters_from_images($images)),
			'active' => '_')
		);
	}
	function get_filters_from_images($images) {
		if (!empty($this->gallery['filters']['list'])) {
			$filters = preg_split('/\,\s*/', $this->gallery['filters']['list']);
		} else {
			$filters = array();
			foreach ($images as $image) {
				if (!empty($image->tags)) {
					$filters = array_merge($filters, $image->tags);
				}
			}
		}
		array_map('trim', $filters);
		$filters = array_filter($filters, array($this, '_not_empty'));
		$filters = array_unique($filters);
		if ($this->gallery['filters']['sort']) {
			sort($filters);
		}
		$result = array();
		foreach($filters as $filter) {
			$result []= $filter;
		}
		return $result;
	}

	function get_images($nocache = false) {
		$images = $this->source->get_images(array(
			'width' => $this->gallery['layout']['width'],
			'height' => $this->gallery['layout']['height'],
			'images' => isset($this->gallery['images']) ? $this->gallery['images'] : '',
			'limit' => $this->gallery['load_more']['style'] == 'off' ? $this->gallery['load_more']['page_size'] : null
		));
		if (is_wp_error($images))
			return $images;
		return $images;
	}
}
