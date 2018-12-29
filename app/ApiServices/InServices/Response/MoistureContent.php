<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/5
 * Time: 10:09
 */

namespace App\ApiServices\InServices\Response;
use App\AccountLog;
use App\ActionLog;
use App\Order;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Validator;
//benefit.html分润明细，数据加载接口，筛选接口
//谢树文
class MoistureContent extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     */
    protected $method = 'MoistureContent';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {
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

    public function run(&$params)
    {

        $page_num = $params['page_num'];

        $yesterday = Carbon::yesterday();

            $datas = AccountLog::select('settle_logs.tube_id','account_logs.amount','users.name','account_logs.created_at')
                ->join('settle_logs','settle_logs.id','=','account_logs.order_id')
                ->join('users','settle_logs.user_id','=','users.id')
                ->where('account_logs.user_id',$params['id'])
                ->where('settle_logs.user_id','>',0)
                ->orderBy('account_logs.created_at', 'desc');

            //定义一个数组接用来存where条件
            $arr =[];
            if (!empty($params['order'])){
                $arr = array_merge(array('users.name'=>$params['merchant']),$arr);
            }
            if (!empty($params['channel'])){
                if ($params['channel'] != 3) {
                    $arr = array_merge(array('tube_id'=>$params['channel']),$arr);
                }
            }
            $now = Carbon::now()->toDateString();
            if (!empty($params['starttime']) && !empty($params['endtime'])) {
                $adate =  $params['starttime'];
                $bdate = $params['endtime'];
                $atime = Carbon::createFromFormat('Y-m-d', $adate)->startOfDay();
                $nobtime = Carbon::createFromFormat('Y-m-d', $adate)->addMonthsNoOverflow(3);
                $btime = Carbon::createFromFormat('Y-m-d', $bdate)->endOfDay();
                $datas->whereBetween('account_logs.created_at', [$atime, $btime]);
            }
            if (!empty($params['starttime'])&&empty($params['endtime'])){
                $adate =  $params['starttime'];
                $atime = Carbon::createFromFormat('Y-m-d', $adate)->startOfDay();
                $nobtime = Carbon::createFromFormat('Y-m-d', $adate)->addMonthsNoOverflow(3);
                $datas->whereBetween('account_logs.created_at', [$atime, $nobtime]);
            }
            $data = $datas->where($arr) ->orderBy('account_logs.created_at', 'desc')->paginate(3, null, null, $page_num);

                // 总数
                $sumdata = $datas->sum('amount');
               //昨日
                $Frontdata = $datas ->whereDate('account_logs.created_at', $yesterday)->sum('amount');

               //今日
                $afterdata = $datas ->whereDate('account_logs.created_at', $now)->sum('amount');

                return [
                    'status' => true,
                    'code' => '200',
                    'sumdata' => $sumdata,
                    'Frontdata' => $Frontdata,
                    'afterdata' => $afterdata,
                    'data' => $data,
                ];
    }

}