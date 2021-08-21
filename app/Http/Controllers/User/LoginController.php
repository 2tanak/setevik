<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use Hash;
use Auth;
//use Modules\Entity\Model\SysUserType\SysUserType;
//use Session;
use Lang;
use Carbon\Carbon;

class LoginController extends Controller {
	
	 function index2 (Request $request){
		 $user = Auth::user();
		 dd($user);
		 echo 500;exit();
		 
	 }
	
	
	
    function index (Request $request){
		$user = Auth::user();
		dd($user);
        $ar = array();
        //$ar['title'] = trans('front_main.title.enter');
        //$login_page = view('orda'.'.user.login')->with(['ar'=>$ar])->render();
		if(Auth::check()) {
			return redirect()->route('admin_index'); 

		}
		
        
        return view('auth.login')->render();

		
    }

    function check(Request $request){
		dd(1800);
       $user = User::where(['email' => $request->login])->first();

        
        if (!$user){
			
			         return redirect()->route('login'); 

            return back()->with('error', trans('front_main.message.wrong_access'));
		}
        if (!Hash::check($request->password, $user->password)){
			
            return back()->with('error', trans('messages.wrong_access'));
		}
					 //echo '400';exit();

	   /*
	   $created_at= $user->created_at;
	   $time_created_at= strtotime($created_at);//когда создали в секундах
	   $date = Carbon::now()->timestamp;//текущее время
	   $dni = 86400 *1;//секунд в одном дне
	   $time= $date-$dni;//прошло секунд
       if($time < $time_created_at){//если прошло более одного дня
	    
       if($user->activator == 'no_active'){
		    return back()->with('error', Lang::get('messages.wrong_activate'));

	   }
	   }
	   */
	 
        Auth::loginUsingId($user->id, true);
        	

        return redirect()->route('attestation'); 
    }

    function logout(Request $request){
        Auth::logout();
       if($request->lang){
         return redirect('/'.$request->lang.'/');
       }else{
         return redirect('/');
        }
       
       
        //return redirect()->back(); 

    }

}
