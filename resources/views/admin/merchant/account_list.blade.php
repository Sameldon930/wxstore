@extends('layouts.admin')

@section('pageTitle')
    提现列表
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
                    <th>账号</th>
                    <th>通道类型</th>
                    <th>余额</th>
                    <th>小额</th>
                    <th>状态</th>
                    <th>创建时间</th>
                </tr>
                @foreach($data as $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>{{$item->user->name}}</td>
                        <td>{{$item->user->mobile}}</td>
                        <td>{{$item->tube->display}}</td>
                        <td>{{$item->balance}} 元</td>
                        <td>{{$item->change}}</td>
                        <td>{{$item->status}}</td>
                        <td>{{$item->created_at}}</td>
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
            $('#finance').addClass('active');
            $('#finance_merchant_account_list').addClass('active');
        });

    </script>
@stop
