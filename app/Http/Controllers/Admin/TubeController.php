<?php

namespace App\Http\Controllers\Admin;

use App\Channel;
use App\Http\Traits\Searchable;
use App\Services\WebServices\StatusSwitchService;
use App\Tube;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TubeController extends Controller
{
    use Searchable;

    public function index()
    {
        $search_items = [
            'created_at' => [
                'type' => 'date',
            ],
        ];

        $data = Tube::latest()
            ->search($search_items)
            ->paginate()
        ;

        return _view('admin.tube.index', compact('data'));
    }

    public function edit(Request $request, $id){
        $data = Tube::findOrFail($id);

        return _view('admin.tube.edit', compact('data'));
    }

    public function update(Request $request, $id){
        $data = Tube::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|unique:tubes,name,' . $data->id,
        ]);

        $data->name = $request->get('name');
        $data->save();

        return redirect()->route('admin.tube.index')->with('msg', '修改成功');
    }

    public function store(Request $request){

        $this->validate($request, [
            'name' => 'required|unique:tubes,name',
        ]);

        Tube::create([
            'name' => $request->get('name'),
        ]);

        return back()->with('msg', '添加成功');
    }

    public function show($id){
        $data = Tube::findOrFail($id);

        return _view('admin.tube.show', compact('data'));
    }

    public function destroy($id){
        Tube::destroy($id);
        Channel::where('tube_id', $id)->delete();

        return back()->with('msg', '删除成功');
    }

    public function switchStatus(Request $request, $id){
        return StatusSwitchService::change(Tube::class, $id, $request->get('status'));
    }
}
