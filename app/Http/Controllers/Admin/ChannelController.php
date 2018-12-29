<?php

namespace App\Http\Controllers\Admin;

use App\Channel;
use App\Order;
use App\Services\WebServices\StatusSwitchService;
use App\Tube;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChannelController extends Controller
{
    public function index()
    {
        $search_items = [
            'created_at' => [
                'type' => 'date',
            ],
        ];

        $tubes = Tube::get();

        $data = Channel::latest()
            ->with('tube')
            ->search($search_items)
            ->paginate()
        ;

        return _view('admin.channel.index', compact('data', 'tubes'));
    }

    public function edit(Request $request, $id){
        $data = Channel::findOrFail($id);

        $tubes = Tube::get();

        return _view('admin.channel.edit', compact('data', 'tubes'));
    }

    public function update(Request $request, $id){
        $data = Channel::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|unique:channels,name,' . $data->id,
            'tube_id' => 'required|exists:tubes,id'
        ]);

        $data->name = $request->get('name');
        $data->tube_id = $request->get('tube_id');
        $data->save();

        return redirect()->route('admin.channel.index')->with('msg', '修改成功');
    }

    public function store(Request $request){

        $this->validate($request, [
            'name' => 'required|unique:channels,name',
            'tube_id' => 'required|exists:tubes,id'
        ]);

        Channel::create([
            'name' => $request->get('name'),
            'tube_id' => $request->get('tube_id'),
        ]);

        return back()->with('msg', '添加成功');
    }

    public function show($id){
        $data = Channel::findOrFail($id);

        return _view('admin.channel.show', compact('data'));
    }

    public function destroy($id){
        Channel::destroy($id);

        return back()->with('msg', '删除成功');
    }

    public function switchStatus(Request $request, $id){
        return StatusSwitchService::change(Channel::class, $id, $request->get('status'));
    }
}
