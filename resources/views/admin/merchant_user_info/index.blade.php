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
                    <th>ID</th>
                    <th>商户名称</th>
                    <th>用户</th>
                    <th>手机号码</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
                @foreach($data as $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>{{$item['user']->name}}({{$item['user']->mobile}})</td>
                        <td>{{$item->company_name}}</td>
                        <td>{{$item->mobile}}</td>
                        <td>{{$item->created_at}}</td>
                        <td>
                            <a class="pretty-btn" href="{{route('admin.merchant_user_info.show',['merchant'=>$item->user_id])}}">查看</a>
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
            $('#merchant').addClass('active');
            $('#merchant_merchant_user_info_index').addClass('active');
        });

    </script>
@stop