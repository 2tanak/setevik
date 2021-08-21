<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Role;
use App\Models\Link;
use App\Models\Cabinet;
use App\Models\Registration;
use App\Models\Trees\BinaryTreeNode;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Contracts\Encryption\DecryptException;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
		$messages = ['email.email'=>'Поле «E-Mail» должно быть заполнено в соответствующем формате'];
        $validator = Validator::make($data, [
            'name'          => 'required|latin|string|max:255',
            'last_name'     => 'required|latin|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users',
            'password'      => 'required|string|min:6|confirmed',
            'birthday'      => 'required|date',
            'phone'         => 'required',
            'country'       => 'required|exists:countries,id',
            'city'          => 'required|latin',
            'contract'      => 'accepted',
            'ref'           => 'required|exists:links,code',
            'is_female'     => 'required',
        ],$messages);

        // todo: needs strong validation
		/*
		if ($validator->fails()){
			dd(18);
		};
		*/
        if ($validator->fails() == false) {
			
            $validator->after(function ($validator) use ($data) {
                try {
                    $decrypted = decrypt($data['ref']);
                    $isValid = false;

                    if ($decrypted['system'] == 'sib') {
                        $isValid = true;
                    } elseif ($decrypted['system'] == 'oss') {
                        $isValid = true;
                    }

                    if (!$isValid) {
                        throw new \Exception('Данная ссылка на регистрацию не активна. Запросите у куратора новую ссылку.');
                    }

                } catch (DecryptException $e) {

                    $validator->errors()->add('ref', 'Некорректная реферальная ссылка');
                    \Log::error(__METHOD__, ['error' => $e->getMessage(), 'data' => $data]);

                } catch (\Exception $e) {

                    $validator->errors()->add('ref', $e->getMessage());
                    \Log::error(__METHOD__, ['error' => $e->getMessage(), 'data' => $data]);

                }
            });
        }

        return $validator;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $link = Link::where('code', $data['ref'])->first();
        $decrypted = decrypt($data['ref']);
        $user = User::create([
            'name'              => $data['name'],
            'last_name'         => $data['last_name'],
            'login'             => $data['email'],
            'email'             => $data['email'],
            'password'          => bcrypt($data['password']),
            'birthday'          => Carbon::parse($data['birthday'])->format('Y-m-d'),
            'phone'             => $data['phone'],
            'country_id'        => $data['country'],
            'city'              => $data['city'],
            'photo'             => '/img/avatars/no-photo.png',
            'is_female'         => $data['is_female'],
            'sib_registered_at' => Carbon::now(),
        ]);

        if ($decrypted['system'] == 'sib') {

            $cabinet = Cabinet::where('code', 'sib')->first();
            $role = Role::where('slug', 'partner-na')->first();

            // binary tree node initialization
            $user->tree_node_id = $decrypted['node_id'];
            $user->tree_inviter_node_id = BinaryTreeNode::where('user_id', $decrypted['user_id'])->first()->root_node_id;

            // mark 'has_activity_sib' manually
            $user->has_activity_sib = true;
            $user->sib_registered_at = Carbon::now();

        } elseif ($decrypted['system'] == 'oss') {

            $cabinet = Cabinet::where('code', 'oss')->first();
            $role = Role::where('slug', 'resident-na')->first();
            $user->oss_registered_at = Carbon::now();

        } else {
            return false;
        }

        $user->cabinet()->associate($cabinet);
        $user->roles()->save($role);
        $user->save();

        Registration::create([
            'user_id' => $user->id,
            'link_id' => $link->id,
        ]);

        return $user;
    }

    /**
     * Another registration from for resident's link
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showRegistrationFormResident()
    {
        return view('auth.register_oss');
    }
}
