@extends('layouts.admin')

@section('pageTitle')
    经营报表
@stop

@section('css')
    <style>

    </style>
@stop

@section('content')
    <div class="box">
        <div class="box-body">
            经营报表
        </div>
    </div>

@stop

@section('js')
    @yield('date_search')
    <script>

        $(document).ready(function () {
            $('#transaction').addClass('active');
            $('#operation_report').addClass('active');
        });

    </script>
@stop