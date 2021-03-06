@extends('layouts.admin')

@section('pageTitle')
    通道列表
@stop

@section('css')
    <style>

    </style>
@stop

@section('content')
    <div class="box">
        <button type="button" class="pretty-btn" data-toggle="modal" data-target="#modal-add">
            添加通道
        </button>

        <div class="modal fade" id="modal-add" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">添加通道</h4>
                    </div>
                    <form class="form-horizontal" method="POST" action="{{ route('admin.tube.store') }}" id="form">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="modal-body">

                            <div class="input-group box-body">
                                <div class="input-group-btn">
                                    <label for="name" class="pretty-btn">名称</label>
                                </div>
                                <input type="text" class="form-control" name="name"
                                       value="{{old('name')}}" required>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="pretty-btn">创建</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <div class="box-body">
            @include('compoment.search_form')
        </div>
        <div class="box-body">
            <p>当前条件数量总计：{{ $data->total()}}</p>
            <table class="table table-bordered text-center table-hover table-responsive">
                <tbody>
                <tr>
                    <th>ID</th>
                    <th>名称</th>
                    <th>开关</th>
                    <th>操作</th>
                </tr>
                @foreach($data as $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>{{$item->name}}</td>
                        <td>
                            <div class="switch" data-target="switch-status"
                                 data-url="{{route('admin.tube.switch', ['tube' => $item->id])}}">
                                <input type="checkbox" name="status" value="{{ $item->status }}"
                                       @if( $item->status == \App\Tube::STATUS_ENABLED ) checked @endif/>
                            </div>
                        </td>
                        <td>
                            <a class="pretty-btn" href="{{route('admin.tube.show', ['tube' => $item->id])}}">详情</a>
                            <a class="pretty-btn" href="{{route('admin.tube.edit', ['tube' => $item->id])}}">修改</a>
                            <form action="{{route('admin.tube.destroy', ['tube' => $item->id])}}" method="POST" class="inline-block">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                <button class="pretty-btn pretty-btn-danger">删除</button>
                            </form>
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
            $('#tube').addClass('active');
            $('#tube_tube_index').addClass('active');
        });

    </script>
@stop
