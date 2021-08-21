<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;

class HasRoles
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @param mixed $roles
     * @return mixed
     */
    public function handle($request, Closure $next, ... $roles)
    {
////        if (auth()->check() && auth()->user()->hasRole($role) == false) {
////            abort(404);
////        }
//
////        if (auth()->check() && !is_null($permission) && !auth()->user()->can($permission)) {
////            abort(404);
////        }
//
//        return $next($request);

        if (auth()->check() && auth()->user()->hasRoles($roles)) {
            return $next($request);
        }

        return new RedirectResponse(url('/'));
    }
}
