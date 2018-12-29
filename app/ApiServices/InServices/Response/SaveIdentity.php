<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/20
 * Time: 15:10
 */

namespace App\ApiServices\InServices\Response;
use App\MerchantInfo;
use EasyWeChat\Payment\Merchant;
use Validator;

class SaveIdentity extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     *
     * @var string
     */
    protected $method = 'SaveIdentity';

    /**
     * 接口参数检验
     */
 public function checkParams(&$params)
  {
    $login = new LoginCheck();
    if(!$login->login_verify($params)){
        return [
            'status' => false,
            'code' => '005',
            'msg' => '请先登录'
        ];
    }
    $rules = [
        'front_img' => 'required',
        'back_img' => 'required',
        'identity_num' => [
            'required',
            'regex:/^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/'
        ],
        'id' => 'required',
    ];
    $messages = [
        'front_img.required' => '请上传身份证正面',
        'back_img.required' => '请上传身份证反面',
        'identity_num.required' => '请填写身份证号缺失',
        'identity_num.regex' => '身份证号格式错误',
//        'identity_name.required' => '请填写姓名',
//        'identity_name.max' => '姓名太长了',
        'id.required' => '商户id缺失',
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
    $merchant = MerchantInfo::where('user_id',$params['id'])->first();
    $merchant_id = $merchant->id;
    $common = new Common();
    if (strpos($params['front_img'], 'storage') == false) {
        $front_path = $common->save_img($merchant_id, $params['front_img'], 'identity_front-');
    } else {
        $merchant_info = MerchantInfo::where('user_id', $params['id'])->first();
        $front_path = $merchant_info->identity_front;
    }

    if (strpos($params['back_img'], 'storage') == false) {
        $back_path = $common->save_img($merchant_id, $params['back_img'], 'identity_back-');
    } else {
        $merchant_info = MerchantInfo::where('user_id', $params['id'])->first();
        $back_path = $merchant_info->identity_contrary;
    }
    //判断商户是否拥有数据
    $merchant_info = MerchantInfo::where('user_id', $params['id'])->first();
    $create_data = [
        'id' => $params['id'],
        'identity_front' => $front_path,
        'identity_contrary' => $back_path,
        'identity_num' => $params['identity_num'],
    ];
    if (empty($merchant_info)) {
        MerchantInfo::create($create_data);
    } else {
        $merchant_info->identity_front = $front_path;
        $merchant_info->identity_contrary = $back_path;
        $merchant_info->identity_num = $params['identity_num'];
        $result = $merchant_info->save();
    }

    return [
        'status' => true,
        'code' => '200',
    ];
}
}