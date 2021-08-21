<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except(['logout','logout2']);
    }

    public function username()
    {
        return 'login';
    }

    /**
     * Block user
     *
     * @see https://stackoverflow.com/a/40640241
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // check blocking
        $user = User::where('login', $request->login)->firstOrFail();
        if ($user && $user->is_blocked) {
            return $this->sendLockedAccountResponse($request);
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function sendLockedAccountResponse(Request $request)
    {
        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([
                $this->username() => \Lang::get('auth.locked'),
            ]);
    }
    public function logout2(Request $request)
    {
        dd(19);
        auth()->logout();
        $request->session()->invalidate();

        return redirect('/login');
    }

    public function logout()
    {
        if(\Auth::guest()){
            return redirect('/login');

        }
        auth()->logout();
        return redirect('/login');
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
            //'g-recaptcha-response' => 'required|captcha'
            //'g-recaptcha-response' => 'required|string'
        ]);

        // ReCaptcha v3
//        if (env('RECAPTCHA', false)) {
//            $url = 'https://www.google.com/recaptcha/api/siteverify';
//            $data = [
//                'secret'    => env('RECAPTCHA_PRIVATE_KEY'),
//                'response'  => $request->input('recaptcha'),
//            ];
//
//            $options = [
//                'http' => [
//                    'header'    => "Content-type: application/x-www-form-urlencoded\r\n",
//                    'method'    => 'POST',
//                    'content'   => http_build_query($data),
//                ],
//            ];
//
//            $context = stream_context_create($options);
//            $content = file_get_contents($url, false, $context);
//            $result = json_decode($content);
//
//            // todo: make validation error!
//            if ($result->success) {
//                if ($result->score < env('RECAPTCHA_VALID_SCORE', 0.9)) {
//                    \Log::error('ReCaptcha invalid level detected', [$request->all(), $result]);
//                    $validator = $this->getValidationFactory()->make($request->all(), ['recaptcha1' => 'required']);
//                    if ($validator->fails()) {
//                        $this->throwValidationException($request, $validator);
//                    }
//                }
//            } else {
//                \Log::error('ReCaptcha not success', [$request->all(), $result]);
//                $validator = $this->getValidationFactory()->make($request->all(), ['recaptcha1' => 'required']);
//                if ($validator->fails()) {
//                    $this->throwValidationException($request, $validator);
//                }
//            }
//        }
    }
}