<style>
    .form-error {
        border: 1px solid red;
    }

    .form-error-label {
        color: red;
        display: block;
    }
</style>
@section('form_tip')
    <script>
        $(function () {
            var $forms = $("[data-action='form-tip']");
            $forms.find(".form-group").each(function (index, ele) {
                $(ele).append("<label class='form-error-label'></label>")
            })

            /* 记录出错的表单 */
            <?php $form_errors = session('form_errors'); ?>
            @if(count($form_errors))
            @foreach($form_errors->keys() as $key)
            $("[name=" + '{{$key}}' + "]")
                .addClass("form-error")
                .siblings(".form-error-label")
                .text('{{array_first($form_errors->get($key))}}')
            ;
            @endforeach
            @endif
        });

        $("[data-action='form-tip'] input").on("input change", function () {
            $(this)
                .removeClass("form-error")
                .siblings(".form-error-label")
                .text("")
                .fadeOut()
        })
    </script>
@endsection
