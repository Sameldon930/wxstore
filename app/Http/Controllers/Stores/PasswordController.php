<?php

namespace App\Http\Controllers\Stores;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
    public function change_password(){
        return _view('stores.password.change_password');
    }

    public function update_password(Request $request){

        if ($data = $request->all()) {
            $old_password = $request->input('old_password');
            $password = $request->input('password');
            $data = $request->all();
            $rules = [
                'old_password'=>'required|between:6,20',
                'password'=>'required|between:6,20|confirmed',
            ];
            $messages = [
                'required' => '密码不能为空',
                'between' => '密码必须是6~20位之间',
                'confirmed' => '新密码和确认密码不匹配'
            ];
            $validator = Validator::make($data, $rules, $messages);
            $user = Auth::guard('stores')->user();
            $validator->after(function($validator) use ($old_password, $user) {
                if (!\Hash::check($old_password, $user->password)) {
                    $validator->errors()->add('old_password', '原密码错误');
                }
            });
            if ($validator->fails()) {
                return back()->withErrors($validator);  //返回一次性错误
            }
            $user->password = bcrypt($password);
            $user->save();
            return redirect()->route('stores.password.change_password')->with('msg', '密码修改成功');
        }
        return redirect()->route('stores.password.change_password')->with('error', '系统出错');
    }
}
