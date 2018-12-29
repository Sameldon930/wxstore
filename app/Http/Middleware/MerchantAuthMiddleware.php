<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
class MerchantAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$guard = null)
    {
       /* if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else{
                return redirect()->guest('merchant/login');
            }
        }*/

        if(empty(Auth::guard($guard)->user())||!Auth::guard($guard)->user()->isMerchant()){
            Auth::guard($guard)->logout();
            $request->session()->flush();
            $request->session()->regenerate();
            return redirect('merchant/login');
        }

        return $next($request);
    }
}
