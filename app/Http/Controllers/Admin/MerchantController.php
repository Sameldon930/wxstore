<?php

namespace App\Http\Controllers\Admin;

use App\ActionLog;
use App\MerchantInfo;
use App\MetaData;
use App\Services\WebServices\StatusSwitchService;
use App\Tube;
use App\User;
use App\UserAccount;
use App\UserMerchantInfo;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMerchantRequest;

use App\Http\Controllers\Controller;

class MerchantController extends Controller
{

    public function index(){

        $search_items = [
            'name' => [
                'type' => 'like',
                'form' => 'text',
                'label' => '名称',
            ],
            'mobile' => [
                'type' => 'like',
                'form' => 'text',
                'label' => '账号',
            ],
            'created_at' => [
                'type' => 'date',
            ],
        ];

        $data = User::merchant()
            ->latest()
            ->with('a_user')
            ->search($search_items)
            ->paginate();

        return _view('admin.merchant.index', compact('data'));
    }

    public function edit(Request $request, $id){
        $data = User::merchant()->with('user_merchant_tubes','user_merchant_info')->findOrFail($id);

        return _view('admin.merchant.edit', compact('data'));
    }

    public function update(Request $request, $id){

        $user = User::merchant()
            ->with('user_merchant_info')
            ->findOrFail($id);

        $this->validate($request, [
            'mobile' => 'required|unique:users,mobile,' . $user->id,
            'name' => 'required',

        ]);
        // TODO
        $maxRates = [
            'WECHAT' => MetaData::getValueByKey(MetaData::MERCHANT_WECHAT_MAX_PROFIT_RATE, 0),
            'ALI' => MetaData::getValueByKey(MetaData::MERCHANT_ALI_MAX_PROFIT_RATE, 0),
        ];
        foreach ($user->user_merchant_tubes as $k => $merchant_tube){
            // 保存分润费率
            $tubeProfitRate = $request->get('profit_rate_' . $merchant_tube->tube_id);
            if (null === $tubeProfitRate){
                return back()->withInput($request->all())->withErrors('请输入每个通道的分润费率');
            }
            $tubeProfitRate = intval($tubeProfitRate);
            if ($tubeProfitRate < 0){
                return back()->withInput($request->all())->withErrors("分润费率必须大于0");
            }

            $tubeMaxRate = $maxRates[$merchant_tube->tube->name];
            if ($tubeProfitRate > $tubeMaxRate){
                return back()->withInput($request->all())->withErrors("对于通道（{$merchant_tube->tube->display}），你对该代理设置的分润费率（{$tubeProfitRate}）不能高于对应通道的最高分润费率（{$tubeMaxRate}）"
                );
            }
            $user->user_merchant_tubes[$k]->profit_rate = $tubeProfitRate;


            // 保存通道费率
            $tubeTubeRate = $request->get('tube_rate_' . $merchant_tube->tube_id);
            if (null === $tubeTubeRate){
                return back()->withInput($request->all())->withErrors('请输入每个通道的通道费率');
            }
            $tubeTubeRate = intval($tubeTubeRate);
            if ($tubeTubeRate < 0){
                return back()->withInput($request->all())->withErrors("通道费率必须大于0");
            }
            $user->user_merchant_tubes[$k]->tube_rate = $tubeTubeRate;
        }

        foreach ($user->user_merchant_tubes as $k => $agentChannel){
            $user->user_merchant_tubes[$k]->save();
        }

        $user->mobile = $request->get('mobile');
        $user->name = $request->get('name');
        $user->save();

        ActionLog::log(ActionLog::TYPE_USER, $user, "更新商户（{$user->id}）");
        User::becomeCheckedMerchant($user, $request->only(['wechat_merchant_no', 'ali_merchant_no', 'ali_auth_token']));

        return redirect()->route('admin.merchant.index')->with('msg', '修改成功');
    }

    public function store(Request $request){

        $this->validate($request, [
            'password' => 'required|min:6|max:18',
            'a_mobile' => 'required',
            'name' => 'required',
            'real_mobile' => 'required|numeric|digits_between:11,11',
        ]);
        $input = $request->all();

        $check_mobile=User::where('real_mobile','=',$request->get('real_mobile'))->first();
        if(!empty($check_mobile)){
            return back()->withInput()->withErrors(['手机号码已经注册!']);
        }
        $aUser = User::getAgentUserByMobile($request->get('a_mobile'));

        $user = User::createMerchant($request->get('name'), $request->get('password'), $aUser,$request->get('real_mobile'));

        $merchant_info = MerchantInfo::where('user_id',$user->id)->first();
        $create_data = [
            'user_id' => $user->id,
            'status' => '4'
        ];
        if(empty($merchant_info)){
            MerchantInfo::create($create_data);
        }else{
            $merchant_info->status = 4;
            $merchant_info->save();
        }

        ActionLog::log(ActionLog::TYPE_USER, $user, "添加商户（{$user->id}）");

        return redirect()->route('admin.merchant.index')->with('msg', '商户添加成功');
    }

    public function show($id){
        $data = User::merchant()->findOrFail($id);
        return _view('admin.merchant.show', compact('data'));
    }

    public function destroy($id){
        User::destroy($id);

        return back()->with('msg', '删除成功');
    }

    public function switchStatus(Request $request, $id){
        return StatusSwitchService::change(User::class, $id, $request->get('status'));
    }


    //商户账户余额列表
    public function account_list(){
        $search_items = [
            'mobile' => [
                'type' => 'equal',
                'form' => 'text',
                'label' => '账号',
            ],
            'created_at' => [
                'type' => 'date',
            ],
        ];
        $data=UserAccount::orderBy('user_accounts.id','desc')
            ->join('users','users.id','=','user_accounts.user_id')
            ->search($search_items)->paginate();
        return _view('admin.merchant.account_list', compact('data'));
    }
}
