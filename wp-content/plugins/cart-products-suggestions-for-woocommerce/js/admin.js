var br_saved_timeout;
var br_savin_ajax = false;
(function ($){
    $(document).ready( function () {
        $('.cart_suggestion_submit_form').submit( function(event) {
            event.preventDefault();
            $('.cat_suggest_position').each(function( i, o ) {
                $(o).val(i);
            });
            $('.product_suggest_position').each(function( i, o ) {
                $(o).val(i);
            });
            $('.br_add_suggestion_to_specific_product table tbody tr').each(function( i, o ) {
                $(o).find('input').each(function ( ii, oo ) {
                    var name = $(oo).data('name');
                    if( name != undefined ) {
                        name = name.replace('%position%', i);
                        $(oo).attr('name', name);
                    }
                });
            });
            if( !br_savin_ajax ) {
                br_savin_ajax = true;
                var form_data = $(this).serialize();
                form_data = 'action=br_cart_suggestion_settings_save&'+form_data;
                var url = ajaxurl;
                clearTimeout(br_saved_timeout);
                destroy_br_saved();
                $('body').append('<span class="br_saved br_saving"><i class="fa fa-refresh fa-spin"></i></span>');
                $.post(url, form_data, function (data) {
                    if($('.br_saved').length > 0) {
                        $('.br_saved').removeClass('br_saving').find('.fa').removeClass('fa-spin').removeClass('fa-refresh').addClass('fa-check');
                    } else {
                        $('body').append('<span class="br_saved"><i class="fa fa-check"></i></span>');
                    }
                    br_saved_timeout = setTimeout( function(){destroy_br_saved();}, 5000 );
                    br_savin_ajax = false;
                }, 'json').fail(function() {
                    if($('.br_saved').length > 0) {
                        $('.br_saved').removeClass('br_saving').addClass('br_not_saved').find('.fa').removeClass('fa-spin').removeClass('fa-refresh').addClass('fa-times');
                    } else {
                        $('body').append('<span class="br_saved br_not_saved"><i class="fa fa-times"></i></span>');
                    }
                    br_saved_timeout = setTimeout( function(){destroy_br_saved();}, 5000 );
                    $('.br_save_error').html(data.responseText);
                    br_savin_ajax = false;
                });
            }
        });
        function destroy_br_saved() {
            $('.br_saved').addClass('br_saved_remove');
            var $get = $('.br_saved');
            setTimeout( function(){$get.remove();}, 200 );
        }
        $(window).on('keydown', function(event) {
            if (event.ctrlKey || event.metaKey) {
                switch (String.fromCharCode(event.which).toLowerCase()) {
                case 's':
                    event.preventDefault();
                    $('.cart_suggestion_submit_form').submit();
                    break;
                }
            }
        });
        $('.br_settings .nav-tab').click(function(event) {
            event.preventDefault();
            $('.nav-tab-active').removeClass('nav-tab-active');
            $('.nav-block-active').removeClass('nav-block-active');
            $(this).addClass('nav-tab-active');
            $('.'+$(this).data('block')+'-block').addClass('nav-block-active');
        });
        $(document).on('click', '.add_category_suggest', function(event) {
            var cat_text = $('.category_suggest').find(':selected').text();
            var cat_val = $('.category_suggest').val();
            if( $('.cat_exist_'+cat_val).length == 0 ) {
                var html = '<tr class="cat_exist_id cat_exist_'+cat_val+'"><td class="move_suggestions"><i class="fa fa-th"></i></td><td><input class="cat_suggest_position" type="hidden" value="" name="br-cart_suggestion-options[category_suggest]['+cat_val+'][position]"><input type="hidden" value="'+cat_val+'" name="br-cart_suggestion-options[category_suggest]['+cat_val+'][category]">'+cat_text+'</td><td>';
                html += category_product_search.replace('%cat_id%', cat_val);
                html += '</td><td class="cat_suggest_remove"><button type="button" class="cat_suggest_remove_button">Remove</button></td></tr>';
                $('.br_add_suggestion_to_specific_category table tbody').append($(html));
            }
            reload_sortable();
        });
        $(document).on('click', '.add_product_suggest', function(event) {
            var html = '<tr class="cat_exist_id"><td class="move_suggestions"><i class="fa fa-th"></i></td><td><input class="product_suggest_position" type="hidden" value="" data-name="br-cart_suggestion-options[product_suggest][%position%][position]" name="br-cart_suggestion-options[product_suggest][%position%][position]">';
            html += product_product_search;
            html += '</td><td>';
            html += product_product_search_2;
            html += '</td><td class="cat_suggest_remove"><button type="button" class="cat_suggest_remove_button">Remove</button></td></tr>';
            $('.br_add_suggestion_to_specific_product table tbody').append($(html));
            reload_sortable();
        });
        $(document).on('click', '.cat_suggest_remove_button', function(event) {
            $(this).parents('.cat_exist_id').remove();
        });
        $(document).on('change', '.brcs_wid_type', function() {
            if($(this).val() == 'slider' || $(this).val() == 'slider_title') {
                $(this).parents('.brcs_widget_setting').find('.brcs_slider_wid').show();
            } else {
                $(this).parents('.brcs_widget_setting').find('.brcs_slider_wid').hide();
            }
        });
    });
})(jQuery);