@extends('layouts.admin')

@section('pageTitle')
    代理编辑
@stop

@section('css')
    <style>

    </style>
@stop

@section('content')
    <div class="box">
        <div class="box-body row">
            <div class="col-sm-6 col-lg-4 col-xs-12">
                <form class="form-horizontal" method="POST" action="{{ route('admin.agent.update', ['agent' => $user->id]) }}">
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">

                    <div class="input-group margin-bottom-sm">
                        <div class="input-group-btn">
                            <label for="name" class="pretty-btn">名称</label>
                        </div>
                        <input class="form-control" name="name" value="{{old_or_new_field('name', $user)}}" required>
                    </div>

                    <label for="">各渠道分润费率（n/10000）：</label>
                    @foreach($channels as $channel)
                        <?php
                        $name = 'profit_rate_' . $channel->id;
                        ?>
                        <div class="input-group margin-bottom-sm">
                            <div class="input-group-btn">
                                <label for="{{ $name }}" class="pretty-btn">{{ $channel->display }} </label>
                            </div>

                            <input class="form-control" type="number" name="{{ $name }}"
                                   value="{{ old($name) ?? $data[$channel->id]['profit_rate']??'' }}" required>
                        </div>
                    @endforeach

                    <button type="submit" class="pretty-btn">修改</button>
                </form>
            </div>

        </div>
    </div>

@stop

@section('js')
    <script>

        $(document).ready(function () {
            $('#agent').addClass('active');
            $('#agent_agent_index').addClass('active');
        });

    </script>
@stop