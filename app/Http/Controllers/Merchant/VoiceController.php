<?php
namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Libs\Baidu\BaiduAPI;
use App\Order;
use App\User;
use Illuminate\Http\Request;



class VoiceController extends Controller
{
    public function index(){
        $user = User::getMerchantAuthUser();
        $order = $this->getRecentOrder($user);

        if ($order){
            $baidu = new BaiduAPI();
            $text = $order->channel->tube->display . '支付收款' . fenToYuan($order->real_amount) . '元';
            $voice = $baidu->generateVoice($text);

            return ['code' => 'SUCCESS', 'data' => $voice];
        }

        return ['code' => 'SUCCESS'];
    }

    private function getRecentOrder($user){
        return Order::with('channel')->first();
    }
}
