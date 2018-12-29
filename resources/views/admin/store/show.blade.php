@extends('layouts.admin')

@section('pageTitle')
    门店详情
@stop

@section('css')
    <style>
        .data-info {
        }
        .data-info > p {
            padding-bottom: 10px;
            color: #666;
            font-size: 18px;
        }
    </style>
@stop

@section('content')
    <div class="box">
        <div class="box-body">
            <div class="data-info">
                <p>名称：{{ $data->name }}</p>
                <p>门店账号：{{ $data->mobile }}</p>
                <p>门店二维码：</p>
                <div id="qrcode" class="img-thumbnail" data-url="{{ env('APP_URL') . '/pay?u=' . $data->mobile }}"></div>
            </div>
        </div>
    </div>
@stop

@section('js')
    @yield('date_search')
    <script src="/js/jquery.qrcode.min.js"></script>
    <script>

        $(document).ready(function () {
            $('#merchant').addClass('active');
            $('#merchant_store_index').addClass('active');

            $qrcode = $("#qrcode");
            $qrcode.qrcode($qrcode.data("url"));
        });


    </script>
@stop