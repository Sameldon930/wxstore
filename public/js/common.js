Array.prototype.range = function ( start,end ){
    var _self = this;
    var length = end - start +1;
    var step = start - 1;
    return Array.apply(null,{length:length}).map(function (v,i){step++;return step;});
};

/**
 * 获取mousewheel 事件实例 （mousewheel不兼容导致）
 * 例子：mousewheel(ele, "mousewheel", function(event)
 * @returns {Function}
 */
var addMousewheelEvent = function (){
    var _eventCompat = function(event) {
        var type = event.type;
        if (type == 'DOMMouseScroll' || type == 'mousewheel') {
            event.delta = (event.wheelDelta) ? event.wheelDelta / 120 : -(event.detail || 0) / 3;
        }
        if (event.srcElement && !event.target) {
            event.target = event.srcElement;
        }
        if (!event.preventDefault && event.returnValue !== undefined) {
            event.preventDefault = function() {
                event.returnValue = false;
            };
        }
        return event;
    };
    if (window.addEventListener) {
        return function(el, type, fn, capture) {
            if (type === "mousewheel" && document.mozHidden !== undefined) {
                type = "DOMMouseScroll";
            }
            el.addEventListener(type, function(event) {
                fn.call(this, _eventCompat(event));
            }, capture || false);
        }
    } else if (window.attachEvent) {
        return function(el, type, fn, capture) {
            el.attachEvent("on" + type, function(event) {
                event = event || window.event;
                fn.call(el, _eventCompat(event));
            });
        }
    }
    return function() {};
};

/**
 * 获取查询字符串
 * 例子 url: localhost?id=2 => getQueryString("id") -> 2
 * @param name
 * @returns {string}
 */
function getQueryString(name)
{
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if(r!=null)return  unescape(r[2]); return null;
}

//隐藏未选择上传时的占位
function hideFileImg() {
    var files = $("[type='file']")
    for(var i=0; i < files.length; i++ ){
        var file_id = files[i].id;
        if($('#'+file_id).attr('accept') == 'image/*'){
            if($('#'+file_id+'-preview').attr('src').length == 0){
                $('#'+file_id+'-preview').parent('.img-size').hide();
            };
        }

    }
}

$('[data-target="file-button"]').on('click', function (e) {
    var id = $(this).attr('id');
    var file_id = id.split("-")[0];
    $('#'+file_id).click();
});

function loading(){
    $("#wis-loading").show();
}
function stopLoading(){
    $("#wis-loading").hide();
}

// bootstrap-switch开关事件
$('[data-target="switch-status"]').on('switch-change', function (e, data) {
    var url = $(this).data("url");
    var status = data.value ? 1 : 2;
    $.ajax({
        url: url,
        data: { status: status },
        success: function (data) {
            if (Number(data.code) === 200){
                toastr.success(data.msg);
            }else {
                toastr.error(data.msg);
            }

        },
        error: function (data) {
            toastr.error(data.msg);
        },
    });
});