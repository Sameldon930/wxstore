@extends('layouts.admin')

@section('pageTitle')
    信息审核列表
@stop

@section('css')
    <style>

    </style>
@stop

@section('content')
    <div class="box">
        <div class="box-body">
            @include('compoment.search_form')
        </div>
        <div class="box-body">
            <p>当前条件数量总计：{{ $data->total()}}</p>
            <table class="table table-bordered text-center table-hover table-responsive">
                <tbody>
                <tr>
                    <th>账号</th>
                    <th>名称</th>
                    <th>账号类型</th>
                    <th>状态</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
                @foreach($data as $item)
                    <tr>
                        <td>{{$item->user->mobile??''}}</td>
                        <td>{{$item->user->name??''}}</td>
                        <td>{{ \App\UserAgentInfo::TYPES[$item->type??''] }}</td>
                        <td>{{ \App\UserAgentInfo::CHECK_STATUS[$item->status??''] }}</td>
                        <td>{{$item->created_at??''}}</td>
                        <td>
                            <a href="{{ route('admin.agent_user_info.show', ['id' => $item->id??'']) }}" class="pretty-btn">详情</a>
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
            $('#agent_agent_user_info_index').addClass('active');
        });

    </script>
@stop