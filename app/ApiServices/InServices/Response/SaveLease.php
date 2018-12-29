<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/20
 * Time: 17:27
 */

namespace App\ApiServices\InServices\Response;

use Validator;
use App\MerchantInfo;

class SaveLease extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     *
     * @var string
     */
    protected $method = 'SaveLease';

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
            'img' => 'required',
            'img' => 'required',
        ];
        $messages = [
            'img.required' => '租赁合同缺失',
            'id.required' => '缺少商户id',
        ];
        $params['img'] = json_decode($params['img']);
        $v = Validator::make($params, $rules, $messages);
        // dd( $v);
        $params['img'] = json_encode($params['img']);
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
        $imgs = json_decode($params['img']);
        $merchant = MerchantInfo::where('user_id',$params['id'])->first();
        $merchant_id = $merchant->id;

        $common = new Common();
        $img_array = [];
        foreach ($imgs as $key=>$img){
            //判断是否为旧数据
            if(stripos($img,'storage') !=false){
                $img_array[$key]=explode('storage',$img)[1];
            }else{
                $path =$common->save_img($merchant_id,$img,'lease-');
                $img_array[$key] = $path;
            }
        }
        //将图片转JSON
        $contract_tenancy = json_encode($img_array);
        $merchant_info = MerchantInfo::where('user_id',$params['id'])->first();
        if(empty($merchant_info)){
            MerchantInfo::create(['user_id'=>$params['id'],'contract_tenancy'=>$contract_tenancy]);
        }else{
            $merchant_info->contract_tenancy = $contract_tenancy;
            $merchant_info->save();
        }

        return [
            'status' => true,
            'code' => '200',
        ];
    }

}