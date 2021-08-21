/**
 *
 * HTML5 Image uploader with Jcrop
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * Copyright 2012, Script Tutorials
 * http://www.script-tutorials.com/
 */

// convert bytes into friendly format
 var jcrop_api, boundx, boundy;
		$('.send_foto').show();
		
function round(value, decimals) {
    return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
}

function bytesToSize(bytes) {
    var sizes = ['Bytes', 'KB', 'MB'];
    if (bytes == 0) return 'n/a';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
};

// check for selected crop region
function checkForm() {
    if (parseInt($('#w').val())) return true;
    $('.error').html('Please select a crop region and then press Upload').show();
    return false;
};

// update info by cropping (onChange and onSelect events handler)

function updateInfo2() {
	$('#width').val('0');
if (typeof jcrop_api != 'undefined'){
	//alert('изображение изменено')
    jcrop_api.destroy();
	$('#x1').val('');
    $('#y1').val('');
    $('#x2').val('');
    $('#y2').val('');
    $('#w').val('');
	$('#h').val('');
    $('#preview').attr('width', '');
    $('#preview').attr('height', '');
				
}
	
};
function updateInfo(e) {
	$('#x1').val(e.x);
	
    $('#y1').val(e.y);
    $('#x2').val(e.x2);
    $('#y2').val(e.y2);
    $('#w').val(e.w);
	$('#h').val(e.h);
	};

// clear info by cropping (onRelease event handler)
function clearInfo() {
    $('.info #w').val('');
    $('.info #h').val('');
};

function fileSelectHandler() {
$('.flag').text('false');
    // get selected file
   

 var oFile = $('#image_file')[0].files[0];

    // hide all errors
    $('.error').hide();

    // check for image type (jpg and png are allowed)
    var rFilter = /^(image\/jpeg|image\/png)$/i;
    if (! rFilter.test(oFile.type)) {
		$('#preview').css({'display':'none'});
        $('.send_foto').hide();
		$('.my_alert2').html(' <div class="alert alert-danger fade in"><strong class="text-left">Пожалуйста, выберите допустимый файл изображения (jpg и png разрешены)</strong></div>').show();
        return;
    }

    // check for file size
    if (oFile.size > 250 * 1024) {
	//$('.a').html('');
				//$('.send_foto').hide();

        //$('.my_alert2').html(' <div class="alert alert-danger fade in"><strong class="text-left">Размер файла должен быть менее 256000</strong></div>').show();
       // return;
    }

    // preview element
    var oImage = document.getElementById('preview');

    // prepare HTML5 FileReader
    var oReader = new FileReader();
        oReader.onload = function(e) {
			var orientation = false;
			
		
		var src = $('#preview').attr('src');
		      oImage.src = src;
        oImage.onload = function () { // onload event handler
	      var n_w = $('#n_width').val();
		  var n_h = $('#n_height').val();
		  //jcrop_api.resizeImage(n_w, n_h);
	      var screen1 = screen.width;
		  if(screen1 > 1000){
			screen1 = 800;
			}
			if(screen1 < 600){
			
			var screen2 = screen1;//ширина модального окна
            }else{
			var screen2 = screen1/2;//ширина модального окна
			}
			
			if(n_w < 300){
		     //$('#preview').css({'display':'none'});
			 //$('.send_foto').hide();
             //$('.my_alert2').html('<div class="alert alert-danger fade in"><strong class="text-left">Ширина изображения должна быть не менне 300px</strong></div>').show();
             //return;
			}
			

			if(n_w > 800){
			 //$('.send_foto').hide();
             //$('.my_alert2').html('<div class="alert alert-danger fade in"><strong class="text-left">Ширина изображения не должна превышать 800px</strong></div>').show();
             //return;
			}
		if(screen1 < 600){
				$('.modal-dialog').css({'width':screen1+'px','padding':'0px','margin':'0px'});
				$('.modal-body').css({'padding':0})

           }else{
			   $('.modal-body').css({'padding':0})
				$('.modal-dialog').css({'width':screen2+'px'});
           }
			
		
	
	 $('.my_alert2').html('');$('.my_alert').html('');
   	 $('.send_foto').show();
     $('#preview').show();
			

		
            // display step 2
            $('.step2').fadeIn(500);

            
					

			
			if(n_w > n_h){
				var max = n_h;
			}else{
				var max = n_w;
			}
			
            min = 100;
			if(screen1 < 600){
			 min = max;
			
			}
			
			
			
			
			
            if (typeof jcrop_api != 'undefined') 
                jcrop_api.destroy();

            // initialize Jcrop
            $('#preview').Jcrop({
                minSize: min, // min crop size
                aspectRatio : 1, // keep aspect ratio 1:1
				setSelect: [0,0,max,max],
                bgFade: true, // use fade effect
                bgOpacity: .3, // fade opacity
                onChange: updateInfo,
                onSelect: updateInfo,
                onRelease: clearInfo
            }, function(){

                // use the Jcrop API to get the real image size
                var bounds = this.getBounds();
                boundx = bounds[0];
                boundy = bounds[1];
				jcrop_api = this;
				jcrop_api.setOptions({ minSize: [min,min],
			    maxSize: [max,max] });
            });
					  jcrop_api.setImage(src);

			
        };
		
    };

    // read selected file as DataURL
    oReader.readAsDataURL(oFile);
}