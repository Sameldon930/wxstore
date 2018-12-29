<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class AgentAuthMiddleware
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
        /*if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else{
                return redirect()->guest('agent/login');
            }
        }*/

        if(empty(Auth::guard($guard)->user())||!Auth::guard($guard)->user()->isAgent()){
            Auth::guard($guard)->logout();
            $request->session()->flush();
            $request->session()->regenerate();

            return redirect('agent/login');
        }

        return $next($request);
    }
}
