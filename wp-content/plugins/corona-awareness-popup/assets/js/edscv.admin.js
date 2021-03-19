(function($, document, window) {
    "use strict";
    
    /**
     * Callback function for the 'click' event of the 'Popup image'     
     *
     * Displays the media uploader for selecting an image.
     *
     * @since 1.0.0
     */
    function renderMediaUploader() {
        'use strict';

        var imgFrame;

        if (undefined !== imgFrame) {

            imgFrame.open();
            return;

        }

        imgFrame = wp.media.frames.imgFrame = wp.media({
            title: 'Select Popup Image',
            multiple: false,
            library: {
                type: 'image',
            }
        });
    
        imgFrame.on('close', function () {
            var selection = imgFrame.state().get('selection');
            selection && selection.each(function (attachment) {
                if (typeof attachment.attributes != "undefined" && typeof attachment.attributes.url != "undefined") {
                    $("#edcsv-setting-form #edscv-popup-image").val(attachment.attributes.url);
                }
            });
        });

        // Now display the actual imgFrame
        imgFrame.open();

    }
    
    $(document).ready(function() {
        $('body').on('click', '#edscv-popup-image-btn', function (evt) {

            // Stop the anchor's default behavior
            evt.preventDefault();

            // Display the media uploader
            renderMediaUploader();

        });        
    });
})(jQuery, document, window);