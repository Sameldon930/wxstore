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

class SaveLicence extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     *
     * @var string
     */
    protected $method = 'SaveLicence';

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
            'certificate_img' => 'required',
            'registration_number' => 'required',
            'id' => 'required',
        ];
        $messages = [
            'certificate_img.required' => '请上传经营许可证',
            'registration_number.required' => '请填写营业注册号',
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
        if (strpos($params['certificate_img'], 'storage') == false) {
            $front_path = $common->save_img($merchant_id, $params['certificate_img'], 'merchant_license-');
        } else {
            $merchant_info = MerchantInfo::where('user_id', $params['id'])->first();
            $front_path = $merchant_info->merchant_license;
        }
        if (strpos($params['licence_img'], 'storage') == false) {
            $back_path = $common->save_img($merchant_id, $params['licence_img'], 'restaurant_license-');
        } else {
            $merchant_info = MerchantInfo::where('user_id', $params['id'])->first();
            $back_path = $merchant_info->restaurant_license;
        }
        //判断商户是否拥有数据
        $merchant_info = MerchantInfo::where('user_id', $params['id'])->first();
        $create_data = [
            'id' => $params['id'],
            'merchant_license' => $front_path,
            'restaurant_license' => $back_path,
            'registration_number' => $params['registration_number'],
        ];
        if (empty($merchant_info)) {
            MerchantInfo::create($create_data);
        } else {
            $merchant_info->merchant_license = $front_path;
            $merchant_info->restaurant_license = $back_path;
            $merchant_info->registration_number = $params['registration_number'];
            $result = $merchant_info->save();
        }


        return [
            'status' => true,
            'code' => '200',
        ];
    }
}