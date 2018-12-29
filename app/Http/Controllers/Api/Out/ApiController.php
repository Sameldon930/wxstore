<?php
namespace App\Http\Controllers\Api\Out;


use App\ApiServices\OutServices\UnifiedOrderServer;
use App\ApiServices\OutServices\OrderQueryServer;
use App\Http\Controllers\Controller;
use App\Order;


class ApiController extends Controller
{
    /**
     * API总入口
     * @return [type] [description]
     */
    public function index($method)
    {
        switch ($method) {
            case 'unifiedorder':
                //统一下单
                $server = new UnifiedOrderServer();
                return $server->run();
                break;
            case 'orderquery':
                //统一下单订单查询
                $server = new OrderQueryServer();
                return $server->run();
                break;
            default:
                return 'error';
        }

    }

    /**
     * 外部API跳转页面，跳转支付以及跳转同步通知
     * @param $id string 订单号
     * @return array 说明
     */
    public function redirect($order_no)
    {
        //根据id得到跳转的链接，执行跳转
        //$url='https://wx.tenpay.com/cgi-bin/mmpayweb-bin/checkmweb?prepay_id=wx201712141623254d04afe26f0249593363&package=3119997146';
        $order_data = Order::where('order_no', '=', $order_no)->first();
        if (empty($order_data)) {
            return '没有该条数据的订单';
        }

        switch ($order_data->channel->name) {
            case 'WECHAT_H5':
                return $this->wxh5_action($order_no, $order_data);
                break;
            default:
                return '';
        }
    }

    /**
     * WXH5跳转至微信返回的页面
     * @param $id string 订单号
     * @param $order_data object 订单信息
     * @return array 说明
     */
    public function wxh5_action($id, &$order_data)
    {
        if ($order_data->pay_status == 2) {
            //微信H5支付完，会回到这个页面，在此执行页面同步跳转
            $sync_url = $order_data->sync_notify_url??null;
            if (empty($sync_url)) {
                return '支付完成！请返回商户查看结果。';
            }
            $sync_url = $sync_url . '?pay_status=' . $order_data->pay_status . '&out_trade_no=' . $order_data->out_trade_no;
            return redirect($sync_url);
        }

        $url = $order_data->response;

        return view('api.redirection', compact('url'));
    }


}
