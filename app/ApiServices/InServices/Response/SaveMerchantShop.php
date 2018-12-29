<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/20
 * Time: 11:53
 */

namespace App\ApiServices\InServices\Response;
use App\MerchantInfo;
use EasyWeChat\Payment\Merchant;
use Validator;

class SaveMerchantShop extends BaseResponse implements InterfaceResponse
{
/**
* 接口名称
* @var string
*/
    protected $method = 'SaveMerchantShop';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {
        $rules = [
            'id' => 'required',
            'username' => 'required|max:12',
            'name' => 'required|max:25',
            'address' => 'required|max:40',
            'email' => 'required',
            'alipay' => 'required',
        ];
        $messages = [
            'id.required' => '缺少商户id',
            'username.required' => '请输入负责人',
            'username.max' => '输入的负责人太长了',
            'name.required' => '请输入商铺名称',
            'name.max' => '商铺全称太长了',
            'address.required' => '请输入商铺地址',
            'address.max' => '商铺地址太长了',
            'email.required' => '请填写邮箱',
            'alipay.required' => '请填写支付宝账号',

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
        $merchant_info = MerchantInfo::where('user_id',$params['id'])->first();

        $create_data = [
            'id' => $params['id'],
            'name' => $params['name'],
            'email' => $params['email'],
            'address' => $params['address'],
            'username' => $params['username'],
            'alipay' => $params['alipay'],
        ];
        if(empty($merchant_info)){
            MerchantInfo::create($create_data);
        }else{
            $merchant_info->company_name = $params['name'];
            $merchant_info->email = $params['email'];
            $merchant_info->merchant_address = $params['address'];
            $merchant_info->business_person = $params['username'];
            $merchant_info->alipay = $params['alipay'];
            $merchant_info->save();
        }
        return [
            'status' => true,
            'code' => '200',
            'msg' => '保存成功',
        ];
    }
}