var url = 'http://localhost/idea/public/post-photos';
var img_path_prefix = 'http://localhost/idea/public/uploads/thumbs';
var vid_path_prefix =  'http://localhost/idea/public/uploads';
var photo_limit_error=10;
var counter=0;       
var jsonimgpush={};
var jsonarray={}; //array for selected json file on project id
var currentDom={}; // Current json file selected on posttype
var lastaction=0;
var isMulti=0;
var locationmap='';
$( document ).ready(function() {
	
	$('#addLocation  .close').on('click',function(){
	 
		if($('iframe').contents().find("body").find('#pac-input').val()!=''){
			
			swal({   
					title: "",   
					text: "Are you sure to discard location ?",   
					type: "warning",   
					showCancelButton: true,   
					confirmButtonColor: "#f2dede",  
					cancelButtonText: "Add location",  
					confirmButtonText: "Yes, discard it!",   
					 closeOnConfirm: true,   
					 closeOnCancel: true
					}, function(isConfirm){
						if (isConfirm) {
									  $('iframe').contents().find("body").find('#pac-input').val('');
									  locationmap='';
									  doPreview();  
						} else {
							locationmap= $('iframe').contents().find("body").find('#pac-input').val();
							doPreview();  
							
						}
				});
			
			
			 
			 
		}
	});
	
	$('#idVideo').on('click',function(){
	
		$('.vddyn').remove();
		if($('.imagename').length>0 && $('#idImage').css('display')=='inline-block'){
			 
			 $('#addVideo .modal-header-content').prepend('<div   class="alert vddyn  alert-warning">You have already uploaded image. Image will be discarded if you upload video to post!</div>');
		}
	});
	
	$('#idImage').on('click',function(){
	
		$('.ivddyn').remove();
		if($('.imagenamev').length>0 && $('#idVideo').css('display')=='inline-block'){
			 
			 $('#addImage .imgmodal-header-content').prepend('<div   class="alert ivddyn  alert-warning">You have already uploaded video. Video will be discarded if you upload image to post!</div>');
		}
	});
	
	$('#addVideo .finish').on('click',function(){
		
		if($('.imagenamev').length>0 && $('#idVideo').css('display')=='inline-block'){
			 $('.iimg').remove();
			 lastaction=1;
		 
	    }
	});
	
	$('#addImage .finish').on('click',function(){
		
		if($('.imagename').length>0 && $('#idImage').css('display')=='inline-block'){
			 $('.vimg').remove();
			 lastaction=0;
	    }
	});
	
	
	$('#addLocation .finish').on('click', function(event) {
          locationmap= $('iframe').contents().find("body").find('#pac-input').val();
	});
	
	$('#addImage .close').on('click', function(event) {
        
        if($('.imagename').length>0){
		
				swal({   
					title: "Are you sure to discard?",   
					text: "You will not be able to recover this image file!",   
					type: "warning",   
					showCancelButton: true,   
					confirmButtonColor: "#f2dede",  
					cancelButtonText: "Finish Upload",  
					confirmButtonText: "Yes, discard it!",   
					 closeOnConfirm: true,   
					 closeOnCancel: true
					}, function(isConfirm){
						if (isConfirm) {
									  $('.iimg').remove();
									  if($('.imagenamev').length>0 && $('#idVideo').css('display')=='inline-block'){
										lastaction=1;
										
									  }else{
										  lastaction=0;
									  }	
									  doPreview();  
						} else {
							
							if($('.imagename').length>0 && $('#idImage').css('display')=='inline-block'){
									$('.vimg').remove();
									lastaction=0;
							}
							
							
						}
				}); 
		}
        
        
	});
	
	$('#addVideo .close').on('click', function(event) {
        
        if($('.imagenamev').length>0){
		
				swal({   title: "Are you sure to discard?",   text: "You will not be able to recover this video file!",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#f2dede",  cancelButtonText: "Finish Upload",  confirmButtonText: "Yes, discard it!",   closeOnConfirm: true,   
					 closeOnCancel: true }, function(isConfirm){
						      if (isConfirm) {
									  $('.vimg').remove();
									  if($('.imagename').length>0 && $('#idImage').css('display')=='inline-block'){
										lastaction=0;
										doPreview();
									  }else{
										  lastaction=1;
										   doPreview();  
									  }	  
								}else{
								 
									if($('.imagenamev').length>0 && $('#idVideo').css('display')=='inline-block'){
										$('.iimg').remove();
										lastaction=1;
										 doPreview();  
			 
									}
									
								}
				
				
				});
		}
        
        
	});
	
	
	$('#addLocation').on('hidden.bs.modal', function () {
      //doPreview();
	})
	
	$('#content_titlev').on('keyup', function () {
      doPreview();
	})
	
	$('body').on('change,keyup','#content_titlev',function(){ doPreview();  });
	   
	//initAutocomplete();
    'use strict';
    // Change this to the location of your server-side upload handler:

    $('#file-original-v').fileupload({
        url: url,
        dropZone: $('#videoup'),
        dataType: 'json',
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        maxFileSize: 10000000,
        autoUpload: true,
        add: function (e, data) {
			        var vctr=0;
			         $('.vidurl').html(''); $('.vidurl').hide();
			        $('.imagenamev').each(function(){ 
							vctr++;
				
					});
					if(vctr>0){
						
							$('.vidurl').html('You can upload only one video one time.');
                            $('.vidurl').show(); return false;
						
					}
		  
		 //if(ImageArr.length>0) $('#mediaupload').html('<p class="alert alert-success">' + ImageArr.length + ' video Uploaded');
			        
			        
			        
                    var fileType = data.files[0].name.split('.').pop(), allowdtypes = 'MP4, mp4';
                    if (allowdtypes.indexOf(fileType.toLowerCase()) < 0) {
                            $('.vidurl').html('Invalid File, you can upload only MP4 files');
                            $('.vidurl').show();
                            return false;
                    }
                    if(data.originalFiles[0]['size'] > 10000000){
                         $('.vidurl').html('Please upload file size less then 10mb');
                         $('.vidurl').show();
                        return false;
                    }  
                    
                     $('#addVideo').css('pointer-events','none');
                    $('#addVideo').css('opacity','0.8');
                   
                    data.submit();
                },
          progress: function (e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $('.progress-bars').css('width', progress + '%');
                },        
                
        done: function (e, data) {
        	$('#file-falso-1').val('');
        	 $('#addVideo').css('pointer-events','all');
                    $('#addVideo').css('opacity','1');
        	if(data.result.status == 'success'){
				lastaction=1;
				
				//$('#files').html(' <div class="imgconte"><img style="width:100%" src="'+ URLBASE  +'/assets/images/img-drag-drop.png" /><div  class="progress"><div class="progress-bars progress-bar-success" style="width: 0%;"></div></div></div>');
				$('.progress-bars').css('width','0%');
				if(counter==0){
					//$('#videoup').html('');
				}	
				$('#videoup').prepend('<div class="imgconte vimg"><img src="' + URLBASE + '/uploads/video_thumb.png" class="img" /><input type="hidden" class="mimetypev" name="mime-type-v['+counter+']" value="' + data.result.result.mime_type + '"/><input class="imagenamev" type="hidden" name="vid_name['+counter+'][]" value="' + data.result.result.image_url + '" /><a href="javascript:void(0);" class="remove-post-photo"><i class="fa fa-close"></i></a></div>');
				 
				//video_thumb.png
				doPreview();
				counter++;
			}else{
				 $('.vidurl').html('Something Gone wrong. Please try again!');
                 $('.vidurl').show();
			}		
        },
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');


     
    $('#file-original').fileupload({
        url: url,
        dropZone: $('#files'),
        dataType: 'json',
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        maxFileSize: 10000000,
        autoUpload: true,
        add: function (e, data) {
			         $('.imgurl').html('');  $('.imgurl').hide();
                    var fileType = data.files[0].name.split('.').pop(), allowdtypes = 'jpeg,jpg,png,gif';
                    if (allowdtypes.indexOf(fileType.toLowerCase()) < 0) {
                            $('.imgurl').html('Invalid File, you can upload only JPG, PNG, GIF files');
                            $('.imgurl').show();
                            return false;
                    }
                    if(data.originalFiles[0]['size'] > 10000000){
                         $('.imgurl').html('Please upload file size less then 10mb');
                         $('.imgurl').show();
                        return false;
                    }  
                    $('#addImage').css('pointer-events','none');
                    $('#addImage').css('opacity','0.8');
                    data.submit();
                },
        progress: function (e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $('.progress-bar').css('width', progress + '%');
                },        
        done: function (e, data) {
        	$('#file-falso').val('');
			$('#addImage').css('pointer-events','all');  
			$('#addImage').css('opacity','1');
        	if(data.result.status == 'success'){
				lastaction=0;
				//$('#videoup').html(' <div class="imgconte"><img style="width:100%" src="'+ URLBASE  +'/assets/images/img-drag-drop.png" /><div  class="progress"><div class="progress-bars progress-bar-success" style="width: 0%;"></div></div></div>');
				$('.progress-bar').css('width','0px');
				if(counter==0){
					//$('#files').html('');
				}	
				$('#files').prepend('<div class="imgconte iimg"><img src="' + img_path_prefix + '/' + data.result.result.image_url + '" class="img" /><input type="hidden" class="mimetype" name="mime-type['+counter+']" value="' + data.result.result.mime_type + '"/><input type="hidden" name="img_name['+counter+'][]" class="imagename"  value="' + data.result.result.image_url + '" /><a href="javascript:void(0);" class="remove-post-photo"><i class="fa fa-close"></i></a></div>');
				doPreview();
				counter++;
			}else{
				 $('.imgurl').html('Something Gone wrong. Please try again!');
                 $('.imgurl').show();
			}		
        },
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
	
	//removing photo			
	 
	$('body').on('click','#files .remove-post-photo',function(){	
		
		 
		   
		 var ImageArr =[];  ictr=0;
		  $('.imagename').each(function(){
				ImageArr[ictr++]=$(this).val();
				
		  });
		   
		  
		 ImageArrV =[];  ictr=0;
		  $('.imagenamev').each(function(){
				ImageArrV[ictr++]=$(this).val();
				
		  });
		 
		 if(ImageArr.length==0 && ImageArrV.length>0) {
		       lastaction=1;	 
		 }else if(ImageArr.length==0 && ImageArrV.length==0){
			   lastaction=0;	 
		 }
		 
			$(this).parent().remove(); doPreview();
	});
				
	$('body').on('click','#addVideo .remove-post-photo',function(){	
		
		 
		   
		 var ImageArr =[];  ictr=0;
		  $('.imagename').each(function(){
				ImageArr[ictr++]=$(this).val();
				
		  });
		   
		  
		 ImageArrV =[];  ictr=0;
		  $('.imagenamev').each(function(){
				ImageArrV[ictr++]=$(this).val();
				
		  });
		 
		 if(ImageArr.length>0 && ImageArrV.length==0) {
		       lastaction=0;	 
		 }else if(ImageArr.length==0 && ImageArrV.length==0){
			   lastaction=0;	 
		 }
		 
			$(this).parent().remove(); doPreview();
	});
	
// get organization and project
	$('#OrgList').on('change',function(){
		jsonarray={};
		currentDom={};
		if($(this).val()==0){
			$('#ProList').html('<option value="0">-- Project --</option>');
			$('.orgName').html('');
			$('.proName').html('');
			$('.select-channel').hide();
			$('#posttypelist').html(''); 
			$('.alertwarning').html("<p class='alert alert-warning'>Please select Project and Organization first!</p>");
			$('.alertwarning').show();		
			$('.breadupdate').html(': : Publish');
			return false;
		}
		
		$('.orgName').html($("#OrgList option:selected").text());
		$('.breadupdate').html(': : Publish / ' + $("#OrgList option:selected").text() );
		$('#ProList').empty();
		 var org_id = $(this).val();
		 var connect_pro_url = URLBASE + "/connect-pro-list";
		$.ajax({
			type: 'GET',
			url: connect_pro_url,
			data: {'org_id': org_id},
			dataType: 'json',
			success: function (res) {
				//alert(res);
				if($.trim(res)=='[]'){

					 $('#ProList').html('<option value="0">-- Project --</option>');
					 $('.proName').html('');
					 $('.select-channel').hide();
					 $('#posttypelist').html(''); 
					 $('.alertwarning').html("<p class='alert alert-warning'>Please select Project and Organization first!</p>");
					 $('.alertwarning').show();							 
				}else{
					for (var i in res) {
					var option = $('<option/>', {value: i, text: res[i]});
					$('#ProList').append(option);
				   }
				   $('#ProList').trigger("change");
				}
			}
		});
		
	});
			
	// get project channel and post type

	$('#ProList').on('change',function(){
			jsonarray={};
			currentDom={};
			$('.seeicon').hide();
			 $('.createpostsection').css('pointer-events','none')
	 		 $('.createpostsection').css('opacity','0.5');
	 		 locationmap='';
			if($(this).val()==0){
				$('.breadupdate').html(': : Publish / ' + $("#OrgList option:selected").text() );
				$('.alertwarning').html("<p class='alert alert-warning'>Please select Project first!</p>");
				$('.select-channel').hide();
				$('#posttypelist').html(''); 
				$('.alertwarning').show();						  
				return false;
			}
			
			$('.proName').html($("#ProList option:selected").text());
			$('.alertwarning,.select-channel').hide();
			$('.accounttype').html('');
			$('.breadupdate').html(': : Publish / ' + $("#OrgList option:selected").text()  + ' / ' + $("#ProList option:selected").text());
			 var org_id = $(this).val();
			var connect_channel_url = URLBASE + "/post/getProjectChannel/"+$(this).val();
				$.ajax({
					type: 'GET',
					url: connect_channel_url,
					dataType: 'json',
					success: function (res){ 
						console.log(res);
							jsonarray=res;
							$('.select-channel').hide();
							$('#posttypelist').html('');
							if(res!=''){
								 
								 var ctr=0;
								 //console.log(res);
								  $.each(res, function(i, obj) {
											 $('#channel_'+i).show();
											// console.log(obj);
											 if(ctr==0) ctr=i;
											 console.log(ctr);
											 var flag=1;
											 $.each(obj, function(j, obj1) {
												  if(flag==1){
														var count=0;
														var name='';
														var pid='';
														
														$.each(obj1, function(k, obj2) {
															
															count =Object.keys(obj).length;
															var post='';
															if(count==1){
															  post=j;	
															}
															name=obj2.name; pid=k;
															
															var showpost=(count)>1? 1 : 0;
															$('#channel_'+ i +' .accounttype').append('<a  data-user="'+ pid + '" data-show="' +  showpost + '" data-post="'+ post +'" class="deactive" data-id="'+ i +'" href="javascript:void(0)">'+ name +'</a>');
														    
														    
														    
														});
														flag=0;
												   }
											});
								  });
								  
								  $.each([ 2,3,6,8,1,5], function( index, value ) {
									  if($('#channel_'+value).css('display')=='block') { $('#channel_'+value).click(); return false;}
 								  });
									return false;
								  
								 
							}else{
								jsonarray='';
								$('.alertwarning').html("<p class='alert alert-warning'>You have not any account in this project!</p>");
								$('.alertwarning').show();
							}
					}
			});
			
		});
				
		$('body').on('click','.accounttype a',function(){
				currentDom={};
				if($(this).hasClass('active')){
						$(this).removeClass('active');
				}else{
						$(this).addClass('active');
				}
				$('#posttypelist').html(''); 
				if($('.accounttype .active').length > 1){
					
					$('#posttypelist').html('');
					isMulti=1;
					
					    // var json =jsonarray[netid][user][0];	
						 $('.uppost').html('');
						 createpost($('.accounttype .active')); 
					
					
				}else if($('.accounttype .active').length ==0){
					isMulti=0;
					$('.seeicon').hide(); $('.uppost').html('');
					$('#collapseThree').css('pointer-events','none');
					$('#collapseThree').css('opacity','0.5');
					$('#posttypelist').html(''); 
					//$('.breadupdate').html(': : Publish / ' + $("#OrgList option:selected").text()  + ' / ' + $("#ProList option:selected").text());
					
				}else{
					
					isMulti=0;
					var netid=$('.accounttype .active').attr('data-id');									
					var user=$('.accounttype .active').attr('data-user');
					var postid=$('.accounttype .active').attr('data-post');
					
					if($('.accounttype .active').attr('data-show')==1){
						 console.log(jsonarray);
						 var json = jsonarray[netid];
						 $.each(json, function(i, obj1) {
							    //var flag=true;
								 //$.each(obj1, function(i, obj) {
								//	 if(flag){
										var obj=obj1[$('.accounttype .active').attr('data-user')];
										$('#posttypelist').append('<li data-id="'+ obj.network_id +'"  role="presentation" class="deactive"><a href="javascript:void(0)" data-id="'+ obj.network_id +'" data-post="'+ obj.id +'" data-user="'+ obj.channel_id  +'" aria-controls="'+ obj.id +'" role="tab" data-toggle="tab">'+ obj.title +'</a></li>');
									  
									  // flag=false;	 
									// }	
								});
						 
						 $('#posttypelist li:first-child').trigger("click");	 
							
					}else{
						
						 var json =jsonarray[netid];
						  $.each(json, function(i, obj1) {
							  postid=i;
						  });	  
						 $('.uppost').html('');
						 
						 createpost($(this),netid,user,postid);
					}
				}
				

		});
			
		$('body').on('click','.text-icon',function(){
			$(this).parent().parent().remove();
		})	
				
		$('body').on('click','#posttypelist li',function(){			
				currentDom={}; 
				var netid=$(this).children().attr('data-id');
				var user=$(this).children().attr('data-user');
				var post=$(this).children().attr('data-post');
				$('#posttypelist li').removeClass('active');
				$(this).addClass('active');
				//console.log(jsonarray[netid][post]);
				
				$('.uppost').html(jsonarray[netid][post][user].title);
				$('.uppost').show();
				createpost($(this),netid,user,post);
		});	
			 
			 	
			 
			 $('#idLink').on('click',function(){
				  $('#url_text').val('');
				  $('#content_url').val('');
		    });
			 
			 $('#idImage').on('click',function(){
				  $('.addimgurl').val('');
				  $('.imgurl').html('');
				  $('.imgurlmsg').html('');
		    });
			 
			 $('.tagsubmit').on('click',function(){
				  if($.trim($('#usertag').val())==''){
					  $('.tagmsg').html('Please enter tag'); $('.tagmsg').show();
				  }else{
					$('.tagit').append('<li class="tagit-choice ui-widget-content ui-state-default ui-corner-all tagit-choice-editable"><span class="tagit-label">'+ $.trim($('#usertag').val()) +'</span><a class="tagit-close"><span class="text-icon">×</span>  ');
					$('#usertag').val('');
					doPreview();
					$('#addTags .close').click();
				  }	
		    });
				 
			 $(document).on('blur', '[id^=content_url],.addimgurl', function () {
				var input = $(this);
				var val = input.val();
				if (val && !val.match(/^http([s]?):\/\/.*/)) {
					input.val('http://' + val);
				}
			 }); 
			 
			 
			 
    $(document).on('click', '[id^=process_url]', function () {
            var current_url = $('#content_url').val();
            if(current_url){
				$(this).css ('pointer-events','none');
				var shortner_name = $('#url_shorten').val();
				 $.ajax({ 
					url: URLBASE + "/shorten",
					data: {'content_url': current_url,'shortner': shortner_name},
					type: 'post',
					success: function (response) {addLink
							if (response.status == 'success') {
							 if($.trim($('#url_text').val())==''){
								 tinymce.activeEditor.execCommand('mceInsertContent', false, ' ' + response.response);
							 }else{
								 tinymce.activeEditor.execCommand('mceInsertContent', false, ' <a href="'+response.response+'">'+ $.trim($('#url_text').val()) +'</a>');
							 }	 
								doPreview();
								$(this).css ('pointer-events','all');
								$('#addLink .close').click();
							}else{
								$('.urlmsg').html('Please enter valid Url'); $('.urlmsg').show();
								$(this).css ('pointer-events','all');
								$('#content_url').val('');
							} 
					}
				});
            }else{
				$('.urlmsg').html('Please enter valid Url'); $('.urlmsg').show();
			}	
    });			 
			 
    $(document).on('click', '.addimgsave', function () { 
			$('.imgurlmsgs').html('');
			$('.imgurlmsgs').hide();
           var  current_url = $('.addimgurl').val();
            if(current_url){
				$(this).css ('pointer-events','none');
				var shortner_name = $('.addimgselect').val();
				/* $.ajax({ 
					url: URLBASE + "/shorten",
					data: {'content_url': current_url,'shortner': shortner_name},
					type: 'post',
					success: function (response) { 
							if (response.status == 'success') { */
								 
								  $.ajax({ 
									url: URLBASE + "/post/imagelink",
									data: {'url_text':current_url},
									type: 'get',
									success: function (responses) {
										responses=eval('('+ $.trim(responses) +')');
											if (responses.success == 1) {
												
												$('#files').prepend('<div class="imgconte"><img src="' + img_path_prefix + '/' + responses.img + '" class="img" /><input type="hidden" class="mimetype" name="mime-type['+counter+']" value="IMAGE"/><input type="hidden" name="img_name['+counter+'][]" class="imagename"  value="' + responses.img + '" /><a href="javascript:void(0);" class="remove-post-photo"><i class="fa fa-close"></i></a></div>');
												doPreview();
												counter++;
												$('.imgurlmsgs').html('Image  has uploaded successfully!'); $('.imgurlmsgs').show();
												$('.addimgurl').val('');
												$(this).css ('pointer-events','all'); 
											}else{
												
												$('.imgurlmsg').html('Please enter valid image Url'); $('.imgurlmsg').show();
												$(this).css ('pointer-events','all');  	
												$('.addimgurl').val('');
											}
										}			
									});		
								 
								 
								 
							/*}else{
								$('.imgurlmsg').html('Please enter valid Url'); $('.imgurlmsg').show();
								$(this).css ('pointer-events','all');  	
								$('.addimgurl').val('');
							} */
				//	}
				//});
            }else{
				$('.imgurlmsg').html('Please enter valid Url'); $('.imgurlmsg').show();
			}	
    });
    
});

function createpost(dom,netid,user,post){
	
	 $('.seeicon').hide();
	 $('.uppost').html('');
	 $('.cretepostpage .post_Title').hide();
	 $('.addimgvd').hide();
	 $('.cretepostpage #mceu_9').hide();
	 $('.createpostsection').css('pointer-events','all');
	 $('.createpostsection').css('opacity','1');
	 $('#url_text').hide();
	 $('.urlmsg').hide();
	 $('.tagmsg').hide();
	 $('.imgurlmsg').hide();
	 $('.imgurl').hide();
	 $('.imgurl').html('');
	 $('.imgurlmsg').html('');
	 
	 
	 
 
	   var setting=jsonarray;
	 
	 
	   $('.cretepostpage #mceu_9').hide();
	   $('.cretepostpage #idImage').hide();
	   $('.cretepostpage #idLink').hide();
	   $('.cretepostpage .post_Title').hide();
	   $('.cretepostpage #idVideo').hide();
	   $('.cretepostpage #idTags').hide();
	   $('#tagit').hide();    
	   $('.cretepostpage #idLocation').hide();
	   $('#url_text').hide();
	   currentDom={};
	   currentDom.is_editor=0;
	   currentDom.is_image=0;
	   currentDom.is_link=0;
	   currentDom.is_title=0;
	   currentDom.is_video=0;
	   currentDom.is_tag=0;
	   currentDom.is_location=0;
	   currentDom.max_editor_char=0;
	   currentDom.youtube=0;
	   currentDom.project_id='';
	   currentDom.network_id={};
	   currentDom.channel_id={};
	   currentDom.post_id={};
	   currentDom.obj={};
	   
	   if(isMulti==0){
		  
		   var setting=jsonarray[netid][post][user];
		   currentDom.obj= setting;
		   var ctr=0;
		   console.log(setting);
			currentDom.project_id=setting.project_id;
			currentDom.network_id[ctr]=setting.network_id;
			currentDom.post_id[ctr]= setting.id;
			currentDom.channel_id[ctr++]= setting.channel_id;
			
			if(setting.is_editor==1) { currentDom.is_editor=1; $('.cretepostpage #mceu_9').show(); }
			if(setting.is_image==1) { currentDom.is_image=1; $('.cretepostpage #idImage').show(); }
			if(setting.is_link==1)  { currentDom.is_link=1; $('.cretepostpage #idLink').show(); }
			if(setting.is_title==1)  { currentDom.is_title=1; $('.cretepostpage .post_Title').show(); }
			if(setting.is_video==1)  { currentDom.is_video=1; $('.cretepostpage #idVideo').show(); }
			if(setting.is_tag==1)  {
				$('.cretepostpage #idTags').show();
				$('#tagit').show();    currentDom.is_tag=1;
			}    
			if(setting.is_location==1)  {  currentDom.is_location=1; $('.cretepostpage #idLocation').show(); }
			if(setting.network_id==3)  $('#url_text').show();
			if(setting.network_id==5) currentDom.youtube=1;
			//console.log(nid);
			$('#postch_'+setting.network_id).show();
			if(currentDom.max_editor_char <  setting.max_editor_char) currentDom.max_editor_char=setting.max_editor_char;
 
		   
	   }else{
			  ctr=0;
			  $( ".accounttype .active" ).each(function( index ) {
					var nid= $(this).attr('data-id');
					var usr= $(this).attr('data-user');
					var post= '';
					//var settings=jsonarray[nid][usr];
					var json =jsonarray[nid];
					//if()
					  var setting={};
					  $.each(json, function(i, obj) {
						      var obj1=obj[usr];
						      if(obj1.is_default==1){
									post=obj1.id
									setting=obj1;
							  }
						   
					  });
					
					 
					
					currentDom.project_id=setting.project_id;
					currentDom.network_id[ctr]=setting.network_id;
					currentDom.channel_id[ctr]= setting.channel_id;
					currentDom.post_id[ctr++]= post;
					
					if(setting.is_editor==1) { currentDom.is_editor=1; $('.cretepostpage #mceu_9').show(); }
					if(setting.is_image==1) { currentDom.is_image=1; $('.cretepostpage #idImage').show(); }
					if(setting.is_link==1)  { currentDom.is_link=1; $('.cretepostpage #idLink').show(); }
					if(setting.is_title==1)  { currentDom.is_title=1; $('.cretepostpage .post_Title').show(); }
					if(setting.is_video==1)  { currentDom.is_video=1; $('.cretepostpage #idVideo').show(); }
					if(setting.is_tag==1)  {
						$('.cretepostpage #idTags').show();
						$('#tagit').show();    currentDom.is_tag=1;
					}    
					if(setting.is_location==1)  {  currentDom.is_location=1; $('.cretepostpage #idLocation').show(); }
					if(nid==3)  $('#url_text').show();
					if(nid==5) currentDom.youtube=1;
					console.log(nid);
					$('#postch_'+nid).show();
					if(currentDom.max_editor_char <  setting.max_editor_char) currentDom.max_editor_char=setting.max_editor_char;
			  }); 
		   
	   }
	   
	   //$('.breadupdate').html(': : Publish / ' + $("#OrgList option:selected").text()  + ' / ' + $("#ProList option:selected").text() + ' / ' +   );
	   
	   
		   doPreview(1);
		   
}

    	 
  function doPreview(e){
	  	  
		 var html=tinyMCE.activeEditor.getContent({format : 'raw'});
		 var posthtm=html;
		 html=html.replace(/(<([^>]+)>)/ig, ''); 
		 var bal=currentDom.max_editor_char - html.length;
		  $('.count-character').html(currentDom.max_editor_char);
		  $('.count-facebook').html(bal);
		  var setting=currentDom; 
		  if(setting.is_editor==0) {
			 posthtm=html.replace(/(<([^>]+)>)/ig, ''); 
			 if(e==1){
				tinyMCE.activeEditor.setContent(posthtm);
				tinyMCE.activeEditor.selection.collapse(false);
				tinyMCE.activeEditor.focus();
			  }
		  }
		  
		 if(currentDom.max_editor_char<html.length) {     
			var ArTmp =  posthtm.match(new RegExp('.{1,' + currentDom.max_editor_char + '}', 'g'));
			ArTmp[1] = '<span style="color:#F00">'+ArTmp[1]+'</span>';
			var newHTML = ArTmp[0]+ArTmp[1];
			tinyMCE.activeEditor.setContent(newHTML);
			//tinyMCE.activeEditor.selection.collapse(false);
			posthtm=newHTML;
		 }
		  
		  $('#mediaupload').html('');
		   var ImageArr =[]; var ictr=0;
		  if(currentDom.is_image==1 && lastaction==0) {
			 
			  $('.imagename').each(function(){
					ImageArr[ictr++]=$(this).val();
					
			  });
		 
			 if(ImageArr.length>0) $('#mediaupload').html('<p class="alert alert-success">' + ImageArr.length + ' images Uploaded');
		  }
		  
		  if(currentDom.is_video==1 && lastaction==1) {
			 ImageArr =[];  ictr=0;
			  $('.imagenamev').each(function(){
					ImageArr[ictr++]=$(this).val();
					
			  });
			  
			 if(ImageArr.length>0) $('#mediaupload').html('<p class="alert alert-success">' + ImageArr.length + ' video Uploaded');
		  }
		var TmpHTML = '';
		
		var title = $("#content_titlev").val();
	//    alert(title);
		TmpHTML = posthtm; //tinyMCE.activeEditor.getContent({format : 'raw'});
		
		
		
		if(currentDom.is_title==1) {
			TmpHTML ='<h2 class="tumblr_title">'+title+'</h2>'+ TmpHTML; 
		}
		
		 TmpHTML = ChangeUrl(TmpHTML);
		
		
		if(currentDom.youtube==1){
			
			imghtml= '<div class="youtubebox clearfix">';
					   
				if(ImageArr.length>0 && lastaction==1  && setting.is_video==1) {
						for (i = 0; i < ImageArr.length; i++) {
								imghtml +=' <div class="youtubepostvdo"><video   controls>  <source src="'+URLBASE+'/uploads/'+ImageArr[i]+'"> Your browser does not support the video tag.</video></div>'
						}
				}
				
				imghtml +=         '<div class="youtubepostDetail">' +                        '<h2><a href="#">'+  title +'</a></h2>' +
									'<p>'+ ChangeUrl(posthtm)  +'</p>'+
						'</div>'+
					'</div>';
			
			 $('#preview #twitter').html(imghtml);
			
			
			
		}else{        
		
		 
				var tagimg=[];
				   
				  if(currentDom.is_tag==1) {
					   
					  var ctr=0;
					  $('.tagit-label').each(function(){
							//data.tags= [$(this).html()];
							//tagimg[ctr++]=$(this).html();
							TmpHTML += ' <a href="javascript:void(0)">'+ $(this).html() +'</a>';
					  });
					  
					  
				  }
				  
				  
				  if(currentDom.is_location==1) {
					  
					 
					if($('iframe').contents().find("body").find('#pac-input').val()!=''){
						TmpHTML += ' <br>  — at <a href="javascript:void(0)">' + $('iframe').contents().find("body").find('#pac-input').val() + '</a>';
					} 
					  
				  }
				
				 
				if(ImageArr.length>0) {

				for (i = 0; i < ImageArr.length; i++) {
					
					if(lastaction==0  && currentDom.is_image==1){
						TmpHTML += '<div class="tmblrimg"><img src="'+URLBASE+'/uploads/thumbs/'+ImageArr[i]+'"></div>';
					}else if(lastaction==1  && currentDom.is_video==1){
						TmpHTML += '<div class="tmblrimg"><video   controls>  <source src="'+URLBASE+'/uploads/'+ImageArr[i]+'"> Your browser does not support the video tag.</video></div>';
					}    
				}
					
				}
				
				 $('#preview #twitter').html(TmpHTML);
		}
		
		
		  
	  
	  
  }
  


  function doPost(action){
	  
	 // alert(action);
	  var setting=currentDom;
	 console.log(setting);
	  var data={};
 
	  $('.errormsg').html('') ;
	  $('.errormsg').hide();
	  
	  if(setting.project_id.length==0 || setting.channel_id.length==0 || setting.network_id.length==0 || setting.post_id.length==0){
		  
			  $('.errormsg').html('Channels not found!!') ;
			  $('.errormsg').show(); return false;
	  }
	  
	  
	  //if(setting.is_editor==1) data.content= tinyMCE.activeEditor.getContent({format : 'raw'});
	  if(setting.is_editor==-1){
		   data.content_text='';
		    
		   
	   }else if(setting.is_editor==1)	   {
		   var html= tinyMCE.activeEditor.getContent({format : 'raw'});
		   html=html.replace('<p><br data-mce-bogus="1"></p>','');
		  
		   data.content_text= html;
		    
	   }else{
		  var html=tinyMCE.activeEditor.getContent({format : 'raw'});
			html=html.replace(/(<([^>]+)>)/ig, '');
			data.content_text= html;
			 
	   }
	    
	   if(isMulti==0  ){
		
			 if($.trim(data.content_text)=='' && setting.id!=8 && setting.id!=5){ 
		   
		   	  $('.errormsg').html('Please enter content to post') ;
			  $('.errormsg').show(); return false;
		   
		    }
		   
	   }else{
 
			if($.trim(data.content_text)=='' ){ 
 
		   	   $('.errormsg').html('Please enter content to post') ;
			   $('.errormsg').show(); 
			   return false;
		   
		    }
		   
	   }	   
	   	   
	  var ictr=0; var imime=0;
	  var arrimg=[];
	  if(setting.is_image==1 && lastaction==0) {
		 
		  $('.imagename').each(function(){
				arrimg[ictr++]=$(this).val();
				
		  });
	  }
	  
	  if(setting.is_title==1 ){
		   data.caption=$('#content_titlev').val();
		   data.title=$('#content_titlev').val();
	  }
	  if(setting.is_video==1 && lastaction==1) {
		   
		  $('.imagenamev').each(function(){
				arrimg[ictr++]=$(this).val();
		  });
		  
		  
		  
	  }
	  
	  data.img_name=arrimg;
	  var tagimg=[];
	  data.mime_type=(lastaction==1)? 'Video' : 'Image' ;
	  if(setting.is_tag==1) {
		   
		  var ctr=0;
		  $('.tagit-label').each(function(){
				//data.tags= [$(this).html()];
				tagimg[ctr++]=$(this).html();
		  });
		  
	  }
	  data.tags= tagimg;
	  if(setting.is_location==1) {
		  data.map=locationmap;
		if(locationmap!=''){
			
			 
					 
					 var address=locationmap;
					$.ajax({
					  url:"http://maps.googleapis.com/maps/api/geocode/json?address="+address+"&sensor=false",
					  type: "POST",
					  success:function(res){
						 data.lat=res.results[0].geometry.location.lat;
						 data.lang=res.results[0].geometry.location.lng;
					  }
					});
				
			 
		}
		  
	  }
	  
	  
	  if(isMulti==0){
		  if(setting.post_id[0]==9 || setting.post_id[0]==5){
			 
			 
			  if(lastaction==0 || arrimg.length==0){
				  
				  $('.errormsg').html('Please select video to post') ;
				  $('.errormsg').show(); return false;
			   
				  
			  }	  
		  }else if(setting.post_id[0]==8){
			  
			  if(lastaction!=0 || arrimg.length==0){
				  
				  $('.errormsg').html('Please select image to post') ;
				  $('.errormsg').show(); return false;
			   
				  
			  }
			  
			  
				
		  }else if(setting.post_id[0]==16){
			  
			  //alert(data.content_text);
			  if (data.content_text.indexOf("http")==-1) {
		  
				  $('.errormsg').html('Please insert link to post') ;
				  $('.errormsg').show(); return false;
		  
			  }
				  
		  }
	  }else{
		  
		  //console.log(JSON.stringify(setting.post_id.toString())); 
		  
			$.each(setting.post_id, function(i,obj) {
			  if (obj == 9) {   
				
				  $('.errormsg').html('Please upload video to post into youtube') ;
				  $('.errormsg').show(); return false;				
				  
			  }
			});  
		  
		  
	  }	  
	   
	   data.channel_id= setting.channel_id;
	   data.posttype_id= setting.post_id;
	   data.network_id= setting.network_id;
	   data.project_id= setting.project_id;
	   data.action= action;
	   console.log(data);
	   if(action=='schedule'){
		    data.schedule_date=$('#datepicker input').val();
		    data.schedule_time=$('#timepicker input').val();
		    
		    if(data.schedule_date=='' || data.schedule_time==''){
				$('.errormsg').html('Please select date/time to post') ;
			    $('.errormsg').show(); return false;
			}
		    
	   }
	   //console.log(data);
	   
	   $('.page-header,.breadcrumb,.box-content').css('pointer-events','none');
	   $('.page-header,.breadcrumb,.box-content').css('opacity','0.4');
	   $('.loader ').show();
	   
	  //console.log(data);
	  	/*	$.ajax({
			type: 'post',
			url: URLBASE + '/post/savestandarddemo',
			data: data,
		 
			success: function (res) {
				      $('.loader ').hide();
					 $('.page-header,.breadcrumb,.box-content').css('pointer-events','all');
					   $('.page-header,.breadcrumb,.box-content').css('opacity','1');
					  window.location.href=URLBASE+"/content-list/"+data.project_id; 
			}
		});  */
	  
	  
  } 	   
	  	
		
function ChangeUrl(txt) {    
    var regex = /[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi; 
    if(regex.test(txt)) {
      var matches = txt.match(regex);
      for(var match in matches) {    
        element = matches[match];    
        var findElement = 'href="'+element+'"';
        var index = txt.indexOf(findElement) ;
        if(index==-1) { 
            var newurl = '';        
            newurl = '<a href="'+element+'">'+element+'</a>'; 
            txt = txt.replace(element, newurl);            
        }        
      } 
    } 
    return txt; 
}
