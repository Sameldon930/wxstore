<?php

namespace App\Http\Controllers\Admin;


use App\Services\WebServices\StatusSwitchService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSideRequest;
use App\Side;

class SideController extends Controller
{
    //获取幻灯片列表  20个分页
    public function index(){
        $datas = Side::paginate(20);
        return _view('admin.side.index',compact('datas'));
    }

    //修改幻灯片页面
    public function edit($id){
        $data = Side::find($id);
        return _view('admin.side.edit',compact('data'));
    }

    //添加幻灯片页面
    public function add(){
        $data = null;
        return _view('admin.side.edit',compact('data'));
    }

    //更新幻灯片
    public function update(StoreSideRequest $request,$id){
        //获取对应幻灯片的id
        $data = Side::findOrFail($id);
        //所有请求放到input变量中
        $input = $request->all();

        //判断文件上传
        if(isset($input['file']) && is_object($input['file'])){
            $file_name = save_image_file($input['file'],'side');
            if(!$file_name){
                return back()->withErrors('msg','图片上传失败');
            }
            $input['image'] = $file_name;
            $data->image = $input['image'];

        }
        //保存原有的传进去的字段值
        $data->note = $input['note'];
        $data->url = $input['url'];
        $data->orderby = $input['orderby'];
        $data->group_id = $input['group_id'];
        $data->save();

        return redirect()->route('admin.side.index')->with('msg','幻灯片更新成功');

    }

    //添加幻灯片
    public function store(StoreSideRequest $request){
        $input = $request->all();
        //判断传入的图片是否符合图片文件要求,如果不符合报错,如果没有传,提示请上传
        if(isset($request['file']) && is_object($request['file'])){
            $file_name = save_image_file($input['file'],'side');
            if(!$file_name){
                return back()->withErrors('msg','图片上传失败');
            }
            $input['image'] = $file_name;
            unset($input['_token']);
            unset($input['file']);
        }else{
            return back()->withErrors('msg','请上传图片');
        }
        $input['url'] =  $input['url'];
        $input['note'] =  $input['note'];
        $input['orderby'] =  $input['orderby'];
        $input['group_id'] =  $input['group_id'];
        //create插入数据
        $result = Side::create($input);

        //判断存入的数据是否成功
        if($result){
            return redirect()->route('admin.side.index')->with('msg','添加幻灯片成功');
        }else{
            return back()->withErrors('msg','添加失败!');
        }


    }

    //删除幻灯片
    public function destroy($id){
        Side::destroy($id);
        return back()->with('msg', '删除成功');
    }

    //开关
    public function switchStatus(Request $request, $id){
        return StatusSwitchService::change(Side::class, $id, $request->get('status'));
    }






}