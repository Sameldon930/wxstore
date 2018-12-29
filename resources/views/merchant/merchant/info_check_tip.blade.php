@extends('layouts.merchant')

@section('pageTitle')
    前往审核
@stop

@section('css')
    <style>

    </style>
@stop

@section('content')
    <div class="box">
        <div class="box-body">
            <div>
                <div class="text-danger h3">
                    必须通过商户审核才能添加门店
                </div>
                <a href="{{ route('merchant.merchant.info_check') }}" class="pretty-btn margin-top-xs">立即前往</a>
            </div>
        </div>
    </div>
@stop

@section('js')

    <script>

        $(document).ready(function () {
            $('#merchant').addClass('active');
            $('#merchant_merchant_index').addClass('active');
        });

    </script>
@stop