@extends('layouts.agent')

@section('pageTitle')
    提现详情
@stop

@section('css')
    <style>

    </style>
@stop

@section('content')
    <div class="box">
        <div class="box-body">
            <div class="box-body well">
                <h4 class="row">
                    <div class="col-xs-2">提现ID：</div>
                    <div class="col-xs-10">{{$data->id}}</div>
                </h4>
                <h4 class="row">
                    <div class="col-xs-2">提现银行名称：</div>
                    <div class="col-xs-10">{{'暂无'}}</div>
                </h4>
                <h4 class="row">
                    <div class="col-xs-2">提现银行卡号：</div>
                    <div class="col-xs-10">{{'暂无'}}</div>
                </h4>
                <h4 class="row">
                    <div class="col-xs-2">提现银行卡户名：</div>
                    <div class="col-xs-10">{{'暂无'}}</div>
                </h4>
                <h4 class="row">
                    <div class="col-xs-2">提现金额：</div>
                    <div class="col-xs-10">{{$data->real_amount}}</div>
                </h4>
                <h4 class="row">
                    <div class="col-xs-2">提现状态：</div>
                    <div class="col-xs-10">{{App\Withdrawal::STATUS[$data->status]}}</div>
                </h4>
                <h4 class="row">
                    <div class="col-xs-2">拒绝理由：</div>
                    <div class="col-xs-10">{{$data->refuse_reason}}</div>
                </h4>
            </div>
        </div>
    </div>

@stop

@section('js')
    <script>

        $(document).ready(function () {
            $('#account').addClass('active');
            $('#agent_withdrawal').addClass('active');
        });

    </script>
@stop
