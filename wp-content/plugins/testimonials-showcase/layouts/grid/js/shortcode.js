
function cm_tt_shortcode_check(shortcode_id) {

	//When Ajax Loads
	jQuery( document ).ajaxComplete(function() {
	  
		jQuery(".tt_colorbox_iframe").colorbox({iframe:true, innerWidth:640, innerHeight:390, transition: 'fade'});

		if (typeof Masonry !== 'undefined') {

			var container = document.querySelector('.ttshowcase_wrap > div');
			
			if(container!=null) {


				var msnry = new Masonry( container, {
			  	itemSelector: '.ttshowcase_rl_box'
				});		

			}
			

		}

		var filter = jQuery("#tts-filter-nav");
		if(filter) {

				jQuery('#tts-all').addClass('tts-current-li');
				jQuery("#tts-filter-nav > li").click(function(){
				    tts_show(this.id);
				}).children().click(function(e) {
				  return false;
				});

				jQuery("#tts-filter-nav > li > ul > li").click(function(){
				    tts_show(this.id);
				});
		}

		var enhancefilter = jQuery("#tts-enhance-filter-nav");
		if(enhancefilter) {

					jQuery('#tts-all').addClass('tts-current-li');
					jQuery("#tts-enhance-filter-nav > li").click(function(){
					    tts_show_enhance(this.id);
					}).children().click(function(e) {
					  return false;
					});

					jQuery("#tts-enhance-filter-nav > li > ul > li").click(function(){
					    tts_show_enhance(this.id);
					});
		}

		var isofilter = jQuery("#tts-isotope-filter-nav");

		if(isofilter.length >= 1) {

			if(jQuery.isFunction(tts_isotope_process)) {

				if (typeof Masonry !== 'undefined') {
				
				tts_isotope_process();

				}
			}
				
		}

		jQuery( document ).unbind('ajaxComplete');
	  
	});

	
}


