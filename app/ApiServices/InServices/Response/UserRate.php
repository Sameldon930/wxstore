<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/16
 * Time: 13:48
 */

namespace App\ApiServices\InServices\Response;

//谢树文
//费率
use App\User;
use App\UserAgentChannel;
use App\UserMerchantInfo;
use App\UserMerchantTube;
use App\Tube;
use Validator;

class UserRate  extends BaseResponse implements InterfaceResponse
{
    protected $method = 'UserRate';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {

        $rules = [
            'id' => 'required'
        ];
        $messages = [
            'id.required' => 'id缺失',
        ];

        $v = Validator::make($params, $rules, $messages);

        // dd( $v);
        if ($v->fails()) {
            return [
                'status' => false,
                'code' => '2001',
                'msg' => $v->errors()->all()
            ];
        } else {
            return $this->run($params);
        }



    }

    /**
     * 执行接口
     * @param  array &$params 请求参数
     * @return array
     */
    public function run(&$params)
    {
        $id =  $params['id'];
//        dd($id);
        $tube = UserMerchantTube::select()
            ->where('user_id','=',$id);
        $tube1 = clone $tube;
        //微信通道费率
        $wechat = $tube->where('tube_id','=','1')->value('profit_rate');
        //支付宝通道费率
        $pay = $tube1->where('tube_id','=','2')->value('profit_rate');
        return [
            'status' => true,
            'code' => '200',
            'wechat' => $wechat,
            'pay' => $pay,

        ];
    }
}