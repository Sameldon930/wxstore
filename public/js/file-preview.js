/**
 * H5图片预览
 */
$(document).on("change", "[accept='image/*']", function (e){
    var $this = $(this);
    var files = e.target.files;
    for (var i = 0, f; f = files[i]; i++) {
        if (!f.type.match('image.*')) {
            continue;
        }
        var reader = new FileReader();
        reader.onload = (function(theFile) {
            return function(e) {
                var id = $this.prop("id") + "-preview";
                var $preview = $("#" + id);
                if ($preview.length){
                    $preview.attr("src", e.target.result);
                    //图片放大必须设置 data-magnify属性
                    if($preview.attr('data-magnify') ===  'gallery'){
                        $preview.attr("data-src", e.target.result);
                    }
                }
            };
        })(f);
        reader.readAsDataURL(f);
    }
});
