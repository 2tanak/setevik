<?php

namespace App\Http\Controllers\Main\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use Hash;
use Auth;
use Modules\Entity\Model\SysUserType\SysUserType;
use Modules\Entity\Actions\ProfileSaveAction;
use Session;
use Modules\Entity\Model\TransLib\TransLib;

class ProfileController extends Controller {
	
	
	
	
    function index (Request $request){
		$trans = TransLib::where(['table_name'=>'lib_country'])->first();
		
		//dd($trans::where(['name'=>'Brazil'])->first());

        if (!$request->user()){
          if($request->lang){
            return redirect('/'.$request->lang.'/login');
          }else{
            return redirect('/login');
          }
            //abort(404);
           }
        $ar = array();
        $ar['title'] = trans('front_main.title.profile');
        $ar['user'] = $request->user();
        $ar['student_data'] = $request->user()->relStudentData()->firstOrCreate(['user_id'=>$request->user()->id]);
		$theme = Session::get('mobilSwitch');
        return view($theme.'.page.user.profile', $ar);
    }

    function save(Request $request, $lang){
		
		
        if (!$request->user())
            abort(404);

        $action = new ProfileSaveAction($request->user(), $request);
         
        try {
            $action->run();
        } catch (\Exception $e) {
            dd(11, $e->getMessage(), $e->getLine(), $e->getFIle());
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', trans('front_main.message.profile_save'));
    }

}
