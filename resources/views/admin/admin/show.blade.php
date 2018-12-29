@extends('layouts.admin')

@section('pageTitle')
    管理员详情
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
                <p>ID：{{ $data->id }}</p>
                <p>名称：{{ $data->name }}</p>
                <p>商户号：{{ $data->mobile }}</p>
            </div>
        </div>
    </div>
@stop

@section('js')
    @yield('date_search')
    <script>

        $(document).ready(function () {
            $('#system').addClass('active');
            $('#system_admin_index').addClass('active');
        });

    </script>
@stop