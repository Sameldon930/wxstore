<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>每人付 - 管理总后台</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="/css/adminlte.min.css">
    <link rel="stylesheet" href="/css/skin-blue.min.css">

    <style>
        body {

            background-image:url("/img/bg-total.jpg");
            background-repeat: no-repeat;
            background-size: cover;
            color: rgb(51, 51, 51);
            font-family: Arial, 微软雅黑, "Microsoft yahei", sans-serif;
        }
        .login-main {
            margin-top: 15%;
            margin-left: 54.1666666%;
            width: 45.8333333%;
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            align-items: center;
        }
        @media (max-width: 767px) {
            .login-main {
                margin-left: 0;
                width: 100%;
                margin-top: 50%;
            }
        }
        .login-head {
            color: #2F8CE5;
            font-weight: bold;
        }

        .login-form {
            /*background-color: white;*/
            padding: 24px 78px;
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            /*height: 33vh;*/
            border-radius: 8px;
            align-items: center;
        }
        .login-form .form-item {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }
        .login-form > img{
            height: 20px;
            width: 20px;
            margin-right: 8px;
        }
        .login-form .form-item > input{
            text-align: center;
            border: 0;
            padding: 8px;
            font-size: 16px;
            width: 290px;
        }
        .login-form .form-item > input:focus{
            outline: none;
        }
        .login-form .checkbox {
            text-align: center;
            color: #a9a9a9;
        }
        .login-form .submit {
            text-align: center;
            background-color: #2F8CE5;
            color: #fff;
            border-radius: 22px;
            font-size: 16px;
            width: 194px;
        }
        .login-form .submit:active:focus, .login-form .submit:focus {
            outline: none;
        }
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="container-fluid">
    <div class="row">
        <div class="login-main col-md-4">
            <h2 class="login-head">
                <img src="../img/share.png"  width="39px" alt="">
                每人付&nbsp;|&nbsp;总后台
            </h2>
            <form class="login-form form-horizontal" method="POST"
                  action="{{ route('admin.login') }}">
                {{ csrf_field() }}

                <div class="form-item {{ $errors->has('mobile') ? ' has-error' : '' }}">
                    <img src="../img/i/login-account.png" width="30px" alt="">

                    <input name="mobile" value="{{ old('mobile') }}" autofocus placeholder="请输入用户名">

                </div>
                @if ($errors->has('mobile'))
                    <span class="help-block ">
                            <strong>{{ $errors->first('mobile') }}</strong>
                        </span>
                @endif

                <div class="form-item {{ $errors->has('password') ? ' has-error' : '' }}">
                    <img src="../img/i/login-pwd.png" width="30px" alt="">

                    <input type="password" name="password" placeholder="请输入密码">

                </div>
                @if ($errors->has('password'))
                    <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                @endif

                <div class="checkbox text-center" style="color: #1a1a1a;margin: 10px auto;">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>记住密码
                    </label>
                </div>

                <button type="submit" class="submit btn">
                    登录
                </button>
            </form>
        </div>

    </div>
</div>
<script src="/js/jquery-2.2.3.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/adminlte.min.js"></script>

</body>
</html>
