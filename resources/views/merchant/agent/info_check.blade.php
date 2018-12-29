@extends('layouts.merchant')

@section('pageTitle')
    代理信息审核
@stop

@section('css')
    <link rel="stylesheet" href="/css/jquery.magnify.min.css">
    <style>
        .img-size {
        }

        .img-size > img {
            max-width: 100%;
            max-height: 300px;
        }

        .form-label {
            width: 154px;
            margin-right: 2em;
            color: rgb(53, 53, 53);
            font-weight: 400;
        }

        .form-input {
            margin: 4px 0;
            width: 30em;
            padding: 6px 6px 6px 12px;
            border: 1px solid #e7e7eb;
            outline: 0;
            border-radius: 2px;
            color: #353535;
        }

        .file-button {
            color: white;
            background: #00C0ED;
            border: 0px;
            padding: 6px 15px;
            cursor: pointer;
        }

        h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
            font-family: "Microsoft YaHei", Arial, sans-serif;
        }
    </style>
@stop

@section('content')
    @include('compoment.form_tip')
    <?php
    $hasInfo = $data ? true : false;
    // $is_checked = $data && (\App\UserInfoCheck::isChecked($data->status));
    ?>
    <?php
    Form::macro('_text', function ($field, $value = false, $label = false) use ($data) {
        $label = $label ?: \App\UserAgentInfo::COLUMNS_MAP[$field] . '：';
        $value = $value ?: old_or_new_field($field, $data);
        return
            '<div class="col-lg-7 col-xs-12 form-group">' .
            Form::label($field, $label, ['class' => 'form-label']) .
            Form::text($field, $value, ['class' => 'form-input']) .
            '</div>';
    });

    Form::macro('_file', function ($field) use ($data) {
        return
            '<div class="col-lg-7 col-xs-12 form-group">' .
            Form::label($field, \App\UserAgentInfo::COLUMNS_MAP[$field] . '：', ['class' => 'form-label']) .
            Form::file($field, [
                'class' => 'hide',
                'accept' => 'image/*',
                /*'required' => !$data && !old_or_new_img($field, $data, false),*/
            ]) .
            '<span class="file-button inline-block" data-target="file-button" id="' . $field . '-button">选择图片</span>' .
            '<div class="img-size h3"><img id="' . $field . '-preview"
                    src="' . old_or_new_img($field, $data, true) . '"  data-src="' . old_or_new_img($field, $data, true) . '"  data-magnify="gallery"></div>' .
            Form::hidden('old_' . $field, old_or_new_img($field, $data, false)) .
            '</div>';

    });

    ?>
    <div class="box">
        <h2><b>代理信息登记</b></h2>
        <div>
            {{
                Form::open([
                    'class'=>'box-body',
                    'data-action' => 'form-tip',
                    'url' => route('agent.agent.info_store')
                ])
            }}

            <div class="input-group margin-bottom-sm">
                <label for="type">账号类型：</label>
                @foreach(\App\UserAgentInfo::TYPES as $value => $label)
                    <label class="pretty-checkbox padding-left-xs padding-top-xs">
                        <input type="radio" name="type" value="{{ $value }}"
                            @if(old_or_new_field('type', $data) == $value) checked @endif
                        >
                        <span></span> {{ $label }}
                    </label>
                @endforeach
            </div>

            <div class="row group-company" style="display: none">
                <h4 class="col-xs-12"><b>公司信息</b></h4>

                <div class="col-xs-12">
                    <div class="row">
                        {!! Form::_text('company_name') !!}
                        {!! Form::_text('company_account') !!}

                        {!! Form::_file('company_business_licence') !!}
                    </div>
                </div>
            </div>

            <div class="row group-company" style="display: none">
                <h4 class="col-xs-12"><b>法人信息</b></h4>

                <div class="col-xs-12">
                    <div class="row">
                        {!! Form::_text('legal_name') !!}
                        {!! Form::_text('legal_idcard') !!}

                        {!! Form::_file('legal_idcard_front') !!}
                        {!! Form::_file('legal_idcard_back') !!}
                    </div>
                </div>

            </div>

            <div class="row group-company" style="display: none">
                <h4 class="col-xs-12"><b>负责人信息</b></h4>

                <div class="col-xs-12">
                    <div class="row">
                        {!! Form::_text('manager_name') !!}
                        {!! Form::_text('manager_mobile') !!}
                    </div>
                </div>
            </div>


            <div class="row group-company group-personal" style="display: none">
                <h4 class="col-xs-12"><b>结算人信息</b></h4>

                <div class="col-xs-12">
                    <div class="row">
                        {!! Form::_text('cleaner_name') !!}
                        {!! Form::_text('cleaner_mobile') !!}
                        {!! Form::_text('cleaner_deposit') !!}
                        {!! Form::_text('cleaner_idcard') !!}

                        {!! Form::_file('cleaner_idcard_front') !!}
                        {!! Form::_file('cleaner_idcard_back') !!}
                    </div>
                </div>
            </div>


            <div class="text-center group-submit" style="display: none;">
                @if($hasInfo)
                    <button type="submit" name="update" value="1" class="pretty-btn">更新</button>
                @else
                    <button type="submit" name="store" value="1" class="pretty-btn">提交</button>
                @endif

            </div>


            {{ Form::close() }}

        </div>
    </div>



@stop

@section('js')
    <script src="/js/dist/lrz.bundle.js"></script>
    <script src="/js/jquery.magnify.min.js"></script>
    <script src="/js/form-check.js"></script>
    @yield('form_tip')
    <script>

        $(document).ready(function () {
            $('#agent').addClass('active');
            $('#agent_agent_index').addClass('active');

            var type = Number('{{ old_or_new_field('type', $data) }}');
            showFormByType(type);
        });

        $("[type='file']").on("change", function (e) {
            var field = $(this).attr("name");
            var $old = $("[name=old_" + field + "]");
            $old.show();
            var file = this.files[0];
            $('#' + this.id + '-preview').parent('.img-size').show()
            lrz(file).then(function (rst) {
                function readBlobAsDataURL(blob, callback) {
                    var a = new FileReader();
                    a.onload = function (e) {
                        callback(e.target.result);
                    };
                    a.readAsDataURL(blob);

                }

                readBlobAsDataURL(rst.file, function (url) {
                    $old.val(url)
                    $preview.show();
                });
            });
        })

        $("[name=type]").on("change", function () {
            $this = $(this);
            var type = Number($this.val());
            showFormByType(type);
        })

        function showFormByType(type){
            if (type === 1) {
                $(".group-submit").show();
                $(".group-company").hide();
                $(".group-personal").show();
            } else if (type === 2) {
                $(".group-submit").show();
                $(".group-personal").hide();
                $("label[for=company_account]").text('公司对公账户（选填）：');
                $(".group-company").show();
            } else if (type === 3) {
                $(".group-submit").show();
                $(".group-personal").hide();
                $("label[for=company_account]").text('公司对公账户：');
                $(".group-company").show();
            }
        }
    </script>
@stop