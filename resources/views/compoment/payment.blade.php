<style>
    .num-group {
        /*text-align: center;*/
        border: 1px solid #E5E5E5;
        margin-top: 10px;
        margin-left: 10px;
        margin-right: 10px;
        padding: 10px;
        background-color: #f9f9f9;
        line-height: 61px;
        height: 61px;
    }
    .num-group input {
        width: 68%;
        outline: none;
        border: 0;
        font-size: 18px;
        font-weight: bold;
        line-height: 18px;
        text-align: right;
        background-color: transparent;
    }
    .num-group label {
        width: 30%;
        font-size: 16px;
        line-height: 16px;
        color: #5F5F5F ;
    }

    .logo{
        display: flex;
        justify-content: center;
    }
    .logo>img{
        width:85px;
        height:85px;
        border-radius:50%; overflow:hidden;
        border:1px solid #f9f9f9;
    }
</style>

<div class="store">

</div>
<div class="logo" style="">
    <img src="../../../../img/pay-logo.png" alt="">
</div>
<div class="num-group">
    <label for="num">支付金额</label>
    <input type="text" id="num" readonly>
</div>


@section('payment')
    <script src="/js/keyboard.js"></script>
    <script>
        $(function () {
            $num = $("#num");

            k = new KeyBoardNum('#num', {
                btnCallBack: function (ev, kind){
                    if (kind === 'sure'){
                        payCheck()
                    }
                }
            });
            k.show();
        });

        $("#num").on('click', function (){
            k.show();
        })

        function payCheck(){
            var amount = $num.val();
            if (amount === '' || Number(amount) === 0){
                alert('请输入金额')
            }else {
                pay(amount);
            }
        }
        function getQueryString(name)
        {
            var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
            var r = window.location.search.substr(1).match(reg);
            if(r!=null)return  unescape(r[2]); return null;
        }
    </script>
@endsection