@extends('layouts.merchant')

@section('pageTitle')
    数据中心
@stop

@section('css')
    <style>
        .badge {

        }

        .badge-success {
            background-color: #5cb85c;
        }

        .block {

        }

        .block-header {
            border-left: 4px solid #3c8dbc;
            padding-left: 12px;
            font-size: 16px;
            line-height: 18px;
            margin-bottom: 20px;
        }

        .block-content {

        }

        .block-item {
            font-size: 16px;
            font-weight: bold;
        }

        .nav > li > a {
            padding: 10px 6px;
            margin: 0 30px 0 0;
        }

        .nav-pills {
            border-bottom: 0;
        }

        .nav-pills > li.active > a, .nav-pills > li.active > a:hover, .nav-pills > li.active > a:focus {
            border-top-color: transparent;
        }

        .nav-pills > li.active > a, .nav-pills > li.active > a:focus, .nav-pills > li.active > a:hover {
            color: #000;
            background-color: #fff;
        }

        .nav-pills > li.active > a {
            font-weight: 600;
            border-bottom: 4px solid #5cb85c;
        }

        .nav > li > a:hover, .nav > li > a:active, .nav > li > a:focus {
            color: #000;
            background: transparent;
        }

        .tab-content > .active {
            display: block;
        }

        .tab-content {
            background-color: #EDEDED;
            padding: 20px 10px;
            min-height: 500px;
        }

        .user {
            padding: 10px 0;
        }

        .user-name {
            font-size: 30px;
            font-weight: bold;
            color: #515151;
        }

        .user-no {
            color: #bbb;
            padding-top: 10px;
            font-size: 16px;
        }

        .card {
            background-color: #fff;
            box-shadow: 0 2px 3px 0 rgba(0, 0, 0, .2);
            margin-bottom: 20px;
            padding: 10px;
        }

        .overview-task .overview-panel-title {
            padding-left: 20px;
        }

        .overview-panel-title {
            font-weight: 700;
            padding-top: 15px;
            font-size: 14px;
        }
        .overview-task-inner:before {
            content: '';
            border: 3px solid #bbb;
            width: 12px;
            height: 12px;
            box-sizing: border-box;
            position: absolute;
            border-radius: 12px;
            top: 21px;
        }
        .overview-task-list {
            -webkit-margin-before: 1em;
            -webkit-margin-after: 1em;
            -webkit-margin-start: 0px;
            -webkit-margin-end: 0px;
            -webkit-padding-start: 0;
        }
        .overview-task-list li {
            line-height: 52px;
            border-bottom: 1px solid #f2f2f2;
            list-style: none;
        }
        .overview-task-inner {
            position: relative;
            display: table;
            width: 100%;
            padding: 0 20px;
            box-sizing: border-box;
        }
        .overview-task-num {
            float: right;
            font-size: 18px;
            color: #aaa;
        }
        .task-title {
            display: table-cell;
            color: #000;
        }
        .overview-task-title {
            padding-left: 20px;
        }
    </style>
@stop

<?php
        $today = \Carbon\Carbon::today()->toDateString();
        $startOfMonth = \Carbon\Carbon::today()->startOfMonth()->toDateString();
        $whereToday = ['start_at' => $today, 'end_at' => $today];
        $whereMonth = ['start_at' => $startOfMonth, 'end_at' => $today]
?>
@section('content')
    <div class="box">
        <div class="user">
            <div class="user-name">
                <span>{{$user->name}}</span>
            </div>

            <div class="user-no">
                <span>No • </span>
                <span>{{$user->mobile}}</span>
            </div>

            <div class="badges padding-top-xs">
                @if($user->isCheckedMerchant())
                    <span class="badge badge-success"><span class="glyphicon glyphicon-ok"></span> 商户认证</span>
                @else
                    <span class="badge">商户未认证</span>
                @endif

                {{--@if($user->isCheckedAgent())--}}
                    {{--<span class="badge badge-success"><span class="glyphicon glyphicon-ok"></span> 代理认证</span>--}}
                {{--@else--}}
                    {{--<span class="badge">代理未认证</span>--}}
                {{--@endif--}}
            </div>


        </div>

        <div>

            <!-- Nav tabs -->
            <ul class="nav nav-pills">
                <li class="active"><a href="#home" data-toggle="tab">我的成员</a></li>
                <li><a href="#profile" data-toggle="tab">我的收益</a></li>
                {{--<li><a href="#messages" data-toggle="tab">Messages</a></li>--}}
                {{--<li><a href="#settings" data-toggle="tab">Settings</a></li>--}}
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active row margin-0" id="home">
                    <div class="col-xs-12 col-lg-3 overview-task overview-panel">
                        <div class="overview-panel-inner card">
                            <div class="overview-panel-title">成员统计</div>
                            <div class="overview-panel-body">

                                <ul class="overview-task-list">
                                        <li>
                                            <a href="{{ route('merchant.store.index') }}" class="overview-task-inner">
                                                <span class="overview-task-title">门店总数</span>
                                                <span class="overview-task-num">{{ $data['total_store'] }}</span>
                                            </a>
                                        </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-lg-3 overview-task overview-panel">
                        <div class="overview-panel-inner card">
                            <div class="overview-panel-title">我的门店</div>
                            <div class="overview-panel-body">
                                <ul class="overview-task-list">
                                    <li>
                                        <a href="{{ route('agent.merchant.index', $whereToday) }}" class="overview-task-inner">
                                            <span class="overview-task-title">今日新增</span>
                                            <span class="overview-task-num">{{ $data['today_merchant'] }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="" class="overview-task-inner">
                                            <span class="overview-task-title">今日已审核</span>
                                            <span class="overview-task-num">{{ $data['today_checked_merchant'] }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('agent.merchant.index') }}" class="overview-task-inner">
                                            <span class="overview-task-title">总数</span>
                                            <span class="overview-task-num">{{ $data['total_merchant'] }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="" class="overview-task-inner">
                                            <span class="overview-task-title">已审核</span>
                                            <span class="overview-task-num">{{ $data['total_checked_merchant'] }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="tab-pane" id="profile">
                    <div class="col-xs-12 col-lg-3 overview-task overview-panel">
                        <div class="overview-panel-inner card">
                            <div class="overview-panel-title">今日收益</div>
                            <div class="overview-panel-body">
                                <ul class="overview-task-list">
                                    <li>
                                        <a href="{{ route('agent.order.index', $whereToday) }}" class="overview-task-inner">
                                            <span class="overview-task-title">订单数</span>
                                            <span class="overview-task-num">{{ $data['today_order'] }} 笔</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('agent.order.index', $whereToday) }}" class="overview-task-inner">
                                            <span class="overview-task-title">交易额</span>
                                            <span class="overview-task-num">{{ $data['today_trade_amount'] }} 元</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('agent.order.index', $whereToday+['pay_status' => \App\Order::PAY_STATUS_PAID]) }}" class="overview-task-inner">
                                            <span class="overview-task-title">收益</span>
                                            <span class="overview-task-num">{{ $data['today_paid_amount'] }} 元</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-lg-3 overview-task overview-panel">
                        <div class="overview-panel-inner card">
                            <div class="overview-panel-title">当月收益</div>
                            <div class="overview-panel-body">
                                <ul class="overview-task-list">
                                    <li>
                                        <a href="{{ route('agent.order.index', $whereMonth) }}" class="overview-task-inner">
                                            <span class="overview-task-title">订单数</span>
                                            <span class="overview-task-num">{{ $data['monthly_order'] }} 笔</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('agent.order.index', $whereMonth) }}" class="overview-task-inner">
                                            <span class="overview-task-title">交易额</span>
                                            <span class="overview-task-num">{{ $data['monthly_trade_amount'] }} 元</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('agent.order.index', $whereMonth+['pay_status' => \App\Order::PAY_STATUS_PAID]) }}" class="overview-task-inner">
                                            <span class="overview-task-title">收益</span>
                                            <span class="overview-task-num">{{ $data['monthly_paid_amount'] }} 元</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-lg-3 overview-task overview-panel">
                        <div class="overview-panel-inner card">
                            <div class="overview-panel-title">总收益</div>
                            <div class="overview-panel-body">
                                <ul class="overview-task-list">
                                    <li>
                                        <a href="{{ route('agent.order.index') }}" class="overview-task-inner">
                                            <span class="overview-task-title">订单数</span>
                                            <span class="overview-task-num">{{ $data['total_order'] }} 笔</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('agent.order.index') }}" class="overview-task-inner">
                                            <span class="overview-task-title">交易额</span>
                                            <span class="overview-task-num">{{ $data['total_trade_amount'] }} 元</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('agent.order.index', ['pay_status' => \App\Order::PAY_STATUS_PAID]) }}" class="overview-task-inner">
                                            <span class="overview-task-title">收益</span>
                                            <span class="overview-task-num">{{ $data['total_paid_amount'] }} 元</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                {{--<div class="tab-pane" id="messages">ms</div>--}}
                {{--<div class="tab-pane" id="settings">settle</div>--}}
            </div>

        </div>

    </div>


@stop

@section('js')
    <script>

        $(document).ready(function () {
            $('#index').addClass('active');
        });

    </script>
@stop