@extends('layouts.merchant')

@section('pageTitle')
    账户信息
@stop

@section('css')
    <style>

    </style>
@stop

@section('content')
    <div class="box">
        <h3>账户信息</h3>

        {{--<div class="row">--}}
            {{--<div class="col-sm-6 col-lg-4 col-xs-12">--}}
                {{--@if($data->user_agent_info)--}}
                    {{--@if($data->user_agent_info->status & \App\UserAgentInfo::CHECKING)--}}
                        {{--<div class="bs-callout bs-callout-info h4 text-info">代理认证审核中</div>--}}
                    {{--@elseif($data->user_agent_info->status & \App\UserAgentInfo::CHECKED)--}}
                        {{--<div class="bs-callout bs-callout-info h4 text-info">代理认证审核通过</div>--}}
                    {{--@elseif($data->user_agent_info->status & \App\UserAgentInfo::REJECTED)--}}
                        {{--<div class="bs-callout bs-callout-danger h4 text-danger">代理认证已拒绝：{{ $data->reject_reason }}</div>--}}
                    {{--@endif--}}
                {{--@else--}}
                    {{--<div class="bs-callout bs-callout-info h4 text-info">--}}
                        {{--<div>还没有提交代理审核</div>--}}
                        {{--<div class="padding-top-xs">--}}
                            {{--<a href="{{ route('agent.agent.info_check') }}" class="pretty-btn margin-top-xs">--}}
                                {{--立即前往--}}
                            {{--</a>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--@endif--}}
            {{--</div>--}}
        {{--</div>--}}



        <div class="box-body">
            <div class="box-body well">

                <h4 class="row">
                    <div class="col-xs-2">用户名：</div>
                    <div class="col-xs-10">{{$data->name}}</div>
                </h4>
                <h4 class="row">
                    <div class="col-xs-2">账号：</div>
                    <div class="col-xs-10">{{$data->mobile}}</div>
                </h4>
                <h4 class="row">
                    <div class="col-xs-2">注册日期：</div>
                    <div class="col-xs-10">{{$data->created_at}}</div>
                </h4>
                <h4>门店二维码：</h4>
                <div id="qrcode" class="img-thumbnail" data-url="{{ env('APP_URL') . '/pay?u=' . $data->mobile }}"></div>
            </div>
        </div>
    </div>
    <div class="box">
        <h4>上级代理信息</h4>
        <div class="box-body">
            @if($data->a_user)
                <div class="box-body well">
                    <h4 class="row">
                        <div class="col-xs-2">用户名：</div>
                        <div class="col-xs-10">{{$data->a_user->name}}</div>
                    </h4>
                    <h4 class="row">
                        <div class="col-xs-2">账号：</div>
                        <div class="col-xs-10">{{$data->a_user->mobile}}</div>
                    </h4>
                </div>
            @else
                <h5>您没有上级代理</h5>
            @endif
        </div>
    </div>
@stop

@section('js')
    @yield('date_search')
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
