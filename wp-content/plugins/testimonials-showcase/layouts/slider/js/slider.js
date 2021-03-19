jQuery(document).ready(function($){

	ttshowcase_build_sliders();

	//Make pager work on hover
	/*
	jQuery.each(cmsliders, function(index,value) {


			jQuery('.bx-pager-item a').mouseenter(function() {
				var idslide = $(this).attr('data-slide-index');
				value.goToSlide(idslide);
				});

		});

	*/

});

/*

jQuery(document).ajaxSuccess(function($) {

		ttshowcase_build_sliders();
			
	});

*/

/*
jQuery(window).on('resize orientationchange', function($) {

	clearTimeout(jQuery.data(this, 'resizeTimer'));
    jQuery.data(this, 'resizeTimer', setTimeout(function() {

    	if(cmsliders && cmsliders.length>0){

    		for (var i = 0; i < cmsliders.length; i++) {


			if (typeof cmsliders[i] != 'undefined') {


			  cmsliders[i].destroySlider();
			  cmsliders[i].unbind();
			  cmsliders[i] = undefined;
			 
			}

		}


		ttshowcase_build_sliders();
		console.log('slider renovated');

    	}

    }, 1000));

});

*/


function ttshowcase_build_sliders() {

	var cmsliders = [];

	if(tt_slider_param !== undefined) {

		for (var key in tt_slider_param) {

			var columns = parseInt(tt_slider_param[key]['columns']);
			
			var wrap = tt_slider_param[key]['wrap_id']; 

				
				var smode = tt_slider_param[key]['mode'];
				var spause = parseInt(tt_slider_param[key]['pause']);
				var sauto = tt_slider_param[key]['auto'];

				//controls
				var next_arrow = tt_slider_param[key]['arrow_next'];
				var prev_arrow = tt_slider_param[key]['arrow_prev'];

				var scontrols = false;
				var sautocontrols = false;
				var spager = false;

				var adaptiveh = tt_slider_param[key]['adaptive_height'];

				/*var windowWidth = jQuery(window).width();
		        if (windowWidth < 650) {
		            adaptiveh = true;
		        }*/

				var sprevSelector = false;
				var snextSelector = false;
				if(tt_slider_param[key]['controls']=='controls') {
					scontrols = true;
					snextSelector = wrap + ' #tt-slider-next';
					sprevSelector = wrap + ' #tt-slider-prev';
				}

				if(tt_slider_param[key]['controls']=='pager') {
					spager = true;
				}


				if(tt_slider_param[key]['controls']=='autocontrols') {
					sautocontrols = true;
				}

				if(tt_slider_param[key]['controls']=='sides') {
					scontrols = true;
				}




				jQuery(wrap +' .ttshowcase_slider').fadeIn('slow');

				cmsliders[key] = jQuery(wrap +' .ttshowcase_wrap').bxSlider({
				  preloadImages: 'all',
				  mode: smode,
				  auto: sauto,
				  controls: scontrols,
				  autoControls: sautocontrols,
				  pager: spager,
				  pause: spause,
				  pagerType: 'full',
				  autoHover: true,
				  nextSelector: snextSelector,	
  				  prevSelector: sprevSelector,
  				  nextText: next_arrow,
  				  prevText: prev_arrow,
  				  speed: 500,
  				  adaptiveHeight:adaptiveh,
  				  touchEnabled: true

				});

				// to fix iOS Safari issue where height was being miscalculated
				cmsliders[key].goToSlide(0);
				if(sauto==true) {
					cmsliders[key].startAuto();
				}

				if(sauto==true) {
					
					jQuery('.bx-next, .bx-prev, .bx-pager a').click(function(){
					    // time to wait (in ms)
					    var wait = 2000;
					    setTimeout(function(){
					        cmsliders[key].startAuto();
					    }, wait);
					});

				}

				
			

		}
	}



}