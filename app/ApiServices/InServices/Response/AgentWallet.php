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
class AgentWallet extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     */
    protected $method = 'AgentWallet';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {
        //检验登录状态
        $is_login = new LoginCheck;
        if (!$is_login->agentlogin_verify($params)){
            return ['status' => false, 'code' => '0005', 'msg' => '还未登录'];
        };
        $rules = [
            'id' => 'required',
        ];
        $messages = [
            'id.required' => 'ID缺失',
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

    /**
     * 执行接口
     * @param  array &$params 请求参数
     * @return array
     */
    public function run(&$params)
    {
        $yesterday = Carbon::yesterday();
        //昨日分润
        $yesterDay = AccountLog::select('account_logs.amount')
            ->join('settle_logs','settle_logs.id','=','account_logs.order_id')
            ->join('users','settle_logs.user_id','=','users.id')
            ->where('account_logs.user_id',$params['id'])
            ->where('settle_logs.user_id','>',0)
            ->whereDate('account_logs.created_at', $yesterday)
            ->sum('amount');
        //余额
        $balance = UserAccount::select()
            ->where('user_accounts.user_id','=', $params['id'])
            ->sum('balance');
        //首页轮播图
        $side = Side::select('image','url')
                ->where('group_id','=','2')
                ->where('status','=','1')
                ->orderby('orderby','asc')
                ->take(5)
                ->get();
        foreach($side as &$value){
            $value->image = 'storage/serve/'.$value->image;
        }


//        //获取最新系统消息
        $message = Message::latest()
                ->where('status','=','1')
                ->first();

        return [
            'status' => true,
            'code' => '200',
            'yesterday_money'=> fenToYuan($yesterDay),
            'balance' =>fenToYuan($balance),
            'side'=>$side,
            'message'=>$message
        ];
    }


}