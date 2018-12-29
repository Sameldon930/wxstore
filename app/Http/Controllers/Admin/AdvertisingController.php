<?php

namespace App\Http\Controllers\Admin;

use App\Advertising;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdRequest;
use Illuminate\Http\Request;
use App\Services\WebServices\StatusSwitchService;


class AdvertisingController extends Controller
{

    //获取广告图列表
    public function index(){
        $datas = Advertising::latest()
            ->get();
        return _view('admin.advertising.index',compact('datas'));
    }
    //编辑广告
    public function  edit($id){
        $data = Advertising::find($id);
        return _view('admin.advertising.edit',compact('data'));

    }
    //添加广告页面
    public function  add(){
        $data = null;
        return _view('admin.advertising.edit',compact('data'));
    }

    //更新广告图
    public function update(StoreAdRequest $request,$id){
        $data = Advertising::findOrFail($id);
        $input =$request->all();
//        dd($input);
//        die;
        //上传图片
        if(isset($input['file'])  && is_object($input['file'])){
            $file_name = save_image_file($input['file'],'advertising');
            if(!$file_name){
                return back()->withErrors('msg','图片上传失败!');
            }
            $input['image'] = $file_name;
            $data->image = $input['image'];
        }
        $data->url = $input['url'];
        $data->orderby = $input['orderby'];
        $data->note = $input['note'];
        $data->save();
        return redirect()->route('admin.advertising.index')->with('msg','更新广告图成功');
    }

    //添加广告控制器
    public function store(StoreAdRequest $request){
        $input = $request->all();
        //如果有上传图片
        if(isset($input['file']) && is_object($input['file'])){
            $file_name = save_image_file($input['file'],'advertising');
            if(!$file_name){
                return back()->withErrors('msg','上传图片失败');//图片格式不符合要求
            }
            $input['image'] = $file_name;
            unset($input['_token']);
            unset($input['file']);
        }else{//没有上传图片
            return back()->withErrors('msg','请上传图片');
        }
        //执行插入新数据
        $result = Advertising::create($input);
        if($result){
            //如果插入成功!
            return redirect()->route('admin.advertising.index')->with('msg','添加广告图成功!');
        }else{
            return back()->withErrors('msg','添加广告图失败!');
        }
    }

    //删除广告图
    public function destroy($id){
        Advertising::destroy($id);
        return back()->with('msg','删除成功!');
    }

    //开关
    public function switchStatus(Request $request, $id){
        return StatusSwitchService::change(Advertising::class, $id, $request->get('status'));
    }





}