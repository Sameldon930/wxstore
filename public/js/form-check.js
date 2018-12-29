$("[data-action='form-check']")
    .popover({content: '请填写此字段', trigger: "focus", placement: "top"})
    .on("invalid", function () {
        $(this).addClass("invalid")
    })
    .on("input change", function () {
        $(this).removeClass("invalid")
    })
;