<?php

namespace App\Http\Controllers\Agent;

use App\AccountLog;
use App\Lib\RegEx\RegEx;
use App\User;
use App\UserAgentInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AgentInfoCheckController extends Controller
{
    public function index(){
        $data = UserAgentInfo::where('user_id', User::getAgentAuthUser()->id)
            ->first()
        ;

        if ($data && $data->isChecked()){
            throw new \Exception('非法请求');
        }

        return _view('agent.agent.info_check', compact('data'));
    }

    public function info_store(Request $request){
        $inputs = $request->all();
        $user = User::getAgentAuthUser();
        $isStore = $inputs['store'] ?? false;
        $userAgentInfo = UserAgentInfo::where('user_id', $user->id)->first();

        if ($userAgentInfo) {
            if ($isStore){
                return back()->withErrors('不可重复上传信息。');
            } else {
                if ($userAgentInfo->isChecked()){
                    return back()->withErrors('已审核通过，修改需联系客服人员。');
                }
            }
        }

        $type = $inputs['type'] = intval($inputs['type']);

        if (!$type){
            return back()->withErrors('非法提交');
        }

        // 先传图
        foreach (UserAgentInfo::IMGS[$type] as $img_column) {
            $old_column = "old_" . $img_column;
            if ($request->$old_column) {
                if (strlen($request->$old_column) <= 200) { // TODO: 判断 ".png" 结尾和 base64 ?
                    $inputs[$img_column] = $request->$old_column;
                    session()->flash($old_column, $request->$old_column);
                } else {
                    $img = base64_decode(explode(',', $request->$old_column)[1]);
                    $img_name = $user->mobile . '-' . date('YmdHis') . '-' . $img_column . '.png'; // TODO：图片不能随意看
                    Storage::disk('user_info')->put($img_name, $img);
                    $inputs[$img_column] = 'user_info/' . $img_name;
                    session()->flash($old_column, 'user_info/' . $img_name);
                }
            }
        }

        $rules = $this->getInfoCheckRules($inputs, $type);
        $v = Validator::make($inputs, $rules, UserAgentInfo::getInfoCheckMessages());
        if ($v->fails()) {
            return back()->with('form_errors', $v->errors())->withInput($inputs);
        }

        $createData = $this->getCreateData($inputs);
        $createData['user_id'] = $user->id;
        $createData['type'] = $type;
        $createData['status'] = UserAgentInfo::CHECKING;

        UserAgentInfo::create($createData);

        if ($userAgentInfo){
            $userAgentInfo->delete();
        }

        return redirect()->back()->with('msg', '信息提交成功，请等待审核');
    }

    private function getInfoCheckRules($inputs, $type){
        $cleanRules = [
            'cleaner_name' => ['required', 'regex:' . RegEx::ZH],
            'cleaner_mobile' => ['required', 'regex:' . RegEx::MOBILE],
            'cleaner_deposit' => ['required', 'regex:' . RegEx::DEPOSIT],
            'cleaner_idcard' => ['required', 'regex:' . RegEx::IDENTITY],
            'cleaner_idcard_front' => ['required'],
            'cleaner_idcard_back' => ['required'],
        ];
        $companyRules = $cleanRules + [
            'company_name' =>  ['required', 'regex:' . RegEx::ZH],
            'company_business_licence' => ['required'],

            'legal_name' => ['required', 'regex:' . RegEx::ZH],
            'legal_idcard' => ['required', 'regex:' . RegEx::IDENTITY],
            'legal_idcard_front' => ['required'],
            'legal_idcard_back' => ['required'],

            'manager_name' => ['required', 'regex:' . RegEx::ZH],
            'manager_mobile' => ['required', 'regex:' . RegEx::MOBILE],
        ];



        if ($type === UserAgentInfo::TYPE_PERSONAL){
            $rules = $cleanRules;
        }else if ($type === UserAgentInfo::TYPE_INDIVIDUAL_BUSINESS){
            $rules = $companyRules;

            if (isset($inputs['company_account'])){
                $rules['company_account'] = ['regex:' . RegEx::DEPOSIT];
            }

        } else if ($type === UserAgentInfo::TYPE_COMPANY) {
            $rules = $companyRules;
            $rules['company_account'] = ['required', 'regex:' . RegEx::DEPOSIT];
        }else {
            throw new \Exception('错误的账户类型');
        }

        return $rules;
    }

    private function getCreateData($inputs){
        $type = $inputs['type'];

        $cleanerData = [
            'cleaner_name' => $inputs['cleaner_name'],
            'cleaner_mobile' => $inputs['cleaner_mobile'],
            'cleaner_deposit' => $inputs['cleaner_deposit'],
            'cleaner_idcard' => $inputs['cleaner_idcard'],
            'cleaner_idcard_front' => $inputs['cleaner_idcard_front'],
            'cleaner_idcard_back' => $inputs['cleaner_idcard_back'],
        ];

        $companyData = $cleanerData + [
                'company_name' =>  $inputs['company_name'],
                'company_business_licence' => $inputs['company_business_licence'],

                'legal_name' => $inputs['legal_name'],
                'legal_idcard' => $inputs['legal_idcard'],
                'legal_idcard_front' => $inputs['legal_idcard_front'],
                'legal_idcard_back' => $inputs['legal_idcard_back'],

                'manager_name' => $inputs['manager_name'],
                'manager_mobile' => $inputs['manager_mobile'],
        ];

        if ($type === UserAgentInfo::TYPE_PERSONAL){
            $data = $cleanerData;
        }else if ($type === UserAgentInfo::TYPE_INDIVIDUAL_BUSINESS){
            $data = $companyData;
            $data['company_account'] = $inputs['company_account'] ?? null;
        } else if ($type === UserAgentInfo::TYPE_COMPANY) {
            $data = $companyData;
            $data['company_account'] = $inputs['company_account'];
        }else {
            throw new \Exception('错误的账户类型');
        }

        return $data;
    }
}
