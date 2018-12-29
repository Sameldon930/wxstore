$(document).on("keyup", "[data-action^=chunk]", function (e) {
    var $this = $(this);
    var _this = this;
    var chunk_type = $this.data("action");
    var delimiter = [];
    switch (chunk_type){
        case "chunk-saving-card":
            delimiter = [4, 4, 4, 4, 3];
            break;
        case "chunk-credit-card":
            delimiter = [4, 4, 4, 4];
            break;
        case "chunk-id-code":
            delimiter = [6, 8, 4];
            break;
    }
    var key_code = e.keyCode;
    var value = $this.val();
    var selectionStart = this.selectionStart;
    var isCaretAtEnd = this.selectionStart === value.length;
    if (key_code === 8){
        if (isCaretAtEnd){
            $this.val($.trim(value))
        }else {
            $this.val(chunkValue(value, delimiter));
            setCaretPosition(this, selectionStart)
        }
    } else if (key_code === 13){
        _this.form.submit();
    } else if ( (key_code >= 96 && key_code <= 105) || (key_code >= 48 && key_code <= 57) || key_code === 88 ){
        if (isCaretAtEnd){
            value = removeAllSpace(value);
            $this.val(chunkValue(value, delimiter));
        }else {
            $this.val(chunkValue(value, delimiter));
            setCaretPosition(this, selectionStart)
        }
    }
});

function chunkValue(str, indexs){
    str = removeAllSpace(str);
    var right = str;
    var chunks = [];
    $.each(indexs, function (i, v) {
        var left = right.substr(0, v);
        if (right.length >= v ){
            right = right.substr(v);
            chunks.push(left);
        }else {
            chunks.push(right);
            return false;
        }
    });
    return chunks.join(' ');

}

// 取出所有空格
function removeAllSpace(str) {
    return str.replace(/\s+/g, "");
}

// 设置光标位置
function setCaretPosition(textDom, pos){
    if(textDom.setSelectionRange) {
        textDom.focus();
        textDom.setSelectionRange(pos, pos);
    }else if (textDom.createTextRange) {
        var range = textDom.createTextRange();
        range.collapse(true);
        range.moveEnd('character', pos);
        range.moveStart('character', pos);
        range.select();
    }
}