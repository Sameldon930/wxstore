<?php

namespace App\Http\Middleware;
use App\Services\WebServices\PermissionService;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class AdminAuthMiddleware
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
        // 登录过滤
        if (Auth::guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                // return response('Unauthorized.', 401);
                return response()->json(['status' => false, 'msg' => '登录过期，请刷新后重新登录','code' => 401]);
            } else{
                return redirect()->guest('admin/login');
            }
        }

        $user = Auth::user();

        $current_route = Route::currentRouteName();

        // 管理员过滤
        if($user->isAdmin()){
            return $next($request);
        }

        // 忽略路由过滤
        if (PermissionService::isIgnored($current_route)){
            return $next($request);
        }

        // 权限过滤
        if ($user->hasPermission($current_route)){
            return $next($request);
        } else {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => false, 'msg' => '没有权限','code' => 401]);
            } else {
                return redirect()->back()->withInput()->withErrors('没有权限！');
            }
        }

        return $next($request);
    }
}
