<?php
namespace App\ApiServices\InServices\Response;

use App\Order;
use App\SettleLog;
use Validator;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * api测试类
 */
class StoreList extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     */
    protected $method = 'storelist';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {

        $rules = [
            'id' => 'required'
        ];
        $messages = [
            'id.required' => 'ID缺失',
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
        //获取所有门店还有门店的收益包含笔数
        $data = User::store()
                    ->where('aid','=',$params['id'])
                    ->with(['orders'=>function($query){
                        $query->select(DB::raw('user_id,sum(trade_amount) as money,count(trade_amount) as number'))
                        ->where('orders.pay_status','=','2')
                        ->groupBy('user_id');
                        }])
                    ->get();
//        //门店数量
        $data1= User::store()->where('aid','=',$params['id'])->count();

        return [
            'status' => true,
            'code' => '200',
            'data' => $data,
            'data1'=>$data1,
        ];
    }


}