

$(function () {
    'use strict';

    // Initialize the jQuery File Upload widget:
    $('#audio-upload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: 'http://localhost/idea/audio-uploader/php/index.php',
	        
    });

    // Enable iframe cross-domain access via redirect option:
    $('#audio-upload').fileupload(
        'option',
        'redirect',
        window.location.href.replace(
            /\/[^\/]*$/,
            '/cors/result.html?%s'
        )
	   
    );
        // Load existing files:
        $('#audio-upload').addClass('fileupload-processing');
        $.ajax({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: $('#audio-upload').fileupload('option', 'url'),
            dataType: 'json',
            context: $('#audio-upload')[0]
        }).always(function () {
            $(this).removeClass('fileupload-processing');
        }).done(function (result) {
            $(this).fileupload('option', 'done')
                .call(this, $.Event('done'), {result: result});
        });
 

});