<html>
<head>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/common.css">

    <style>
        th {
            text-align: center;
        }
        td {
            font-size: 14px;
        }
        body {
            overflow-x: hidden;
        }
    </style>
</head>
<body>
<div class="box">

    <div class="box-body">
        @include('compoment.search_form')
    </div>

    <div class="box-body">
        <table class="table table-bordered text-center table-hover table-responsive margin-top-sm"
               data-action="collapse">
            <tbody>
            <tr>
                <th>平台订单号</th>
                <th>外部订单号</th>
                <th>交易金额</th>
                <th>实际金额</th>
                <th>支付渠道</th>
                <th>支付状态</th>
                <th>创建时间</th>
                <th>支付时间</th>
            </tr>
            @foreach($orders as $order)
                <tr>
                    <td>{{$order->order_no}}</td>
                    <td>{{$order->out_order_no}}</td>
                    <td>{{$order->trade_amount}}</td>
                    <td>{{$order->real_amount}}</td>
                    <td>{{$order->channel->display}}</td>
                    <td>{{App\Order::PAY_STATUS[$order->pay_status]}}</td>
                    <td>{{$order->created_at}}</td>
                    <td>{{$order->paid_at}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="text-center">
            {{ $orders->links() }}
        </div>
    </div>
</div>
</body>

@yield('date_search')

</html>

