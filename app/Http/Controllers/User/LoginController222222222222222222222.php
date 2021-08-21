<?php

namespace App\Http\Controllers\Main\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use Hash;
use Auth;
use Modules\Entity\Model\SysUserType\SysUserType;
use Session;
use Lang;
use Carbon\Carbon;

class LoginController extends Controller {
    function index (Request $request){
		
        $ar = array();
        $ar['title'] = trans('front_main.title.enter');
        $login_page = view('orda'.'.user.login')->with(['ar'=>$ar])->render();
		if(Auth::check()) {
			return redirect()->route('admin_index'); 

		}
		

        return view('orda'.'.user.index')->with(['content'=>$login_page])->render();

		
    }

    function check(Request $request){
        $user = User::where(['email' => $request->input('email')])->whereIn('type_id', [SysUserType::USER,SysUserType::MODERATOR,SysUserType::GID,SysUserType::ADMIN,SysUserType::MANAGER,SysUserType::TYROPERATOR])->first();
        if (!$user){
            return back()->with('error', trans('front_main.message.wrong_access'));
		}
        if (!Hash::check($request->password, $user->password)){
            return back()->with('error', trans('messages.wrong_access'));
		}
		
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
	   
        Auth::loginUsingId($user->id, true);
        		

        return redirect()->route('admin_index'); 
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
