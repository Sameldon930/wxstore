<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/3
 * Time: 13:58
 */

namespace App\ApiServices\InServices\Response;

use App\AccountLog;
use App\Order;
use Carbon\Carbon;
use Faker\Provider\DateTime;
use Illuminate\Support\Facades\DB;
use Validator;
//报表接口statement.html
//谢树文
class ReportForm extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     */
    protected $method = 'ReportForm';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {
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
        //请求参数
        //method = demo
        //nonce = demo
        //sign = 4FE4E806FC10025458AE1BBA32655833

        if ($params['title'] != "") {
            $adate = Carbon::now()->format('Y') . '-' . $params['title'];
            $currenttime = Carbon::createFromFormat('Y-m-d', $adate)->startOfDay();
            $aftertime = Carbon::createFromFormat('Y-m-d', $adate)->endOfDay();
            $startoftime = date('Y-m-d H:i:s', strtotime($currenttime));
            $Endtime = date('Y-m-d H:i:s', strtotime($aftertime));
            function screen($startoftime, $Endtime, $params)
            {
                $datas = AccountLog::select()
                    ->join('settle_logs', 'settle_logs.id', '=', 'account_logs.order_id')
                    ->join('users', 'settle_logs.user_id', '=', 'users.id')
                    ->where('account_logs.user_id', $params['id'])
                    ->where('settle_logs.user_id', '>', 0)
                    ->where('account_logs.created_at', '>=', $startoftime)
                    ->where('account_logs.created_at', '<', $Endtime);
                return $datas;
            }

            $sum = screen($startoftime, $Endtime, $params)
                ->sum('account_logs.amount');
//             dd($sum,$startoftime);

            $count = screen($startoftime, $Endtime, $params)
                ->count();
//        微信金额
            $wxsum = screen($startoftime, $Endtime, $params)
                ->where('settle_logs.tube_id', '=', 1)
                ->sum('account_logs.amount');
//            dd($wxsum);
//       笔数
            $wxcount = screen($startoftime, $Endtime, $params)
                ->where('settle_logs.tube_id', '=', 1)
                ->count();
//        支付宝金额
            $zfbsum = screen($startoftime, $Endtime, $params)
                ->where('settle_logs.tube_id', '=', 2)
                ->sum('account_logs.amount');

//        笔数
            $zfbcount = screen($startoftime, $Endtime, $params)
                ->where('settle_logs.tube_id', '=', 2)
                ->count();

            $a = screen($startoftime, $Endtime, $params)
                ->where('account_logs.amount', '>', 0)
                ->where('account_logs.amount', '<', 100)
                ->count();
            $b = screen($startoftime, $Endtime, $params)
                ->where('account_logs.amount', '>=', 100)
                ->where('account_logs.amount', '<', 1000)
                ->count();

            $c = screen($startoftime, $Endtime, $params)
                ->where('account_logs.amount', '>=', 1000)
                ->count();
            return [
                'status' => true,
                'code' => '200',
                'data' => [
                    'sum' => $sum,
                    'count' => $count,
                    'wxsum' => $wxsum,
                    'wxcount' => $wxcount,
                    'zfbsum' => $zfbsum,
                    'zfbcount' => $zfbcount,
                    'a' => $a,
                    'b' => $b,
                    'c' => $c,
                ]
            ];
        } else {
            function data($params)
            {
                $yesterday = Carbon::yesterday();
                $datas = AccountLog::select()
                    ->join('settle_logs', 'settle_logs.id', '=', 'account_logs.order_id')
                    ->join('users', 'settle_logs.user_id', '=', 'users.id')
                    ->where('account_logs.user_id', $params['id'])
                    ->where('settle_logs.user_id', '>', 0)
                    ->whereDate('account_logs.created_at', $yesterday);
                return $datas;
            }

//        合计
            $sum = data($params)
                ->sum('account_logs.amount');


            $count = data($params)
                ->count();
//        微信金额
            $wxsum = data($params)->where('settle_logs.tube_id', '=', 1)
                ->sum('account_logs.amount');
//            dd($wxsum);
//       笔数
            $wxcount = data($params)
                ->where('settle_logs.tube_id', '=', 1)
                ->count();
//        支付宝金额
            $zfbsum = data($params)
                ->where('settle_logs.tube_id', '=', 2)
                ->sum('account_logs.amount');

//        笔数
            $zfbcount = data($params)
                ->where('settle_logs.tube_id', '=', 2)
                ->count();

            $a = data($params)
                ->where('account_logs.amount', '>', 0)
                ->where('account_logs.amount', '<', 100)
                ->count();
            $b = data($params)
                ->where('account_logs.amount', '>=', 100)
                ->where('account_logs.amount', '<', 1000)
                ->count();

            $c = data($params)
                ->where('account_logs.amount', '>=', 1000)
                ->count();
            return [
                'status' => true,
                'code' => '200',
                'data' => [
                    'sum' => $sum,
                    'count' => $count,
                    'wxsum' => $wxsum,
                    'wxcount' => $wxcount,
                    'zfbsum' => $zfbsum,
                    'zfbcount' => $zfbcount,
                    'a' => $a,
                    'b' => $b,
                    'c' => $c,
                ]
            ];
        }
    }
}