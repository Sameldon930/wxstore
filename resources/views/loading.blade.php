<!doctype html>
<html >
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        {{--<meta name="viewport" content="width=device-width, initial-scale=1">--}}
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <title>每人付</title>

        <!-- Fonts -->
        <link href="./css/bootstrap.min.css" rel="stylesheet">
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }


        </style>
        <script>
            window.onload=function () {
                var _hmt = _hmt || [];
                (function() {
                    var hm = document.createElement("script");
                    hm.src = "https://hm.baidu.com/hm.js?a1cc9a1adc423d7dcaf0d4d5d6e41400";
                    var s = document.getElementsByTagName("script")[0];
                    s.parentNode.insertBefore(hm, s);
                })();

                var oUrl = document.getElementById('tt').innerHTML;
                window.location.href=oUrl;

            }
        </script>
    </head>
    <body>
    <div id="tt">{{$data->url??''}}</div>
    </body>


</html>
