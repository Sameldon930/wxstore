@extends('layouts.admin')

@section('pageTitle')
    添加/修改管理员
@stop

@section('css')

    <style>

    </style>
@stop

@section('content')
    添加/修改管理员
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