@extends('layouts.admin')

@section('pageTitle')
    商户对账单列表
@stop

@section('css')
    <style>

    </style>
@stop

@section('content')
    <div class="box">

        <div class="box-body">
            @include('compoment.search_form')
        </div>

        <div class="box-body">
            <a class="pretty-btn"
                href="{{ route('admin.merchant_settle.batchSettle') }}"
                onclick="return confirm('批量结算当日结算单，请谨慎操作')">批量对账</a>
        </div>
        <div class="box-body">
            <p>当前条件数量总计：{{ $data->total()}}</p>
            <table class="table table-bordered text-center table-hover table-responsive">
                <tbody>
                <tr>
                    <th>ID</th>
                    <th>结算号</th>
                    <th>商户</th>
                    <th>结算状态</th>
                    <th>通道类型</th>
                    <th>总金额</th>
                    <th>实际金额</th>
                    <th>退款金额</th>
                    <th>分润金额</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
                @foreach($data as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->settle_no }}</td>
                        <td>{{ $item->user->name }}</td>
                        <td>{{ \App\SettleLog::STATUS[$item->status] ?? '未指定' }}</td>
                        <td>{{ $item->tube->display }}</td>
                        <td>{{ $item->total_amount }}</td>
                        <td>{{ $item->real_amount }}</td>
                        <td>{{ $item->refund_amount }}</td>
                        <td>{{ $item->charge_amount }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>
                            @if($item->status === \App\SettleLog::STATUS_WAITING)
                                <a href="{{route('admin.merchant_settle.detail',['id'=>$item->id])}}" class="pretty-btn pretty-btn-danger">结算</a>
                            @else
                                <a href="{{route('admin.merchant_settle.detail',['id'=>$item->id])}}" class="pretty-btn">详情</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-center">{{ links_custom($data, $search_items) }}</div>
@stop

@section('js')
    @yield('date_search')
    <script>

        $(document).ready(function () {
            $('#finance').addClass('active');
            $('#finance_merchant_settle_index').addClass('active');
        });

    </script>
@stop
