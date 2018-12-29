<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/16
 * Time: 13:48
 */

namespace App\ApiServices\InServices\Response;
//渠道cost_channel.html
//谢树文
//费率
use App\User;
use App\UserAgentChannel;
use App\UserMerchantTube;
use App\Tube;
use PhpParser\Node\Expr\Clone_;
use Validator;

class AgentChannel  extends BaseResponse implements InterfaceResponse
{
    protected $method = 'AgentChannel';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {

        $rules = [
            'id' => 'required|min:1'
        ];
        $messages = [
            'id.required' => 'id缺失',
            'id.min' => 'id最少1个字符'
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
        //公共筛选
        $channel = UserAgentChannel::with('channel')
        ->where('user_id','=',$id)->get();
//        dd($channel->toArray());
        $data = array();
        foreach ($channel as $k=>$v){
            $str = $v->channel['name'];
            $data[$str]=$v->profit_rate;
        }
        return [
            'status' => true,
            'code' => '200',
            'data'  => $data
        ];
    }
}