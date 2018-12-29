@extends('layouts.admin')

@section('pageTitle')
    控制台
@stop

@section('css')
    <style>

    </style>
@stop

@section('content')
    <div class="box">
        控制台
    </div>

@stop

@section('js')
    <script>

        $(document).ready(function () {
            $('#dashboard_index').addClass('active');
        });

    </script>
@stop