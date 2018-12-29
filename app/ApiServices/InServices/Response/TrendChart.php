<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/3
 * Time: 18:58
 */

namespace App\ApiServices\InServices\Response;
use App\AccountLog;
use App\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Validator;
//趋势接口trendchart.html
//谢树文
//张泽山
class TrendChart extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     */
    protected $method = 'TrendChart';

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
    public function run(&$params)
    {
        //提出需要调用的查询为方法,下面可进行调用
        function days($starttime,$Endtime,$params){

            $data =AccountLog::select('settle_logss.tube_id','account_logs.amount','account_logs.created_at')
                ->join('settle_logs','settle_logs.id','=','account_logs.order_id')
                ->join('users','settle_logs.user_id','=','users.id')
                ->where('account_logs.user_id',$params['id'])
                ->where('settle_logs.user_id','>',0)
                ->where('account_logs.created_at', '>=', $starttime)
                ->where('account_logs.created_at', '<', $Endtime);
            return $data;
        }
        //日数据
        if ($params['type']==1){
//        0-6的数据
        $today = Carbon::today();
        $starttime = date('Y-m-d H:i:s', strtotime($today));//今天的0:00开始
        $aftertoday = $today->addHours(6);//增加6小时
        $Endtime = date('Y-m-d H:i:s', strtotime($aftertoday));//6:00结束
        $adata= days($starttime,$Endtime,$params)->sum('account_logs.amount');//这个时间段的金额总和
        $acount= days($starttime,$Endtime,$params)->count();//这个时间段的交易笔数

//       6-9的数据
        $today = Carbon::today();
        $starttime = $Endtime ;//上面的结束时间点为开始时间
        $aftertoday = $today->addHours(9);//增加9小时
        $Endtime = date('Y-m-d H:i:s', strtotime($aftertoday));
        $bdata= days($starttime,$Endtime,$params)->sum('account_logs.amount');//这个时间段的金额总和
        $bcount= days($starttime,$Endtime,$params)->count();//这个时间段的交易笔数

        //9-12的数据
        $today = Carbon::today();
        $starttime = $Endtime ;//上面的结束时间点为开始时间
        $aftertoday = $today->addHours(12);//增加12小时
        $Endtime = date('Y-m-d H:i:s', strtotime($aftertoday));
        $cdata= days($starttime,$Endtime,$params)->sum('account_logs.amount');//这个时间段的金额总和
        $ccount= days($starttime,$Endtime,$params)->count();//这个时间段的交易笔数
//        12-15的数据
        $today = Carbon::today();
        $starttime = $Endtime ;//上面的结束时间点为开始时间
        $aftertoday = $today->addHours(15);//增加15小时
        $Endtime = date('Y-m-d H:i:s', strtotime($aftertoday));
        $ddata= days($starttime,$Endtime,$params)->sum('account_logs.amount');//这个时间段的金额总和
        $dcount= days($starttime,$Endtime,$params)->count();//这个时间段的交易笔数
        //15-18的数据
        $today = Carbon::today();
        $starttime = $Endtime ;//上面的结束时间点为开始时间
        $aftertoday = $today->addHours(18);//增加18小时
        $Endtime = date('Y-m-d H:i:s', strtotime($aftertoday));
        $edata= days($starttime,$Endtime,$params)->sum('account_logs.amount');//这个时间段的金额总和
        $ecount= days($starttime,$Endtime,$params)->count();//这个时间段的交易笔数
        //18到21的数据
        $today = Carbon::today();
        $starttime = $Endtime ;//上面的结束时间点为开始时间
        $aftertoday = $today->addHours(21);//增加21小时
        $Endtime = date('Y-m-d H:i:s', strtotime($aftertoday));
        $fdata= days($starttime,$Endtime,$params)->sum('account_logs.amount');//这个时间段的金额总和
        $fcount= days($starttime,$Endtime,$params)->count();//这个时间段的交易笔数
//        21到24的数据
        $today = Carbon::today();
        $starttime = $Endtime ;//上面的结束时间点为开始时间
        $aftertoday = $today->addHours(24);//增加24小时
        $Endtime = date('Y-m-d H:i:s', strtotime($aftertoday));
        $gdata= days($starttime,$Endtime,$params)->sum('account_logs.amount');//这个时间段的金额总和
        $gcount= days($starttime,$Endtime,$params)->count();//这个时间段的交易笔数


        return [
            'status' => true,
            'code' => '200',
            'adata' => $adata,//0-6金额
            'bdata' => $bdata,// 6-9的金额
            'cdata' => $cdata,//9-12的金额
            'ddata' => $ddata,//12-15的金额
            'edata' => $edata,//15-18的金额
            'fdata' => $fdata,//18-21的金额
            'gdata' => $gdata,//21-24的金额

            'acount' => $acount,//0-6笔数
            'bcount' => $bcount,//6-9笔数
            'ccount' => $ccount,//9-12笔数
            'dcount' => $dcount,//12-15笔数
            'ecount' => $ecount,//15-18笔数
            'fcount' => $fcount,//18-21笔数
            'gcount' => $gcount,//21-24笔数


        ];
    }
        //月数据
        if ($params['type']==2){
            $startOfMonth = Carbon::now()->startOfmonth();
            $endOfMonth = Carbon::now()->endOfMonth();
            $c = AccountLog::select('account_logs.amount','account_logs.created_at')
                ->join('settle_logs','settle_logs.id','=','account_logs.order_id')
                ->join('users','settle_logs.user_id','=','users.id')
                ->where('account_logs.user_id',$params['id'])
                ->where('settle_logs.user_id','>',0)
                ->where('account_logs.created_at', '>=', $startOfMonth)
                ->where('account_logs.created_at', '<', $endOfMonth)
                ->get();
            return[
                'status' => true,
                'code' => '200',
                'data'=>$c,
                ];
        }
        //周数据
        if($params['type']==3){
        //星期一
        $time = Carbon::now()->startOfWeek();
        $startOfWeek =date('Y-m-d H:i:s', strtotime($time));
        $aftertoday = $time->addDays(1);
        $EndWeek = date('Y-m-d H:i:s', strtotime($aftertoday));
        $Monday = days($startOfWeek,$EndWeek,$params)->sum('account_logs.amount');
        $Mondaycount = days($startOfWeek,$EndWeek,$params)->count();
        //星期二
        $time = Carbon::now()->startOfWeek();
        $startOfWeek = $EndWeek ;
        $aftertoday = $time->addDays(2);
        $EndWeek = date('Y-m-d H:i:s', strtotime($aftertoday));
        $Tuesday= days($startOfWeek,$EndWeek,$params)->sum('account_logs.amount');
        $Tuesdaycount= days($startOfWeek,$EndWeek,$params)->count();
        //星期三
        $time = Carbon::now()->startOfWeek();
        $startOfWeek = $EndWeek ;
        $aftertoday = $time->addDays(3);
        $EndWeek = date('Y-m-d H:i:s', strtotime($aftertoday));
        $Wednesday= days($startOfWeek,$EndWeek,$params)->sum('account_logs.amount');
        $Wednesdaycount= days($startOfWeek,$EndWeek,$params)->count();
        //星期四
        $time = Carbon::now()->startOfWeek();
        $startOfWeek = $EndWeek ;
        $aftertoday = $time->addDays(4);
        $EndWeek = date('Y-m-d H:i:s', strtotime($aftertoday));
        $Thursday= days($startOfWeek,$EndWeek,$params)->sum('account_logs.amount');
        $Thursdaycount= days($startOfWeek,$EndWeek,$params)->count();
        //星期五
        $time = Carbon::now()->startOfWeek();
        $startOfWeek = $EndWeek ;
        $aftertoday = $time->addDays(5);
        $EndWeek = date('Y-m-d H:i:s', strtotime($aftertoday));
        $Friday= days($startOfWeek,$EndWeek,$params)->sum('account_logs.amount');
        $Fridaycount= days($startOfWeek,$EndWeek,$params)->count();
        //星期六
        $time = Carbon::now()->startOfWeek();
        $startOfWeek = $EndWeek ;
        $aftertoday = $time->addDays(6);
        $EndWeek = date('Y-m-d H:i:s', strtotime($aftertoday));
        $Saturday= days($startOfWeek,$EndWeek,$params)->sum('account_logs.amount');
        $Saturdaycount= days($startOfWeek,$EndWeek,$params)->count();
        //星期天
        $time = Carbon::now()->startOfWeek();
        $startOfWeek = $EndWeek ;
        $aftertoday = $time->addDays(7);
        $EndWeek = date('Y-m-d H:i:s', strtotime($aftertoday));
        $Sunday= days($startOfWeek,$EndWeek,$params)->sum('account_logs.amount');
        $Sundaycount= days($startOfWeek,$EndWeek,$params)->count();

        return[
            'status' => true,
            'code' => '200',
            'Monday' =>$Monday,
            'Tuesday' =>$Tuesday,
            'Wednesday' =>$Wednesday,
            'Thursday' =>$Thursday,
            'Friday' =>$Friday,
            'Saturday' =>$Saturday,
            'Sunday' =>$Sunday,

            'Mondaycount' =>$Mondaycount,
            'Tuesdaycount' =>$Tuesdaycount,
            'Wednesdaycount' =>$Wednesdaycount,
            'Thursdaycount' =>$Thursdaycount,
            'Fridaycount' =>$Fridaycount,
            'Saturdaycount' =>$Saturdaycount,
            'Sundaycount' =>$Sundaycount,
        ];
    }
    }

}