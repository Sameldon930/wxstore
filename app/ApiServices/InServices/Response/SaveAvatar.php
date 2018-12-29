<?php
namespace App\ApiServices\InServices\Response;

use App\UserLiveness;
use App\User;
use Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * api测试类
 */
//agent_my.html 修改图片与个人信息接口
//谢树文
class SaveAvatar extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称 kui
     * @var string
     * 接口id 0005
     */
    protected $method = 'SaveAvatar';


    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {
        $rules = [
            'id' => 'required',
            'avatar' => 'required',
        ];
        $messages = [
            'id.required' => 'id缺少',
            'avatar.required' => '上传头像不能为空'
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
        $user = User::where('id',$params['id'])->first();
        if(empty($user)){
            return [
                'status' => false,
                'code' => '404',
                'msg' => '该用户不存在',
            ];
            }
            $common = new Common();
            $id = $params['id'];
            $img_path = $common->save_img($id,$params['avatar'],'avatar');
            $user = User::find($params['id']);
            $user->avatar = $img_path;
            $user->save();
            return [
                'status' => true,
                'code' => '200',
                'msg' => '头像修改成功！'
            ];
       }



}