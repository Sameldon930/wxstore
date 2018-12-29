@extends('layouts.admin')

@section('pageTitle')
    商户编辑
@stop

@section('css')
    <style>

    </style>
@stop

@section('content')
    <div class="box">
        <div class="box-body row">
            <div class="col-sm-6 col-lg-5 col-xs-12">
                <form class="form-horizontal" method="POST" action="{{ route('admin.merchant.update', ['merchant' => $data->id]) }}">
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">

                    <div class="input-group margin-bottom-sm">
                        <div class="input-group-btn">
                            <label for="name" class="pretty-btn">名称</label>
                        </div>
                        <input class="form-control" name="name" value="{{old_or_new_field('name', $data)}}" required>
                    </div>

                    <div class="input-group margin-bottom-sm">
                        <div class="input-group-btn">
                            <label for="mobile" class="pretty-btn">账号</label>
                        </div>
                        <input class="form-control" name="mobile" value="{{old_or_new_field('mobile', $data)}}" required>
                    </div>

                    <div class="margin-bottom-sm">
                        <label for="">各通道费率（n/10000）（例：0.6% 则输入 60）：</label>
                    </div>
                    @foreach($data->user_merchant_tubes as $merchant_tube)
                        <?php
                        $profit_name = 'profit_rate_' . $merchant_tube->tube_id;
                        $tube_name = 'tube_rate_' . $merchant_tube->tube_id;
                        ?>
                        <label for="">{{ $merchant_tube->tube->display }}：</label>
                        <div class="input-group margin-bottom-sm">
                            <div class="input-group-btn">
                                <label for="{{ $tube_name }}" class="pretty-btn">基础费率（由上游获取）</label>
                            </div>

                            <input class="form-control" type="number" name="{{ $tube_name }}"
                                   value="{{ old($tube_name) ?? $merchant_tube->tube_rate }}" required
                            >
                        </div>
                        <div class="input-group margin-bottom-sm">
                            <div class="input-group-btn">
                                <label for="{{ $profit_name }}" class="pretty-btn">分润费率（最终结算给商户的比例）</label>
                            </div>

                            <input class="form-control" type="number" name="{{ $profit_name }}"
                                   value="{{ old($profit_name) ?? $merchant_tube->profit_rate }}" required
                            >
                        </div>

                    @endforeach
                    <label for="">上游信息：</label>
                    <div class="input-group margin-bottom-sm">
                        <div class="input-group-btn">
                            <label for="wechat_merchant_no" class="pretty-btn">微信商户号</label>
                        </div>
                        <input class="form-control" name="wechat_merchant_no"
                               value="{{old_or_new_field('wechat_merchant_no',$data->user_merchant_info)}}" required>
                    </div>

                    <div class="input-group margin-bottom-sm">
                        <div class="input-group-btn">
                            <label for="ali_merchant_no" class="pretty-btn">支付宝商户号</label>
                        </div>
                        <input class="form-control" name="ali_merchant_no"
                               value="{{old_or_new_field('ali_merchant_no',$data->user_merchant_info)}}" required>
                    </div>

                    <div class="input-group margin-bottom-sm">
                        <div class="input-group-btn">
                            <label for="ali_auth_token" class="pretty-btn">支付宝授权码</label>
                        </div>
                        <input class="form-control" name="ali_auth_token"
                               value="{{old_or_new_field('ali_auth_token',$data->user_merchant_info)}}" required>
                    </div>
                    <button type="submit" class="pretty-btn">修改</button>
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