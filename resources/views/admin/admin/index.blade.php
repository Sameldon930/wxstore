@extends('layouts.admin')

@section('pageTitle')
    管理员列表
@stop

@section('css')
    <style>

    </style>
@stop

@section('content')
    <div class="box">
        <div class="box-body">
            <button type="button" class="pretty-btn" data-toggle="modal" data-target="#modal-user-add">
                添加管理员
            </button>

            <div class="modal fade" id="modal-user-add" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">添加管理员</h4>
                        </div>
                        <form class="form-horizontal" method="POST" action="{{ route('admin.admin.store') }}" id="form">
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
                                        <label for="mobile" class="pretty-btn">手机号</label>
                                    </div>
                                    <input type="text" class="form-control" name="mobile"
                                           value="{{old('mobile')}}" required>
                                </div>

                                <div class="input-group box-body">
                                    <div class="input-group-btn">
                                        <label for="roles[]" class="pretty-btn">选择角色</label>
                                    </div>
                                    <div class="roles padding-left-sm padding-top-xs">
                                        @foreach($roles as $role)
                                            <label class="pretty-checkbox">
                                                <input type="checkbox" name="roles[]" value="{{$role->id}}"
                                                       @if(old('roles') && in_array($role->id, old('roles'))) checked @endif
                                                >
                                                <span></span> {{$role->display}}
                                            </label>
                                        @endforeach
                                    </div>

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
                    <th>ID</th>
                    <th>姓名</th>
                    <th>手机号</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
                @foreach($data as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->mobile }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>
                            <a class="pretty-btn" href="{{route('admin.admin.show', ['admin' => $item->id])}}">详情</a>
                            <a class="pretty-btn" href="{{route('admin.admin.edit', ['admin' => $item->id])}}">修改</a>
                            <form action="{{route('admin.admin.destroy', ['admin' => $item->id])}}" method="POST" class="inline-block">
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
            $('#system').addClass('active');
            $('#system_admin_index').addClass('active');
        });

        $("#form").on("submit", function () {
            if ($(this).find("input[type=checkbox]:checked").length === 0) {
                toastr.error("请至少选择一个角色");
                return false;
            }
        })

    </script>
@stop