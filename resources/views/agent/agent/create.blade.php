@extends('layouts.agent')

@section('pageTitle')
    添加代理
@stop

@section('css')
    <style>

    </style>
@stop

@section('content')
    <div class="box">
        <div class="box-body">
            <div class="col-sm-6 col-lg-4 col-xs-12">
                <form class="form-horizontal" method="POST" action="{{ route('agent.agent.store') }}">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">

                    <div class="input-group margin-bottom-sm">
                        <div class="input-group-btn">
                            <label for="name" class="pretty-btn">名称</label>
                        </div>
                        <input type="text" class="form-control" name="name"
                               value="{{old('name')}}" required>
                    </div>

                    {{--<div class="input-group margin-bottom-sm">
                        <div class="input-group-btn">
                            <label for="mobile" class="pretty-btn">商户号</label>
                        </div>
                        <input type="text" class="form-control" name="mobile"
                               value="{{old('mobile')}}" required>
                    </div>--}}

                    <div class="input-group margin-bottom-sm">
                        <div class="input-group-btn">
                            <label for="password" class="pretty-btn">密码</label>
                        </div>
                        <input type="password" class="form-control" name="password"
                               value="{{old('password')}}"
                               required>
                    </div>

                    <button type="submit" class="pretty-btn">创建</button>
                </form>
            </div>

        </div>
    </div>

@stop

@section('js')
    <script>

        $(document).ready(function () {
            $('#member').addClass('active');
            $('#member_agent').addClass('active');
        });

    </script>
@stop