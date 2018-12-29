@extends('layouts.agent')

@section('pageTitle')
    商户信息审核
@stop

@section('css')
    <style>

    </style>
@stop

@section('content')
    <div class="box">
        <div class="box-body">
            <div>
                <div class="text-danger h3">
                    信息审核
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')

    <script>

        $(document).ready(function () {
            $('#member').addClass('active');
            $('#member_merchant').addClass('active');
        });

    </script>
@stop