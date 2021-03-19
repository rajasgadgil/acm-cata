<?php
class ASG_Settings{
	function __construct(){
		add_action('admin_menu', array($this, '_admin_menu'));
		add_action('admin_init', array($this, '_admin_init'));
		add_action('admin_enqueue_scripts', array($this, '_enqueue_scripts'));
	}

	function _admin_menu(){
		add_options_page(__('Awesome Gallery', 'asg'), __('Awesome Gallery', 'asg'), 'manage_options',
		'asg-options', array($this, 'build_option_pages'));
	}

	function _admin_init(){
		$settings = array(
			'asg_shortcode_hack' => 'intval',
			'asg_scroll_binder' => 'stripslashes',
			'asg_lightbox' => 'stripslashes',
			'asg_link_rel' => 'stripslashes',
			'asg_link_custom_attr_name' => 'stripslashes',
			'asg_link_custom_attr_value' => 'stripslashes',
			'asg_link_class' => 'stripslashes',
			'asg_disable_buttons' => 'intval'
		);
		foreach($settings as $setting => $filter)
			register_setting('asg-options', $setting, $filter);
	}

	function _enqueue_scripts(){
		global $hook_suffix;
		if ($hook_suffix != 'settings_page_asg-options')
			return;
		wp_enqueue_style('asg-settings',
			ASG_URL . "assets/admin/css/settings.css");
	}

	function build_option_pages(){
	?>
		<div class="wrap">
			<div class="icon32" id="icon-options-general"><br></div>
			<h2><?php _e('Awesome Gallery Settings', 'asg')?></h2>
			<form method="post" action="options.php" id="asg-settings">
				<?php settings_fields( 'asg-options' ) ?>
				<ul>
					<li class="clear-after"><h3><?php _e('General', 'asg') ?></h3></li>
					<li class="clear-after">
						<label class="asg-options-label">
							<?php _e('Hide buttons at frontend', 'asg') ?></label>
						<p class="inputs">
							<label class="checkbox-label">
								<input type="checkbox" name="asg_disable_buttons" value="1"
									<?php echo checked(get_option('asg_disable_buttons')) ?>>
							</label>
							<em><?php _e('This will disable the buttons appearing to the admins next to the gallery', 'asg') ?></em>
						</p>
					</li>
					<li class="clear-after">
						<h3><?php _e('Compatibility', 'asg')?></h3></li>
					<li class="clear-after">
						<label class="asg-options-label"><?php _e('Use shortcode hack', 'asg')?></label>
						<p class="inputs">
							<label class="checkbox-label">
								<input type="checkbox" name="asg_shortcode_hack" value="1"
									<?php echo checked(get_option('asg_shortcode_hack')) ?>>
							</label>
							<em>
								<?php _e('Try this if Awesome Gallery looks strange on your site', 'asg') ?>
							</em>
						</p>
					</li>
					<li class="clear-after">
						<h3><?php _e('Endless scroll', 'asg') ?></h3></li>
					<li class="clear-after">
						<label class="asg-options-label"><?php _e('Endless scroll binder selector', 'asg') ?></label>
						<p class="inputs">
							<input name="asg_scroll_binder" value="<?php echo esc_attr(get_option('asg_scroll_binder')) ?>" class="regular-text" type="text" placeholder="jQuery selector here">
							<em><?php _e('jQuery selector for your binder element if some scrolling plugin is used') ?></em>
						</p>
					</li>
				</ul>
				<?php submit_button(); ?>
			</form>
		</div>
<?php
	}
}
new ASG_Settings;
