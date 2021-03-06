@extends('layouts.admin')

@section('pageTitle')
    渠道编辑
@stop

@section('css')
    <style>

    </style>
@stop

@section('content')
    <div class="box">
        <div class="box-body row">
            <div class="col-sm-6 col-lg-4 col-xs-12">
                <form class="form-horizontal" method="POST" action="{{ route('admin.channel.update', ['admin' => $data->id]) }}" id="form">
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
                            <label for="tube_id" class="pretty-btn">通道</label>
                        </div>
                        <select name="tube_id" class="form-control">
                            @foreach($tubes as $tube)
                                <option value="{{ $tube->id }}" @if(old_or_new_field('tube_id', $data) == $tube->id) selected @endif>{{ $tube->name }}</option>
                            @endforeach
                        </select>
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
            $('#tube').addClass('active');
            $('#tube_channel_index').addClass('active');
        });

    </script>
@stop