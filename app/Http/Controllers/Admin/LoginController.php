<?php

namespace App\Http\Controllers\Admin;
use App\ActionLog;
use App\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Session;

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
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin/';
    protected $username;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest:agency', ['except' => 'logout']);
    }

    public function showLoginForm()
    {
        return view('admin.login');
    }

    #设置登录账号
    public function username()
    {
        return 'mobile';
    }
    /**
     * 退出登录
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect('admin/login');
    }


    /**
     * 自定义认证驱动
     */
    #定义使用的模型
    protected function guard()
    {
        return auth()->guard('admin');
    }

    /**
     * 重写登录成功响应，插入登录记录
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \App\Exceptions\ErrorMessageException
     */
    public function sendLoginResponse(Request $request){
        $user = $this->guard()->user();
        ActionLog::log(ActionLog::TYPE_USER, $user, "用户登录（{$user->id}）");

        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectPath());
    }
}