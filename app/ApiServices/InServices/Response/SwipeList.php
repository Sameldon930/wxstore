<?php
namespace App\ApiServices\InServices\Response;

use App\AccountLog;
use App\Message;
use App\Order;
use App\SettleLog;
use App\Side;
use App\UserAccount;
use App\Withdrawal;
use Validator;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * api测试类
 */
//首页分润与余额agent_index.html
//张泽山
class SwipeList extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     */
    protected $method = 'SwipeList';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {

            return $this->run($params);




    }

    /**
     * 执行接口
     * @param  array &$params 请求参数
     * @return array
     */
    public function run(&$params)
    {

//        //获取最新系统消息
        $message = Message::latest()
                ->first();

        return [
            'status' => true,
            'code' => '200',
            'message'=>$message
        ];
    }


}