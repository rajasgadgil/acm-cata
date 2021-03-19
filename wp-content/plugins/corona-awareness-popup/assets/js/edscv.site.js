(function ($, document, window) {
    "use strict";    
    
    var cookieName = 'edscv-popup-cookie';

    var $body = $('body');    

    function renderPopup() {

        var settings = (typeof edscv_obj != "undefind") ? (edscv_obj.settings || {}) : {};

        if(!Object.keys(settings).length) {
            return;
        }

        var $popup = $('<div id="edscv-popup" class="edscv-popup edscv-popup-hidden"><div class="edscv-popup-overlay"></div><div class="edscv-popup-body"><a href="#" id="edscv-close-popup-btn" class="edscv-close-popup-btn">&times;</a></div></div>');
        var $popupBody = $popup.find(".edscv-popup-body");
        if (settings.popup_image) {
            var $popup_image = $(['<div class="edscv-popup-image"><img src="', settings.popup_image, '" /></div>'].join(''));
            $popupBody.append($popup_image);
        }

        if (settings.popup_content) {
            var $content = $(['<div class="edscv-popup-content">', settings.popup_content, '</div>'].join(''));
            $popupBody.append($content);
        }

        if (settings.readmore_link && settings.readmore_text) {
            var target = settings.readmore_new_tab ? 'target="_blank"' : '';        
            var $footer = $(['<div class="edscv-popup-footer"><a href="', settings.readmore_link, '" ', target, '>', settings.readmore_text, '</a></div>'].join(''));
            $popupBody.append($footer);
        }       

        $body.append($popup);
    }

    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + ( parseInt(exdays) * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return null;
    }

    function closePopup() {
        $body.find('#edscv-popup').addClass('edscv-popup-hidden');
        $body.off('keydown', handleEscapeKeyPress);
    }

    var handleEscapeKeyPress = function (e) {        
        if (e.key == 'Escape' || e.key == 'Esc' || e.keyCode == 27) {            
            closePopup();
        }
    }

    function displayPopup() {
        var settings = (typeof edscv_obj != "undefind") ? (edscv_obj.settings || {}) : {};        
        var dayCookieName = cookieName + '-day',
            weekCookieName = cookieName + '-week';
        if (settings.popup_frequency != 'always') {                        
            switch (settings.popup_frequency) {
                case 'day':                    
                    // delete week cookie if exist
                    setCookie(weekCookieName, 1, -1);
                    if (getCookie(dayCookieName)) {
                        return;
                    }
                    setCookie(dayCookieName, 1, 1);
                    break;
                case 'week':
                    // delete day cookie if exist
                    setCookie(dayCookieName, 1, -1);
                    if (getCookie(weekCookieName)) {
                        return;
                    }
                    setCookie(weekCookieName, 1, 7);
                    break;
            }           
        } else {
            // delete cookie if exist
            setCookie(dayCookieName, 1, -1);
            setCookie(weekCookieName, 1, -1);
        }

        //rendering popup
        renderPopup();

        $body.find('#edscv-popup').removeClass('edscv-popup-hidden');

        $body.on('click', '#edscv-popup #edscv-close-popup-btn, #edscv-popup .edscv-popup-overlay', function (e) {
            e.preventDefault()
            closePopup();
        });

        $body.on('keydown', handleEscapeKeyPress);
    }

    $(document).ready(function () {        
        //display popup
        displayPopup();

    });
})(jQuery, document, window);