@extends('layouts.admin')

@section('pageTitle')
    广告图列表
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
            <a href="{{route('admin.advertising.add')}}">
                <button class="pretty-btn">广告图添加</button>
            </a>
        </div>

    </div>
    <div class="box-body">
        <table class="table table-common table-hover">
            <tr>
                <th>ID</th>
                <th>缩略图</th>
                <th>跳转链接</th>
                <th>备注</th>
                <th>排序值</th>
                <th>发布状态</th>
                <th>操作</th>
            </tr>
            @foreach($datas as $data)
                <tr>
                    <td>{{$data->id}}</td>
                    <td><img src="{{asset('storage/serve').'/'.old_or_new_field('image',$data)}}"
                             style="height:50px; width:50px;line-height: 130px;margin-left:10px;"></td>
                    <td>{{$data->url}}</td>
                    <td>{{$data->note}}</td>
                    <td>{{$data->orderby}}</td>
                    <td>
                        <div class="switch" data-target="switch-status"
                             data-url="{{route('admin.advertising.switch', ['advertising' => $data->id])}}">
                            <input type="checkbox" name="status" value="{{ $data->status }}"
                                   @if( $data->status == \App\Advertising::STATUS_ENABLED ) checked @endif/>
                        </div>
                    </td>
                    <td><a href="{{route('admin.advertising.edit',['advertising'=>$data->id])}}">
                            <button class="pretty-btn">编辑</button>
                        </a>
                        <form action="{{route('admin.advertising.destroy', ['advertising' => $data->id])}}" method="POST" class="inline-block">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button class="pretty-btn pretty-btn-danger">删除</button>
                        </form>
                    </td>
                </tr>
            @endforeach

        </table>
    </div>
@stop

@section('js')
    <script>

        $(document).ready(function () {
            $('#operation').addClass('active');
            $('#operation_advertising_index').addClass('active');
        });

    </script>
@stop
