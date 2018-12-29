<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>每人付 - 商户后台-找回密码</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="/css/adminlte.min.css">
    <link rel="stylesheet" href="/css/skin-blue.min.css">
    <link rel="stylesheet" href="https://unpkg.com/vant/lib/vant-css/index.css">

    <style>

    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">找回密码</div>
                <div class="panel-body form-horizontal" id="app">

                    <div class="form-group">
                        <label for="email" class="col-md-4 control-label">手机号码</label>

                        <div class="col-md-6">
                            <input id="mobile" class="form-control" v-model='mobile' name="mobile" value="{{ old('mobile') }}" required autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="col-md-4 control-label">新密码</label>

                        <div class="col-md-6">
                            <input id="password" type="password" v-model='password' class="form-control" name="password" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="col-md-4 control-label">确认新密码</label>
                        <div class="col-md-6">
                            <input id="password_check" type="password" v-model='password_check' class="form-control" name="password" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="col-md-4 control-label">短信验证码</label>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-xs-8">
                                    <input id="mobile_code" type="password" v-model='mobile_code' class="form-control" name="password" style="display: inline-block" required>
                                </div>
                                <div class="col-xs-4">
                                    <button class="van-button van-button--primary van-button--small" @click="getMsg" style="height:34px;">
                                    <span class="van-button__text" v-cloak>@{{msg_number}}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <button @click="goSubmit" class="btn btn-primary">
                                修改
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/js/jquery-2.2.3.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/mrf.common.js"></script>
<script src="/js/vue.js"></script>
<script src="/js/axios.min.js"></script>
<script src="https://unpkg.com/vant/lib/vant.min.js"></script>
<script>

    var app = new Vue({
        el: '#app',
        data: {
            mobile:'',
            password:'',
            password_check:'',
            mobile_code:'',
            msg_number:'获取验证码'
        },
        created:function(){
            var date1=Date.parse(new Date());
            var date2=window.localStorage.getItem('mobile_message');
            if(date2-date1<0){

            }else{
                var time=(date2-date1)/1000;
                this.msg_number=time;
                this.countDown();
            }
        },
        methods:{
            goSubmit:function(){
                var post_data = {
                    method: 'admin.agent.change.password',
                    nonce: 'admin.agent.change.password',
                    mobile: this.mobile,
                    password:this.password,
                    mobile_code:this.mobile_code
                };
                var _self=this;
                axios.post('/api/v1', api_data_sign(post_data, 'wis'))
                        .then(function (response) {
                            _self.flag=true;
                            var data=response.data;
                            if(data.code=='200'){
                                _self.$toast(data['msg']);
                                setTimeout(function(){
                                    window.location.href='{{route('agent.login')}}';
                                },3000)
                            }else{
                                if (Array.isArray(data['msg'])) {
                                    var len = data['msg'].length;
                                    len--;
                                    for (var i = 0; i <= len; i++) {
                                        var msg = data['msg'][i];
                                        _self.$toast(msg);
                                    }
                                } else {
                                    _self.$toast(data['msg']);
                                }
                            }

                        })
                        .catch(function (error) {
                            console.log(error);
                        });
            },
            getMsg(){
                var post_data = {
                    method: 'mobile.msg.get',
                    nonce: 'mobile.msg.get',
                    mobile: this.mobile,
                };
                var _self=this;
                axios.post('/api/v1', api_data_sign(post_data, 'wis'))
                        .then(function (response) {
                            _self.flag=true;
                            var data=response.data;
                            if(data.code=='200'){
                                //准备倒计时内容
                                _self.$toast(data['msg']);
                                var date=Date.parse(new Date());
                                date+=60000;
                                window.localStorage.setItem('mobile_message',date);
                                _self.msg_number=60;
                                _self.countDown();
                            }else{
                                if (Array.isArray(data['msg'])) {
                                    var len = data['msg'].length;
                                    len--;
                                    for (var i = 0; i <= len; i++) {
                                        var msg = data['msg'][i];
                                        _self.$toast(msg);
                                    }
                                } else {
                                    _self.$toast(data['msg']);
                                }
                            }

                        })
                        .catch(function (error) {
                            console.log(error);
                        });
            },
            countDown:function(){
                if(this.msg_number>0){
                    this.msg_number--;
                    setTimeout(this.countDown,1000);
                }else{
                    this.msg_number='获取验证码';
                }
            }
        }
    });

</script>
</body>
</html>
