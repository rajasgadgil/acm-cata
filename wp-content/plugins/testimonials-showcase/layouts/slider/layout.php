<?php
/*

Layout Name: Slider
Description: Slider layout using bxslider
Author: Carlos Moreira

*/


if ( !class_exists( 'cm_tt_slider' ) ):
class cm_tt_slider {

	//make it public so it can be accessed by cmshowcase constructor
	public $layout_id = 'slider'; //should be same name as folder
	public $layout_name = 'Slider';
	public $settings;
	public $options;
	public $enqueue_files;
	public $shortcode_check; // js function to run for preview to work properly
	public $footer_content; //to add custom css
	public $custom_css;
	public $custom_js;

	function __construct($id = ''){

		$this->showcase_id = $id;
		$this->footer_content = '';

		//custom css
		//we define it here, so we empty it after first time it's used
		$advanced_section = $this->showcase_id.'_advanced_settings';
		$this->custom_css = cmshowcase_get_option( 'custom_css', $advanced_section,  '' );
		$this->custom_js = cmshowcase_get_option( 'custom_js', $advanced_section,  '' );

		//check if the advanced rich snippets options need to display. Otherwise we will unset the array options further down
		$rich_snippets_section = $this->showcase_id.'_rich_snippets';
		$adv_rich_snippets = cmshowcase_get_boolean(cmshowcase_get_option( 'adv_rich_snippets', $rich_snippets_section,  'off' ));


		// js function to run when preview is triggered
		$this->shortcode_check = 'cm_tt_slider_shortcode_check';

		//In case we want to add options to the settings page
		
		$settings = array(
						'section_id' => $this->layout_id,
						'section_title' => __('Slider Layout','ttshowcase'),
						'section_description' => __('These options apply only to the Slider layout. Some options are specific to some layout themes. Read the description to understand what the field information will be used on.','ttshowcase'),
						'section_order' => 7,
						'fields' => array(
							'text-info-html' => array(
								'label' => '<h4>'.__('General Settings','ttshowcase').'</h4>',
								'description' => '',
								'type' => 'html',
								),
							'display_empty' => array(
								'label' => __('Display Empty Entries','ttshowcase'),
								'description' => __('If enabled, entries with empty testimonial text, but with other data will also display','ttshowcase'),
								'type' => 'checkbox',
								'default' => 'on'
								),
							'default_italic' => array(
								'label' => __('Italic by Default','ttshowcase'),
								'description' => __('Default all text of Testimonial to <i>Italic</i>','ttshowcase'),
								'type' => 'checkbox',
								'default' => 'off'
								),

							'read_more_label' => array(
								'label' => __('Read More Label','ttshowcase'),
								'description' => __('Text to display in the Read More link to single page of entry.','ttshowcase'),
								'type' => 'text',
								'size' => 'medium',
								'default' => __('Read More...','ttshowcase')
								),
							'use_default_image' => array(
								'label' => __('Use default image','ttshowcase'),
								'description' => __('Display default image (set below) when there is no image present','ttshowcase'),
								'type' => 'checkbox',
								'default' => 'on',
								'value' => 'on'
								),

							'default_image' => array(
								'label' => __('Default Image','ttshowcase'),
								'description' => __('Image to display when there is not featured image set (If option is active). <br>You can use the default:','ttshowcase').plugins_url( '/imgs/default.png' , __FILE__),
								'type' => 'image',
								'default' => plugins_url( '/imgs/default.png' , __FILE__)
								),

							'category_prefix' => array(
								'label' => __('Category/Group Prefix','ttshowcase'),
								'description' => __('Text to display before the category on the layout, if chosen to be displayed. Tip: If category description includes a URL, the category will link to that URL.','ttshowcase'),
								'type' => 'text',
								'size' => 'medium',
								'default' => __('Posted on:','ttshowcase')
								),
							
							'empty_message' => array(
								'label' => __('Empty Shortcode Content','ttshowcase'),
								'description' => __('Content to display when there are no entries to display on a given shortcode.','ttshowcase'),
								'type' => 'textarea',
								'size' => 'medium',
								'default' => __('<!-- Empty TShowcase Container -->','ttshowcase')
								),

							'speech-info-html' => array(
								'label' => '<h4>'.__('Speech Bubble Themes Color Options','ttshowcase').'</h4>',
								'description' => '',
								'type' => 'html',
								),

							'main-color' => array(
								'label' => __('Speech Bubble Background Color','ttshowcase'),
								'description' => __('Main color for the text box','ttshowcase'),
								'type' => 'color',
								'default' => '#f5f5f5'
								),
							'text-color' => array(
								'label' => __('Speech Bubble Text Color','ttshowcase'),
								'description' => __('Color of the text inside the speech bubble','ttshowcase'),
								'type' => 'color',
								'default' => '#333333'
								),

							'box-info-html' => array(
								'label' => '<h4>'.__('Card Box Theme Color Options','ttshowcase').'</h4>',
								'description' => '',
								'type' => 'html',
								),
							'box-color' => array(
								'label' => __('Main Background Color','ttshowcase'),
								'description' => __('Main color for the text box','ttshowcase'),
								'type' => 'color',
								'default' => '#f5f5f5'
								),
							'box-text-color' => array(
								'label' => __('Main Text Color','ttshowcase'),
								'description' => __('Color of the text inside the box','ttshowcase'),
								'type' => 'color',
								'default' => '#333333'
								),

							'box-sec-color' => array(
								'label' => __('Author Background Color','ttshowcase'),
								'description' => __('Will only be used in Flat Speech Bubbles Layout','ttshowcase'),
								'type' => 'color',
								'default' => '#2c3e50'
								),
							'box-sec-text-color' => array(
								'label' => __('Author Text Color','ttshowcase'),
								'description' => __('Color of the text inside the box - to be used only for Flat Speech Bubbles Layout','ttshowcase'),
								'type' => 'color',
								'default' => '#FFFFFF'
								),

							'arrows-html' => array(
								'label' => '<h4>'.__('Slider Controls Options','ttshowcase').'</h4>',
								'description' => '',
								'type' => 'html',
								),

							'next-arrow-code' => array(
								'label' => __('Next Arrow Code','ttshowcase'),
								'description' => __('Code to display for Next Arrow.<br> By Default we use a <a href="http://fontawesome.io/icons/#directional" target="_blank">Font Awesome Icon</a>.<br>Suggestions:<br><xmp style="margin:0px;"><i class="fa fa-caret-right fa-2x" ></i></xmp><xmp style="margin:0px;"><i class="fa fa-chevron-circle-right fa-2x" ></i></xmp>','ttshowcase'),
								'type' => 'text',
								'default' => '<i class="fa fa-caret-right fa-2x" ></i>'
								),

							'prev-arrow-code' => array(
								'label' => __('Previous Arrow Code','ttshowcase'),
								'description' => __('Code to display for Previous Arrow.<br> By Default we use a <a href="http://fontawesome.io/icons/#directional" target="_blank">Font Awesome Icon</a>.<br>Suggestions:<br><xmp style="margin:0px;"><i class="fa fa-caret-left fa-2x" ></i></xmp><xmp style="margin:0px;"><i class="fa fa-chevron-circle-left fa-2x" ></i></xmp>','ttshowcase'),
								'type' => 'text',
								'default' => '<i class="fa fa-caret-left fa-2x" ></i>'
								),
							
						)
					);

		$this->settings = $settings;

		
		$section = 'ttshowcase_slider';
	
		//Options for the Generator
		$options = array(

			'sep01' => array(
					'type' => 'seperator'
			),

			'slider_info' => array(
				'label' => __('Slider Settings','ttshowcase'),
				'description' => '',
				'type' => 'html_bold'
				),


			'sep02' => array(
					'label' => '',
					'type' => 'seperator'
			),

			'transition' => array(
					'label' => __('Transition','ttshowcase'),
					'description' => __('Slider Transition Effect','ttshowcase'),
					'type' => 'select',
					'default' => 'fade',
					'options' => array(
						'fade' => __('Fade','ttshowcase'),
						'horizontal' => __('Horizontal Slide','ttshowcase'),
						'vertical' => __('Vertical Slide','ttshowcase'),
						)
			),
			'adaptive' => array(
					'label' => __('Adaptive Height','ttshowcase'),
					'description' => __('Fixed height or adaptive. When fixed, the slider will have the height of the longest text entry. Setting will be ignored in "vertical" transition mode.','ttshowcase'),
					'type' => 'select',
					'default' => 'false',
					'options' => array(
						'false' => __('Fixed','ttshowcase'),
						'true' => __('Adaptive','ttshowcase')
						)
			),

			/*'pager' => array(
					'label' => __('Pager','ttshowcase'),
					'description' => __('Display Pager','ttshowcase'),
					'type' => 'checkbox',
					'default' => 'on',
					),*/

			'controls' => array(
					'label' => __('Controls','ttshowcase'),
					'description' => __('What kind of controls to display','ttshowcase'),
					'type' => 'select',
					'default' => 'pager',
					'options' => array(
						'pager' => __('Pager Circles','ttshowcase'),
						'controls' => __('Next / Previous Arrows below', 'ttshowcase'),
						'sides' => __('Next / Previous Arrows on sides', 'ttshowcase'),
						'autocontrols' => __('Play/Pause Controls','ttshowcase'),
						'none' => __('None','ttshowcase')
						)
					),

			'controls-next' => array(
					'type' => 'hidden_html',
					'default' => '<i class="fa fa-caret-right fa-2x" ></i>',
					'value' => cmshowcase_get_option( 'next-arrow-code', $section, '<i class="fa fa-caret-right fa-2x" ></i>' )
					),

			'controls-prev' => array(
					'type' => 'hidden_html',
					'default' => '<i class="fa fa-caret-left fa-2x" ></i>',
					'value' => cmshowcase_get_option( 'prev-arrow-code', $section, '<i class="fa fa-caret-left fa-2x" ></i>' )
					),

			'pause' => array(
					'label' => __('Pause Time','ttshowcase'),
					'description' => __('Pause time between slides (in ms)','ttshowcase'),
					'type' => 'text',
					'size' => 'small',
					'default' => '4000'
			),

			'auto' => array(
					'label' => __('Auto Start','ttshowcase'),
					'description' => __('If inactive the sliders will only change if user clicks the controls','ttshowcase'),
					'type' => 'checkbox',
					'default' => 'on',
					'value' => 'on'
			),

			'columns' => array(
					'label' => __('Entries','ttshowcase'),
					'description' => __('Number of entries to display in each slide','ttshowcase'),
					'type' => 'select',
					'default' => '1',
					'options' => array(
						'1' => __('1 Entry','ttshowcase'),
						'2' => __('2 Entry','ttshowcase'),
						'3' => __('3 Entries','ttshowcase'),
						'4' => __('4 Entries','ttshowcase'),
						/*'5' => __('5 Columns','ttshowcase'),
						'6' => __('6 Columns','ttshowcase')*/
						)
			),


			'seperator' => array(
					'type' => 'seperator'
			),

			'visual-seperator' => array(
					'label' => __('Visual Options','ttshowcase'),
					'description' => '',
					'type' => 'html_bold'
			),

			'seperator_00' => array(
					'type' => 'seperator'
			),


			'theme' => array(
					'label' => __('Theme','ttshowcase'),
					'description' => __('Aspect of the grid elements','ttshowcase'),
					'type' => 'select',
					'default' => 'speech',
					'options' => array(
						'speech' => __('Rounded Speech Bubbles','ttshowcase'),
						'flat' => __('Flat Speech Bubbles','ttshowcase'),
						'card' => __('Flat Card Box','ttshowcase'),
						'quotes' => __('Quote Signs','ttshowcase'),
						'separator' => __('Simple Separator','ttshowcase'),
						'none' => __('None','ttshowcase'),

						)
			),

			


			'info-position' => array(
					'label' => __('Composition','ttshowcase'),
					'description' => __('Position of the elements','ttshowcase'),
					'type' => 'select',
					'default' => 'info-left',
					'options' => array(
						'info-below' => __('Author Below, Text Above','ttshowcase'),
						'info-above' => __('Author Above, Text Below','ttshowcase'),
						'info-left' => __('Author Left, Text Right','ttshowcase'),
						'info-right' => __('Author Right, Text Left','ttshowcase')
						)
			),
			'text-alignment' => array(
					'label' => __('Alignment','ttshowcase'),
					'description' => __('General Alignment of Elements','ttshowcase'),
					'type' => 'select',
					'options' => array(
						'left' => 'Left',
						'right' => 'Right',
						'center' => 'Center'
						)
			),

			'what_seperator' => array(
					'type' => 'seperator'
			),

			'what_info' => array(
				'label' => __('What do display','ttshowcase'),
				'description' => '',
				'type' => 'html_bold'
				),


			'what_seperator_0' => array(
					'label' => '',
					'type' => 'seperator'
			),

			'review_title' => array(
				'label' => __('Short Testimonial Title','ttshowcase'),
				'description' => __('Display Short Testimonial/Review title','ttshowcase'),
				'type' => 'checkbox',
				'default' => 'off',
				'value' => 'on'
				),

			'rating' => array(
				'label' => __('Star Rating','ttshowcase'),
				'description' => __('Display Star Rating below the author info','ttshowcase'),
				'type' => 'checkbox',
				'default' => 'off',
				'value' => 'on'
				),

			'taxonomy' => array(
				'label' => __('Display Category','ttshowcase'),
				'description' => __('Display Category of entry','ttshowcase'),
				'type' => 'checkbox',
				'default' => 'off',
				'value' => 'on'
				),

			'date' => array(
				'label' => __('Display Date','ttshowcase'),
				'description' => __('Display Date of entry','ttshowcase'),
				'type' => 'checkbox',
				'default' => 'off',
				'value' => 'on'
				),

			'quote-content' => array(
				'label' => __('Text Content','ttshowcase'),
				'description' => __('What should display in the quote content.','ttshowcase'),
				'type' => 'select',
					'default' => 'short',
					'options' => array(
						'short' => __('Short Testimonial','ttshowcase'),
						'full' => __('Extended Content','ttshowcase'),
						'both' => __('Both','ttshowcase')
						)
				),

			'charlimit' => array(
					'label' => __('Char. limit','ttshowcase'),
					'description' => __('Number of characters from the testimonial text to display. Leave blank to display all. Usefull to make slides have the same size, when text has different lenghts. Shortcodes will be disabeld.','ttshowcase'),
					'type' => 'text',
					'size' => 'small'

			),

			'charlimitextra' => array(
					'label' => __('Cutout text','ttshowcase'),
					'description' => __('Text string to display if Char Limit is being used and text has been cut off.','ttshowcase'),
					'type' => 'text',
					'size' => 'medium',
					'default' => ' (...)'

			), 


			'read-more' => array(
				'label' => __('Read More','ttshowcase'),
				'description' => __('Default URL will be to single page of entry. Custom URL option must be set below. Subtitle URL will be the same as the entry Sub title url field.','ttshowcase'),
				'type' => 'select',
				'default' => 'off',
				'options' => array(
					'off' => __('Disabled','ttshowcase'),
					'on' => __('Default URL','ttshowcase'),
					'on_cutout' => __('Default URL (cutout text)','ttshowcase'),
					'haslong' => __('Default URL (if extended content)'),
					'custom_url' => __('Custom URL','ttshowcase'),
					'subtitle_url' => __('Subtitle URL field','ttshowcase') 
					)
				),

			'custom-read-more-url' => array(
				'label' => __('Custom Read More URL','ttshowcase'),
				'description' => __('Place URL here if Custom URL option is chosen above. If left blank, default single entry URL will be used','ttshowcase'),
				'type' => 'text',
				'size' => 'medium',
				'default' => ''
				),


			'seperator_000' => array(
					'type' => 'seperator'
			),

			'image_info' => array(
				'label' => __('Image Settings','ttshowcase'),
				'description' => '',
				'type' => 'html_bold'
				),


			'seperator_0' => array(
					'label' => '',
					'type' => 'seperator'
			),

			'display-image' => array(
					'label' => __('Display Image','ttshowcase'),
					'description' => __('If disabeled, the settings below will be ignored','ttshowcase'),
					'type' => 'checkbox',
					'default' => 'on',
					'value' => 'on'
			),


			'image-size' => array(
					'label' => __('Image Size','ttshowcase'),
					'description' => __('You can change the custom sizes in the Settings page','ttshowcase'),
					'type' => 'select',
					'options' => array(
						'ttshowcase_small' => __('Custom Small Size','ttshowcase'),
						'ttshowcase_normal' => __('Custom Medium Size','ttshowcase'),
						'thumbnail' => __('Default Thumbnail Size','ttshowcase')
						)
			),
			'image-shape' => array(
					'label' => __('Image Shape','ttshowcase'),
					'description' => __('Image shape effect','ttshowcase'),
					'type' => 'select',
					'default' => 'circle',
					'options' => array(
						'square' => __('Squared','ttshowcase'),
						'round' => __('Rounded Corners','ttshowcase'),
						'circle' => __('Circular','ttshowcase')
						)
			),

			'image-effect' => array(
					'label' => __('Image Effect','ttshowcase'),
					'description' => __('image shape description','ttshowcase'),
					'type' => 'select',
					'options' => array(
						'none' => 'None',
						'transparency' => __('Transparency','ttshowcase'),
						'grayscale' => __('Grayscale','ttshowcase'),
						'white-border' => __('White Border','ttshowcase'),
						'shadow-highlight' => __('Shadow Highlight','ttshowcase')
						)
			),


			'image-link' => array(
					'label' => __('Image Link','ttshowcase'),
					'description' => __('If active, the image will follow the options chosen in the Advanced URL settings for each entry','ttshowcase'),
					'type' => 'checkbox',
					'default' => 'on',
					'value' => 'on'
			),

			'image-size-override' => array(
					'label' => __('Image Size Override','ttshowcase'),
					'description' => __('Place new image dimensions in this format: widthXheight. For example: 100x100. It will ignore the image size option and try to redimension the image to this size.','ttshowcase'),
					'type' => 'text',
					'size' => 'small'
			),

			'seperator_2' => array(
					'label' => '',
					'type' => 'seperator'
			),

			'product_info' => array(
				'label' => __('Structured Data','ttshowcase'),
				'description' => 'Fields below will override defaults. If left blank, default values will be used.',
				'type' => 'html_bold'
				),


			'seperator_3' => array(
					'label' => '',
					'type' => 'seperator'
			),

			'richsnippets' => array(
					'label' => __('Include Structured Data','ttshowcase'),
					'description' => __('Keep the default settings or override them for this specific shortcode','ttshowcase'),
					'type' => 'select',
					'default' => '',
					'options' => array(
						'' => __('Default Settings','ttshowcase'),
						'false' => __('Do not include structured data','ttshowcase'),
						'true' => __('Include structured data','ttshowcase')
						)
			),

			'schema' => array(
				'label' => __('Schema to use','ttshowcase'),
				'description' => __('If you wish to use another type of schema, insert the URL here.','ttshowcase'),
				'type' => 'text',
				'default' => '',
				'size' => 'medium'
				),



			'product' => array(
				'label' => __('Name','ttshowcase'),
				'description' => __('Name of "thing" being reviewed','ttshowcase'),
				'type' => 'text',
				'default' => '',
				'size' => 'medium'
				),

			'url' => array(
				'label' => __('Website','ttshowcase'),
				'description' => __('Url to official Website','ttshowcase'),
				'type' => 'text',
				'default' => '',
				'size' => 'medium'
				),

			'image_url' => array(
				'label' => __('Image URL','ttshowcase'),
				'description' => __('The URL of the product photo.','ttshowcase'),
				'type' => 'text',
				'default' => '',
				'size' => 'medium'
				),

			'description' => array(
				'label' => __('Product Description','ttshowcase'),
				'description' => __('Custom Product Description for Rich Snippets.','ttshowcase'),
				'type' => 'text',
				'default' => '',
				'size' => 'medium'
				),

			

			'sameas' => array(
				'label' => __('Same As URL(s)','ttshowcase'),
				'description' => __('URL of a reference Web page that unambiguously indicates the item\'s identity. E.g. the URL of the item\'s Wikipedia page, Wikidata entry, or official website. You can use multiple URLs separated by a comma.','ttshowcase'),
				'type' => 'text',
				'default' => '',
				'size' => 'medium'
			),	

			'seperator_4' => array(
					'label' => '',
					'type' => 'seperator'
			),

			'business_meta' => array(
				'label' => __('Business Only data','ttshowcase'),
				'description' => '',
				'type' => 'html_bold'
				),


			'seperator_5' => array(
					'label' => '',
					'type' => 'seperator'
			),

			'business_address' => array(
				'label' => __('Address','ttshowcase'),
				'description' => __('Physical address of the item.','ttshowcase'),
				'type' => 'text',
				'default' => '',
				'size' => 'medium'
			),
			'business_telephone' => array(
				'label' => __('Telephone','ttshowcase'),
				'description' => __('Telephone number','ttshowcase'),
				'type' => 'text',
				'default' => '',
				'size' => 'medium'
			),

			'business_pricerange' => array(
				'label' => __('Price Range','ttshowcase'),
				'description' => __('The price range of the business, for example $$$.','ttshowcase'),
				'type' => 'text',
				'default' => '',
				'size' => 'medium'
			),
			'business_email' => array(
				'label' => __('Email','ttshowcase'),
				'description' => __('Email address.','ttshowcase'),
				'type' => 'text',
				'default' => '',
				'size' => 'medium'
			),
			'business_logo' => array(
				'label' => __('Logo URL','ttshowcase'),
				'description' => __('An associated logo.','ttshowcase'),
				'type' => 'text',
				'default' => '',
				'size' => 'medium'
			),


			

			'seperator_6' => array(
					'label' => '',
					'type' => 'seperator'
			),

			'product_offer' => array(
				'label' => __('Product Only - Offer Metadata','ttshowcase'),
				'description' => '',
				'type' => 'html_bold'
				),


			'seperator_7' => array(
					'label' => '',
					'type' => 'seperator'
			),

			'price' => array(
				'label' => __('Price','ttshowcase'),
				'description' => __('Number or text with the price for the product being reviewed.','ttshowcase'),
				'type' => 'text',
				'default' => '',
				'size' => 'medium'
				),
			'price_currency' => array(
				'label' => __('Currency','ttshowcase'),
				'description' => __('The currency (in 3-letter ISO 4217 format) of the price','ttshowcase'),
				'type' => 'text',
				'default' => '',
				'size' => 'medium'
				),
			'price_valid' => array(
				'label' => __('Date','ttshowcase'),
				'description' => __('The date (in ISO 8601 date format) after which the price will no longer be available.','ttshowcase'),
				'type' => 'text',
				'default' => '',
				'size' => 'medium'
				),
			'availability' => array(
					'label' => __('Availability','ttshowcase'),
					'description' => __('','ttshowcase'),
					'type' => 'select',
					'default' => '',
					'options' => array(
						'' => __('Empty (Default)','ttshowcase'),
						'inStock' => __('In Stock','ttshowcase'),
						'OutOfStock' => __('Out of Stock','ttshowcase'),
						'SoldOut'=> __('Sold Out','ttshowcase'),
						'PreOrder'=> __('Pre-order','ttshowcase'),
						'OnlineOnly'=> __('Online Only','ttshowcase'),
						'LimitedAvailability'=> __('Limited Availability','ttshowcase'),
						'InStoreOnly'=> __('In Store Only','ttshowcase'),
						'Discontinued'=> __('Discontinued','ttshowcase'),
						)
			),
				



		);

		
		//we unset the advanced rich snippets option, if the option is disabled in the settings
		if(!$adv_rich_snippets) {

				unset($options['availability']);
				unset($options['price_valid']);
				unset($options['price_currency']);
				unset($options['price']);
				unset($options['seperator_5']);
				unset($options['seperator_4']);
				unset($options['seperator_6']);
				unset($options['seperator_7']);
				unset($options['product_offer']);
				unset($options['image_url']);
				unset($options['description']);
				unset($options['countmeta']);
				unset($options['schema']);
				unset($options['product']);
				unset($options['url']);
				unset($options['description']);
				unset($options['business_meta']);
				unset($options['product']);
				unset($options['business_pricerange']);
				unset($options['business_email']);
				unset($options['business_logo']);
				unset($options['sameas']);
				unset($options['business_address']);
				unset($options['product_offer']);
				unset($options['business_telephone']);


		}



		$this->options = $options; 

		//Files to enqueue on the generator and when building the layout

		$enqueue = array(
			
			'css' => array(
				'tt-normal-layout-style' => array(
					'file' => '/layouts/grid/styles.css'
					),
				'tt-global-styles' => array(
					'file' => '/resources/global.css'
					),
				'tt-colorbox-shortcode' => array(
					'file' => '/resources/colorbox/colorbox.css'
					),
				'tt-bxslider' => array(
					'file' => '/layouts/slider/bxslider.css'
					),
				'tt-font-awesome' => array(
					'file' => '/resources/font-awesome/css/font-awesome.min.css'
					),
				),
			'javascript' => array(
				'tt-colorbox-script' => array(
					'file' => '/resources/colorbox/jquery.colorbox.js',
					'dependencies' => array('jquery'),
					),
				'tt-colorbox-shortcode' => array(
					'file' => '/layouts/grid/js/shortcode.js',
					'dependencies' => array('jquery','tt-colorbox-script')
					),
				'tt-bxslider' => array(
					'file' => '/resources/bxslider/jquery.bxslider.js',
					'dependencies' => array('jquery')
					),
				'tt-bxslider-build' => array(
					'file' => '/layouts/slider/js/slider-shortcode.js',
					'dependencies' => array('tt-bxslider')
					)
				)
			);
		
		$this->enqueue_files = $enqueue;

		

		
	}
	

	public function build_layout( $query = array() , $options = array(), $preview = false ) {

		//If Query is empty we simply return a invisible html comment


		if(!$query->have_posts()) {

			$section = $this->showcase_id.'_'.$this->layout_id; // = ttshowcase_grid

			$empty_message = cmshowcase_get_option( 'empty_message', $section, __('<!-- Empty TShowcase Container -->','ttshowcase') );

			return $empty_message;

		}

		//We start by enqueuing the necessary files
		cmshowcase_enqueue_layout_scripts($this->enqueue_files);

		//Global, in case colorbox is used
		global $tt_colorbox_params;
		//Global slider paramaters 
		//%%SLIDER SPECIFIC
		global $tt_slider_params;

		//using counter to set the wrapper div
		global $tt_showcase_counter;
		$wrap = '#'.$this->showcase_id.'_'.$tt_showcase_counter;

		//variable to check if we need to unregister colorbox or not
		$colorbox = false;

		//Custom CSS Global
		$custom_css = $this->custom_css;
		$custom_js = $this->custom_js;

		//Variables from the ADVANCED SETTINGS PAGE
			$advanced_section = $this->showcase_id.'_advanced_settings';

			$gravatar_on = cmshowcase_get_boolean(cmshowcase_get_option( 'use_gravatar', $advanced_section,  'on' ));
			$shortcodes_on = cmshowcase_get_option( 'render_shortcodes', $advanced_section,  'on' );
			$smiles_on = cmshowcase_get_option( 'render_smiles', $advanced_section,  'off' );
			$custom_lightbox = cmshowcase_get_option( 'custom_lightbox', $advanced_section,  '' );
			$custom_lightbox_rel = cmshowcase_get_option( 'custom_lightbox_rel', $advanced_section,  '' );
			$quote_elements = cmshowcase_get_option( 'quote_elements', $advanced_section, '{review_title}{quote}{read_more}' ); 
			$info_elements = cmshowcase_get_option( 'info_elements', $advanced_section, '{author}{subtitle}{category}{stars}{date}' ); 
			$aspectratio = cmshowcase_get_boolean(cmshowcase_get_option( 'force_aspect_ratio', $advanced_section,  'off' ));


		//Variables from the GRID LAYOUT OPTIONS PAGE
			$section = $this->showcase_id.'_'.$this->layout_id; // = ttshowcase_grid
			$category_prefix = cmshowcase_get_option( 'category_prefix', $section, __('Posted on: ','ttshowcase') );
			$read_more_label = cmshowcase_get_option( 'read_more_label', $section, __('Read More...','ttshowcase') );
			$main_color = cmshowcase_get_option( 'main-color', $section, '#f5f5f5' );
			$text_color = cmshowcase_get_option( 'text-color', $section, '#333333' );
			$card_main_color = cmshowcase_get_option( 'box-color', $section, '#f5f5f5' );
			$card_text_color = cmshowcase_get_option( 'box-text-color', $section, '#333333' );
			$card_sec_color = cmshowcase_get_option( 'box-sec-color', $section, '#2c3e50' );
			$card_sec_text_color = cmshowcase_get_option( 'box-sec-text-color', $section, '#FFFFFF' );
			$italic = cmshowcase_get_boolean(cmshowcase_get_option( 'default_italic', $section, 'off' ));
			$use_default_image = cmshowcase_get_boolean(cmshowcase_get_option( 'use_default_image', $section, 'on' ));
			$default_img = cmshowcase_get_option( 'default_image', $section, plugins_url( '/imgs/default.png' , __FILE__) );
			$display_empty = cmshowcase_get_boolean(cmshowcase_get_option( 'display_empty', $section,  'on' ));
			//%%SLIDER SPECIFIC
			$next_arrow = cmshowcase_get_option( 'next-arrow-code', $section, '<i class="fa fa-caret-right fa-2x" ></i>' );
			$prev_arrow = cmshowcase_get_option( 'prev-arrow-code', $section, '<i class="fa fa-caret-left fa-2x" ></i>' );
			

		//Variables from the BASIC SETTINGS PAGE.
			$main_section = $this->showcase_id.'_basic_settings';

			//we build a sizes array for the images
			$s = array();
			$s[$this->showcase_id.'_small']['width'] = cmshowcase_get_option( 'thumb-small-width', $main_section, '55' );
			$s[$this->showcase_id.'_small']['height'] = cmshowcase_get_option( 'thumb-small-height', $main_section, '55' );
			$s[$this->showcase_id.'_normal']['width'] = cmshowcase_get_option( 'thumb-normal-width', $main_section, '200' );
			$s[$this->showcase_id.'_normal']['height'] = cmshowcase_get_option( 'thumb-normal-height', $main_section, '200' );

			//get date format
			$date_format = cmshowcase_get_option( 'date_format', $main_section, '' );
			
			if($date_format == 'custom'){
				$date_format = cmshowcase_get_option( 'custom_date_format', $main_section, '' );
			}

			$cpt_title = cmshowcase_get_option( 'singular', $main_section, 'Testimonial' );
			$empty_stars = cmshowcase_get_boolean(cmshowcase_get_option( 'empty_stars', $main_section, 'off' ));

		//Variables from the SHORTCODE
			$display_image = isset($options['display-image']) ? cmshowcase_get_boolean($options['display-image']) : false;
			$display_date = isset($options['date']) ? cmshowcase_get_boolean($options['date']) : false;
			$theme = isset($options['theme']) ? $options['theme'] : '';	
			$rating_on = isset($options['rating']) ? cmshowcase_get_boolean($options['rating']) : false;
			$read_more_on = isset($options['read-more']) && $options['read-more'] != 'off' ? $options['read-more'] : false;
			$custom_read_more = isset($options['custom-read-more-url']) ? $options['custom-read-more-url'] : false;
			$char_limit = isset($options['charlimit']) ? intval($options['charlimit']) : 0;
			$char_limit_extra = isset($options['charlimitextra']) ? $options['charlimitextra'] : '(...)';
			$quote_content = isset($options['quote-content']) ? $options['quote-content'] : 'short';


			if(isset($options['custom-read-more-label'])) {
				$read_more_label = $options['custom-read-more-label'];
			}



		//Other Dynamic Variables

			$theme_class = isset($options['theme']) ? 'tt_theme_'.$options['theme'] : '';
			$prefix = $this->showcase_id;

		
		//Rich Snippets Variables 

			//get option defined in settings
			$snippet_on = cmshowcase_get_boolean(cmshowcase_get_option('shortcode_active','ttshowcase_rich_snippets','off'));
			//shortcode override
			$snippet_on = isset($options['richsnippets']) ? cmshowcase_get_boolean($options['richsnippets']) : $snippet_on;



			//schema
			$schema = cmshowcase_get_option('schema','ttshowcase_rich_snippets','http://schema.org/Product');
			//schema override
			$schema = isset($options['schema']) && $options['schema'] != '' ? cmshowcase_add_http($options['schema']) : $schema;


			$rating_highest = 0;
			$rating_total = 0;
			$review_count = 0;
			$rating_count = 0;

			

		//Start Layout Output

		//We start by adding the wrapper classes
		//%%SLIDER SPECIFIC WRAPPER CLASS
		$wrapperclass = 'ttshowcase_slider';
		if(isset($options['controls']) && $options['controls'] == 'sides') {
			$wrapperclass.= ' ttshowcase_hascontrols';
		}
		$html = '<div class="'.$wrapperclass.'" style="display:none">';
		

		//%%SLIDER SPECIFIC entries count
		$entries_count = 0;


		

		//%%Slider div after rich snippets wrap div 
		$html .= '<div class="ttshowcase_wrap '. $theme_class.'">';

		$reviewsdata = array();
		

        if ( $query->have_posts() ) {

			while ( $query->have_posts() ) {

				
				$query->the_post();
				$post_id = get_the_ID();

				$cat = '';
				$terms = get_the_terms( $post_id , 'ttshowcase_groups' );
			      if(is_array($terms)) {
			        foreach ( $terms as $term ) {
			        $cat .= 'tts-'.$term->slug.' '.'tts-id-'.$term->term_id.' ';
			        }
			      } 

				//Get the Content

				$quote = get_post_meta( $post_id, '_aditional_info_short_testimonial', true );
				$original_quote = $quote;

				if($quote_content == 'both') {
					$quote = '<div>'.$quote.'</div>'.get_the_content();
				}

				if($quote_content == 'full') {
					$quote = get_the_content();
				}

				//check if empty entries should display
				if(!$display_empty) {

					//check if lenght of testimonial is bigger than 0

					if(strlen(trim(strip_tags($quote))) == 0) {
						//if empty skips this entry
						continue;

					}

				}

				//if everything is ok, proceed
					
					$author = get_the_title();
					


				//we get the categories in case we need to display it

					$taxcategory = '';

					if(isset($options['taxonomy']) && cmshowcase_get_boolean($options['taxonomy'])) {
					
						$taxonomy = 'ttshowcase_groups';
						$terms = wp_get_post_terms( $post_id, $taxonomy, array("fields" => "all") );
						$termprefix = $category_prefix;
						
						foreach ($terms as $term) {

							$termshow = $term->name;

							//this should check if it's an url but should also consider relative urls
							if($term->description!='') {
								$termshow = '<a href="'.$term->description.'">'.$term->name.'</a>';
							}
							//if description is empty, but it's numeric, we link to that page
							if($term->description=='' && is_numeric($term->slug)) {

								$idpage = intval($term->slug);
								$termshow = '<a href="'.get_permalink( $idpage ).'">'.$term->name.'</a>';
							}

							$taxcategory .= $termprefix.'<div class="tt_category_title">'.$termshow.'</div>';
						}
					
					}

						//check if there is a character limit
						if($char_limit > 0) {
							$quote = strip_shortcodes($quote);
							$quote = cmshowcase_truncate($quote, $char_limit, $char_limit_extra, $exact = true, $considerHtml = true);
						}

					//check if review title is on
					$review_title = get_post_meta( $post_id, '_aditional_info_review_title', true ); 
					$original_title = $review_title;

					if(isset($options['review_title']) && cmshowcase_get_boolean($options['review_title'])) {
						$review_title = '<div class="tt_review_title">'.$review_title.'</div>';
					} else {
						$review_title = '';
					}

					$entry_date = get_the_date($date_format);
					if($date_format == 'human') {
						$ago = ' '.__('ago','ttshowcase');
						$entry_date = human_time_diff( get_the_date('U') ).$ago;
					} 
					$sub_title = get_post_meta( $post_id, '_aditional_info_name', true );
					$sub_title_url = cmshowcase_add_http(get_post_meta( $post_id, '_aditional_info_url', true ));
					$sub_title_target = cmshowcase_get_url_target(get_post_meta( $post_id, '_aditional_info_target', true ));
					$email = get_post_meta( $post_id, '_aditional_info_email', true ); 
					//if advanced image url is set:
					$custom_url = get_post_meta( $post_id, '_advanced_info_custom_url', true ); 
					$rating = get_post_meta( $post_id, '_aditional_info_rating', true ); 

					$quote_before = '';
					$quote_after = '';


					//process content options


						$read_more = '';
						if($read_more_on != false) {

							$read_more_url = get_permalink();
							//$read_more_url = $sub_title_url;

							//if($read_more_on == 'custom_url' && $custom_read_more != false){
							if($custom_read_more != false){
								$read_more_url = cmshowcase_add_http($custom_read_more);
								//add id hastag to custom read more url 
								//$read_more_url .= '#tt'.$post_id;
							}

							if($read_more_on == 'subtitle_url' && $sub_title_url != '') {
								$read_more_url = $sub_title_url;
							}

							



							//$read_more = "<div class='ttshowcase_rl_readmore'> <a href='".$read_more_url."'>".__('Read More...','ttshowcase')."</a> </div>";
							$read_more = "<div class='ttshowcase_rl_readmore'> <a href='".$read_more_url."'>".$read_more_label."</a> </div>";


							//show read more only when text was cuttout
							if($read_more_on == 'on_cutout') {

								if($char_limit > 0) {	

									if (!strpos($quote,$char_limit_extra) !== false) {
									   $read_more = '';
									}
								
								}

							}
							//show read more only when long testimonial exists
							if($read_more_on == 'haslong') {

								$long = get_the_content();
								if ($long=='') {
								   $read_more = '';
								}

							}

						}

						//add wpautop
						$quote = wpautop($quote);

						//use smiles
						if($smiles_on=='on'){
							$quote = convert_smilies($quote);
						}

						//render shortcodes
						if($shortcodes_on == 'on') {
							$quote = do_shortcode($quote);
						}

						//we check if it's quotes theme
						if($theme=="quotes") {
							$quote_before = '<i class="fa fa-quote-left fa-2x pull-left tt_quote_transparency"></i>';
						}
			
					//Image Process
				
						//we set the default size array, in case it's not a default thumb size.
						//this is needed to set the correct width in the images when they don't have the proper size
						$dsize = array();

						if(isset($options['image-size-override']) && $options['image-size-override'] != '' && is_array(explode('x', $options['image-size-override']))) {
							$dsize = explode('x', $options['image-size-override']);		
						}

						else {

							if( !in_array( $options['image-size'], array( 'thumbnail', 'medium', 'large' ) ) ) {

								$dsize = array($s[$options['image-size']]['width'],$s[$options['image-size']]['height']);
							
							}
						}
						
						$image = cmshowcase_get_featured_img( $post_id, $options['image-size'], $email, $use_default_image, $default_img, $dsize, $gravatar_on, $aspectratio);

						//image alt text
						if($image['alt']!='') {
							$alt = $image['alt'];
						} else {
							//$alt = get_the_title();
							$alt = '';
						}

						$height = ($image['height']!='') ? ' height="'.$image['height'].'"' : '';

						$image_html = '<img src="'.$image['src'].'" width="'.$image['width'].'" '.$height.' alt="'.$alt.'" class="ttshowcase_image" />';

						if(isset($options['image-link'])) {

							$img_url_type = get_post_meta( $post_id, '_advanced_info_target', true );

							if($img_url_type=='read_more') {

								$image_html = "<a href='".get_permalink()."'>".$image_html."</a>";

							}

							if($img_url_type=='sub_title') {

								$image_html ='<a href="'.$sub_title_url.'" '.$sub_title_target.'>'.$image_html.'</a>';
							}

							if($img_url_type == 'custom_iframe') {
								$image_html ='<a href="'.$custom_url.'" class="tt_colorbox_iframe tt_colorbox_'.$post_id.'">'.$image_html.'</a>';
								
								$this_tt_params = array(
									'id' => 'tt_colorbox_'.$post_id,
									'wrap_id' => $wrap,
									'type' => 'iframe'
									);

								
								array_push($tt_colorbox_params, $this_tt_params);
								$this->ttshowcase_add_colorbox_js ($tt_colorbox_params);
								$colorbox = true;

							}

							if($img_url_type == 'content') {
								$image_html ='<a href="#tt_content_'.$post_id.'" class="tt_colorbox_inline tt_colorbox_'.$post_id.'">'.$image_html.'</a>';
								$image_html .='<div style="display:none"><div id="tt_content_'.$post_id.'">';
								$image_html .= nl2br(get_the_content());
								$image_html .= '</div></div>';
								$this_tt_params = array(
									'id' => 'tt_colorbox_'.$post_id,
									'wrap_id' => $wrap,
									'type' => 'inline'
									);

								
								array_push($tt_colorbox_params, $this_tt_params);
								$this->ttshowcase_add_colorbox_js ($tt_colorbox_params);
								$colorbox = true;

							}

							if($img_url_type == 'custom_lightbox') {

								$image_html ='<a href="'.$custom_url.'" class="'.$custom_lightbox.'" rel="'.$custom_lightbox_rel.'">'.$image_html.'</a>';

							}

							if($img_url_type == 'custom_url') {

								$image_html ='<a href="'.$custom_url.'" target="_blank">'.$image_html.'</a>';

							}

							if($img_url_type == 'custom_url_same') {

								$image_html ='<a href="'.$custom_url.'">'.$image_html.'</a>';

							}

						}

					//End Image Process



				//End Get the Content


				$info = '';
				
				//Sanitize options to get css

				//%%SLIDER SPECIFIC
				$columns_class = 'tt_full_column';
				if(isset($options['columns']) && $options['columns'] != '1') {
					$columns_class = '';
				}

				$image_class = isset($options['image-shape']) && isset($options['image-effect']) ? 'tt_img_'.$options['image-shape'].' tt_img_'.$options['image-effect'] : '';
				$text_class = isset($options['text-alignment']) ? 'tt_text_'.$options['text-alignment'] : '';
				$info_alignment = isset($options['info-position']) ? 'tt_'.$options['info-position'] : '';
				$quote_alignment = isset($options['info-position']) ? 'tt_quote_'.$options['info-position'] : '';
				$quote_color = 'tt_speech_balloon_color';
				
				$aditional_info_class = '';
				$quote_class = 'ttshowcase_rl_quote';
				
				if($italic) {
					$quote_class .= ' tt_italic';
				}

				//layout corrections:

				if(isset($options['info-position'])) {
					if($options['image-shape']=='circle' && ($options['info-position'] == 'info-left' || $options['info-position'] == 'info-right')) {
						$info_alignment .= ' tt_text_center';
					}

					if($options['text-alignment'] == 'left' && ($options['info-position'] == 'info-below' || $options['info-position'] == 'info-above')) {
						$info_alignment .= '';
						$image_class .= ' tt_img_left_alignment';
						$aditional_info_class .= ' tt_info_left_alignment'; 
					}

					if($options['text-alignment'] == 'left' && $options['image-shape'] == 'circle' && ($options['info-position'] == 'info-below' || $options['info-position'] == 'info-above')) {
						$aditional_info_class = 'tt_table_cell_right';
					}

					if($options['text-alignment'] == 'right' && ($options['info-position'] == 'info-below' || $options['info-position'] == 'info-above')) {
						$aditional_info_class = 'tt_info_right_alignment';
						$image_class .= ' tt_img_right_alignment';
					}

					if($options['info-position'] == 'info-below' || $options['info-position'] == 'info-above') {
						$quote_alignment .= ' ttshowcase_rl_quote_block';
					}

					if($options['info-position'] == 'info-right' || $options['info-position'] == 'info-left') {
						$quote_alignment .= ' ttshowcase_rl_quote_sided';
					}

				}

				//Prepare paramaters

					

					$title = '<div class="ttshowcase_rl_title reviewer">' . $author . '</div>';

					if($sub_title != '') {

						if($sub_title_url!='') {
								$sub_title = '<a href="'.$sub_title_url.'" '.$sub_title_target.'>'.$sub_title.'</a>';
							
						}

						$sub_title = '<div class="ttshowcase_rl_subtitle">' . $sub_title . '</div>';
					}

					$stars = '';
						if($rating_on) {
							$stars = '<i class="fa fa-star"></i>';
							$halfstar = '<i class="fa fa-star-half-o"></i>';
							$rstars = str_repeat($stars,intval($rating));

							//build half stars
							$half = round($rating-intval($rating));
							$rstars .= str_repeat($halfstar,$half);

							if($empty_stars){
								$emptystar = '<i class="fa fa-star-o"></i>';
								
								if(5-intval($rating) != 5){
									$estars = str_repeat($emptystar,5-round($rating));
									$rstars .= $estars;
								} 
							}


							$stars = '<div class="ttshowcase_rating rating-foreground rating"><span class="value-title" title="'.$rating.'"> </span> '.$rstars.' </div>';
							
						}

					$date = '';
					if($display_date){

						$date = '<div class="ttshowcase_rl_date">'.$entry_date.'</div>';

					}

					if(intval($rating) > $rating_highest) { $rating_highest = $rating; }
					$rating_total = intval($rating) + $rating_total;

					if(intval($rating)>=1){
						$rating_count++;
					}

				//in case we need a clear:both div
				$cleardiv = "<div class='ttshowcase_clear '>&nbsp;</div>";

				//%%SLIDER SPECIFIC
				//We only open the wrapping div if the counter is set to 0
				if($entries_count==0) {

					//$html .= '<div class="ttshowcase_rl_box '.$columns_class.' '.$text_class.'" >';
					$html .= '<div class="'.$columns_class.' '.$text_class.' '.$cat.'" id="tt'.$post_id.'" >';

				}

				//%%SLIDER SPECIFIC
				if(isset($options['columns']) && $options['columns'] != '1') {

					$html .= '<div class="tt_'.$options['columns'].'cl">';
					$entries_count++;

				}


				//If Rich Snippets are off
				/*
				
				Variables we can use:
				$quote -> testimonial text + review_title + read_more
				$img -> img html
				$title
				$sub_title
				$stars
				$date
				$review_title
				$read_more
				*/

				$template_search  = array('{quote}','{read_more}','{review_title}','{author}','{stars}','{date}','{subtitle}','{category}'); 
				$template_replace = array($quote, $read_more, $review_title, $title, $stars, $date, $sub_title, $taxcategory); 


					$html .= '<div class="ttshowcase_rl_box '.$columns_class.' '.$text_class.' " >';

					//breed space div class
					//%%SLIDER SPECIFIC for the slider we remove the breed class
					$html .= '<div>';


					//QUOTE BOX CONTENT

					//we wrap the meta data around the quote, if rich snippets are on
					$reviewbody = '';


					$quote_template = $quote_elements; //"{review_title}{quote}{read_more}";
					$final_quote = str_replace($template_search,$template_replace, $quote_template);

					$quote = '<div class="'.$quote_class.'">'.$quote_before.$final_quote.$quote_after.'</div>';
					$quote = '<div class="'.$quote_alignment.'" '.$reviewbody.'>' . $quote . '</div>';

					
					//INFO BOX CONTENT
					$info = '<div class="ttshowcase_rl_info_wrap '.$info_alignment.'">';

						$img = '<div class="ttshowcase_rl_image '.$image_class.'">' . $image_html. '</div>';

						$ad_info = '<div class="ttshowcase_rl_aditional_info '.$aditional_info_class.'">';
					
							//ORDER of aditional info elements
							$info_template = $info_elements; //"{author}{subtitle}{stars}{date}";
							$final_info = str_replace($template_search,$template_replace, $info_template);

						$ad_info .= $final_info.'</div>';

						//If option is not to display image or image source is empty, we don't display the image
						if($display_image!=true || $image['src'] == ''){
							$img = '';
						}
						//check for the alignment, to see how to display elements
						if(isset($options['text-alignment']) && ($options['text-alignment'] == 'right') && ($options['info-position'] == 'info-below' || $options['info-position'] == 'info-above')) {
							$info .= $img.$ad_info;
							$info .= $cleardiv;
						} else {
							$info .= $img.$ad_info;
						}

					
					$info .= '</div>';

				if(isset($options['info-position']) && ($options['info-position']=='info-right' || $options['info-position']=='info-below')) { 
					$html .= $quote.$info;
				}
				else {
					$html .= $info.$quote;
				}

				

				

				$html .= '</div>'; //close ttshowcase_rl_breed

				$html .= '</div>'; //close tshowcase_box


				$review_count++;

				//%%SLIDER
				//closing div for the columns, in case we have to display more than one per slide
				if(isset($options['columns']) && $options['columns'] != '1') {

					$html .= '</div>';

				}

				

				//%%SLIDER
				//in case we have to display more than one column per slide, we only close the wrapping div when we have
				//the necessary number of entries
				if(isset($options['columns']) && $options['columns'] != '1') {

					if($entries_count == $options['columns'] || $review_count == $query->post_count) {
						$entries_count = 0;
						$html .= '</div>';
					}

				} 
				//if number of columns is only 1 we allways close the wrap div
				else {

					$html .= '&nbsp;</div>';
				
				}


				//Structured Data
				$this_review = array();
				$this_review['@type'] = 'Review';
				$this_review['reviewBody'] = $original_quote;
				$this_review['author'] = array(
					'@type' => 'Person',
					'name' => $author,
					);
				$this_review['datePublished'] = get_the_date('Y-m-d');

				if($original_title!=''){
					$this_review['name'] = $original_title;
				}

				if(intval($rating) >= 1){
					$this_review['reviewRating'] = array(
						'@type' => 'Rating',
						'ratingValue' => $rating
					);
				}

				array_push($reviewsdata, $this_review);

				
			}

			
		} 

		

		


		//%%SLIDER SPECIFIC
		//closing slider wrapper div
		$html .="</div>";


		
		
		if(isset($options['controls']) && $options['controls'] == 'controls') {
			$html .= '<div id="tt-slider-controls-'.$tt_showcase_counter.'" class="tt-slider-controls">';
			$html .= '<span id="tt-slider-prev"> </span>';
			$html .= '<span id="tt-slider-next"> </span>';
			$html .= '</div>';

		}
		

		//closing wrapping div ttshowcase_wrap
		$html .= '</div>';

		if($snippet_on && $rating_total > 0 && $rating_total > 0 ) {

			
			$average = $rating_total/$rating_count;
			//schema
			$schema = cmshowcase_get_option('schema','ttshowcase_rich_snippets','http://schema.org/Product');
			//schema override
			$schema = isset($options['schema']) && $options['schema'] != '' ? cmshowcase_add_http($options['schema']) : $schema;
			$thistype = explode('/', $schema);
			$type = end($thistype);

			

			//we grab the product name
			$itemreviewed = cmshowcase_get_option( 'default_product', 'ttshowcase_rich_snippets', get_bloginfo() );
			$use_cat_as_prod = cmshowcase_get_boolean($use_cat_as_prod);
			
			//override
			$custom_product = isset($options['product']) ? $options['product'] : '';


			$tax_prod = false;


			if($custom_product!='') {
				$itemreviewed = $custom_product;
			} 

			//if there isn't an override, then we check if the categories should be used as products
			else {

				if($use_cat_as_prod) {

					if(isset($query->query_vars['tax_query'])) {

						$taxonomy = 'ttshowcase_groups';
						$terms = wp_get_post_terms( $post_id, $taxonomy, array("fields" => "all") );
						foreach ($terms as $term) {
							$itemreviewed = $term->name;
						}
						$tax_prod = true;

					}
				
				}

			}

			//Count type
			$metaparameter = 'ratingCount';
			if(isset($options['countmeta']) && $options['countmeta'] != '' && $options['countmeta'] == 'reviewCount') {
				$metaparameter = 'reviewCount';
			}

			$properties = array(
				'@context'  => 'http://schema.org',
				'@type' 	=> $type,
				'name' => $itemreviewed,
				'review' => $reviewsdata,
				'aggregaterating' => array(
					'@type' => 'AggregateRating',
					'reviewCount' => $review_count,
					'ratingCount' => $rating_count,
					'bestRating' => '5',
					'ratingValue' => $average,
					'worstRating' => '1',
					),
				
			);

			
		
			//general
			$image = isset($options['image_url']) ? $options['image_url'] : (!$tax_prod ? cmshowcase_get_option( 'default_image', 'ttshowcase_rich_snippets', '' ) : '');
			$description = isset($options['description']) ? $options['description'] : (!$tax_prod ? cmshowcase_get_option( 'default_description', 'ttshowcase_rich_snippets', '' ) : '');
			$sameas = isset($options['sameas']) ? $options['sameas'] : (!$tax_prod ? cmshowcase_get_option( 'default_sameas', 'ttshowcase_rich_snippets', '' ) : '');
			$url = isset($options['url']) ? $options['url'] : (!$tax_prod ? cmshowcase_get_option( 'default_url', 'ttshowcase_rich_snippets', '' ) : '');


			if($image!=''){
				$properties['image'] = $image;
			}
			
			if($description!=''){
				$properties['description'] = $description;
			}

			if($sameas!=''){
				$properties['sameAs'] = explode(',', $sameas);
			}
			
			if($url!=''){
				$properties['url'] = $url;
			}

			//business
			if($type!='Product' && $type!='SoftwareApplication'){

				$tel = isset($options['business_telephone']) ? $options['business_telephone'] : (!$tax_prod ? cmshowcase_get_option( 'business_telephone', 'ttshowcase_rich_snippets', '' ) : '');
				$address = isset($options['business_address']) ? $options['business_address'] : (!$tax_prod ? cmshowcase_get_option( 'business_address', 'ttshowcase_rich_snippets', '' ) : '');
				$priceRange = isset($options['business_pricerange']) ? $options['business_pricerange'] : (!$tax_prod ? cmshowcase_get_option( 'business_pricerange', 'ttshowcase_rich_snippets', '' ) : '');
				$email = isset($options['business_email']) ? $options['business_email'] : (!$tax_prod ? cmshowcase_get_option( 'business_email', 'ttshowcase_rich_snippets', '' ) : '');
				$logo = isset($options['business_logo']) ? $options['business_logo'] : (!$tax_prod ? cmshowcase_get_option( 'business_logo', 'ttshowcase_rich_snippets', '' ) : '');

				if($tel!=''){
					$properties['telephone'] = $tel;
				}
				if($address!=''){
					$properties['address'] = $address;
				}
				if($priceRange!=''){
					$properties['priceRange'] = $priceRange;
				}

				if($email!=''){
					$properties['email'] = $email;
				}
				if($logo!=''){
					$properties['logo'] = $logo;
				}
				

			}
			
			//product
			if($type=='Product' || $type=='SoftwareApplication'){

				$price = isset($options['price']) ? $options['price'] : (!$tax_prod ? cmshowcase_get_option( 'product_price', 'ttshowcase_rich_snippets', '' ) : '');
				$currency = isset($options['price_currency']) ? $options['price_currency']  : (!$tax_prod ? cmshowcase_get_option( 'product_currency', 'ttshowcase_rich_snippets', '' ) : '');
				$date = isset($options['price_valid']) ? $options['price_valid'] : (!$tax_prod ? cmshowcase_get_option( 'product_date', 'ttshowcase_rich_snippets', '' ) : '');
				$availability = isset($options['availability']) ? $options['availability'] : (!$tax_prod ? cmshowcase_get_option( 'product_availability', 'ttshowcase_rich_snippets', '' ) : '');
				
				if($price!='' && $availability != '' && $currency != ''){
					$properties['offers'] = array(
						'@type' => 'Offer',
						'availability' => $availability,
						'price' => $price,
						'priceCurrency' => $currency
						);
					if($date!=''){
						$properties['offers']['priceValidUntil'] = $date;
					}
				}
			}

			$html .= '<script type="application/ld+json">'.json_encode($properties).'</script>';




		}


		$imagewidth = '';
		if(isset($image['width'])) {

			$imagewidth = 'width: '.intval($image['width']+20).'px;';
		
		}

		$css = '';

		//Extra CSS for speech bubbles
		if($theme == 'speech' || $theme == 'flat') {
		$css .= '<!-- Custom Styles for Grid Layout of Testimonials Showcase -->';
			    $css .= '<style type="text/css" scoped>';
			    $css .= $wrap.' .tt_theme_'.$theme.' .ttshowcase_rl_quote_sided .ttshowcase_rl_quote, 
			         	'.$wrap.' .tt_theme_'.$theme.' .ttshowcase_rl_quote_block .ttshowcase_rl_quote, 
			         	'.$wrap.' .tt_theme_'.$theme.' .ttshowcase_rl_quote_sided .ttshowcase_rl_quote a, 
	         			'.$wrap.' .tt_theme_'.$theme.' .ttshowcase_rl_quote_block .ttshowcase_rl_quote a
			    			{
								background:'.$main_color.';
								color:'.$text_color.';
							}'.$wrap.' .tt_theme_'.$theme.' .ttshowcase_rl_quote_block .ttshowcase_rl_quote:after,
							'.$wrap.' .tt_theme_'.$theme.' .tt_quote_info-below .ttshowcase_rl_quote:after,
							'.$wrap.' .tt_theme_'.$theme.' .tt_quote_info-above .ttshowcase_rl_quote:after
							 {
								border-color: '.$main_color.' transparent;
							}'.$wrap.' .tt_theme_'.$theme.' .ttshowcase_rl_quote_sided .ttshowcase_rl_quote:after {
								border-color: transparent '.$main_color.';
							}'.$wrap.' .tt_theme_'.$theme.' .tt_info-left,
							'.$wrap.' .tt_theme_'.$theme.' .tt_info-right,
							 {
								'.$imagewidth.'
							}';
			    $css .= '</style>';
		}



		if($theme == 'card') {

		

		$css .= '<!-- Custom Styles for Grid Layout of Testimonials Showcase -->';
			    $css .= '<style type="text/css" scoped>';
			    $css .= $wrap.' .tt_theme_card .ttshowcase_rl_quote_sided, 
			    		'.$wrap.' .tt_theme_card .ttshowcase_rl_quote_block .ttshowcase_rl_quote, 
			    		'.$wrap.' .tt_theme_card .ttshowcase_rl_quote_sided a, 
			    		'.$wrap.' .tt_theme_card .ttshowcase_rl_quote_block .ttshowcase_rl_quote a
			    			{
								background:'.$card_main_color.';
								color:'.$card_text_color.';
							}'.$wrap.' .tt_theme_card .ttshowcase_rl_quote_block .ttshowcase_rl_quote:after,
							'.$wrap.' .tt_theme_card .tt_quote_info-below .ttshowcase_rl_quote:after,
							'.$wrap.' .tt_theme_card .tt_quote_info-above .ttshowcase_rl_quote:after
							 {
								border-color: '.$card_main_color.' transparent;
							}'.$wrap.' .tt_theme_card .ttshowcase_rl_quote_sided .ttshowcase_rl_quote:after {
								border-color: transparent '.$card_main_color.';
							}'.$wrap.' .tt_theme_card .tt_info-left,
							'.$wrap.' .tt_theme_card .tt_info-left a,
							'.$wrap.' .tt_theme_card .tt_info-right,
							'.$wrap.' .tt_theme_card .tt_info-right a,
							'.$wrap.' .tt_theme_card .tt_info-below,
							'.$wrap.' .tt_theme_card .tt_info-below a,
							'.$wrap.' .tt_theme_card .tt_info-above,
							'.$wrap.' .tt_theme_card .tt_info-above a
							 {
								background:'.$card_sec_color.';
								color:'.$card_sec_text_color.';
							}'.$wrap.' .tt_theme_'.$theme.' .tt_info-left,
							'.$wrap.' .tt_theme_'.$theme.' .tt_info-right,
							 {
								'.$imagewidth.'
							}';
			    $css .= '</style>';
		}

		


		//Slider Parameters:
		$this_slider_tt_params = array(
					'wrap_id' => $wrap,
					'counter' => $tt_showcase_counter,
					'mode' => isset($options['transition']) ? $options['transition'] : 'fade',
					'pause' => isset($options['pause']) ? $options['pause'] : '4000',
					'auto' => isset($options['auto']) && $options['auto'] == 'on' ? true : false,
					'controls' => isset($options['controls']) ? $options['controls'] : 'pager',
					'columns' => isset($options['columns']) ? $options['columns'] : 'off', 
					'arrow_next' => $next_arrow,
					'arrow_prev' => $prev_arrow,
					'adaptive_height' => isset($options['adaptive']) ? cmshowcase_get_boolean($options['adaptive']) : false
					);

		array_push($tt_slider_params, $this_slider_tt_params);
		$this->ttshowcase_add_slider_js ($tt_slider_params);


		//remove the colorbox shortcode styles
		$this->ttshowcase_remove_shortcode_colorbox();
		

		//we need to close loop here, so the posts work well
		wp_reset_postdata();

		


		if($custom_css!='') {

			$css .= '<!-- Custom Styles for Testimonials Showcase -->';
			    $css .= '<style type="text/css" scoped>';
			    $css .= $custom_css;
			    $css .= '</style>';

			$this->custom_css = '';

		}

		$js = '';
		if($custom_js!='') {

			$js .= '<!-- Custom Script for Testimonials Showcase -->';
			    $js .= '<script type="text/javascript">';
			    $js .= $custom_js;
			    $js .= '</script>';

			$this->custom_js = '';

		}

		//if it's a preview, we display the css together with the output html
		if($preview) {

			$html = $html.$css;

		}
		//otherwise we add it to the footer
		else {
			
			$this->footer_content .= $js;
			add_action('wp_footer', array($this,'ttshowcase_footer_content'),100);

			//changed so the scoped css is included in the beggining of the div
			$html = $css.$html;
		}

		//return final HTML
        return $html;

	}

	function ttshowcase_footer_content() {

		echo $this->footer_content;

	}



	function ttshowcase_add_slider_js($array)
	{

		

		wp_deregister_script( 'tt-bxslider-build' );
		wp_register_script( 'tt-bxslider-build', plugins_url( '/js/slider.js', __FILE__) , array(
			'jquery',
			'tt-bxslider'
		) , false, false);

		wp_enqueue_script( 'tt-bxslider-build' );

		wp_localize_script('tt-bxslider-build', 'tt_slider_param', $array);
		
		
	}

	function ttshowcase_add_colorbox_js($array)

		{

			$this->ttshowcase_remove_shortcode_colorbox();
			
			wp_register_script( 'tt-colorbox', plugins_url( '/resources/colorbox/jquery.colorbox.js',dirname(dirname(__FILE__))) , array(
				'jquery',
			) , false, false);

			wp_enqueue_script( 'tt-colorbox' );

			wp_register_script( 'tt-colorbox-individual', plugins_url( '/js/colorbox.js', __FILE__) , array(
				'jquery',
				'tt-colorbox'
			) , false, false);

			wp_enqueue_script( 'tt-colorbox-individual' );

			wp_register_style( 'tt-colorbox-individual', plugins_url( '/resources/colorbox/colorbox.css',dirname(dirname(__FILE__))),array(),false,'all' );
			wp_enqueue_style( 'tt-colorbox-individual' );

			//wp_localize_script('tt-colorbox-individual', 'tt_colorbox_param', $array);
			
			
		}

	function ttshowcase_remove_shortcode_colorbox () {

 		wp_dequeue_script( 'tt-colorbox-shortcode' );
   		wp_dequeue_script( 'tt-colorbox-script' );
   		wp_dequeue_style( 'tt-colorbox-shortcode' );

		wp_deregister_script( 'tt-colorbox-shortcode' );
		wp_deregister_script( 'tt-colorbox-script' );
		wp_deregister_style( 'tt-colorbox-shortcode' );


	}


}
endif;
?>