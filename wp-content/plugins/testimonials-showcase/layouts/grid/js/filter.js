
jQuery( document ).ready(function() {

	jQuery('#tts-all').addClass('tts-current-li');
	jQuery("#tts-filter-nav > li").click(function(){
	    tts_show(this.id);
	}).children().click(function(e) {
	  return false;
	});

	jQuery("#tts-filter-nav > li > ul > li").click(function(){
	    tts_show(this.id);
	});

	//Load specific category via url hash

	var start_filter = tts_get_hash()["show"];
	if(start_filter!='') {
		jQuery('#'+start_filter).click();
		
	}

});

//In case you want all entries to hide when the page loads
//jQuery('.ts-05_project-5').hide();	
//To load a particular category
//jQuery('#tts-01-sales-team').click();




//FILTER CODE
function tts_show(category) {	 

	
	
	if (category == "tts-all") {
        jQuery('#tts-filter-nav li').removeClass('tts-current-li');
        jQuery('#tts-all').addClass('tts-current-li');
        jQuery('.ttshowcase_rl_box').show(1600,'easeInOutExpo');

		}
	
	else {

		jQuery('#tts-filter-nav li').removeClass('tts-current-li');
   		jQuery('#' + category).addClass('tts-current-li');
   		if(jQuery('#' + category).parent().parent().is('li')) {
   			jQuery('#' + category).parent().parent().addClass('tts-current-li');
   		} 

		jQuery('.' + category).show(1600,'easeInOutExpo');
		jQuery('.ttshowcase_rl_box:not(.'+ category+')').hide(1000,'easeInOutExpo');
			
	}


	//hack to solve menu left open on touch devices
	/*

		jQuery('ul li ul li.ts-current-li')
		.parent()
		.hide()
		.parent()
		.on('click', function(){ 
			jQuery(this).addClass('ts-current-li')
			.children().show(); 
		});

	*/
}


jQuery(document).ajaxSuccess(function() {
  
	jQuery('#tts-all').addClass('tts-current-li');
	jQuery("#tts-filter-nav > li").click(function(){
	    tts_show(this.id);
	}).children().click(function(e) {
	  return false;
	});

	jQuery("#tts-filter-nav > li > ul > li").click(function(){
	    ts_show(this.id);
	});
});





function tts_get_hash(){
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('#') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

