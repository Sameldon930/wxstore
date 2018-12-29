<?php
namespace App\ApiServices\InServices\Response;

use App\Libs\Baidu\BaiduAPI;
use Illuminate\Support\Facades\Storage;
use Validator;

/**
 * api测试类
 */
class GetVoice extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     */
    protected $method = 'GetVoice';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {

        $rules = [
            'money' => 'required|numeric',
            'type' => 'required|numeric',
        ];
        $messages = [
            'money.required' => 'money缺失',
            'money.numeric' => 'money必须为数字',
            'type.required' => 'type缺失',
            'type.numeric' => 'type必须为数字',
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
        if($params['action']=='get') {
            $baidu = new BaiduAPI();
            $pay_from = $params['type'] == 1 ? '微信' : '支付宝';
            $url = $baidu->generateVoice($pay_from . '到账' . $params['money'] . '元', 0);
            return [
                'status' => true,
                'code' => '200',
                'data' => [
                    'url' => $url,
                ]
            ];
        }elseif($params['action']=='delete') {
            Storage::disk('voice')->delete($params['src']);
            return [
                'status' => true,
                'code' => '200',
                'data' => 'delete'
            ];
        }
    }


}