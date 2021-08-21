<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Auth;

use Session;
class CheckAuth  {
    protected $auth;

    function __construct(Guard $auth) {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next){
		$date = "10.12.2020 1:30:59";
        $date_sec = strtotime($date);//обращаем в секунды
        header('Last-Modified: '.gmdate('D, d M Y H:i:s', $date_sec).' GMT');
       

        return $next($request);
    }
}
