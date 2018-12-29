@extends('layouts.admin')

@section('pageTitle')
    代理审核信息详情
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

        .form-text {
            margin: 4px 0;
            width: 30em;
            padding: 6px 6px 6px 12px;
            outline: 0;
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
    <?php
    Form::macro('_text', function ($field, $value = false, $label = false) use ($data) {
        $label = $label ?: \App\UserAgentInfo::COLUMNS_MAP[$field] . '：';
        $value = $data->$field;
        return
            '<div class="col-lg-7 col-xs-12 form-group">' .
            Form::label($field, $label, ['class' => 'form-label']) .
            // Form::label($field, $value, ['class' => 'form-input']) .
            '<label class="form-text">' . $value .'</label>' .
            '</div>';
    });

    Form::macro('_file', function ($field) use ($data) {
        return
            '<div class="col-lg-7 col-xs-12 form-group">' .
            Form::label($field, \App\UserAgentInfo::COLUMNS_MAP[$field] . '：', ['class' => 'form-label']) .
            '<div class="img-size h3"><img id="' . $field . '-preview"
                    src="' . old_or_new_img($field, $data, true) . '"  data-src="' . old_or_new_img($field, $data, true) . '"  data-magnify="gallery"></div>' .
            '</div>';

    });

    ?>
    <div class="box">
        <h2><b>代理信息</b></h2>

        <div class="row">
            <div class="col-sm-6 col-lg-4 col-xs-12">
                @if($data->status & \App\UserAgentInfo::CHECKING)
                    <div class="bs-callout bs-callout-info h4 text-info">审核中</div>
                @elseif($data->status & \App\UserAgentInfo::CHECKED)
                    <div class="bs-callout bs-callout-info h4 text-info">审核通过</div>
                @elseif($data->status & \App\UserAgentInfo::REJECTED)
                    <div class="bs-callout bs-callout-danger h4 text-danger">已拒绝：{{ $data->reject_reason }}</div>
                @endif
            </div>
        </div>





        <div>

            <div class="input-group margin-bottom-sm">
                <label for="type">账号类型：</label>
                <label for="">{{ \App\UserAgentInfo::TYPES[$data->type] }}</label>
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
        </div>
    </div>

    @if($data->isUnchecked())

    <div class="box">
        <div class="row">
            <div class="col-sm-6 col-lg-4 col-xs-12">
                <form class="form-horizontal" method="POST" action="{{ route('admin.agent_user_info.pass', ['agent' => $data->user->id]) }}">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">

                    <button type="submit" class="pretty-btn">通过审核</button>
                </form>
            </div>
        </div>
    </div>

    <div class="box">
        <div class="row">
            <div class="col-sm-6 col-lg-4 col-xs-12">
                <form class="form-horizontal" method="POST" action="{{ route('admin.agent_user_info.reject', ['agent' => $data->id]) }}">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">

                    <div class="input-group margin-bottom-sm">
                        <div class="input-group-btn">
                            <label for="reject_reason" class="pretty-btn">拒绝理由</label>
                        </div>
                        <input class="form-control" name="reject_reason" value="{{old('reject_reason')}}" required>
                    </div>

                    <button type="submit" class="pretty-btn pretty-btn-danger">拒绝审核</button>
                </form>
            </div>
        </div>
    </div>

    @endif


@stop

@section('js')
    <script src="/js/jquery.magnify.min.js"></script>
    <script>

        $(document).ready(function () {
            $('#agent').addClass('active');
            $('#agent_agent_user_info_index').addClass('active');

            var type = Number('{{ $data->type }}');
            showFormByType(type);


        });

        function showFormByType(type){
            if (type === 1) {
                $(".group-personal").show();
            } else if (type === 2) {
                $(".group-company").show();
            } else if (type === 3) {
                $(".group-company").show();
            }
        }
    </script>
@stop