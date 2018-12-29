<?php

namespace App\Http\Controllers\Admin;

use App\ActionLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActionLogController extends Controller
{
    public function index(){
        $search_items = [
            'type' => [
                'type' => 'equal',
                'form' => 'select',
                'label' => '操作类型',
                'options' => ActionLog::TYPES,
            ],
            'created_at' => [
                'type' => 'date',
            ],
        ];

        $data = ActionLog::latest()
            ->search($search_items)
            ->paginate();

        return _view('admin.action_log.index', compact('data'));
    }
}
