@extends('layouts.agent')

@section('pageTitle')
    订单列表
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
                    <th>平台订单号</th>
                    <th>外部订单号</th>
                    <th>代理名称</th>
                    <th>交易金额</th>
                    <th>实际金额</th>
                    <th>支付渠道</th>
                    <th>支付状态</th>
                    <th>支付时间</th>
                    <th>创建时间</th>
                </tr>
                @foreach($data as $item)
                    <tr>
                        <td>{{$item->order_no}}</td>
                        <td>{{$item->out_order_no}}</td>
                        <td>{{$item->user->name ?? '未知'}}</td>
                        <td>{{$item->trade_amount}}</td>
                        <td>{{$item->real_amount}}</td>
                        <td>{{$item->channel->display ?? '未知'}}</td>
                        <td>{{App\Order::PAY_STATUS[$item->pay_status]}}</td>
                        <td>{{$item->paid_at}}</td>
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
            $('#trade').addClass('active');
            $('#store_order_index').addClass('active');
        });

    </script>
@stop
