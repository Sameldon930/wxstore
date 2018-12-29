<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequest;
use App\Message;
use Illuminate\Support\Facades\Auth;
use App\Services\WebServices\StatusSwitchService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    //获取系统信息列表
    public function  index(){
        $search_items = [
            'title'=>[
                'type'=>'like',
                'form'=>'text',
                'label'=>'标题',
            ],
            'created_at'=>[
                'type'=>'date',
            ]
        ];
        $datas = Message::latest()
            ->search($search_items)
            ->paginate(20);
        return _view('admin.message.index',compact('datas'));

    }
    //获取列表中的单条消息进行编辑
    public function edit($id){
        $data  = Message::find($id);
        return _view('admin.message.edit',compact('data'));
    }

    //系统消息更新
    public function update(StoreMessageRequest $request,$id){
        $data = Message::findOrFail($id);
        $input = $request->all();
        $data->title = $input['title'];
        $data->content = $input['content'];
        $data->text = $input['text'];
        $data->now = $input['now'];
        $data->save();

        return redirect()->route('admin.message.index')->with('msg','系统消息更新成功!');

    }

    //系统消息添加页面
    public function  add(){
        $data = null;
        return _view('admin.message.edit',compact('data'));
    }

    //系统消息添加控制器
    public function store(StoreMessageRequest $request){
        $input = $request->all();
        $result = Message::create($input);
        if($result){
            return redirect()->route('admin.message.index')->with('添加成功!');
        }else{
            return back()->withErrors('msg','添加失败');
        }
    }

    //删除单条信息
    public function destroy($id){

        Message::destroy($id);
        return back()->with('msg','删除成功!');

    }
    //开关
    public function switchStatus(Request $request, $id){
        return StatusSwitchService::change(Message::class, $id, $request->get('status'));
    }








}