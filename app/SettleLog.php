<?php

namespace App;

use App\Exceptions\ErrorMessageException;
use App\Http\Traits\CommonExportTrait;
use App\Http\Traits\Searchable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettleLog extends Model
{
    use Searchable, CommonExportTrait;

    protected $fillable = [
        'user_id', 'tube_id', 'status', 'settle_no', 'type',
        'settled_amount', 'waiting_amount', 'total_amount', 'refund_amount', 'real_amount', 'charge_amount',
        'settled_orders', 'waiting_orders', 'snap',
    ];

    protected $hidden = ['snap'];

    const STATUS_WAITING = 1;
    const STATUS_SETTLED = 2;
    const STATUS = [
        self::STATUS_WAITING => '待结算',
        self::STATUS_SETTLED => '已结算',
    ];

    const TYPE_TUBE = 1;
    const TYPE_MERCHANT = 2;
    const TYPE = [
        self::TYPE_TUBE => '通道结算单',
        self::TYPE_MERCHANT => '商户结算单',
    ];

    public function tube()
    {
        return $this->belongsTo(Tube::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);

    }

    public final function custom_mobile($search_item, $search_params)
    {
        $this->builder->whereHas('user', function ($query) use ($search_item) {
            $query->where('mobile', 'like', $this->request->get($search_item))->orwhere('name', 'like', $this->request->get($search_item));
        });
    }

    public function scopeTube($query)
    {
        return $query->where('type', self::TYPE_TUBE);
    }

    public function scopeMerchant($query)
    {
        return $query->where('type', self::TYPE_MERCHANT);
    }

    public function scopeStore($query, $user)
    {
        return $query->whereHas('user', function ($query) use ($user) {
            $query->store()->where('aid', $user->id);
        });
    }

    public function scopeWaiting($query)
    {
        return $query->where('status', self::STATUS_WAITING);
    }

    public function scopeSettled($query)
    {
        return $query->where('status', self::STATUS_SETTLED);
    }


    public function isWaiting()
    {
        return intval($this->status) === self::STATUS_WAITING;
    }

    public function isSettled()
    {
        return intval($this->status) === self::STATUS_SETTLED;
    }

    public function getSettledAmountAttribute($value)
    {
        return app()->resolved('blade.compiler') ? fenToYuan($value) : $value;
    }

    public function getWaitingAmountAttribute($value)
    {
        return app()->resolved('blade.compiler') ? fenToYuan($value) : $value;
    }

    public function getTotalAmountAttribute($value)
    {
        return app()->resolved('blade.compiler') ? fenToYuan($value) : $value;
    }

    public function getRealAmountAttribute($value)
    {
        return app()->resolved('blade.compiler') ? fenToYuan($value) : $value;
    }

    public function getRefundAmountAttribute($value)
    {
        return app()->resolved('blade.compiler') ? fenToYuan($value) : $value;
    }

    public function getChargeAmountAttribute($value)
    {
        return app()->resolved('blade.compiler') ? haoToYuan($value) : $value;
    }

    public final function custom_tube_id($search_item, $search_params)
    {
        $this->builder->whereHas('tube', function ($query) use ($search_item) {
            $query->where('id', $this->request->get($search_item));
        });
    }

    protected static function getProfitsByOrder(Order $order)
    {

        // paid()

        $aUsers = User::whereIn('id', explode(',', $order->user->aids))
            ->with('user_agent_channels')
            ->checkedAgent()
            ->get()
            ->reverse();

        $lastProfitRate = 0;
        $profits = collect();

        foreach ($aUsers as $aUser) {

            $profitRate = $aUser->user_agent_channels->where('channel_id', $order->channel_id)->first()->profit_rate ?? 0;
            $profit = ($profitRate - $lastProfitRate) * $order->real_amount / 10000;
            $earn = floor($profit);
            $change = floor(($profit * 10000 - $earn * 10000) / 10000 * 100);

            $lastProfitRate = $profitRate;

            $profits[] = [
                'user' => $aUser,
                'profit' => $profit,
                'earn' => $earn,
                'change' => $change,
            ];
        }

        return $profits;

    }

    public static function generateSettleLogs()
    {

        $ordersUsersTubes = Order::PaidDate(self::getSettleDay())->Paid()
            ->with('channel', 'user')
            ->get()
            ->groupBy(['channel.tube_id', 'user_id']);
        foreach ($ordersUsersTubes as $tube_id => $ordersUsers) {
            foreach ($ordersUsers as $user_id => $orders) {
                self::generateMerchantSettleLogs($tube_id, $orders, $user_id);
            }
            self::generateTubeSettleLogs($tube_id, $ordersUsers->collapse());
        }

        return 'success';
    }

    private static function generateTubeSettleLogs($tube_id, $orders)
    {
        $now = self::getCreateDay();
        $check_log = self::CreateDate($now)->Tube()->where('tube_id', '=', $tube_id)->first();
        if (!empty($check_log)) {
            return false;
        }

        $totalAmount = 0;
        $paidAmount = 0;
        $chargeAmount = 0;
        foreach ($orders as $order) {
            $totalAmount += $order->real_amount;
            /*if ($order->isPaid()) {
                $paidAmount += $order->real_amount;
                $profits = self::getProfitsByOrder($order);
                $chargeAmount += $profits->sum('profit') * 100;
            }*/
        }

        $settleData = [
            'tube_id' => $tube_id,
            'status' => SettleLog::STATUS_WAITING,
            'settle_no' => self::generateSettleNo($tube_id),
            'type' => self::TYPE_TUBE,
            'total_amount' => $totalAmount,
            'waiting_amount' => $totalAmount,
            'real_amount' => $totalAmount,
            'settled_amount' => 0,
            'refund_amount' => 0,
            'charge_amount' => $chargeAmount,
            'snap' => '',
            'created_at' => $now,
            'updated_at' => $now,
        ];

        SettleLog::create($settleData);
    }

    private static function generateMerchantSettleLogs($tube_id, $orders, $user_id)
    {
        $now = self::getCreateDay();
        $check_log = self::CreateDate($now)->Merchant()->where('tube_id', '=', $tube_id)->where('user_id', '=', $user_id)->first();
        //dd($check_log);
        if (!empty($check_log)) {
            return false;
        }
        $totalAmount = 0;
        $paidAmount = 0;
        $chargeAmount = 0;
        $waitSettleAmount = 0;
        $isSettledAmount = 0;
        $settled_orders = '';
        $waiting_orders = '';

        $user = User::find($user_id);
        $profitRate = $user->payable_user->user_merchant_tubes->where('tube_id', $tube_id)->first()->profit_rate ?? 0;

        foreach ($orders as $order) {
            $totalAmount += $order->real_amount;
            if ($order->waitingSettle()) {
                $waitSettleAmount += $order->real_amount;
                $waiting_orders .= ($order->id . ',');
            } else {
                $isSettledAmount += $order->real_amount;
                $settled_orders .= ($order->id . ',');
            }
        }


        $chargeAmount = $profitRate * $waitSettleAmount / 100;  //单位毫！！！！ 这个是容易忽略的点

        $settleData = [
            'tube_id' => $tube_id,
            'user_id' => $user->id,
            'status' => SettleLog::STATUS_WAITING,
            'settle_no' => self::generateSettleNo($tube_id),
            'type' => self::TYPE_MERCHANT,
            'total_amount' => $totalAmount,
            'waiting_amount' => $waitSettleAmount,
            'settled_amount' => $isSettledAmount,
            'real_amount' => $totalAmount,
            'settled_orders' => $settled_orders,
            'waiting_orders' => $waiting_orders,
            'refund_amount' => 0,//暂时不做退款
            'charge_amount' => $chargeAmount,
            'snap' => json_encode(['profitRate' => $profitRate]),
            'created_at' => $now,
            'updated_at' => $now,
        ];

        SettleLog::create($settleData);
    }

    private static function generateSettleNo($tube_id)
    {
        return date('YmdHis') . $tube_id . random_int(10000, 99999);
    }

    public function getSettleOrderDate()
    {
        return $this->created_at->subDay()->toDateString();
    }

    //结算通道对账单- 代理
    public function settleForTube()
    {
        $usersChannelsOrders = SettleLog::CreateDate($this->created_at)->where('user_id', '!=', 0)->whereHas('tube', function ($query) {
            $query->where('id', $this->tube_id);
        })->get()->groupBy(['user_id', 'tube_id']);

        //dd($usersChannelsOrders);
        DB::beginTransaction();
        try {
            foreach ($usersChannelsOrders as $user_id => $channelsOrders) {
                $user = User::findOrFail($user_id)->payable_user;

                //获取商户上级的所有推荐人用户数据
                $aUsers = User::whereIn('id', explode(',', $user->aids))
                    ->with('user_agent_channels')
                    ->checkedAgent()
                    ->get()
                    ->reverse(); //翻转

                foreach ($channelsOrders as $channel_id => $channel_settle) {
                    if (!empty($channel_settle)) {

                        foreach ($channel_settle as $v) {
                            $paidAmount = $v->total_amount;
                            // 对每个代理执行分润
                            $lastProfitRate = 0;

                            //分润方式，是从终端商户，一级一级上面递推并返利，总计60个点，  如果没有分配，则以0处理
                            foreach ($aUsers as $aUser) {
                                if (!$aUser->isCheckedAgent()) {
                                    continue;
                                }

                                $profitRate = $aUser->user_agent_channels->where('channel_id', $channel_id)->first()->profit_rate ?? 0;
                                $intervalProfit = $profitRate - $lastProfitRate;
                                if ($intervalProfit < 0) {
                                    throw new ErrorMessageException("$aUser->id:渠道 $channel_id 费率异常！");
                                }
                                $profit = $intervalProfit * $paidAmount / 10000;  //得到代理当前应得的费率
                                $saveAmount = floor($profit);
                                $change = floor(($profit * 10000 - $saveAmount * 10000) / 10000 * 100);

                                // 代理增加余额、零钱
                                $account = $aUser->getAccountByTubeId($this->tube->id);

                                $account->change += $change;

                                if ($account->change >= 100) {
                                    $account->change -= 100;
                                    $saveAmount += 1;
                                }
                                $account->balance += $saveAmount;
                                $account->save();

                                // 商户增加余额


                                // 添加账变
                                AccountLog::create([
                                    'user_id' => $aUser->id,
                                    'order_id' => $v->id,
                                    'no' => $this->settle_no,
                                    'amount' => $saveAmount,
                                    'business' => AccountLog::BUSINESS_SETTLE,
                                    'balance' => $account->balance,
                                    'type' => AccountLog::SETTLE_MERCHANT_TRADE_PROFIT,
                                    'flow' => AccountLog::FLOW_IN,
                                    'snap' => $this,
                                ]);

                                $lastProfitRate = $profitRate;
                            }
                        }
                    }
                }

            }
            // 修改结算单状态
            $this->status = SettleLog::STATUS_SETTLED;
            $this->settled_amount += $this->waiting_amount;
            $this->waiting_amount = 0;
            $this->save();

            // 添加操作日志


            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('通道结算单结算错误');
            Log::error($e->getMessage());

            throw new ErrorMessageException('结算异常');
        }

    }

    //结算商户对账单
    public function settleForMerchant()
    {
        //如果是门店，收益转商户
        //TODO 增加商户流水说明，来至哪个门店
        $user = $this->user->payable_user;

        /*  $orders = Order::PaidDate($this->created_at->subDay())
              ->whereHas('channel.tube', function ($query) {
                  $query->where('id', $this->tube_id);
              })
              ->where('user_id', $this->user->id)
              ->get();

          dd($orders);*/
        DB::beginTransaction();
        try {
            /*  $totalAmount = 0;
              $paidAmount = 0;
              foreach ($orders as $order) {
                  $totalAmount += $order->real_amount;
                  if ($order->isPaid()) {
                      $paidAmount += $order->real_amount;
                  }
              }*/

            // 计算商户分润
            //$profitRate = $user->user_merchant_tubes->where('tube_id', $this->tube_id)->first()->profit_rate ?? 0;
            //$profit = $profitRate * $paidAmount / 10000;
            //$saveAmount = floor($profit);
            //$change = floor(($profit * 10000 - $saveAmount * 10000) / 10000 * 100);


            $profit = $this->charge_amount;
            $saveAmount = floor($profit / 100);
            $change = $this->charge_amount - $saveAmount * 100;

            // 商户增加余额、零钱
            $account = $user->getAccountByTubeId($this->tube->id);

            //$account->balance += $saveAmount;
            $account->change += $change; //毫的计算

            if ($account->change >= 100) {
                $account->change -= 100;
                $saveAmount++;
            }
            $account->balance += $saveAmount;
            $account->save();


            // 添加账变
            AccountLog::create([
                'user_id' => $user->id,
                'order_id' => $this->id, //账变来源id
                'no' => $this->settle_no,
                'amount' => $saveAmount,
                'business' => AccountLog::BUSINESS_SETTLE,
                'balance' => $account->balance,
                'type' => AccountLog::SETTLE_TRADE_PROFIT,
                'flow' => AccountLog::FLOW_IN,
                'snap' => $this,
            ]);

            // 修改结算单状态
            $this->status = SettleLog::STATUS_SETTLED;
            $this->settled_amount += $this->waiting_amount;
            $this->waiting_amount = 0;
            $update_arr = '';
            if (($order_list = $this->waiting_orders) != '') {
                $order_list = substr($order_list, 0, strlen($order_list) - 1);
                $update_arr = explode(",", $order_list);
            }

            DB::table('orders')
                ->whereIn('id', $update_arr)
                ->update(['is_settle' => 1]);

            $this->save();

            // 添加操作日志


            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('商户结算单结算错误');
            Log::error($e->getMessage());

            throw new ErrorMessageException('结算异常');
        }
    }

    public function scopeCreateDate($query, Carbon $date)
    {
        return $query->whereBetween('created_at', [$date->startOfDay(), $date->copy()->endOfDay()]);
    }

    private static function getSettleDay()
    {
        //return Carbon::today();
        return Carbon::yesterday();
    }

    private static function getCreateDay()
    {
        return Carbon::today();
        //return Carbon::yesterday();
    }


}
