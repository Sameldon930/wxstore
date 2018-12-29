@extends('layouts.admin')

@section('pageTitle')
    通道对账单列表
@stop

@section('css')
    <style>

    </style>
@stop

@section('content')
    <div class="box">

        <div class="box-body">
            @include('compoment.search_form',['export'=>'list_export'])
        </div>
        <div class="box-body">
            <p>当前条件数量总计：{{ $data->total()}}</p>
            <table class="table table-bordered text-center table-hover table-responsive">
                <tbody>

                @foreach ($table as $key=>$item)
                    <tr>
                        @if($key === 0)
                            @foreach($item as $th)
                                <th>{{$th}}</th>
                            @endforeach
                            <th>操作</th>
                        @else
                            @foreach($item as $k =>$td)
                                <td>{{$td}}</td>
                            @endforeach
                            <td>
                                @if($item[2] === \App\SettleLog::STATUS[\App\SettleLog::STATUS_WAITING])
                                    <a href="{{route('admin.tube_settle.detail',['id'=>$item[0]])}}" class="pretty-btn pretty-btn-danger">结算</a>
                                @else
                                    <a href="{{route('admin.tube_settle.detail',['id'=>$item[0]])}}" class="pretty-btn">详情</a>
                                @endif
                            </td>
                        @endif
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
            $('#finance_tube_settle_index').addClass('active');
        });

    </script>
@stop
