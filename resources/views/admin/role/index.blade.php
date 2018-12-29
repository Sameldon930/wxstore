@extends('layouts.admin')

@section('pageTitle')
    角色管理
@stop

@section('css')
    <style>
        .form-group {
            margin-right: 40px;
        }
    </style>
@stop

@section('content')
    <div class="box shadow">

        <div class="box-body">
            <a href="{{ route('admin.role.create') }}" class="pretty-btn">添加角色</a>
        </div>

        <div class="box-body">
            <table class="table table-bordered text-center table-hover table-responsive">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>名称</th>
                    <th>显示名称</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data as $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>{{$item->name}}</td>
                        <td>{{$item->display}}</td>
                        <td>{{$item->created_at}}</td>
                        <td>
                            @if(!$item->isAdminRole())
                                <a href="{{route('admin.role.edit', ['id'=>$item->id])}}"
                                   class="pretty-btn">编辑</a>
                                <form action="{{route('admin.role.destroy', ['role' => $item->id])}}" method="POST" class="inline-block">
                                    {{ method_field('DELETE') }}
                                    {{ csrf_field() }}
                                    <button class="pretty-btn pretty-btn-danger">删除</button>
                                </form>

                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>


@stop

@section('js')
    <script>
        $(document).ready(function () {
            $('#system').addClass('active');
            $('#system_role_index').addClass('active');
        });
    </script>
@stop