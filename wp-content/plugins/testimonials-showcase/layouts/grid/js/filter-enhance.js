	
	jQuery('#tts-all').addClass('tts-current-li');
	jQuery("#tts-enhance-filter-nav > li").click(function(){
	    tts_show_enhance(this.id);
	}).children().click(function(e) {
	  return false;
	});

	jQuery("#tts-enhance-filter-nav > li > ul > li").click(function(){
	    tts_show_enhance(this.id);
	});


//FILTER CODE
function tts_show_enhance(category) {	

	if (category == "tts-all") {
        jQuery('#tts-enhance-filter-nav > li').removeClass('tts-current-li');
        jQuery('#tts-all').addClass('tts-current-li');
        jQuery('.ttshowcase_rl_box').addClass('tts-current').removeClass('tts-not-current');
		}
	
	else {
		jQuery('#tts-enhance-filter-nav > li').removeClass('tts-current-li');
   		jQuery('#' + category).addClass('tts-current-li');  
		jQuery('.' + category).addClass('tts-current').removeClass('tts-not-current'); 
		jQuery('.ttshowcase_rl_box:not(.'+ category+')').addClass('tts-not-current').removeClass('tts-current');
	}
	
}



jQuery(document).ajaxSuccess(function() {
	jQuery('#tts-all').addClass('ts-current-li');
	jQuery("#tts-enhance-filter-nav > li").click(function(){
	    tts_show_enhance(this.id);
	}).children().click(function(e) {
	  return false;
	});

	jQuery("#tts-enhance-filter-nav > li > ul > li").click(function(){
	    tts_show_enhance(this.id);
	});
});