@extends('layouts.admin')

@section('pageTitle')
    管理员列表
@stop

@section('css')

    <style>

    </style>
@stop

@section('content')
    管理员列表
@stop

@section('js')
    @yield('date_search')
    <script>

        $(document).ready(function () {
            $('#system').addClass('active');
            $('#system_index').addClass('active');
        });

    </script>
@stop