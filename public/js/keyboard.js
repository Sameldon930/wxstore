(function(global,factory){
    if(typeof define === 'function' && define.amd){
        define(function(){
            return factory();
        });
    }else if(typeof module !== 'undefined' && module.exports){
        module.exports = factory();
    }else{
        global.KeyBoardNum = factory(global);
    }
}(typeof window !== 'undefined' ? window : this,function(win){
    var idTag = 0;
    var keyBoardNum = function(numberId,option){
        this.version = '0.0.1';
        var me = this,
            insertId = 'keyBoardNum'+(++idTag),
            payId = 'keyBoardPay'+(idTag),
            defaultOpt = {
                itemHeight:55, //一个数字键的高度
                decimal:2, //小数点长度
                integer:8, //整数部分长度
                fontSize:'25px', //数字字体大小
                color:'#333', //数字的颜色
                bgColor:'#fff', //背景颜色
                borderColor:'#dcdcdc', //边框颜色
                activeColor:'#dcdcdc', //键盘被点击时的背景颜色
                btnText:'确<br>认', //按钮显示的文字
                btnColor:'#d4d4d4', //按钮文字的颜色
                btnActiveColor:'#ff8f3b', //按钮激活时文字的颜色
                btnFontSize:'70px', //按钮文字大小
                btnBgColor:'#fff', //按钮的背景颜色
                btnActiveBgColor:'#fff', //按钮激活的背景颜色
                btnTouchBgColor:'#dcdcdc', //按钮激活后被点击时的背景颜色
                btnCallBack:null, //按钮点击后的回调
                delImg:delImgBase, //删除按钮图片
                hideImg:hideImgBase, //隐藏按钮图片
                canChangeBtnColor:false, //是否要点亮确认按钮,当这个选项值为true时，不应再由键盘对应的input框是否有值来判断是否变化确认按钮的颜色
                lightBtnColor:false //和 canChangeBtnColor配套使用，当canChangeBtnColor=true时，这个选项才生效，lightBtnColor=true时点亮确认按钮，为false时，灰掉确认按钮
            };
        for(var item in defaultOpt){
            if(option[item]){
                defaultOpt[item] = option[item];
            }
        }

        this.option = defaultOpt;
        var style = '.keyboardNum,.keyboardNum *{-webkit-tap-highlight-color: transparent;-webkit-focus-ring-color: transparent;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;margin: 0;padding: 0;border: 0;font-style: normal;}body {font-family: Hiragino Sans GB, Heiti SC, "微软雅黑", Verdana, sans-serif, SimHei, "黑体";}.keyboardNum.show {-webkit-transform: translateY(0);-moz-transform: translateY(0);-ms-transform: translateY(0);-o-transform: translateY(0);transform: translateY(0);}.keyboardNum:after{content: "";height: 0;visibility: hidden;display: block;clear: both;}.keyboardNum.show-trans {-webkit-transition: all linear 0.2s;-moz-transition: all linear 0.2s;-ms-transition: all linear 0.2s;-o-transition: all linear 0.2s;transition: all linear 0.2s;}.keyboardNum {width: 100%;height: '+me.option.itemHeight*4+'px;position: fixed;bottom: 0;left:0;background-color: '+me.option.bgColor+';z-index: 999;color: '+me.option.color+';font-size: '+me.option.fontSize+';line-height:'+me.option.itemHeight+'px;-webkit-transform: translateY(100%);-moz-transform: translateY(100%);-ms-transform: translateY(100%);-o-transform: translateY(100%);transform: translateY(100%);-webkit-transition: all linear 0.2s;-moz-transition: all linear 0.2s;-ms-transition: all linear 0.2s;-o-transition: all linear 0.2s;transition: all linear 0.2s;}.keyboardNum i {display: block;width: 25%;height: 25%;line-height: 60px;font-size: inherit;color: inherit;text-align: center;transition: all 0.1s;float: left;position: relative;}.keyboardNum i >img {height: 20px;position: absolute;top: 0;left: 0;bottom: 0;right: 0;margin: auto;}.keyboardNum i.del{float: right;height: '+me.option.itemHeight*1+'px;line-height: 1.2;padding: 0px 20px;display: -webkit-box;box-align: center;-webkit-box-align: center;box-pack: center;-webkit-box-pack: center;}.keyboardNum i.keyBoardPay {float: right;height: 170px;line-height: 4.2;background-color: #2F8CE5;color: #fff;font-size: 20px;padding: 0px 20px;display: -webkit-box;box-align: center;-webkit-box-align: center;box-pack: center;-webkit-box-pack: center;}.keyboardNum i.hover,.keyboardNum i:active:not(.keyboardNum i.keyBoardPay) {background-color: '+me.option.activeColor+';}.keyboardNum i.keyBoardPay.active {background-color: #2F8CE5;color:#fff;}.keyboardNum i.keyBoardPay.active.hover,.keyboardNum i.keyBoardPay.active:active {background-color: '+me.option.btnTouchBgColor+';}.border-bottom-1px:after,.border-bottom-1px:before,.border-left-1px:after,.border-left-1px:before,.border-right-1px:after,.border-right-1px:before,.border-top-1px:after,.border-top-1px:before{content:"";display:block;position:absolute;-webkit-transform-origin:0 0;transform-origin:0 0}.border-top-1px:before{border-top:1px solid '+me.option.borderColor+';left:0;top:0;width:100%}.border-right-1px:after{border-right:1px solid '+me.option.borderColor+';top:0;right:0;height:100%;-webkit-transform-origin:right 0;transform-origin:right 0}.border-bottom-1px:after{border-bottom:1px solid '+me.option.borderColor+';left:0;bottom:0;width:100%;-webkit-transform-origin:0 bottom;transform-origin:0 bottom}.border-left-1px:before{border-left:1px solid '+me.option.borderColor+';top:0;left:0;height:100%}@media (-webkit-min-device-pixel-ratio:2),(min-device-pixel-ratio:2){.border-top-1px:before{width:200%}.border-right-1px:after,.border-top-1px:before{-webkit-transform:scale(.5) translateZ(0);transform:scale(.5) translateZ(0)}.border-right-1px:after{height:200%}.border-bottom-1px:after{width:200%}.border-bottom-1px:after,.border-left-1px:before{-webkit-transform:scale(.5) translateZ(0);transform:scale(.5) translateZ(0)}.border-left-1px:before{height:200%}}@media (-webkit-min-device-pixel-ratio:3),(min-device-pixel-ratio:3){.border-top-1px:before{width:300%}.border-right-1px:after,.border-top-1px:before{-webkit-transform:scale(.333) translateZ(0);transform:scale(.333) translateZ(0)}.border-right-1px:after{height:300%}.border-bottom-1px:after{width:300%}.border-bottom-1px:after,.border-left-1px:before{-webkit-transform:scale(.333) translateZ(0);transform:scale(.333) translateZ(0)}.border-left-1px:before{height:300%}}';

        $('head').append('<style type="text/css">'+style+'</style>');

        var html = '<div id="'+insertId+'" class="keyboardNum">'+
            '    <i data-num="del" class="del border-top-1px border-right-1px">'+
            '       <img src="'+me.option.delImg+'">'+
            '    </i>'+
            '    <i data-num="1" class="border-top-1px border-right-1px">1</i>'+
            '    <i data-num="2" class="border-top-1px border-right-1px">2</i>'+
            '    <i data-num="3" class="border-top-1px border-right-1px">3</i>'+
            '    <i data-num="4" class="border-top-1px border-right-1px">4</i>'+
            '    <i data-num="5" class="border-top-1px border-right-1px">5</i>'+
            '    <i data-num="6" class="border-top-1px border-right-1px">6</i>'+
            '<i data-num="callback" id="'+payId+'" class="keyBoardPay border-top-1px border-right-1px ">'+me.option.btnText+'</i>'+
            '    <i data-num="7" class="border-top-1px border-right-1px">7</i>'+
            '    <i data-num="8" class="border-top-1px border-right-1px">8</i>'+
            '    <i data-num="9" class="border-top-1px border-right-1px">9</i>'+
            '    <i data-num="hide" class="border-top-1px border-right-1px">'+
            '       <img src="'+me.option.hideImg+'"></i>'+
            '    <i data-num="0" class="border-top-1px border-right-1px">0</i>'+
            '    <i data-num="." class="border-top-1px border-right-1px"><span>.</span></i>'+
            '</div>';
        $('body').append(html);
        this.dom = $('#'+insertId);
        this.numDom = $(numberId);
        this.payDom = $('#'+payId);
        this.init = function(){ //插入style、元素
            me.dom.on('touchstart','i',function(evt){
                evt.preventDefault();
                var num = $(this).data('num');
                switch(num){
                    case 'del':
                        if(me.numDom.val() != ""){
                            me.delete(me.numDom);
                            me.callback(evt,'del');
                        }
                        break;
                    case 'callback':
                        me.callback(evt,'sure');
                        break;
                    case 'hide':
                        me.hideBoard(me.dom);
                        me.callback(evt,'hide');
                        break;
                    default:
                        var beginNum = me.numDom.val();
                        me.insert(me.numDom,num,me.option);
                        if(me.numDom.val() != beginNum){//只有两次输入有变化时，才回调
                            me.callback(evt,'num');
                        }
                }
            });
        }

        this.init();
        return this;
    }
    keyBoardNum.prototype.show = function(){
        var me = this;
        if(me.option.canChangeBtnColor){
            if(me.option.lightBtnColor){
                me.payDom.addClass('active');
            }else {
                me.payDom.removeClass('active');
            }
        }
        me.dom.addClass('show');
        return this;
    }
    keyBoardNum.prototype.delete = function(dom){
        var str = dom.val().trim();
        if(str !== ''){
            dom.val(str.substring(0,str.length-1));
            if(this.option.canChangeBtnColor && this.option.lightBtnColor){

            }else if(dom.val()==''){
                this.payDom.removeClass('active');
            }

            if(dom.val()==''){
                this.num = 0;
            }else{
                this.num = dom.val();
            }
        }
        return this;
    }
    keyBoardNum.prototype.callback = function(evt,kind){
        this.option.btnCallBack && this.option.btnCallBack(evt,kind,parseFloat(this.num));
    };
    keyBoardNum.prototype.hideBoard = function(){
        this.dom.removeClass('show');
        return this;
    };
    keyBoardNum.prototype.insert = function(dom,num,option){
        var str = dom.val();
        if(str == ''){ //没有内容
            if(num == '.'){
                dom.val('0.');
                return;
            }
        }else if(str == '0' && str.length == 1 && num != '.'){ //如果之前输入了0，后面只能输入小数点
            return;
        }else if(str.indexOf('.')>-1){ //已经输入过小数点了
            if(num == '.') return;
            if(str.substring(str.indexOf('.')+1,str.length).length == option.decimal) return;
        }else if(num != '.'){ //没有输入过小数点
            if(str.length==option.integer) return;
        }
        this.num = str + num;
        dom.val(this.num);
        if(this.option.canChangeBtnColor && !this.option.lightBtnColor){

        }else {
            this.payDom.addClass('active');
        }
        return this;
    }
    var delImgBase = 'data:image/png;base64,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';
    var hideImgBase = 'data:image/png;base64,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';
    return keyBoardNum;
}));