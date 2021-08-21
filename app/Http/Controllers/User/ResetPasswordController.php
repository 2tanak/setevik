<?php

namespace App\Http\Controllers\Main\User;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/logout';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		
        $this->middleware('guest');
    }

    public function restore()
    {
        if(!request()->ajax())
        {
            throw new BadRequestHttpException();
        }

        $language = request('language');

        Languages::localizeApp($language);

        $user = User::whereEmail(request('email'))->first();

        if (!$user)
        {
            return response()->json([
                'status' => 'error',
                'failed' => 'email'
            ]);
        }

        $newPassword = str_random(10);

        DB::beginTransaction();

        $user->password = bcrypt($newPassword);

        $user->save();

        DB::commit();

        return response()->json([
            'status' => 'success'
        ]);
    }
}
