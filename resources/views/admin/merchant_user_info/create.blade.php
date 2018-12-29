@extends('layouts.admin')

@section('pageTitle')
    商户审核
@stop

@section('css')
    <style>

    </style>
@stop

@section('content')
    <div class="box">
        <div class="box-body row">
            <div class="col-sm-6 col-lg-4 col-xs-12">
                <form class="form-horizontal" method="POST" action="{{ route('admin.merchant_user_info.pass', ['merchant' => $id]) }}">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">

                    <div class="input-group margin-bottom-sm">
                        <div class="input-group-btn">
                            <label for="wechat_merchant_no" class="pretty-btn">微信商户号</label>
                        </div>
                        <input class="form-control" name="wechat_merchant_no"
                               value="{{old('wechat_merchant_no')}}" required>
                    </div>

                    <div class="input-group margin-bottom-sm">
                        <div class="input-group-btn">
                            <label for="ali_merchant_no" class="pretty-btn">支付宝商户号</label>
                        </div>
                        <input class="form-control" name="ali_merchant_no"
                               value="{{old('ali_merchant_no')}}" required>
                    </div>

                    <div class="input-group margin-bottom-sm">
                        <div class="input-group-btn">
                            <label for="ali_auth_token" class="pretty-btn">支付宝授权码</label>
                        </div>
                        <input class="form-control" name="ali_auth_token"
                               value="{{old('ali_auth_token')}}" required>
                    </div>

                    <button type="submit" class="pretty-btn">通过审核</button>
                </form>
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