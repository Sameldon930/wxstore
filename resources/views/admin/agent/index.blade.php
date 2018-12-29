@extends('layouts.admin')

@section('pageTitle')
    代理列表
@stop

@section('css')
    <style>

    </style>
@stop

@section('content')

    <div class="box">
        <div class="box-body">
            <a class="pretty-btn" href="{{ route('admin.agent.create') }}">添加代理</a>
        </div>

        <div class="box-body">
            @include('compoment.search_form')
        </div>

        <div class="box-body">
            <p>当前条件数量总计：{{ $data->total()}}</p>
            <table class="table table-bordered text-center table-hover table-responsive">
                <tbody>
                <tr>
                    <th>ID</th>
                    <th>姓名（账号）</th>
                    <th>上级代理名称</th>
                    <th>审核状态</th>
                    <th>创建时间</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                @foreach($data as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}（{{ $item->mobile }}）</td>
                        <td>{{ optional($item->a_user)->name }}</td>
                        <td>@if($item->isCheckedAgent()) 已审核 @else <span class="text-danger">未审核</span> @endif</td>
                        <td>{{ $item->created_at }}</td>
                        <td>
                            <div class="switch" data-target="switch-status"
                                 data-url="{{route('admin.agent.switch', ['agent' => $item->id])}}">
                                <input type="checkbox" name="status" value="{{ $item->status }}"
                                       @if( $item->status == \App\User::STATUS_ENABLED ) checked @endif/>
                            </div>
                        </td>
                        <td>
                            <a class="pretty-btn" href="{{route('admin.agent.show', ['agent' => $item->id])}}">详情</a>
                            <a class="pretty-btn" href="{{route('admin.agent.edit', ['agent' => $item->id])}}">修改</a>
                            {{--<form action="{{route('admin.agent.destroy', ['agent' => $item->id])}}" method="POST" class="inline-block">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                <button class="pretty-btn pretty-btn-danger">删除</button>
                            </form>--}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-center">{{ links_custom($data, $search_items) }}</div>
@stop

@section('js')
    @yield('date_search')
    <script>

        $(document).ready(function () {
            $('#agent').addClass('active');
            $('#agent_agent_index').addClass('active');
        });

    </script>
@stop