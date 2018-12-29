@extends('layouts.admin')

@section('pageTitle')
    商户详情
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
                <p>账号：{{ $data->mobile }}</p>
                <p>手机号:{{$data->real_mobile}}</p>
                <p>商户二维码：</p>
                {{--@if($data->isCheckedMerchant())--}}
                    <div id="qrcode" class="img-thumbnail" data-url="{{ env('APP_URL') . '/pay?u=' . $data->mobile }}"></div>
                {{--@else--}}
                    {{--商户未审核--}}
                {{--@endif--}}
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
            $('#merchant_merchant_index').addClass('active');

            $qrcode = $("#qrcode");
            $qrcode.qrcode($qrcode.data("url"));
        });


    </script>
@stop