<?php
namespace App\Http\Controllers\User;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use Hash;
use App\User;
use Lang;
//use Mail;
class RegistrationAction {
    private $model = false;
    private $request = false;
 
	
    function __construct(Model $model, Request $request){
        $this->model = $model; 
        $this->request = $request;
        		
    }

    function run(){
        $ar = $this->request->all();
	
        //$ar['edited_user_id'] = 1;
	
        if ($this->request->password){
			$ar['password'] = Hash::make($this->request->password);
		}
        else{
            unset($ar['password']);
		}


       $key = $this->activateKey($this->request->email);
       $host =	$this->request->root();
	   $link = $host.'/activate/' .$key;
       $body    = 'Вы зарегестрировались на сайте '.$host.'</br>'.'Вам необходимо активировать акаунт по ссылке '.'<a href="'.$link.'">'.$link.'</a>';
	   $data = $this->request->all();
	   $this->mail($data,$body,$host);


        if(isset($this->request->tyr_operator)){$ar['type_id'] = 6;}
		else{
			$ar['type_id'] = 2;
		    
		}
		
		$ar['activate_key'] = $key;
		$this->model->fill($ar);
        $this->model->save();

/*
        $data = new Gid();
        $data->user_id = $this->model->id;
		$data->family = $this->request->family;
		$data->imya = $this->request->name;
		$data->phone = $this->request->phone;

        //$data->edited_user_id = 2;
		$data->save();
        */
		
        
    }
	
	
	
		protected function activateKey($login){
	   	 return md5($login . "|" . uniqid(time()));
	   }
	
	  protected function mail($data,$body,$host){
		   $result = Mail::send('orda.email.email',['data'=>$data,'body'=>$body], function($message) use ($data,$host) {
			   
				$mail_admin = env('MAIL_ADMIN');
				$message->from($mail_admin, Lang::get('messages.online'));
				$message->to($data['email'],'Mr. Admin')->subject(Lang::get('messages.activate'));
			});
			return true;
			}
  
	protected function create(array $data,$key)
    {
        return User::create([
		    'name' => $data['name'],
            //'login' => $data['login'],
            'email' => $data['email'],
			'gorod' => $data['gorod'],
			'phone' => $data['phone'],
            'password' => bcrypt($data['password']),
			'activate_key'=>$key,
			// New fields
			'last_name' => $data['family'] ? $data['family'] : null,
			'patronymic' => $data['oth'] ? $data['oth'] : null,
			'lang' => $data['lang'] ? $data['lang'] : null,
			'class_id' => $data['class_id'] ? $data['class_id'] : null,
			'smena_id' => $data['smena_id'] ? $data['smena_id'] : null,
        ]);
    }
	
	
	  
	



}