@extends('layouts.admin')

@section('pageTitle')
    修改密码
@stop

@section('css')

    <style>

    </style>
@stop

@section('content')
    修改密码
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