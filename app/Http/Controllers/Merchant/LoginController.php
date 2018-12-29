<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
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
    protected $redirectTo = '/merchant/';
    protected $username;

    protected $user_name='mobile';//搭配的多字段登录


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function showLoginForm()
    {
        return view('merchant.login');
    }

    /**
     * 设置登录账号
     */
    public function username()
    {
        //return 'mobile';
        return $this->user_name;  //搭配的多字段登录
    }

    /**
     * 退出登录
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect('merchant/login');
    }


    /**
     * 自定义认证驱动
     */
    protected function guard()
    {
        return auth()->guard('merchant');
    }


    public function login(Request $request)
    {
        $this->validateLogin($request);

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

        //用户多字段登录
        //这里的mobile1 字段可以换成其他，同时留意在用户模型中添加对应字段，数据库迁移时 增加字段
       if($this->user_name!='real_mobile'){
            $request['real_mobile']=$request['mobile'];
            $this->user_name='real_mobile';
            if ($this->attemptLogin($request)) {
                return $this->sendLoginResponse($request);
            }
        }
        unset($request['real_mobile']);
        $this->user_name='mobile';

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }


    public function findPassword(){
        return _view('merchant.findPassword');
    }

}