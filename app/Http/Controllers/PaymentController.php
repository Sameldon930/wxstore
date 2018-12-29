<?php

namespace App\Http\Controllers;

use App\Advertising;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Payment\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    const REDIRECT_URI = 'http://mrf.huanhe.pro/pay';

    const ALI_API_AUTH_BASE = 'https://openauth.alipay.com/oauth2/publicAppAuthorize.htm';
    const WECHAT_API_AUTH_BASE = 'https://open.weixin.qq.com/connect/oauth2/authorize';

    const SESSION_WECHAT_USER = 'wechat_user';
    const SESSION_WECHAT_REDIRECT = 'wechat_redirect';

    public function wxOauthCallback(){
        session_start();
        $app = new Application(config('wechat'));

        // 获取微信授权用户 保存到session
        $user = $app->oauth->user();
        $_SESSION[self::SESSION_WECHAT_USER] = $user->toArray();

        // 从session中取出 index 中保存的回调地址并跳转
        $redirect = $_SESSION[self::SESSION_WECHAT_REDIRECT] ?? 'pay';
        header('location:' . $redirect);
    }

    // 判断微信和支付宝客户端 并做响应的授权跳转
    public function index(Request $request){
        if (isWechatClient()){
            session_start();
            if (empty($_SESSION[self::SESSION_WECHAT_USER])) {
                $_SESSION[self::SESSION_WECHAT_REDIRECT] = $request->fullUrl();
                $app = new Application(config('wechat'));

                return $app->oauth->redirect();
            }
            return view('payment_wechat');

        }else if (isAliClient()){
            if ($request->get('auth_code')){
                if(empty($request->get('u'))){
                    return view('ali_auth_success');
                }
            } else {
                $config = config('ali');
                $app_id = $config['app_id'];
                $scope = 'auth_base';
                $merchantNo = $request->get('u');

                $queryData = [
                    'app_id' => $app_id,
                    'scope' => $scope,
                    'redirect_uri' => self::REDIRECT_URI,
                    'u' => $merchantNo,
                ];
                $redirect = self::ALI_API_AUTH_BASE . '?' . http_build_query($queryData);

                return redirect($redirect);
            }

            return view('payment_ali');
        }else {
            return view('payment_nonsupport');
        }
    }

    //支付成功跳转新页面
    public function success(Request $request){
        $data = Advertising::select('advertising.image')
            ->where('status', '=','1')
            ->orderBy(\DB::raw('RAND()'))
            ->take(1)
            ->first();
        return view('success',compact('data'));
    }
    //支付成功跳转之后的加载记录百度统计页面
    public function loading(Request $request){
        $data = Advertising::select('url')
            ->where('status', '=','1')
            ->orderBy(\DB::raw('RAND()'))
            ->take(1)
            ->first();
        return view('loading',compact('data'));
    }
}
