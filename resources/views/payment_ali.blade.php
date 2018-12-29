<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>
    <script src="https://gw.alipayobjects.com/as/g/h5-lib/alipayjsapi/3.1.1/alipayjsapi.min.js"></script>

    <style>

    </style>
</head>
<body>
{{--<a href="javascript:void(0)" class="btn pay">立即支付</a>--}}

@include('compoment.payment')
</body>

<script src="/js/jquery.min.js"></script>
@yield('payment')
<script>
    $(function () {
        keyBoardNum.show();
    });
    function ready(callback) {
        // 如果jsbridge已经注入则直接调用
        if (window.AlipayJSBridge) {
            callback && callback();
        } else {
            // 如果没有注入则监听注入的事件
            document.addEventListener('AlipayJSBridgeReady', callback, false);
        }
    }
    ready(function(){

    });

    function pay(amount){
        var params = ap.parseQueryString();
        $.ajax({
            type: "POST",
            url: "api/pay",
            data: {
                auth_code: params.auth_code,
                amount: amount,
                u: params.u
            },
            success: function(data){
                if (data.code === 'SUCCESS'){
                    AlipayJSBridge.call("tradePay", {
                        tradeNO: data.trade_no
                    }, function(result) {
                        if (result.resultCode === '9000'){
//                            alert('支付成功')
                            window.location.href="./success";
                        }else {
                            alert('支付失败')
                        }
                    });
                } else {
                    alert('下单失败：' + data.msg)
                }

            }
        });
    }

    /*document.querySelector('.pay').addEventListener('click', function() {
        var params = ap.parseQueryString();
        $.ajax({
            type: "POST",
            url: "api/pay",
            data: {
                auth_code: params.auth_code
            },
            success: function(data){
                AlipayJSBridge.call("tradePay", {
                    tradeNO: data.trade_no
                }, function(result) {
                    if (result.resultCode === '9000'){
                        alert('支付成功')
                    }else {
                        alert('支付失败')
                    }
                });
            }
        });
    });*/
</script>
</html>
