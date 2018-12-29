<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>扫码支付</title>

    <link rel="stylesheet" href="/css/bootstrap.min.css">

    <style>
        .navbar {
            border-radius: 0;
            border: 0;
            border-bottom: 1px solid #eee;
        }
        .form-control, .btn {
            border-radius: 2px;
        }
        #img {
            max-width: 100%;
            height: auto;
            padding: 4px;
            line-height: 1.42857143;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            -webkit-transition: all .2s ease-in-out;
            -o-transition: all .2s ease-in-out;
            transition: all .2s ease-in-out;
        }
    </style>
</head>
<body>

{{--<nav class="navbar navbar-default navbar-inverseq">
    <div class="container">
        <div class="navbar-header ">
            <a class="navbar-brand">扫码支付</a>
        </div>
    </div>
</nav>--}}

<div class="container" style="margin-top: 22px">
    <div class="row">
        <div class="col-xs-12">
            <form class="form-horizontal">
                <div class="form-group">
                    <div class="col-xs-12">
                        <input type="number" class="form-control" id="account" placeholder="请输入金额">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-12">
                        <button type="button" data-loading-text="生成中请稍后。。"
                                class="btn btn-primary btn-lg btn-block" id="create">
                            <span class="glyphicon glyphicon-hand-right"></span> 点我生成二维码
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row" style="margin-top: 30px;">
        <div class="col-xs-12 text-center">
            <div id="code" style="display: none;"></div>
            <img src="" alt="" id="img">
        </div>
    </div>
</div>



<script src="/js/jquery.min.js"></script>
<script src="/js/jquery.qrcode.min.js"></script>
<script src="/js/bootstrap.min.js"></script>

<script>
    $("#create").on("click", function (){

        var account = $("#account").val();
        if (!account || account == '' || Number(account) <= 0){
            alert('请输入大于0的金额。')
            return false;
        }

        var $btn = $(this).button('loading')
        $.ajax({
            url: "order?account=" + account,
            success: function(data){
                var codeUrl = data.code_url;
                var qrcode = $('#code').html("").qrcode({
                    render: "canvas", //也可以替换为table
                    text: codeUrl
                });
                var canvas=qrcode.find('canvas').get(0);
                $('#img').attr('src',canvas.toDataURL('image/jpg'))
            },
            complete:function () {
                $btn.button('reset')
            }
        });
    })
</script>
</body>
</html>