@extends('layouts.admin')

@section('pageTitle')
    代理详情
@stop

@section('css')
    <style>
        .data-info {
        }
        .data-info > p {
            padding-bottom: 10px;
            color: #666;
            font-size: 18px;
        }
    </style>
@stop

@section('content')
    <div class="box">
        <div class="box-body">
            <div class="data-info">
                <p>名称：{{ $data->name }}</p>
                <p>账号：{{ $data->mobile }}</p>
            </div>
        </div>
    </div>
@stop

@section('js')
    @yield('date_search')
    <script>

        $(document).ready(function () {
            $('#agent').addClass('active');
            $('#agent_agent_index').addClass('active');
        });

    </script>
@stop