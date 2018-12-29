@extends('layouts.admin')

@section('pageTitle')
    操作日志
@stop

@section('css')
    <style>

    </style>
@stop

@section('content')
    <div class="box">

        <div class="box-body">
            @include('compoment.search_form')
        </div>
        <div class="box-body">
            <p>当前条件数量总计：{{ $data->total()}}</p>
            <table class="table table-bordered text-center table-hover table-responsive">
                <tbody>
                <tr>
                    <th>ID</th>
                    <th>类型</th>
                    <th>备注</th>
                    <th>创建时间</th>
                </tr>
                @foreach($data as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ \App\ActionLog::TYPES[$item->type] }}</td>
                        <td>{{ $item->note }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-center">{{ links_custom($data, $search_items) }}</div>
@stop

@section('js')
    @yield('date_search')
    <script>

        $(document).ready(function () {
            $('#system').addClass('active');
            $('#system_action_log_index').addClass('active');
        });

    </script>
@stop