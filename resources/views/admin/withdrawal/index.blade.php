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
                    <th>申请人</th>
                    <th>订单号</th>
                    <th>提现金额</th>
                    <th>实际金额</th>
                    <th>提现类型</th>
                    <th>提现状态</th>
                    <th>申请时间</th>
                    <th>操作</th>
                </tr>
                @foreach($data as $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>{{$item->user->name}}（{{$item->user->mobile}}）</td>
                        <td>{{$item->order_no}}</td>
                        <td>{{$item->trade_amount}}</td>
                        <td>{{$item->real_amount}}</td>
                        <td>{{App\Withdrawal::TYPES[$item->type]}}</td>
                        <td>{{App\Withdrawal::STATUS[$item->status]}}</td>
                        <td>{{$item->created_at}}</td>
                        <td></td>
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
            $('#finance_withdrawal_index').addClass('active');
        });

    </script>
@stop
