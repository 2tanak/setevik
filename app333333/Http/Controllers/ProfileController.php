<?php

namespace App\Http\Controllers;

use App\Models\File;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OssNews;
use Intervention\Image\ImageManagerStatic as Image;
use Validator;
//use Storage;
use Illuminate\Support\Facades\Storage;
class ProfileController extends Controller
{
    public function index()
    {
		
        $user = Auth::user();
		$country= $user->country;
		return view('profile')->with(['user'=>$user,'country'=>$country]);
       // return view('profile', compact('user'));
	   

    }


function RDir( $path ) {
	
 // если путь существует и это папка
 if ( file_exists( $path ) AND is_dir( $path ) ) {
   // открываем папку
    $dir = opendir($path);
    while ( false !== ( $element = readdir( $dir ) ) ) {
		preg_match('/.png|.jpg/i',$element,$arr);
		if(isset($arr[0])){
			continue;
		}		
		
		
      // удаляем только содержимое папки
      if ( $element != '.' AND $element != '..' )  {
        $tmp = $path . '/' . $element;
        chmod( $tmp, 0777 );
       // если элемент является папкой, то
       // удаляем его используя нашу функцию RDir
        if ( is_dir( $tmp ) ) {
         RDir( $tmp );
       // если элемент является файлом, то удаляем файл
        } else {
          unlink( $tmp );
       }
     }
   }
   // закрываем папку
    closedir($dir);
    // удаляем саму папку
   if ( file_exists( $path ) ) {
     //rmdir( $path );
   }
 }
}
    public function sing(Request $request){
		
	  $messages = ['files.mimes'=>'Допустимый формат: jpg, png, размером не более 20 МБ','files.max'=>'Допустимый размер файла не более 20МБ','files.image'=>'Файл должен быть картинкой.'];
        $validator = Validator::make($request->all(),[
		  'files' => 'image|mimes:png,jpeg,jpg','max:20480',
       ],$messages);
           
		   
		if($validator->fails()) {
			//return 11;
	         $view = view('errors.validator_errors')->with(['error' => $validator->errors()])->render();
			   return response()->json([
                'view' => $view,
				'html'=>'errors'
            ], 200);
		   }
			
		
		
		
	if ($request->hasFile('files')) {
		
	$image = $request->file('files');
	$r= $image->getClientOriginalName();
	
	$exts= $image->getClientOriginalExtension();
	$orientation = false;
	$exif=false;
	$width = $request->n_width;
	
    $width= $width/2;
	if($exts != 'png'){
        $exif = exif_read_data($image);
		if(!empty($exif['Orientation'])) {
            switch($exif['Orientation']) {
                case 8:{
					$orientation=8;
					//$width = $request->n_height;
                    break;
				}
                case 3:{
					//$width = $request->n_width;
                    $orientation=3;
                    break;
				}
                case 6:{
					//$width = $request->n_height;
					$orientation=6;
                    break;
				}
            }
        }
		
	}
	
	$user = Auth::user();
	$image_resize = Image::make($image->getRealPath());
	
    if($orientation == 6){
		$image_resize->rotate(-90);
	}
	if($orientation == 8){
	  $image_resize->rotate(90);
	}
	if($orientation == 3){
	  $image_resize->rotate(180);
	}
	$rand = $user->id.'_'.md5(time().rand());
    $dir = './storage/avatars/';
	if(is_dir('./storage/avatars/'.$user->id)){
	 $this->RDir( './storage/avatars/'.$user->id);
		//rmdir('./storage/avatars/'.$user->id);
	}
	
	$dir= $dir.$user->id.'/'; 
    $sTempFileName = $dir.$rand;
	@chmod('./storage/avatars/', 0777);
	@chmod('./storage', 0777);
    @chmod($sTempFileName, 0777);
	if(!is_dir('./storage/avatars/'.$user->id)){
	mkdir('./storage/avatars/'.$user->id, 0777);
	}
	
	
	
	 $image_resize->resize($width, null, function ($constraint) {
     $constraint->aspectRatio();
            });
			
	 $width = $image_resize->width();
	 $height = $image_resize->height();
	
	 
//$image_resize->orientate();
     $image_resize->save(public_path($sTempFileName));
	 $domen= $_SERVER['HTTP_HOST'];
	 return response()->json([
                'url'=>trim($sTempFileName,'.'),
				'width'=>$width,
				'height'=>$height,
				'r'=>$r
            ], 200);
	 
	 
	 		  

	 return 'ok';
	 }//если есть файл
        return $image;
	
}

    public function update(Request $request)
    { 
	      
    // return $request->all();
	 if ($request->has('vkladka1')) {
				 $messages = ['password.min'=>'Пароль не менее 6 символов','password.confirmed'=>'Не совподают пароли'];
				 $validator = Validator::make($request->all(),[
				'password'  => 'min:6|confirmed'
			],$messages);
			}else {
				$messages = ['name.required'=>'Не передан файл','w.not_in'=>'Вы не отправили область с выделением','h.not_in'=>'Нет выделенной области'];
                 $validator = Validator::make($request->all(),[
				'name' => 'required','w'=>'required|numeric|not_in:0','h'=>'required|numeric|not_in:0'

			],$messages);
           }
		  
        
		if($validator->fails()) {
	         $view = view('errors.validator_errors')->with(['error' => $validator->errors()])->render();
			   return response()->json([
			   
                'view' => $view,
				'html'=>'errors'
            ], 200);
		   }
			
		
	
        $user = Auth::user();
	

        // photo
    try{
		
	if ($request->name) {
		   $user = Auth::user();
		   $dir = './storage/avatars/';
           $sTempFileName = $dir.$user->id.'/'.$request->name;
		   $sResultFileName = $this->crop($sTempFileName,$request);
		  
		   @unlink('./'.$user->photo);
           $user->photo =  $sResultFileName;

            File::create([
                'dir'           => '/storage/avatars/',
                'path'          => $sResultFileName,
                'size'          => 4,
                'mime_type'     => 3,
                'name'          =>  $sResultFileName,
                'original_name' => 5,
            ]);
        }//end files
	}
	 catch (\Exception $e) {
		 return 3;
		@unlink('./'.$user->photo);
		@unlink($sTempFileName);
	}
	
	
	
		$a='Фото успешно сохранено';

        // password
        if ($request->has('password')) {
			$sResultFileName=false;
			$a='Изменеия успешно сохранены';
            $user->password = bcrypt($request->input('password'));
        }
         $user->save();
		 	

	     $view = view('errors.validator_success')->with(['a'=>$a])->render();
			   return response()->json([
                'view' => $view,
				'url'=>$sResultFileName,
				'html'=>'success'
            ], 200);
        //return back()->with('success', 'Изменения сохранены');
    }
	
	
	function crop($sTempFileName,$request) {
		
		 $x1 = $request->x1;
		 $y1 = $request->y1;
		 $x2 = $request->x2;
		 $y2 = $request->y2;
		 $w = $request->w;
		 $h = $request->h;
		 if(!$x1 && !$y1 && !$x2 && !$y2){
			 $view = view('errors.validator_errors')->with(['error' => $validator->errors()])->render();
			   return response()->json([
                'view' => $view,
				'html'=>'errors'
            ], 200);
			 return false;
		 }
		 
		 $sResultFileName=false;
		 
		
		if (file_exists($sTempFileName) && filesize($sTempFileName) > 0) {
			
                  $aSize = getimagesize($sTempFileName); // try to obtain image info
				 
                        if (!$aSize) {
                            @unlink($sTempFileName);
                            return;
                        }
				  
				  switch($aSize[2]) {
                     case IMAGETYPE_JPEG:
					 
					    $sExt = '.jpg';
					    $vImg = @imagecreatefromjpeg($sTempFileName);
						
						break;
                            /*case IMAGETYPE_GIF:
                                $sExt = '.gif';

                                // create a new image from file 
                                $vImg = @imagecreatefromgif($sTempFileName);
                                break;*/
                     case IMAGETYPE_PNG:
					 
                       $sExt = '.png';
                       $vImg = @imagecreatefrompng($sTempFileName);
					   
					   break;
                     default:
                       @unlink($sTempFileName);
                       return;
                        }
						
				     $iWidth = $iHeight = 150; // desired image result dimensions
					 $iJpgQuality = 90;
                     $vDstImg = @imagecreatetruecolor( $iWidth, $iHeight );
                     imagecopyresampled($vDstImg, $vImg, 0, 0, (int)$x1, (int)$y1, $iWidth, $iHeight, (int)$w, (int)$h);
					 
					 
					 
					 
                     $sResultFileName = $sTempFileName.$sExt;
					 imagejpeg($vDstImg, $sResultFileName, $iJpgQuality);
                     @unlink($sTempFileName);
						
				}
				return trim($sResultFileName,'.');
			// return array($sResultFileName);
	
}

}
