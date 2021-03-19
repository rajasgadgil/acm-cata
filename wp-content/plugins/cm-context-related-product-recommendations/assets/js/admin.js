
( function ( $ ) {

    $.fn.extend( {
        limiter: function ( limit, elem ) {
            $( this ).on( "keyup focus", function () {
                setCount( this, elem );
            } );
            function setCount( src, elem ) {
                var chars = src.value.length;
                if ( chars > limit ) {
                    src.value = src.value.substr( 0, limit );
                    chars = limit;
                }
                elem.html( limit - chars );
            }
            setCount( $( this )[0], elem );
        }
    } );

    $.fn.extend( {
        counter: function ( elem ) {
            $( this ).on( "keyup focus", function () {
                setCount( this, elem );
            } );
            function setCount( src, elem ) {
                var chars = '(' + src.value.length + ')';
                elem.html( chars );
            }
            setCount( $( this )[0], elem );
        }
    } );


    $( document ).ready( function () {

        // Add Color Picker to all inputs that have 'color-field' class
        $( 'input[type="text"].colorpicker' ).wpColorPicker();

        $( 'input#cmcrpr_description' ).each( function ( i, obj ) {
            var displayLimit = $( obj ).after( '<span class="cmcrpr-display-count"></span>' ).next( '.cmcrpr-display-count' );
            $( obj ).counter( displayLimit );
        } );

        /*
         * CUSTOM REPLACEMENTS
         */
        $.fn.add_new_replacement_row = function () {
            var articleRow, articleRowHtml, rowId;

            rowId = $( ".custom-related-article" ).length;
            articleRow = $( '<div class="custom-related-article"></div>' );
            articleRowHtml = $( '<input type="text" name="cmcrpr_related_article_name[]" style="width: 40%" id="cmcrpr_related_article_name" value="" placeholder="Name"><input type="text" name="cmcrpr_related_article_url[]" style="width: 50%" id="cmcrpr_related_article_url" value="" placeholder="http://"><a href="#javascript" class="cmcrpr_related_article_remove">Remove</a>' );
            articleRow.append( articleRowHtml );
            articleRow.attr( 'id', 'custom-related-article-' + rowId );

            $( "#cmcrpr-related-article-list" ).append( articleRow );
            return false;
        };

        $.fn.delete_replacement_row = function ( row_id ) {
            $( "#custom-related-article-" + row_id ).remove();
            return false;
        };

        /*
         * Added in 2.7.7 remove replacement_row
         */
        $( document ).on( 'click', 'a.cmcrpr_related_article_remove', function () {
            var $this = $( this ), $parent;
            $parent = $this.parents( '.custom-related-article' ).remove();
            return false;
        } );

        /*
         * Added in 2.4.9 (shows/hides the explanations to the synonyms/abbreviations)
         */
        $( document ).on( 'click showHideInit', '.cm-showhide-handle', function () {
            var $this = $( this ), $parent, $content;

            $parent = $this.parent();
            $content = $this.siblings( '.cm-showhide-content' );

            if ( !$parent.hasClass( 'closed' ) )
            {
                $content.hide();
                $parent.addClass( 'closed' );
            }
            else
            {
                $content.show();
                $parent.removeClass( 'closed' );
            }
        } );

        $( '.cm-showhide-handle' ).trigger( 'showHideInit' );

        /*
         * CUSTOM REPLACEMENTS - END
         */

        if ( $.fn.tabs ) {
            $( '#cmcrpr_tabs' ).tabs( {
                activate: function ( event, ui ) {
                    window.location.hash = ui.newPanel.attr( 'id' ).replace( /-/g, '_' );
                },
                create: function ( event, ui ) {
                    var tab = location.hash.replace( /\_/g, '-' );
                    var tabContainer = $( ui.panel.context ).find( 'a[href="' + tab + '"]' );
                    if ( typeof tabContainer !== 'undefined' && tabContainer.length )
                    {
                        var index = tabContainer.parent().index();
                        $( ui.panel.context ).tabs( 'option', 'active', index );
                    }
                }
            } );
        }

        $( '.cmcrpr_field_help_container' ).each( function () {
            var newElement,
                element = $( this );

            newElement = $( '<div class="cmcrpr_field_help"></div>' );
            newElement.attr( 'title', element.html() );

            if ( element.siblings( 'th' ).length )
            {
                element.siblings( 'th' ).append( newElement );
            }
            else
            {
                element.siblings( '*' ).append( newElement );
            }
            element.remove();
        } );

        $( '.cmcrpr_field_help' ).tooltip( {
            show: {
                effect: "slideDown",
                delay: 100
            },
            position: {
                my: "left top",
                at: "right top"
            },
            content: function () {
                var element = $( this );
                return element.attr( 'title' );
            },
            close: function ( event, ui ) {
                ui.tooltip.hover(
                    function () {
                        $( this ).stop( true ).fadeTo( 400, 1 );
                    },
                    function () {
                        $( this ).fadeOut( "400", function () {
                            $( this ).remove();
                        } );
                    } );
            }
        } );

    } );

} )( jQuery );