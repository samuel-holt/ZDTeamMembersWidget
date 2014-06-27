jQuery(document).ready( function($) {
    //Expand the widget box when the arrow is clicked.
    var currentUrlInput;

    $(document).on('click', "input[id*='upload_image_button']",
        function( event ) {
            currentUrlInput = $(this).prev();
            tb_show('Upload a feature image', 'media-upload.php?type=image&TB_iframe=true&post_id=0', false);
            window.send_to_editor = function(html) {

                var image_url = $('img',html).attr('src');
                $(currentUrlInput).val(image_url);
                tb_remove();

            };
            return false;
        }
    );


});