/*
 * jQuery File Upload Plugin JS Example 8.9.1
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/* global $, window */
$(window).load(function() {
$(function () {
    'use strict';
   
    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
	    
	
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: $('#baseurl').val()+'/idea/multiFileUpload',
        maxFileSize: 500*1000
    });

    // Enable iframe cross-domain access via redirect option:
    $('#fileupload').fileupload(
        'option',
        'redirect',
        window.location.href.replace(
            /\/[^\/]*$/,
            '/cors/result.html?%s'
        )
    );

  
    // Load existing files:
    $('#fileupload').addClass('fileupload-processing');   
    console.log($('#fileupload').fileupload('option', 'url'));
    $.ajax({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: $('#fileupload').fileupload('option', 'url'),
        dataType: 'json',
        context: $('#fileupload')[0],	  
    }).always(function () {
        $(this).removeClass('fileupload-processing');
    });
   

});


});
