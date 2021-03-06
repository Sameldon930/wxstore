@extends('layouts.agent')

@section('pageTitle')
    商户编辑
@stop

@section('css')
    <style>

    </style>
@stop

@section('content')
    <div class="box">
        <div class="box-body row">
            <div class="col-sm-6 col-lg-4 col-xs-12">
                <form class="form-horizontal" method="POST" action="{{ route('agent.merchant.update', ['merchant' => $data->id]) }}">
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">

                    <div class="input-group margin-bottom-sm">
                        <div class="input-group-btn">
                            <label for="name" class="pretty-btn">名称</label>
                        </div>
                        <input class="form-control" name="name" value="{{old_or_new_field('name', $data)}}" required>
                    </div>

                    <div class="input-group margin-bottom-sm">
                        <div class="input-group-btn">
                            <label for="mobile" class="pretty-btn">账号</label>
                        </div>
                        <input class="form-control" name="mobile" value="{{old_or_new_field('mobile', $data)}}" required>
                    </div>

                    <button type="submit" class="pretty-btn">修改</button>
                </form>
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