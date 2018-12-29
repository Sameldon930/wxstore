@extends('layouts.admin')

@section('pageTitle')
    角色添加
@stop

@section('css')
    <style>
        .form-inline .form-group, .file-preview-wrap {
            margin-bottom: 20px;
        }

        .control-label {
            display: inline-block;
            min-width: 100px;
        }

        .permission-group > .panel-body {
            line-height: 40px;
        }
        .permission-group .permission {

        }
    </style>
@stop

@section('content')
    <div class="box shadow">

        <div class="box-body">
            <form class="form form-inline box-body" method="post" id="form"
                  action="{{ route('admin.role.store') }}">
                {{ csrf_field() }}
                {{ method_field('post') }}
                <div class="form-group">
                    <label class="control-label" for="name">名称：</label>
                    <input class="form-control " name="name" id="name" required value="{{ old('name') }}">
                </div>
                <br>

                <div class="form-group">
                    <label class="control-label" for="display">显示名称：</label>
                    <input class="form-control " name="display" id="display" required value="{{ old('display') }}">
                </div>
                <br>

                <div class="form-group">
                    <label class="control-label">选择权限：</label>
                    @foreach($routes_groups as $group => $routes)
                        <div class="panel panel-info margin-top-lg permission-group">
                            <div class="panel-heading">
                                <label class="pretty-checkbox">
                                    <input type="checkbox" class="select-all" data-target="{{$group}}">
                                    <span></span> {{\App\Services\WebServices\PermissionService::getPermissionGroupsMap()[$group] ?? $group}}
                                </label>
                            </div>
                            <div class="panel-body permission">
                                @foreach($routes as $route)
                                    <label class="pretty-checkbox">
                                        <input type="checkbox" name="permissions[]" data-group="{{$group}}"
                                               value="{{$route->action['as']}}"
                                               @if(old('permissions') && in_array($route->action['as'], old('permissions'))) checked @endif
                                        >
                                        <span></span> {{$route->action['display'] ?? "未指定"}}
                                    </label>
                                @endforeach
                            </div>

                        </div>
                    @endforeach
                </div>
                <br>

                <button type="submit" class="pretty-btn">提交</button>
            </form>
        </div>
    </div>


@stop

@section('js')
    <script>
        $(document).ready(function () {
            $('#system').addClass('active');
            $('#system_role_index').addClass('active');
        });

        $(".select-all").on("change", function () {
            $this = $(this);

            $("[data-group=" + $this.data("target") + "]").prop("checked", $this.is(":checked"))
        })
    </script>
@stop