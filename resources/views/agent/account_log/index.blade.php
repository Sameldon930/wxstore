@extends('layouts.agent')

@section('pageTitle')
    账变列表
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
                    <th>单号</th>
                    <th>账变金额</th>
                    <th>通道类型</th>
                    <th>剩余金额</th>
                    <th>账变类型</th>
                    <th>创建时间</th>
                </tr>
                @foreach($data as $item)
                    <tr>
                        <td>{{$item->no}}</td>
                        <td>
                            @if($item->flow == \App\AccountLog::FLOW_IN) + @else - @endif{{$item->amount}}
                        </td>
                        <td>{{$item->settle_order->tube->display??''}}</td>
                        <td>{{$item->balance}}</td>
                        <td>{{App\AccountLog::ACCOUNT_TYPES[$item->type]}}</td>
                        <td>{{$item->created_at}}</td>
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
            $('#account_log').addClass('active');
            $('#account_log_index').addClass('active');
        });

    </script>
@stop
