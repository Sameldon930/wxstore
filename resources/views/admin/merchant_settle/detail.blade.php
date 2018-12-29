@extends('layouts.admin')

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
                    <th>ID</th>
                    <th>结算号</th>
                    <th>结算状态</th>
                    <th>支付通道</th>
                    <th>总金额（元）</th>
                    <th>实际金额（元）</th>
                    <th>退款金额（元）</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>

                <tr>
                    <td>{{ $settleLog->id }}</td>
                    <td>{{ $settleLog->settle_no }}</td>
                    <td>{{ \App\SettleLog::STATUS[$settleLog->status] ?? '未指定' }}</td>
                    <td>{{ $settleLog->tube->display }}</td>
                    <td>{{ $settleLog->total_amount }}</td>
                    <td>{{ $settleLog->real_amount }}</td>
                    <td>{{ $settleLog->refund_amount }}</td>
                    <td>{{ $settleLog->created_at }}</td>
                    <td>
                        @if($settleLog->status === \App\SettleLog::STATUS_WAITING)
                            <a class="pretty-btn pretty-btn-danger"
                               href="{{route('admin.merchant_settle.settle',['id'=>$settleLog->id])}}"
                               onclick="return confirm('结算后不可修改，确认结算吗？')">
                                结算
                            </a>
                        @endif
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="box-body">
            <h3 class="box-title">结算子订单</h3>
            <iframe frameborder="0" width="100%" height="600" scrolling="auto" id="sub-orders"
                    src="{{ route('admin.merchant_settle.subOrders', ['settleLog' => $settleLog]) }}">

            </iframe>
        </div>

    </div>

@stop

@section('js')
    @yield('date_search')
    <script>

        $(document).ready(function () {
            $('#finance').addClass('active');
            $('#finance_merchant_settle_index').addClass('active');
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
