(function ($) {
    'use strict';

    jQuery(document).ready(function ($) {

        // Uploading files
        var file_frame;
        var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
        var set_to_post_id = 0; // Set this

        jQuery('.upload_button').on('click', function (event) {
            var clickedBtnID = $(this).attr('id');
            set_to_post_id = $('#' + clickedBtnID).closest('td').find('input.upload_field:first').val();
            event.preventDefault();

            // Create the media frame.
            file_frame = wp.media.frames.file_frame = wp.media({
                title: 'Select a image to upload',
                button: {
                    text: 'Use this image',
                },
                multiple: false, // Set to true to allow multiple files to be selected
                library: {
                    query: true,
                    post_mime_type: ['image/png'] // pass all mimes in array
                },
            });

            // When an image is selected, run a callback.
            file_frame.on('select', function () {
                // We set multiple to false so only get one image from the uploader
                let attachment = file_frame.state().get('selection').first().toJSON();

                // Do something with attachment.id and/or attachment.url here
                $('#' + clickedBtnID).closest('td').find('img.upload_preview:first').attr('src', attachment.url).fadeIn();
                $('#' + clickedBtnID).closest('td').find('input.upload_field:first').val(attachment.id);
                $('#' + clickedBtnID).closest('td').find('input.upload_clear:first').removeClass('disabled');

                // Restore the main post ID
                wp.media.model.settings.post.id = wp_media_post_id;
            });

            // Finally, open the modal
            file_frame.open();
        });

        // Restore the main ID when the add media button is pressed
        jQuery('a.add_media').on('click', function () {
            wp.media.model.settings.post.id = wp_media_post_id;
        });

        jQuery('.upload_clear').on('click', function (event) {
            event.preventDefault();
            $(this).addClass('disabled');
            $(this).closest('td').find('img.upload_preview:first').fadeOut();
            $(this).closest('td').find('input.upload_field:first').val(0);
            return false;
        });

        $(function() {
            $('.esi-color-picker').wpColorPicker();
        });
    });
})(jQuery);
