@extends('layouts.admin')

@section('pageTitle')
    管理员编辑
@stop

@section('css')
    <style>

    </style>
@stop

@section('content')
    <div class="box">
        <div class="box-body row">
            <div class="col-sm-6 col-lg-4 col-xs-12">
                <form class="form-horizontal" method="POST" action="{{ route('admin.admin.update', ['admin' => $data->id]) }}" id="form">
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
                            <label for="mobile" class="pretty-btn">手机号</label>
                        </div>
                        <input class="form-control" name="mobile" value="{{old_or_new_field('mobile', $data)}}" required>
                    </div>

                    <div class="input-group margin-bottom-sm">
                        <?php $admin_roles = old('roles') ?? $data->roles->pluck('id')->all() ?? [] ?>
                        <div class="input-group-btn">
                            <label for="mobile" class="pretty-btn">选择角色</label>
                        </div>
                        <div class="roles padding-left-sm padding-top-xs">
                            @foreach($roles as $role)
                                <label class="pretty-checkbox">
                                    <input type="checkbox" name="roles[]" value="{{$role->id}}"
                                           @if(in_array($role->id, $admin_roles)) checked @endif
                                    >
                                    <span></span> {{$role->display}}
                                </label>
                            @endforeach
                        </div>
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