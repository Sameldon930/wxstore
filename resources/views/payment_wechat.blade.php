<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

    {{--
    <script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">
        config = {!! $js->config(['chooseWXPay'], true) !!}
        wx.config(config);
    </script>
    --}}

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


    function pay(amount) {
        var u = getQueryString('u');
        $.ajax({
            type: "POST",
            url: "api/pay",
            data: {
                amount: amount,
                u: u
            },
            success: function (data) {
                if (data.code === 'SUCCESS'){
                    if (typeof WeixinJSBridge == "undefined") {
                        if (document.addEventListener) {
                            document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
                        } else if (document.attachEvent) {
                            document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
                            document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
                        }
                    } else {
                        WeixinJSBridge.invoke(
                            'getBrandWCPayRequest', JSON.parse(data.paymentParams),
                            function (res) {
                                if (res.err_msg === "get_brand_wcpay_request:ok") {
//                                    alert('支付成功')
                                    window.location.href="./success";
                                } else {
                                    alert('支付失败')
                                }
                            }
                        );
                    }
                } else {
                    alert('下单失败：' + data.msg)
                }

            },
            error: function (a) {
                alert(JSON.stringify(a))
            }
        });

    }

    /*wx.ready(function () {

    });

    wx.error(function (res) {
        alert('微信配置错误');
    });*/


    /*wx.ready(function () {
        document.querySelector('.pay').addEventListener('click', function () {
            $.ajax({
                type: "POST",
                url: "api/pay",
                data: {},
                success: function (data) {
                    if (typeof WeixinJSBridge == "undefined") {
                        if (document.addEventListener) {
                            document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
                        } else if (document.attachEvent) {
                            document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
                            document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
                        }
                    } else {
                        pay(data);
                    }
                },
                error: function (a) {
                    alert(JSON.stringify(a))
                }
            });
        });
    });

    function pay(data) {
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest', JSON.parse(data.paymentParams),
            function (res) {
                if (res.err_msg === "get_brand_wcpay_request:ok") {
                    alert('支付成功')
                } else {
                    alert('支付失败')
                }
            }
        );
    }*/

</script>
</html>
