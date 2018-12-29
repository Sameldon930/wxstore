<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

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

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            正在跳转...{{--<a href="{{$url}}">{{$url}}</a>--}}
        </div>
    </body>
    <script>
        function htmlEncodeByRegExp(str){
            var s = "";
            if(str.length == 0) return "";
            s = str.replace(/&amp;/g,"&");
            s = s.replace(/&lt;/g,"<");
            s = s.replace(/&gt;/g,">");
            s = s.replace(/&nbsp;/g," ");
            s = s.replace(/&#39/g,"\'");
            s = s.replace(/&quot;/g,"\"");
            return s;
        };
        var url=htmlEncodeByRegExp('{{$url}}');
        window.location.href = url;
    </script>
</html>
