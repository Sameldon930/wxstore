<?php
namespace App\ApiServices\OutServices;

use Request;
use App\Libs\Error\Error;
use App\ApiServices\ApiCommon;
use App\Channel;
use Illuminate\Support\Facades\Validator;

use App\Libs\Pay\Payment;
/*use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;*/

/**
 * 统一下单总入口
 * @author linkin 2017-06-06
 */
class UnifiedOrderServer
{
    use ApiCommon;
    /**
     * 请求参数
     * @var array
     */
    protected $params = [];

    /**
     * 错误码
     * @var boolean
     */
    protected $error = '';

    /**
     * 初始化
     * @param Error $error Error对象
     */
    public function __construct()
    {
        $this->params = Request::all();
        $this->error = new Error;
    }

    /**
     * api服务入口执行
     * @return array  执行结果
     */
    public function run()
    {
        //交易时间限制
        if (!$this->checkTime()) {
            return $this->response(['status' => false, 'code' => '2002']);
        };


        //初步校验
        $result = $this->checkCommonParams();
        if (!$result['status']) {
            return $this->response(['status' => false, 'code' => $result['code']]);
        }

        /*//校验渠道类型
        $rules = [
            'settlement_type' => 'required',
        ];
        $message = [
            'settlement_type.required' => '缺少settlement_type',
        ];
        $validator = Validator::make($this->params, $rules, $message);
        if ($validator->fails()) {
            return [
                'status' => false,
                'code' => '2001',
                'msg' => $validator->errors()->all()
            ];
        }*/


        //判断平台通道是否禁用
        $channel = Channel::where('name', '=', $this->params['pay_type'])->Enabled()->first();
        if (empty($channel)) {
            return $this->response(['status' => false, 'code' => '3001']);
        }
        $this->params['channel_id']=$channel->id;
        $index=strpos($this->params['pay_type'],'_');
        $pay_type= substr($this->params['pay_type'],0,$index);
        switch ($pay_type) {
            case 'WECHAT':
                return $this->runRoute('Weixin');
                break;
            case 'ALI':
                return $this->runRoute('Ali');
                break;
            default:
                return $this->response(['status' => false, 'code' => '3001']);
        }

    }

    protected function runRoute($route)
    {
       /* $result1 = $this->checkChannelAuth($this->merchant_data->aid, $this->params['channel_id']);
        if (!$result1) {
            return $this->response([
                'status' => false,
                'code' => '200101',
            ]);
        }*/

        //检查是否有权限执行通道， 状态必须为1时方可表示使用中
        /*$result2 = $this->checkChannelAuth($this->params['user_id'], $this->params['channel_id']);
        if (!$result2) {
            return $this->response([
                'status' => false,
                'code' => '200123',
            ]);
        }*/ //else {
            //$this->params['order_snap'] = $result2;
           // $this->params['order_snap']['tube_rate'] = $this->params['tube_rate'];
           // $this->params['order_snap']['tube_fee'] = $this->params['tube_fee'];
           // $this->params['order_snap']['agency_channel_rate'] = $result1['channel_rate'];
           // $this->params['order_snap']['agency_channel_fee'] = $result1['channel_fee'];
       // };
        return $this->checkRoute($route, __NAMESPACE__);
    }
}
