@extends('layouts.store')

@section('pageTitle')
    账户信息
@stop

@section('css')
    <style>
    </style>
@stop

@section('content')
    <div class="box">
        <h4>账户信息</h4>
        <div class="box-body">
            <div class="box-body well">
                <h4 class="row">
                    <div class="col-xs-2">账号ID：</div>
                    <div class="col-xs-10">{{$data->id}}</div>

                </h4>
                <h4 class="row">
                    <div class="col-xs-2">用户名：</div>
                    <div class="col-xs-10">{{$data->name}}</div>
                </h4>
                <h4 class="row">
                    <div class="col-xs-2">账号(手机号)：</div>
                    <div class="col-xs-10">{{$data->mobile}}</div>
                </h4>
                <h4 class="row">
                    <div class="col-xs-2">注册日期：</div>
                    <div class="col-xs-10">{{$data->created_at}}</div>
                </h4>

                <h4>门店二维码：</h4>
                    <div id="qrcode" class="img-thumbnail col-xs-2" data-url="{{ env('APP_URL') . '/pay?u=' . $data->mobile }}"></div>



            </div>
        </div>
    </div>

@stop

@section('js')
    <script src="/js/jquery.qrcode.min.js"></script>
    <script>

        $(document).ready(function () {
            $('#system').addClass('active');
            $('#system_profile').addClass('active');
            $qrcode = $("#qrcode");
            $qrcode.qrcode($qrcode.data("url"))
        });

    </script>
@stop
