axios.defaults.timeout = 10000 ;
// 添加响应拦截器
axios.interceptors.response.use(function (response) {
    // 对响应数据做点什么
    return response;
}, function (error) {
    // 对响应错误做点什么
    if(error.code=='ECONNABORTED'){
        WIS_toast({msg: '连接超时！请检查网络或联系客服', time: 5000, location: 'middle'});
    }

    //no_network();
    return Promise.reject(error);
});


var md5 = function (string) {
    function RotateLeft(lValue, iShiftBits) {
        return (lValue<<iShiftBits) | (lValue>>>(32-iShiftBits));
    }
    function AddUnsigned(lX,lY) {
        var lX4,lY4,lX8,lY8,lResult;
        lX8 = (lX & 0x80000000);
        lY8 = (lY & 0x80000000);
        lX4 = (lX & 0x40000000);
        lY4 = (lY & 0x40000000);
        lResult = (lX & 0x3FFFFFFF)+(lY & 0x3FFFFFFF);
        if (lX4 & lY4) {
            return (lResult ^ 0x80000000 ^ lX8 ^ lY8);
        }
        if (lX4 | lY4) {
            if (lResult & 0x40000000) {
                return (lResult ^ 0xC0000000 ^ lX8 ^ lY8);
            } else {
                return (lResult ^ 0x40000000 ^ lX8 ^ lY8);
            }
        } else {
            return (lResult ^ lX8 ^ lY8);
        }
    }
    function F(x,y,z) { return (x & y) | ((~x) & z); }
    function G(x,y,z) { return (x & z) | (y & (~z)); }
    function H(x,y,z) { return (x ^ y ^ z); }
    function I(x,y,z) { return (y ^ (x | (~z))); }
    function FF(a,b,c,d,x,s,ac) {
        a = AddUnsigned(a, AddUnsigned(AddUnsigned(F(b, c, d), x), ac));
        return AddUnsigned(RotateLeft(a, s), b);
    };
    function GG(a,b,c,d,x,s,ac) {
        a = AddUnsigned(a, AddUnsigned(AddUnsigned(G(b, c, d), x), ac));
        return AddUnsigned(RotateLeft(a, s), b);
    };
    function HH(a,b,c,d,x,s,ac) {
        a = AddUnsigned(a, AddUnsigned(AddUnsigned(H(b, c, d), x), ac));
        return AddUnsigned(RotateLeft(a, s), b);
    };
    function II(a,b,c,d,x,s,ac) {
        a = AddUnsigned(a, AddUnsigned(AddUnsigned(I(b, c, d), x), ac));
        return AddUnsigned(RotateLeft(a, s), b);
    };
    function ConvertToWordArray(string) {
        var lWordCount;
        var lMessageLength = string.length;
        var lNumberOfWords_temp1=lMessageLength + 8;
        var lNumberOfWords_temp2=(lNumberOfWords_temp1-(lNumberOfWords_temp1 % 64))/64;
        var lNumberOfWords = (lNumberOfWords_temp2+1)*16;
        var lWordArray=Array(lNumberOfWords-1);
        var lBytePosition = 0;
        var lByteCount = 0;
        while ( lByteCount < lMessageLength ) {
            lWordCount = (lByteCount-(lByteCount % 4))/4;
            lBytePosition = (lByteCount % 4)*8;
            lWordArray[lWordCount] = (lWordArray[lWordCount] | (string.charCodeAt(lByteCount)<<lBytePosition));
            lByteCount++;
        }
        lWordCount = (lByteCount-(lByteCount % 4))/4;
        lBytePosition = (lByteCount % 4)*8;
        lWordArray[lWordCount] = lWordArray[lWordCount] | (0x80<<lBytePosition);
        lWordArray[lNumberOfWords-2] = lMessageLength<<3;
        lWordArray[lNumberOfWords-1] = lMessageLength>>>29;
        return lWordArray;
    };
    function WordToHex(lValue) {
        var WordToHexValue="",WordToHexValue_temp="",lByte,lCount;
        for (lCount = 0;lCount<=3;lCount++) {
            lByte = (lValue>>>(lCount*8)) & 255;
            WordToHexValue_temp = "0" + lByte.toString(16);
            WordToHexValue = WordToHexValue + WordToHexValue_temp.substr(WordToHexValue_temp.length-2,2);
        }
        return WordToHexValue;
    };
    function Utf8Encode(string) {
        string = string.replace(/\r\n/g,"\n");
        var utftext = "";

        for (var n = 0; n < string.length; n++) {

            var c = string.charCodeAt(n);

            if (c < 128) {
                utftext += String.fromCharCode(c);
            }
            else if((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            }
            else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }

        }

        return utftext;
    };
    var x=Array();
    var k,AA,BB,CC,DD,a,b,c,d;
    var S11=7, S12=12, S13=17, S14=22;
    var S21=5, S22=9 , S23=14, S24=20;
    var S31=4, S32=11, S33=16, S34=23;
    var S41=6, S42=10, S43=15, S44=21;

    string = Utf8Encode(string);
    x = ConvertToWordArray(string);
    a = 0x67452301; b = 0xEFCDAB89; c = 0x98BADCFE; d = 0x10325476;
    for (k=0;k<x.length;k+=16) {
        AA=a; BB=b; CC=c; DD=d;
        a=FF(a,b,c,d,x[k+0], S11,0xD76AA478);
        d=FF(d,a,b,c,x[k+1], S12,0xE8C7B756);
        c=FF(c,d,a,b,x[k+2], S13,0x242070DB);
        b=FF(b,c,d,a,x[k+3], S14,0xC1BDCEEE);
        a=FF(a,b,c,d,x[k+4], S11,0xF57C0FAF);
        d=FF(d,a,b,c,x[k+5], S12,0x4787C62A);
        c=FF(c,d,a,b,x[k+6], S13,0xA8304613);
        b=FF(b,c,d,a,x[k+7], S14,0xFD469501);
        a=FF(a,b,c,d,x[k+8], S11,0x698098D8);
        d=FF(d,a,b,c,x[k+9], S12,0x8B44F7AF);
        c=FF(c,d,a,b,x[k+10],S13,0xFFFF5BB1);
        b=FF(b,c,d,a,x[k+11],S14,0x895CD7BE);
        a=FF(a,b,c,d,x[k+12],S11,0x6B901122);
        d=FF(d,a,b,c,x[k+13],S12,0xFD987193);
        c=FF(c,d,a,b,x[k+14],S13,0xA679438E);
        b=FF(b,c,d,a,x[k+15],S14,0x49B40821);
        a=GG(a,b,c,d,x[k+1], S21,0xF61E2562);
        d=GG(d,a,b,c,x[k+6], S22,0xC040B340);
        c=GG(c,d,a,b,x[k+11],S23,0x265E5A51);
        b=GG(b,c,d,a,x[k+0], S24,0xE9B6C7AA);
        a=GG(a,b,c,d,x[k+5], S21,0xD62F105D);
        d=GG(d,a,b,c,x[k+10],S22,0x2441453);
        c=GG(c,d,a,b,x[k+15],S23,0xD8A1E681);
        b=GG(b,c,d,a,x[k+4], S24,0xE7D3FBC8);
        a=GG(a,b,c,d,x[k+9], S21,0x21E1CDE6);
        d=GG(d,a,b,c,x[k+14],S22,0xC33707D6);
        c=GG(c,d,a,b,x[k+3], S23,0xF4D50D87);
        b=GG(b,c,d,a,x[k+8], S24,0x455A14ED);
        a=GG(a,b,c,d,x[k+13],S21,0xA9E3E905);
        d=GG(d,a,b,c,x[k+2], S22,0xFCEFA3F8);
        c=GG(c,d,a,b,x[k+7], S23,0x676F02D9);
        b=GG(b,c,d,a,x[k+12],S24,0x8D2A4C8A);
        a=HH(a,b,c,d,x[k+5], S31,0xFFFA3942);
        d=HH(d,a,b,c,x[k+8], S32,0x8771F681);
        c=HH(c,d,a,b,x[k+11],S33,0x6D9D6122);
        b=HH(b,c,d,a,x[k+14],S34,0xFDE5380C);
        a=HH(a,b,c,d,x[k+1], S31,0xA4BEEA44);
        d=HH(d,a,b,c,x[k+4], S32,0x4BDECFA9);
        c=HH(c,d,a,b,x[k+7], S33,0xF6BB4B60);
        b=HH(b,c,d,a,x[k+10],S34,0xBEBFBC70);
        a=HH(a,b,c,d,x[k+13],S31,0x289B7EC6);
        d=HH(d,a,b,c,x[k+0], S32,0xEAA127FA);
        c=HH(c,d,a,b,x[k+3], S33,0xD4EF3085);
        b=HH(b,c,d,a,x[k+6], S34,0x4881D05);
        a=HH(a,b,c,d,x[k+9], S31,0xD9D4D039);
        d=HH(d,a,b,c,x[k+12],S32,0xE6DB99E5);
        c=HH(c,d,a,b,x[k+15],S33,0x1FA27CF8);
        b=HH(b,c,d,a,x[k+2], S34,0xC4AC5665);
        a=II(a,b,c,d,x[k+0], S41,0xF4292244);
        d=II(d,a,b,c,x[k+7], S42,0x432AFF97);
        c=II(c,d,a,b,x[k+14],S43,0xAB9423A7);
        b=II(b,c,d,a,x[k+5], S44,0xFC93A039);
        a=II(a,b,c,d,x[k+12],S41,0x655B59C3);
        d=II(d,a,b,c,x[k+3], S42,0x8F0CCC92);
        c=II(c,d,a,b,x[k+10],S43,0xFFEFF47D);
        b=II(b,c,d,a,x[k+1], S44,0x85845DD1);
        a=II(a,b,c,d,x[k+8], S41,0x6FA87E4F);
        d=II(d,a,b,c,x[k+15],S42,0xFE2CE6E0);
        c=II(c,d,a,b,x[k+6], S43,0xA3014314);
        b=II(b,c,d,a,x[k+13],S44,0x4E0811A1);
        a=II(a,b,c,d,x[k+4], S41,0xF7537E82);
        d=II(d,a,b,c,x[k+11],S42,0xBD3AF235);
        c=II(c,d,a,b,x[k+2], S43,0x2AD7D2BB);
        b=II(b,c,d,a,x[k+9], S44,0xEB86D391);
        a=AddUnsigned(a,AA);
        b=AddUnsigned(b,BB);
        c=AddUnsigned(c,CC);
        d=AddUnsigned(d,DD);
    }
    var temp = WordToHex(a)+WordToHex(b)+WordToHex(c)+WordToHex(d);
    //转大写
    return temp.toUpperCase();
}

//接口数据签名
//token . post_data . token
/*function api_data_sign(post_data, token){
 var token = 'wis';  //定期修改
 var post_data_string = '';
 key_sort(post_data);
 for (var key in post_data) {
 post_data_string += key;
 post_data_string += post_data[key];
 }
 post_data_string = token + post_data_string + token;
 post_data.sign = md5(post_data_string);
 return post_data;
 }*/

//排序的函数(对象)
function key_sort(obj) {
    var newkey = Object.keys(obj).sort();
    //先用Object内置类的keys方法获取要排序对象的属性名，再利用Array原型上的sort方法对获取的属性名进行排序，newkey是一个数组
    var newobj = {};//创建一个新的对象，用于存放排好序的键值对
    for (var i = 0; i < newkey.length; i++) {//遍历newkey数组
        newobj[newkey[i]] = obj[newkey[i]];//向新创建的对象中按照排好的顺序依次增加键值对
    }
    return newobj;//返回排好序的新对象
}

/**
 * 接口数据签名 token . post_data . token @cxy
 * @param post_data
 * @param token
 * @returns {*}
 * 原代码中 for in 遍历对象不能保证按原来的顺序输出
 * 修改：将对象的键取出放到数组中 对数组排序 然后遍历数组中的键到post_data中取值
 */
function api_data_sign(post_data, token){
    var token = 'wis';  //定期修改
    var post_data_string = '';
    var keys = [];
    for (var key in post_data) {  // 将对象的键取出放到数组中
        keys.push(key);
    }
    keys = keys.sort();  // 对数组排序
    for (var i=0; i<keys.length; i++){  // 遍历数组中的键到post_data中取值
        post_data_string += keys[i];
        post_data_string += post_data[keys[i]];
    }
    post_data_string = token + post_data_string + token;
    post_data.sign = md5(post_data_string);
    return post_data;
}

/**
 * 公共弹出框 普通
 * @param msg 提示消息
 * @param type 1:成功提示 2:失败警告
 * @param onConfirm 确定回调
 * @author chenxinyue
 */
var WIS_alert = function () {
    $('#wis-alert').remove();
    // 获取参数
    var config = arguments[0] ? arguments[0] : {};
    var msg = config.msg ? config.msg : '';
    var type = config.type ? config.type : 1;
    var onConfirm = config.onConfirm ? config.onConfirm : function(){return false};
    var textConfirm = config.textConfirm ? config.textConfirm : '确定';

    // 拼接弹窗HTML 并插入DOM
    var html = [];
    html.push('<div class="am-modal am-modal-alert am-modal-active" style="display: block" tabindex="10010" id="wis-alert">');
    html.push('<div class="am-modal-dialog">');
    if (type === 1){
        html.push('<div class="am-modal-hd"><span class="am-icon-btn am-success am-icon-check"></span></div>');
    } else {
        html.push('<div class="am-modal-hd"><span class="am-icon-btn am-warning am-icon-exclamation"></span></div>');
    }
    html.push('<div class="am-modal-bd">' + msg + '</div>');
    html.push('<div class="am-ellipsis am-modal-footer"><span class="am-modal-btn">' + textConfirm +'</span></div>');
    html.push('</div></div>');
    html = html.join('');
    $(html).appendTo(document.body);
    $('#wis-alert').on('close.modal.amui', onConfirm);
    // 弹出执行
    $('#wis-alert').modal();
};

/**
 * 公共弹出框 询问
 * @param msg 提示消息
 * @param onConfirm 确定回调
 * @param onCancel 取消回调
 * @author chenxinyue
 */
var WIS_confirm = function () {
    //click.dimmer.modal.amui  confirm.modal.amui click.cancel.modal.amui cancel.modal.amui

    $('#wis-confirm').off('cancel.modal.amui').off('confirm.modal.amui').remove();
    // 获取参数
    var config = arguments[0] ? arguments[0] : {};
    var msg = config.msg ? config.msg : '';
    var type = config.type ? config.type : 1;
    var onConfirm = config.onConfirm ? config.onConfirm : function(){return false};
    var onCancel = config.onCancel ? config.onCancel : function(){return false};
    var closeViaDimmer = config.closeViaDimmer ? config.closeViaDimmer : false;
    var textConfirm = config.textConfirm ? config.textConfirm : '确定';
    var textCancel = config.textCancel ? config.textCancel : '取消';
    var styleConfirm= config.styleConfirm ? config.styleConfirm : "";
    var styleCancel = config.styleCancel ? config.styleCancel : "";
    var title = config.title ? config.title : '';
    // 拼接弹窗HTML 并插入DOM
    var html = [];
    html.push('<div class="am-modal am-modal-confirm am-modal-active" style="display: block" tabindex="10010" id="wis-confirm">');
    html.push('<div class="am-modal-dialog">');
    if (type === 1){
        html.push('<div class="am-modal-hd"><span class="am-icon-btn am-warning am-icon-exclamation"></span></div>');
    } else if(type === 'close' ){
        html.push('<div class="am-modal-hd wis-background-primary wis-text-white am-margin-bottom-sm " style="height: 49px; padding: 0px; line-height: 49px">'+title+'<a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a></div>');
    }else {
        html.push('<div class="am-modal-hd"><span class="am-icon-btn am-success am-icon-check"></span></div>');
    }
    html.push('<div class="am-modal-bd">' + msg + '</div>');
    html.push('<div class="am-modal-footer">');
    html.push('<span class="am-ellipsis am-modal-btn" ' + styleCancel +' data-am-modal-cancel>' + textCancel +'</span>');
    html.push('<span class="am-ellipsis am-modal-btn" ' + styleConfirm +' data-am-modal-confirm>' + textConfirm +'</span>');
    html.push('</div>');
    html.push('</div></div>');
    html = html.join('');
    $(html).appendTo(document.body);

    // 弹出执行
    $('#wis-confirm').modal({
        relatedTarget: this,
        onConfirm: onConfirm,
        onCancel: onCancel,
        closeViaDimmer: closeViaDimmer,
    });
};

/**
 * 公共弹出框 文本提交
 * @param msg 提示消息
 * @param onConfirm 确定回调
 * @param onCancel 取消回调
 * @author chenxinyue
 */
var WIS_prompt = function () {
    $('#wis-prompt').off('cancel.modal.amui').off('confirm.modal.amui').remove();
    // 获取参数
    var config = arguments[0] ? arguments[0] : {};
    var msg = config.msg ? config.msg : '';
    var type = config.type ? config.type : false;
    var onConfirm = config.onConfirm ? config.onConfirm : function(){return false};
    var onCancel = config.onCancel ? config.onCancel : function(){return false};
    var textConfirm = config.textConfirm ? config.textConfirm : '确定';
    var textCancel = config.textCancel ? config.textCancel : '取消';
    var htmlInput = config.htmlInput ? config.htmlInput : '<input type="text" class="am-modal-prompt-input"></div>';
    var htmlClose = '<a class="am-close prompt-close" data-am-modal-close>&times;</a>';
    var hasCancel = config.hasCancel === undefined ? true : config.hasCancel;
    var hasIcon = config.hasIcon === undefined ? true : config.hasIcon;
    var closeOnConfirm = config.closeOnConfirm === undefined ? true : config.closeOnConfirm;
    var styleConfirm= config.styleConfirm ? config.styleConfirm : "";
    var styleCancel = config.styleCancel ? config.styleCancel : "";

    var loadingConfirm = config.loadingConfirm ? 'data-action="loading"' : '';
    // 拼接弹窗HTML 并插入DOM
    var html = [];
    html.push('<div class="am-modal am-modal-prompt am-modal-active" style="display: block" tabindex="10010" id="wis-prompt">');
    html.push('<div class="am-modal-dialog">');
    if (hasIcon){
        html.push('<div class="am-modal-hd"><span class="am-icon-btn am-warning am-icon-pencil"></span></div>');
    }
    html.push('<div class="am-modal-bd">' + msg + htmlInput + htmlClose + '</div>');
    html.push('<div class="am-modal-footer">');
    if (hasCancel){
        html.push('<span class="am-ellipsis am-modal-btn" data-am-modal-cancel ' + styleCancel + '>' + textCancel +'</span>');
    }
    html.push('<span class="am-ellipsis am-modal-btn" data-am-modal-confirm ' + loadingConfirm + ' ' + styleConfirm + '> ' + textConfirm +'</span>');
    html.push('</div>');
    html.push('</div></div>');
    html = html.join('');
    $(html).appendTo(document.body);

    // 弹出执行
    $('#wis-prompt').modal({
        closeOnConfirm: closeOnConfirm,
        relatedTarget: this,
        onConfirm: onConfirm,
        onCancel: onCancel
    });
}

/**
 * 公共弹出框 仿Toast提示
 * @param msg
 * @param time default:3000
 * @param location top|middle|bottom  default:bottom
 * @author chenxinyue
 */
var WIS_toast = function(config){
    $("#WIS_toast").remove();

    var context = $(document.body);
    var msg = config.msg;
    var time = config.time ? config.time : 3000;
    var location = config.location ? config.location : 'bottom';
    var background = config.background ? config.background : '#000';
    //消息体
    var html = [];
    html.push('<div id="WIS_toast">');
    html.push('<span>'+ msg +'</span>');
    html.push('</div>');
    var htmlEntity = $(html.join('')).appendTo(context);
    htmlEntity.css({
        position: 'absolute',
        'z-index': 1110,
        background: background,
        color: 'white',
        'font-size': '14px',
        padding: '10px',
        'border-radius': '4px'
    });
    //消息样式
    var left = ( window.screen.width - htmlEntity.outerWidth() ) / 2 ;
    var top;
    switch (location){
        case 'top':
            top = window.screen.height*0.15 + window.pageYOffset;
            break;
        case 'middle':
            top = window.screen.height * 0.5 + window.pageYOffset;
            break;
        default:
            top = window.screen.height*0.7 + window.pageYOffset;
            break;
    }
    // 计算完宽高在给样式 否则位置有偏差
    htmlEntity.css({
        top: top,
        left: left,
    });

    htmlEntity.hide();
    htmlEntity.fadeIn(time/4);
    setTimeout(function(){htmlEntity.fadeOut(time/4)},time/2);
    //htmlEntity.fadeOut(time/6);

}

/**
 * 公共弹出框 通知
 * @param msg 提示消息
 * @param type 1:成功提示 2:失败警告
 * @param onConfirm 确定回调
 * @author chenxinyue
 */
var WIS_notice = function () {
    $('#wis-notice').remove();
    // 获取参数
    var config = arguments[0] ? arguments[0] : {};
    var msg = config.msg ? config.msg : '';
    var type = config.type ? config.type : 1;
    var onConfirm = config.onConfirm ? config.onConfirm : function(){return false};
    var textConfirm = config.textConfirm ? config.textConfirm : '我知道了';
    var textTitle = config.textTitle ? config.textTitle : '系统通知';

    // 拼接弹窗HTML 并插入DOM
    var html = [];
    html.push('<div class="am-modal am-modal-alert am-modal-active" style="display: block" tabindex="10010" id="wis-notice">');
    html.push('<div class="am-modal-dialog">');
    html.push('<div class="am-modal-hd wis-border-bottom">' + textTitle +'</span></div>');
    html.push('<div class="am-modal-bd">' + msg + '</div>');
    html.push('<div class="am-ellipsis am-modal-footer"><span class="am-modal-btn">' + textConfirm +'</span></div>');
    html.push('</div></div>');
    html = html.join('');
    $(html).appendTo(document.body);
    $('#wis-notice').on('close.modal.amui', onConfirm);
    // 弹出执行
    $('#wis-notice').modal();
};

var WIS_notice_img = function () {
    $('#wis-notice-img').remove();
    // 获取参数
    var config = arguments[0] ? arguments[0] : {};
    var msg = config.msg ? config.msg : '';
    var url = config.url ? config.url : '';
    var onConfirm = config.onConfirm ? config.onConfirm : function(){return false};

    var fullUrl = 'http://' + document.domain + '/Image/uploads/' + url;
    // 拼接弹窗HTML 并插入DOM
    var html = [];
    html.push('<div class="am-modal am-modal-alert am-modal-active" style="display: block" tabindex="10010" id="wis-notice-img">');
    html.push(  '<div class="am-modal-dialog">');
    html.push(      '<img class="am-modal-img" src=' + fullUrl +' />');
    html.push(      '<div class="am-modal-bd">' + msg + '</div>');
    html.push(      '<div class="am-ellipsis am-modal-footer">');
    html.push(          '<span class="am-modal-btn"></span>');
    html.push(      '</div>');
    html.push(  '</div>');
    html.push('</div>');
    html = html.join('');
    $(html).appendTo(document.body);
    $('#wis-notice-img').on('close.modal.amui', onConfirm);
    // 弹出执行
    $('#wis-notice-img').modal();
};

var WIS_aa = function () {
    //click.dimmer.modal.amui  confirm.modal.amui click.cancel.modal.amui cancel.modal.amui

    $('#wis-confirm').off('cancel.modal.amui').off('confirm.modal.amui').remove();
    // 获取参数
    var config = arguments[0] ? arguments[0] : {};
    var msg = config.msg ? config.msg : '';
    var type = config.type ? config.type : 1;
    var onConfirm = config.onConfirm ? config.onConfirm : function(){return false};
    var onCancel = config.onCancel ? config.onCancel : function(){return false};
    var closeViaDimmer = config.closeViaDimmer ? config.closeViaDimmer : false;
    var textConfirm = config.textConfirm ? config.textConfirm : '确定';
    var textCancel = config.textCancel ? config.textCancel : '取消';
    var styleConfirm= config.styleConfirm ? config.styleConfirm : "";
    var styleCancel = config.styleCancel ? config.styleCancel : "";
    var title = config.title ? config.title : '';
    var html = config.html ? config.html : '';
    // 拼接弹窗HTML 并插入DOM
    var dialogHtml = [];
    dialogHtml.push('<div class="am-modal am-modal-confirm am-modal-active" style="display: block" tabindex="10010" id="wis-confirm">');
    dialogHtml.push(html);
    /*dialogHtml.push('<div class="am-modal-dialog">');
    if (type === 1){
        dialogHtml.push('<div class="am-modal-hd"><span class="am-icon-btn am-warning am-icon-exclamation"></span></div>');
    } else if(type === 'close' ){
        dialogHtml.push('<div class="am-modal-hd wis-background-primary wis-text-white am-margin-bottom-sm " style="height: 49px; padding: 0px; line-height: 49px">'+title+'<a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a></div>');
    }else {
        dialogHtml.push('<div class="am-modal-hd"><span class="am-icon-btn am-success am-icon-check"></span></div>');
    }
    dialogHtml.push('<div class="am-modal-bd">' + msg + '</div>');
    dialogHtml.push('<div class="am-modal-footer">');
    dialogHtml.push('<span class="am-ellipsis am-modal-btn" ' + styleCancel +' data-am-modal-cancel>' + textCancel +'</span>');
    dialogHtml.push('<span class="am-ellipsis am-modal-btn" ' + styleConfirm +' data-am-modal-confirm>' + textConfirm +'</span>');
    dialogHtml.push('</div></div>');*/
    dialogHtml.push('</div>');
    dialogHtml = dialogHtml.join('');
    $(dialogHtml).appendTo(document.body);

    // 弹出执行
    $('#wis-confirm').modal({
        relatedTarget: this,
        onConfirm: onConfirm,
        onCancel: onCancel,
        closeViaDimmer: closeViaDimmer,
    });
}

//获取cookie
function get_cookie(Name) {
    var search = Name + "="//查询检索的值
    var returnvalue = "";//返回值
    if (document.cookie.length > 0) {
        sd = document.cookie.indexOf(search);
        if (sd!= -1) {
            sd += search.length;
            end = document.cookie.indexOf(";", sd);
            if (end == -1)
                end = document.cookie.length;
            //unescape() 函数可对通过 escape() 编码的字符串进行解码。
            returnvalue=unescape(document.cookie.substring(sd, end))
        }
    }
    return returnvalue;
}

/*
 get_cookie('credit_id')

 设置cookie 以及有效期
 var date=new Date();
 date.setTime(date.getTime()+30*60*1000);
 document.cookie="credit_id="+id+"; expires="+date.toGMTString();
 */

/**
 * 公共弹窗遮罩层(只适用VUE) 请求开始自动添加遮罩层 请求结束自动隐藏
 * 依赖 jquery axios amazeui.css
 * @author chenxinyue
 */
$(document).on('mousedown', "[data-action=loading]", function (ele){
    if ($('.wis-shade').length < 1){
        // 拼接弹窗HTML 并插入DOM
        var html = [];
        html.push('<div class="wis-shade"><i class="am-icon-spinner am-icon-lg am-icon-spin loading"></i></div>');
        var htmlEntity = $(html.join('')).appendTo(document.body);
        htmlEntity.css({
            opacity: 1,
            color: '#2F8CE5',
            width: '100%',
            height: '100%',
            position: 'fixed',
            top: 0,
            left: 0,
            display: 'none',
            'z-index': 10010
        });
        htmlEntity.find('i').css({
            position: 'fixed',
            margin: 'auto',
            left: 0,
            right: 0,
            top: 0,
            bottom: 0,
            width: '38px',
            height: '38px'
        })
    }
    var dom_shade = $('.wis-shade');

    // 请求拦截器 请求前显示遮罩层
    var requestInterceptor = axios.interceptors.request.use(function(config){
        dom_shade.fadeIn();
        axios.interceptors.request.eject(requestInterceptor);
        return config;
    }, function(error){
        axios.interceptors.request.eject(requestInterceptor);
        return Promise.reject(error);
    });
    // 响应拦截器 请求后隐藏遮罩层
    var responseInterceptor = axios.interceptors.response.use(function(response){
        dom_shade.fadeOut();
        axios.interceptors.response.eject(responseInterceptor);
        return response;
    }, function(error){
        axios.interceptors.response.eject(responseInterceptor);
        return Promise.reject(error);
    });

});

/**
 * 表单验证 通过验证返回true 失败返回false
 * @param form $(form)
 * @returns {boolean}
 * @author chenxinyue
 */
function WIS_validate(form) {
    var fields = form.find('input:not([data-action=not-validate]), select:not([data-action=not-validate])');
    var msg = '';
    fields.each(function (i,field) {
        $field = $(field)
        var label = $('label[for='+$field.attr('id')+']').text();
        if (label[0] === "*"){
            label = label.substr(1)
        }
        var value = $field.val();
        var pattern = new RegExp($field.data('pattern'));
        if (isNull(value)){
            if ($field.attr('type') === 'file'){
                if ($field.data('required') === true){
                    msg = '请选择文件：' + label;
                    return false;
                }else {
                    return true;
                }
            }else {
                msg = label + '未填写银行卡';
                return false;
            }
        }

        if (pattern.test(value) === false){
            msg = label + '格式不正确'
            return false;
        }
    });

    if (msg === ''){
        return true;
    }else {
        WIS_toast({msg: msg, time: 5000})
        return false;
    }
}

/**
 * 判断空
 * @param string
 * @returns {boolean}
 */
function isNull(string)
{
    return !string && string!==0 && typeof string!=="boolean"?true:false;
}



/**
 *  设置/获取缓存时间
 * @param string
 * @param get/set
 * @returns time
 * xia
 */
function wis_cache_time(time_form,dir_form){
    var now = Date.parse(new Date());
    if(dir_form == 'set'){
        var torage_time ={};
        torage_time[time_form] = now;
        window.localStorage.setItem('WIS_MRF.time',JSON.stringify(torage_time));
        return torage_time[time_form];
    }
    if(dir_form == 'get'){
        var torage_time = window.localStorage.getItem('WIS_MRF.time');
        var set_time = JSON.parse(torage_time);
        return set_time[time_form] ;
    }
    return false;
}

/**
 *  去除字符串中html标签
 * @str string
 * @return string
 * xia
 */
function delHtmlTag(str){
    return str.replace(/<[^>]+>/g,"");//去掉所有的html标记
}

// WIS_storage 插件
;(function (window, document, undefined) {
    var constructor = function (name) {
        this.name = 'WIS_MRF.' + name;
        this.obj = this.getStorage();
    };

    constructor.prototype = {
        getStorage: function () {
            var config = window.localStorage.getItem(this.name);
            if (this.isNull(config)){
                this.setStorage('');
                return '';
            }else {
                return JSON.parse(config);
            }
        },
        setStorage: function (value) {
            window.localStorage.setItem(this.name, JSON.stringify(this.obj || value));
        },
        getItem: function (key) {
            return this.isNull(this.obj[key]) ? null : this.obj[key]
        },
        set: function (value) {
            this.obj = value;
            this.setStorage();
        },
        setItem: function (key, value) {
            this.obj[key] = value;
            this.setStorage();
        },
        find: function (key) {
            return this.getItem(key) ? true : false;
        },
        findItem: function (value, obj) {
            return this.isNull(this.obj[obj]) ? false : this.inArray(value, this.obj[obj])
        },
        push: function (value) {
            if (!Array.isArray(this.obj)){
                this.obj = [];
            }
            this.obj.push(value);
            this.setStorage();
        },
        pushItem: function (key, value) {
            if (typeof this.obj !== 'object'){
                this.obj = {};
            }
            if (!Array.isArray(this.obj[key])){
                this.obj[key] = []
            }
            this.obj[key].push(value);
            this.setStorage();
        },
        inArray: function (needle, array, bool) {
            if(typeof needle === 'string' || typeof needle=== 'number'){
                var len= array.length;
                for(var i=0; i<len; i++){
                    if(needle === array[i]){
                        if(bool){
                            return i;
                        }
                        return true;
                    }
                }
            }
            return false;
        },
        isNull: function (string) {
            return !string && string!==0 && typeof string!=="boolean"?true:false;
        }
    };

    window.WIS_storage = function(name){
        return new constructor(name);
    };
})(window, document);

function getDate(datetime, separator){
    separator = separator || '-';
    datetime = new Date(datetime);
    year = datetime.getFullYear();
    month = datetime.getMonth() + 1;
    day = datetime.getDate();
    return year + separator + month + separator + day;
}

/**
 * 获取URL参数
 * @param name
 * @returns {*}
 */
function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if ( r !== null ){
        return decodeURI(r[2]);
    }else{
        return null;
    }
}

/**
 * 公共ajax
 * @param required_data ajax调用的接口地址和数据
 * @param success_data 回调函数
 * @author xzx
 */
var WIS_ajax = function(){
    //获取用户信息
    var obj=JSON.parse(window.localStorage.getItem('WIS_MRF.user'));
    var token=obj.token?obj.token:'';
    var user_id=obj.id?obj.id:'';


    // 获取参数
    var config = arguments[0] ? arguments[0] : {};
    var user_info ={token:token,user_id:user_id}
    var required_data = config.required_data ? config.required_data : {};
    var post_data =$.extend({},user_info,required_data)
    var success_data = config.success_data ? config.success_data : function(){return false};
        //调用ajax
    axios.post('/api/v1', api_data_sign(post_data, 'wis'))
            .then(function (response) {
                var data = response.data;
                if(response.data.code=='0005'){
                    WIS_confirm({
                        msg:'请先登录，才能查看',
                        onConfirm:function(e){
                            window.location.href='login.html';
                        },
                        onCancel:function(e){
                            history.back();
                        }
                    })
                }else{
                     success_data(data)
                }

            })
            .catch(function (error) {
                console.log(error);
            });
}

/**
 * 显示隐藏密码
 * @param e
 */
function hideShowPsw  (e) {
    var img = e.target.id;
    var id_type = img.split('_')[0];
    if($('#'+id_type).attr('type') == 'password'){
        $('#'+id_type).attr("type","text");
        $('#'+img).attr('src',"../img/i/show@2x.png");
    }else{
        $('#'+id_type).attr("type","password");
        $('#'+img).attr('src',"../img/i/hide@2x.png");
    }
}

/**
 * 没网络处理
 */
function no_network() {
    WIS_confirm({
        type: 1,
        msg: "网络连接中断",
        textConfirm: "刷新试试",
        onConfirm: function () {
            location.reload();
        }
    })
}