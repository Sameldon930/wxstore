@extends('layouts.store')

@section('pageTitle')
    修改密码
@stop

@section('css')
    <style>

    </style>
@stop

@section('content')
    <div class="box-body" style="width:500px;">
        <h4 class="modal-title">修改密码</h4>
        <form class="form-horizontal" method="POST" action="{{ route('stores.password.update_password') }}" id="form">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="modal-body">

                <div class="input-group box-body">
                    <div class="input-group-btn">
                        <label for="old_password" class="pretty-btn">旧密码</label>
                    </div>
                    <input type="password" class="form-control" name="old_password" maxlength="20" required>
                </div>

                <div class="input-group box-body">
                    <div class="input-group-btn">
                        <label for="password" class="pretty-btn">新密码</label>
                    </div>
                    <input type="password" class="form-control" name="password" maxlength="20" required>
                </div>

                <div class="input-group box-body">
                    <div class="input-group-btn">
                        <label for="once_password" class="pretty-btn">确认密码</label>
                    </div>
                    <input type="password" class="form-control" name="password_confirmation" maxlength="20" required>
                </div>
                <div class="box-body">
                    <button type="submit" class="pretty-btn">确认修改</button>
                </div>
            </div>

        </form>
    </div>

@stop

@section('js')
    <script>

        $(document).ready(function () {
            $('#system').addClass('active');
            $('#system_change_password').addClass('active');
        });

    </script>
@stop
