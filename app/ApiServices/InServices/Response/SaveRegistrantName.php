<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/20
 * Time: 17:40
 */

namespace App\ApiServices\InServices\Response;
use Validator;
use App\MerchantInfo;

class SaveRegistrantName extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     *
     * @var string
     */
    protected $method = 'SaveRegistrantName';

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
            'registrantname' => 'required|max:12',
            'id' => 'required',
            'mobile' => 'required|max:11',
        ];
        $messages = [
            'id.required' => '缺少商户id',
            'registrantname.required' => '请输入负责人',
            'registrantname.max' => '输入的负责人太长了',
            'mobile.required' => '手机号码缺失',
            'mobile.max' => '手机号码最多11个字符',
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
            'registrantname' => $params['registrantname'],
            'mobile' => $params['mobile'],
        ];
        if(empty($merchant_info)){
            MerchantInfo::create($create_data);
        }else{
            $merchant_info->registrantname = $params['registrantname'];
            $merchant_info->mobile = $params['mobile'];
            $merchant_info->save();
        }
        return [
            'status' => true,
            'code' => '200',
            'msg' => '保存成功',
        ];
    }

}