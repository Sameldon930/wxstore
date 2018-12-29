@extends('layouts.agent')

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
            <p>当前条件数量总计：{{ $data->total()}}</p>
            <table class="table table-bordered text-center table-hover table-responsive">
                <tbody>
                <tr>
                    <th>结算号</th>
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
                        <td>{{ $item->settle_no }}</td>
                        <td>{{ \App\SettleLog::STATUS[$item->status] ?? '未指定' }}</td>
                        <td>{{ $item->tube->display }}</td>
                        <td>{{ $item->total_amount }}</td>
                        <td>{{ $item->real_amount }}</td>
                        <td>{{ $item->refund_amount }}</td>
                        <td>{{ $item->charge_amount }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>
                            <a href="{{route('agent.settle_log.detail',['id'=>$item->id])}}" class="pretty-btn">详情</a>
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
            $('#trade').addClass('active');
            $('#settle_log_index').addClass('active');
        });

    </script>
@stop
