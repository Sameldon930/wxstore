@extends('layouts.agent')

@section('pageTitle')
    代理列表
@stop

@section('css')
    <style>

    </style>
@stop

@section('content')
    <div class="box">
        <div class="box-body">
            <a class="pretty-btn" href="{{ route('agent.agent.create') }}">添加子代理</a>
        </div>

        <div class="box-body">
            <div class="modal fade" id="modal-user-add" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">添加代理</h4>
                        </div>
                        <form class="form-horizontal" method="POST" action="{{ route('agent.agent.store') }}">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="modal-body">

                                <div class="input-group box-body">
                                    <div class="input-group-btn">
                                        <label for="name" class="pretty-btn">名称</label>
                                    </div>
                                    <input type="text" class="form-control" name="name"
                                           value="{{old('name')}}" required>
                                </div>

                                <div class="input-group box-body">
                                    <div class="input-group-btn">
                                        <label for="mobile" class="pretty-btn">账号</label>
                                    </div>
                                    <input type="text" class="form-control" name="mobile"
                                           value="{{old('mobile')}}" required>
                                </div>

                                <div class="input-group box-body">
                                    <div class="input-group-btn">
                                        <label for="password" class="pretty-btn">密码</label>
                                    </div>
                                    <input type="password" class="form-control" name="password"
                                           value="{{old('password')}}"
                                           required>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="pretty-btn">创建</button>
                            </div>
                        </form>

                    </div>
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
                    <th>姓名</th>
                    <th>账号</th>
                    <th>审核状态</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
                @foreach($data as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->mobile }}</td>
                        {{--{{ $item->isCheckedAgent() ? '代理已审核' : '代理未审核' }}--}}
                        <td>@if($item->isCheckedAgent())代理已审核@else<span class="text-danger">代理未审核</span>@endif</td>
                        <td>{{ $item->created_at }}</td>
                        <td>
                            <a class="pretty-btn" href="{{route('agent.agent.show', ['agent' => $item->id])}}">详情</a>
                            <a class="pretty-btn" href="{{route('agent.agent.edit', ['agent' => $item->id])}}">修改</a>
                            {{--<form action="{{route('agent.agent.destroy', ['agent' => $item->id])}}" method="POST" class="inline-block">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                <button class="pretty-btn pretty-btn-danger">删除</button>
                            </form>--}}
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
            $('#member').addClass('active');
            $('#member_agent').addClass('active');
        });

    </script>
@stop