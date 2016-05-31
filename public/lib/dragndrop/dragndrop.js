(function( $ )
{
    $.fn.BuilDropZone = function() 
    {
        $( this ).each(function() 
        {
            if( typeof FileReader !== 'undefined' && Modernizr.draganddrop)
                new DropZone( $(this), dragAndDropConfig);
            else
                alert("You are using legacy browser.Please update your browser before use it.");
        });
        return false;
    }; 
})( jQuery );

var amazon_file_id_list=[];

function DropZone(dropZoneItem,config)
{
    SetDefaultForUnsetValueOfConfig();
    
    var html = "<div class='dragndrop'><span>Drop file to attach, or <a class='browse' href='javascript:void(0);'>browse</a><div class='dropzoneFileList'><input type='file' class='clickFileUploader' style='display:none' multiple></span></div></div>";
    var dropZoneBindedHtml = $(html);
    
    dropZoneBindedHtml.find('.browse').click(function(){
        dropZoneBindedHtml.find('.clickFileUploader').click();
    });
    
    var fileBrowserElement = dropZoneBindedHtml.find('.clickFileUploader');
    dropZoneItem.html(dropZoneBindedHtml);
    
    var dropZone = dropZoneItem.get( 0 );
    dropZone.addEventListener('dragover', HandleDragOver, false);
    dropZone.addEventListener('drop', HandleFileSelect, false);
    fileBrowserElement.get( 0 ).addEventListener('change', HandleFileSelectClick, false);
    
    
    function SetDefaultForUnsetValueOfConfig()
    {//
        if (typeof config.get_amazon_credential_url === 'undefined') 
            config.get_amazon_credential_url = 'get_amazon_credential.php';
        if (typeof config.allowed_extension_list === 'undefined') 
            config.allowed_extension_list = '*';
    }
    
    function HandleFileSelectClick(evt) 
    {
        //fileListHolder = $(this).closest('.dragndrop').find('.dropzoneFileList');
        var files = evt.target.files; // FileList object
      
        //crazy staff.need to push in another array before process
        var fileArr = [];
        for (var index = 0; index < files.length; index++)
            fileArr.push(files[index]);
        for (var index = 0; index < fileArr.length; index++)
            GenerateFileInfoUIAndSendFile( fileArr[index] );
    }
    
    function HandleFileSelect(evt) 
    {
        $(this).removeClass('dragover');
        evt.stopPropagation();
        evt.preventDefault();
        
        //var fileListHolder = $(this).find('.dropzoneFileList');
        var files = evt.dataTransfer.files; // FileList object.
        var output = [];
        for (var i = 0, f; f = files[i]; i++) 
        {
            GenerateFileInfoUIAndSendFile( files[i] );
        }
    }
    
    
    function GenerateFileInfoUIAndSendFile(fileInput)
    {
        var fileListHolder = dropZoneBindedHtml.find('.dropzoneFileList');
        var fileName = fileInput.name;
        var extension = fileName.split('.').pop();
        var iconUrl = "";
        
        var configFileiconList = "";
        var imageExtensionList = config.image_extension_list;

        var ifImage = false;
        for( var index=0;index<imageExtensionList.length;index++)
        {
            if(imageExtensionList[index].toUpperCase() === extension.toUpperCase())
            {
                ifImage = true;
                break;
            }
        }
        if(!ifImage)
        {
            var iconList = config.preview_icon_list;
            for(var index=0;index<iconList.length;index++)
            {
                var oneFileTypeIcon = iconList[index];
                if (oneFileTypeIcon.extension instanceof Array)
                {
                    for(var jindex=0;jindex<oneFileTypeIcon.extension.length;jindex++)
                    {
                        if(oneFileTypeIcon.extension[jindex].toUpperCase() === extension.toUpperCase())
                        {
                            iconUrl = oneFileTypeIcon.icon_url;
                            break;
                        }
                    }
                    if(iconUrl.length>0)
                        break;
                }
                else 
                {
                    if(oneFileTypeIcon.extension.toUpperCase() === extension.toUpperCase())
                    {
                        iconUrl = oneFileTypeIcon.icon_url;
                        break;
                    }
                }
            }
        }

        //debugger;
        ////allowed_extension_list
        var allowedExetensionList = config.allowed_extension_list;
        if(allowedExetensionList!="*")
        {
            var found = false;
            for(var index=0;index<allowedExetensionList.length;index++)
            {
                var oneFileExtension = allowedExetensionList[index];
                if(oneFileExtension.toUpperCase() === extension.toUpperCase())
                {
                    found = true;
                    break;
                }
            }
            if(!found)
            {
                fileInfoHtml = '<div class="uploadedfileDetails"><div class="FilePreview"><img src="'+iconUrl+'"></div><div class="fileDetail"><p style="color: red; margin: 6px 0px;">File type not supported</p><strong style="margin-right: 15px;">' + escape(fileInput.name) + '</strong>'+ '' +BeautifyFileSize(fileInput.size) +'<p class="message"></p><p class="exception"></p>'+ '</div></div>';
                var bindedElement = $(fileInfoHtml);
                fileListHolder.append(bindedElement);
                
                dropZoneBindedHtml.find(".clickFileUploader").val("");
                setTimeout(function(){ 
                            bindedElement.slideUp('slow','linear',function()
                                { 
                                    bindedElement.remove(); 
                                }); 
                            }, 4000);
                
                return;
            }
        }
        
        if (typeof config.file_size_restrictions !== 'undefined') 
        {
            var maximumAllowedSize = config.file_size_restrictions * 1024*1024;
            if(fileInput.size>maximumAllowedSize)
            {
                fileInfoHtml = '<div class="uploadedfileDetails"><div class="FilePreview"><img src="'+iconUrl+'"></div><div class="fileDetail"><p style="color: red; margin: 6px 0px;">File Size is greater than maximum allowed file size</p><strong style="margin-right: 15px;">' + escape(fileInput.name) + '</strong>'+ '' +BeautifyFileSize(fileInput.size) +'<p class="message"></p><p class="exception"></p>'+ '</div></div>';
                var bindedElement = $(fileInfoHtml);
                fileListHolder.append(bindedElement);
                
                dropZoneBindedHtml.find(".clickFileUploader").val("");
                setTimeout(function(){ 
                            bindedElement.slideUp('slow','linear',function()
                                { 
                                    bindedElement.remove(); 
                                }); 
                            }, 4000);
                
                return;
            }
        }

        var fileInfoHtml = '<div><div class="uploadedfileDetails"><div class="FilePreview"><img src="'+iconUrl+'"></div><div class="fileDetail"><progress class="fileProgressBar" max="100" value="0"></progress><strong style="margin-right: 15px;">' + escape(fileInput.name) + '</strong>'+ '' +BeautifyFileSize(fileInput.size) +'<p class="message"></p><p class="exception"></p>'+ '<button type="button" class="cancelUpload">Cancel</button><input type="hidden" class="amazon_file_id" value=""></div></div><div class="overlayEffect hideOverlayEffect"></div><div class="loader hideOverlayEffect">'+'<img src="images/loader.gif" alt="">'+'</div></div>';
        var bindedElement = $(fileInfoHtml);
        
        if(ifImage)
        {
            var reader = new FileReader();
            reader.addEventListener("load", function () {

                bindedElement.find('img').attr('src' , reader.result);
            }, false);

            reader.readAsDataURL(fileInput);
        }

        fileListHolder.append(bindedElement);
        SendFile(fileInput,bindedElement);
    }
    
    function SendFile(file,bindedElement) 
    {
        var progressbarElement = bindedElement.find(".fileProgressBar");
        var uploadStatus = '';
        
        $.post( config.get_amazon_credential_url, {filename: file.name}, function(response)
        {
            var amazonFileName = response.form_parameters.key;
            var formData = new FormData();
            $.each(response.form_parameters, function(k, v){
                formData.append(k, v);
            });
            
            var amazonBucketName = response.bucket_name;
            
            var uri = "https://"+ amazonBucketName +".s3.amazonaws.com/";
            var xhr = new XMLHttpRequest();
            
            xhr.upload.addEventListener('progress',function(ev){
                var progressPercentage = parseInt( (ev.loaded / ev.total * 100));
                progressbarElement.val(progressPercentage);
                //progressbarElement.css("width", progressPercentage+"%");
            }, false);
            
            xhr.upload.addEventListener("error", function transferFailed(evt) 
            {
                progressbarElement.hide();
                bindedElement.find(".exception").html("<span>"+"Error Occured"+"</span>");
                uploadStatus = 'error_occured';
            });
            
            bindedElement.find(".cancelUpload").click(function(){
                if(uploadStatus=='uploading')
                {
                    xhr.abort();
                    bindedElement.find( ".overlayEffect" ).removeClass( "hideOverlayEffect" );
                    bindedElement.find( ".loader" ).removeClass( "hideOverlayEffect" );
                    bindedElement.find(".exception").html("<span>"+"Canceled"+"</span>");
                    bindedElement.slideUp('slow','linear',function(){ bindedElement.remove(); });
                    uploadStatus = 'aborted';
                }
                else if(uploadStatus=='upload_completed' || uploadStatus=='error_occured')
                {
                    bindedElement.find( ".overlayEffect" ).removeClass( "hideOverlayEffect" );
                    bindedElement.find( ".loader" ).removeClass( "hideOverlayEffect" );
                    var fileDeleteUrl = "delete_file_from_amazon.php";
                    if (typeof config.delete_file_file_url !== 'undefined') 
                        fileDeleteUrl = config.delete_file_file_url;

                    var amazon_file_id = bindedElement.find('.amazon_file_id').val();
                    
                    $.post( fileDeleteUrl, {amazon_file_id: amazon_file_id}, function(response)
                    {
                        response = jQuery.parseJSON(response);
                        if(response.success)
                        {
                            bindedElement.slideUp('slow','linear',function(){ bindedElement.remove(); });
                            //file_delete_event
                            if (typeof config.delete_file_file_url !== 'undefined')
                                config.file_delete_event( file.name,amazonFileName, amazonBucketName );
                        }
                    });
                }
            });
            
            xhr.open("POST", uri, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 201) 
                {
                    if (typeof config.file_upload_complete_event !== 'undefined') 
                    {
                        config.file_upload_complete_event( file.name, file.size,amazonFileName, amazonBucketName );
                    }
                    
                    
                    if (typeof config.post_uploaded_file_info_url !== 'undefined') 
                    {
                        uploadStatus = 'upload_completed';
                        ////call function where to be uploadedfileDetails

                        added_employee_id = $('#added_employee_id').val();

                        $.post( config.post_uploaded_file_info_url , {file_name: file.name, file_size: file.size, amazon_bucket_file_name:amazonFileName, amazon_bucket_name: amazonBucketName, added_employee_id:added_employee_id}, function(response)
                        {
                            response = jQuery.parseJSON(response);

                            //progressbarElement.css("width", "100%");
                            progressbarElement.val(100);
                            if(response.success)
                            {
                                bindedElement.find('.amazon_file_id').val(response.file_info.amazon_file_id);
                            }
                            bindedElement.find(".message").html("<span>"+"Uploaded"+"</span>");
                            bindedElement.find(".cancelUpload").html('Delete');

                            amazon_file_id_list.push(response.file_info.amazon_file_id);
                            $("#amazon_file_id_list").val(JSON.stringify(amazon_file_id_list));

                        });
                    }
                    else
                    {
                        //progressbarElement.css("width", "100%");
                        progressbarElement.val(100);
                        bindedElement.find(".message").html("<span>"+"Uploaded"+"</span>");
                        bindedElement.find(".cancelUpload").html('Delete');
                        uploadStatus = 'upload_completed';
                    }
                }
            };
            formData.append('file', file);
            xhr.send(formData);
            uploadStatus = 'uploading';
            dropZoneBindedHtml.find(".clickFileUploader").val("");
        },'JSON');
    }
    
    function BeautifyFileSize(fileSize)
    {
        fileSize = fileSize/1024 ;

        if(fileSize < 1024) {
            return Math.round(fileSize * 100) / 100 + " KB";
        }
        fileSize = fileSize/1024 ;
        return Math.round(fileSize * 100) / 100 + " MB";
    }

    function HandleDragOver(evt) 
    {
        $(this).addClass('dragover');
        evt.stopPropagation();
        evt.preventDefault();
        evt.dataTransfer.dropEffect = 'copy'; // Explicitly show this is a copy.
        
    }
}