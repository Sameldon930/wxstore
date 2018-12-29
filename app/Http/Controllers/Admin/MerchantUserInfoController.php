<?php

namespace App\Http\Controllers\Admin;

use App\ActionLog;
use App\Libs\MobileMsg\MobileMsgSend;
use App\SavingCard;
use App\Http\Controllers\Controller;
use App\MerchantInfo;
use App\Tube;
use App\User;
use App\UserAccount;
use App\UserInfo;
use App\UserMerchantInfo;
use Illuminate\Http\Request;

class MerchantUserInfoController extends Controller
{
    public function index(){

        $search_items = [
            'company_name' => [
                'type' => 'like',
                'form' => 'text',
                'label' => '商户名称',
            ],
            'mobile' => [
                'type' => 'like',
                'form' => 'text',
                'label' => '手机号',
            ],
            'status' => [
                'type' => 'equal',
                'form' => 'select',
                'label' => '状态',
                'options' => MerchantInfo::STATUS,
            ],
            'created_at' => [
                'type' => 'date',
            ],
        ];

        $data = MerchantInfo::latest()
            ->with('user')
            ->search($search_items)
            ->where('status','1')
            ->paginate();
//        dd($data->toArray());
        return _view('admin.merchant_user_info.index', compact('data'));
    }

    public function create($id){
        return _view('admin.merchant_user_info.create', compact('id'));
    }
    public function show($id){
        $data = MerchantInfo::latest()
            ->with('user')
            ->where('user_id',$id)
            ->first();
        $data->contract_tenancy = json_decode( $data->contract_tenancy);
        $data->interior_picture = json_decode( $data->interior_picture);
           $back = SavingCard::latest()
               ->where('user_id',$id)
               ->first();
//           dd($back);
        return _view('admin.merchant_user_info.show', compact('data','back'));

    }

    public function adopt(Request $request, $id)
    {
        $successId = '376423';//通过短信模版
        $failId = '376419';//不通过短信模版
        $mobileSend = new MobileMsgSend();
        if ($request->status == MerchantInfo::SUCCESS_AUDIT) {
            $merchantInfo = MerchantInfo::where('id', $id)->first();
            $merchantInfo->save();
            $mobileSend->send($merchantInfo->mobile, '商户认证', $successId, false);
            return redirect()->route('admin.merchant.index')->with('msg', '资料录入成功，该审核通过');
        }else{
            $time = date("Y-m-d H:i:s");
            $merchantInfo = MerchantInfo::find($id);
            $merchantInfo->status = MerchantInfo::NOT_AUDIT;
            $merchantInfo->feedback = $request->feedback;
            $merchantInfo->save();
//            $refuses = SavingCard::where('user_id',$merchantInfo->user_id)->first();
//             $refuses->deleted_at = $time;
//             $refuses->save();
            $mobileSend->send($merchantInfo->mobile, '拒绝进件',$failId,false);
            return redirect()->route('admin.merchant.index')->with('msg', '拒绝成功');
        }
    }
    public function pass(Request $request, $id){
        $user = User::enabled()->findOrFail($id);

        $this->validate($request, [
            'wechat_merchant_no' => 'required',
            'ali_merchant_no' => 'required',
            'ali_auth_token' => 'required',
        ]);

        User::becomeCheckedMerchant($user, $request->only(['wechat_merchant_no', 'ali_merchant_no', 'ali_auth_token']));

        ActionLog::log(ActionLog::TYPE_USER, $user, "商户信息审核（{$user->id}）");

        return redirect()->route('admin.merchant.index')->with('msg', '资料录入成功，该审核通过');
    }

}
