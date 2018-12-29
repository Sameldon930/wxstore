@extends('layouts.admin')

@section('pageTitle')
    消息列表
@stop

@section('css')
    <style>
        th {
            text-align: center!important;
        }
        .box-body>.table {
            text-align: center!important;
        }

    </style>
@stop

@section('content')
    <div class="box">
        <div class="padding-top-sm">
            <a href="{{route('admin.message.add')}}">
                <button class="pretty-btn">添加消息</button>
            </a>
        </div>

    </div>

    <div class="box-body">
        @include('compoment.search_form')
    </div>

    <div class="box-body">
        <table class="table table-common table-hover">
            <tr>
                <th>ID</th>
                <th>消息标题</th>
                <th>简介</th>
                <th>置顶状态</th>
                <th>发布状态</th>
                <th>操作</th>
            </tr>
            @foreach($datas as $data)
                <tr>
                    <td>{{$data->id}}</td>
                    <td>{{$data->title}}</td>
                    <td style="overflow: hidden;text-overflow: ellipsis;white-space:nowrap; ">{{$data->text}}</td>
                    <td>{{ \App\Message::TOP_SWITCH[$data->top] ?? '未指定' }}</td>
                    <td>
                        <div class="switch" data-target="switch-status"
                             data-url="{{route('admin.message.switch', ['message' => $data->id])}}">
                            <input type="checkbox" name="status" value="{{ $data->status }}"
                                   @if( $data->status == \App\Message::STATUS_ENABLED ) checked @endif/>
                        </div>
                    </td>
                    <td>
                        <a href="{{route('admin.message.edit',['message'=>$data->id])}}" class="pretty-btn">消息内容编辑</a>
                        <form action="{{route('admin.message.destroy', ['message' => $data->id])}}" method="POST" class="inline-block">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button class="pretty-btn pretty-btn-danger">删除</button>
                        </form>
                    </td>
                </tr>
            @endforeach

        </table>

        <div class="text-center">{{ links_custom($datas, $search_items) }}</div>
    </div>

@stop

@section('js')
    @yield('date_search')
    <script>

        $(document).ready(function () {
            $('#operation').addClass('active');
            $('#operation_message_index').addClass('active');
        });

    </script>
@stop