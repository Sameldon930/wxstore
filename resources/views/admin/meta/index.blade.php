@extends('layouts.admin')

@section('pageTitle')
    系统配置
@stop

@section('css')
    <style>

    </style>
@stop

@section('content')
    <div class="box">
        <div class="box-body">

            @foreach(\App\MetaData::META_DATA as $key => $name)
                <form class="row margin-bottom-sm" method="post" action="{{ route('admin.meta.update') }}">
                    {{ csrf_field() }}
                    <div class="col-xs-12 col-sm-6 col-lg-4">
                        <div class="input-group">
                            <span class="input-group-btn">
                                <button class="pretty-btn" type="button">{{ $name }}</button>
                            </span>
                                <input type="text" class="form-control" name="{{ $key }}" value="{{ optional($data)[$key] }}" required>
                                <span class="input-group-btn">
                                  <button class="pretty-btn" type="submit">提交</button>
                            </span>
                        </div>
                    </div>
                </form>
            @endforeach

        </div>
    </div>

@stop

@section('js')
    <script>

        $(document).ready(function () {
            $('#system').addClass('active');
            $('#system_meta_index').addClass('active');
        });

    </script>
@stop
