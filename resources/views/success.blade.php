<!doctype html>
<html lang="{{ app()->getLocale() }}">
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
    </head>
    <body>
    <div id="app" v-cloak="">
        <div class="container-fluid">
            <div class="row">
                <div>
                    <van-nav-bar title="每人付" fixed/>
                </div>
            </div>
            <div class="row">
                <div  class="col-xs-12" style="text-align: center;margin-top: 50px">
                    <img src="../img/pay-logo.png" width="50%">
                     <h3>付款成功</h3>
                    <p>欢迎下次光临</p>
                </div>
            </div>
            <div class="row" style="margin-bottom: 30px">
                <div class="col-xs-12">
                    <button @click="goback" class="btn btn-success btn-lg btn-block">确定</button>
                </div>
            </div>
            <div class="row">
                @if($data != null)
                <div  class="col-xs-12" @click="goCount">
                    {{--<a href="{{$data->url??''}}" >--}}
                    <img src="{{asset('storage/serve').'/'.$data->image ?? ''}}"  width="100%" height="50%"/>
                    {{--</a>--}}
                </div>
                @else
                    <div  class="col-xs-12">
                        <img src="../../../img/logo.png"  width="100%" height="152px"/>
                    </div>
                @endif
            </div>
        </div>
    </div>
    </body>
    <script src="../js/app1.js"></script>
    <script src="https://unpkg.com/vant/lib/vant.min.js"></script>
    <script>
    var app = new Vue({
        el: "#app",
        data() {
            return {

            }
        },
        methods: {
            goback(){
              window.history.go(-1);
            },
            goCount(){
                location.href="./loading"

            }
        },
    })
    </script>
</html>
