@extends('layouts.agent')

@section('pageTitle')
    商户对账单详情
@stop

@section('css')
    <style>

    </style>
@stop

@section('content')
    <div class="box">

        <div class="box-body">
            <h3 class="box-title">结算总订单</h3>
            <table class="table table-bordered text-center table-hover table-responsive">
                <tbody>
                <tr>
                    <th>结算号</th>
                    <th>结算状态</th>
                    <th>支付通道</th>
                    <th>总金额（元）</th>
                    <th>实际金额（元）</th>
                    <th>退款金额（元）</th>
                    <th>创建时间</th>
                </tr>

                <tr>
                    <td>{{ $settleLog->settle_no }}</td>
                    <td>{{ \App\SettleLog::STATUS[$settleLog->status] ?? '未指定' }}</td>
                    <td>{{ $settleLog->tube->display }}</td>
                    <td>{{ $settleLog->total_amount }}</td>
                    <td>{{ $settleLog->real_amount }}</td>
                    <td>{{ $settleLog->refund_amount }}</td>
                    <td>{{ $settleLog->created_at }}</td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="box-body">
            <h3 class="box-title">结算子订单</h3>
            <iframe frameborder="0" width="100%" height="600" scrolling="auto" id="sub-orders"
                    src="{{ route('agent.settle_log.subOrders', ['settleLog' => $settleLog]) }}">

            </iframe>
        </div>

    </div>

@stop

@section('js')
    @yield('date_search')
    <script>

        $(document).ready(function () {
            $('#trade').addClass('active');
            $('#settle_log_index').addClass('active');
        });

        function changeFrameHeight(){
            var ifm= document.getElementById("sub-orders");
            ifm.height=document.documentElement.clientHeight-300;
        }
        window.onresize=function(){ changeFrameHeight();}
        $(function(){
            changeFrameHeight();
        });
    </script>
@stop
