<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/31
 * Time: 16:03
 */

namespace App\ApiServices\InServices\Response;
use App\BankBranch;
use Validator;
//银行卡名称列表 my_add_card.html
//谢树文
class BankBranchGetList extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     * 接口id 4007
     */
    protected $method = 'BankBranchGetList';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {
        $rules = [
            'bank_id' => 'required',
            'province' => 'required',
            'city' => 'required',
        ];
        $messages = [
            'bank_id:required' => '必须输入银行',
            'province:required' => '必须输入省份',
            'city:required' => '必须输入市区',
        ];

        $v = Validator::make($params, $rules, $messages);

        if ($v->fails()) {
            return ['status' => false, 'code' => '2001', 'msg' => $v->errors()->all()];
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
        $branchs = BankBranch::where('bank_id', $params['bank_id'])
            ->where('province', 'like', "%{$params['province']}%")
            ->where('city', 'like', "%{$params['city']}%")
            ->get()
        ;
        return [
            'status' => true,
            'code' => '200',
            'data' => $branchs,
            'msg' => '请求成功'
        ];
    }
}