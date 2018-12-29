<?php

namespace App\Http\Controllers\Merchant;

use App\AccountLog;
use App\Exceptions\ErrorMessageException;
use App\User;
use App\UserAccount;
use App\Withdrawal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WithdrawalController extends Controller
{
    public function index(){

        $search_items = [
            'mobile' => [
                'type' => 'custom',
                'form' => 'text',
                'label' => '商户号',
            ],
            'out_order_no' => [
                'type' => 'like',
                'form' => 'text',
                'label' => '提现订单号',
            ],
            'status' => [
                'type' => 'equal',
                'form' => 'select',
                'label' => '提现状态',
                'options' => Withdrawal::STATUS,
            ],
            'created_at' => [
                'type' => 'date',
            ],
        ];

        $user = User::getMerchantAuthUser();

        $data = Withdrawal::latest()
            ->with('user')
            ->where('user_id', $user->id)
            ->search($search_items)
            ->paginate();
        
        return _view('merchant.withdrawal.index', compact('data', 'user'));
    }

    public function detail($id)
    {
        $user_id = Auth::guard('merchant')->id();

        $data = Withdrawal::where([['id', "=", $id],['user_id', "=", $user_id]])
            ->with('user')
            ->first();

        if (empty($data)) {
            return back()->withErrors('该提现订单不合法');
        }

        return _view('merchant.withdrawal.detail', compact('data'));
    }

    public function withdrawal(Request $request){
        $messages = [
            'amount.required' => '提现金额必须',
            'amount.numeric' => '提现金额必须为数字',
            'amount.min' => '提现金额必须大于10元',
        ];

        $this->validate($request, [
            'amount' => 'required|numeric|min:10',
            'tube_id' => 'required|exists:tubes,id'
        ], $messages);


        $amount = yuanToFen($request->get('amount'));
        $tube_id = $request->get('tube_id');

        $user = User::getMerchantAuthUser();
        $orderNo = Withdrawal::generateOrderNo($tube_id);

        $userAccount = UserAccount::getUserAccount($user->id, $tube_id);

        if ($amount > $userAccount->balance){
            throw new ErrorMessageException('提现金额不能大于账户余额');
        }

        DB::beginTransaction();
        try {
            // 修改账户余额
            $userAccount->balance -= $amount;
            if ($userAccount->balance < 0){
                throw new \Exception('');
            }
            $userAccount->save();

            // 增加提现记录
            $withdrawal = Withdrawal::create([
                'user_id' => $user->id,
                'order_no' => $orderNo,
                'trade_amount' => $amount,
                'real_amount' => $amount,
                'type' => Withdrawal::TYPE_ACCOUNT_BALANCE,
                'status' => Withdrawal::STATUS_WAITING,
            ]);

            // 增加账变流水
            AccountLog::create([
                'user_id' => $user->id,
                'no' => $orderNo,
                'amount' => $amount,
                'business' => AccountLog::BUSINESS_WITHDRAWAL,
                'balance' => $userAccount->balance,
                'type' => AccountLog::WITHDRAWAL_ACCOUNT_BALANCE,
                'flow' => AccountLog::FLOW_OUT,
                'snap' => json_encode(['withdrawal' => $withdrawal, 'user_account' => $userAccount]),
            ]);

            DB::commit();
        }catch (\Exception $e){
            DB::rollback();
            Log::error('提现申请异常');
            Log::error($e->getMessage());

            throw new ErrorMessageException('提现申请异常');
        }

        return back()->with('msg', '提现申请成功');
    }

}
