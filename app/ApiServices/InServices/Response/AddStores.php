<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/12
 * Time: 11:29
 */

namespace App\ApiServices\InServices\Response;
use App\User;
use Validator;
use Illuminate\Support\Facades\Cache;
class AddStores extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     */
    protected $method = 'AddStores';

    /**
     * 接口参数检验
     */
    public function checkParams(&$params)
    {

        $rules = [
            'name' => 'required',
            'password' => 'required|min:6|max:18',
        ];
        $messages = [
            'name.required' => '门店名称缺失',
            'password.required' => '密码缺失',
            'password.min'=>'密码最少要6位数',
            'password.max'=>'密码最多18位数'
        ];
        $v = Validator::make($params, $rules, $messages);
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

    public function run(&$params )
    {
        $id = $params['id'];


        $user = User::merchant()->find($id);
        $name = $params['name'];
        $password = $params['password'];
        $password = bcrypt($password);
        User::createStore($name, $password, $user);
        return [
            'status' => true,
            'code' => '200',
            'msg' => '请求成功',
        ];


    }

}