jQuery( window ).ready( function() {

  tts_isotope_process();

});


function tts_isotope_process() {

  jQuery( ".ttshowcase_wrap" ).each(function( index ) {
    
    jQuery(this).find('ul#tts-isotope-filter-nav li#tts-all').addClass('tts-current-li');
    
    jQuery(this).find('ul#tts-isotope-filter-nav > li').on( 'click', function() {
      
      var filterValue = jQuery(this).attr('data-filter');

       //jQuery(this).find('#ts-isotope-filter-nav > li')
       jQuery(this).siblings().removeClass('tts-current-li');
       jQuery(this).addClass('tts-current-li');

       jQuery(this).siblings().find("ul").click(function(e) {
          e.stopPropagation();
        });

      jQuery('.ttshowcase_masonry').isotope({ filter: filterValue });
   
    });

    jQuery( this ).find('ul#tts-isotope-filter-nav > li > ul > li').on( 'click', function() {
      var filterValue = jQuery(this).attr('data-filter');

       jQuery( this ).find('#tts-isotope-filter-nav > li').removeClass('tts-current-li');
       jQuery(this).addClass('tts-current-li');

      jQuery('.ttshowcase_masonry').isotope({ filter: filterValue });
    });

  
  });


  

}

