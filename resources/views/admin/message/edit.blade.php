@extends('layouts.admin')

@section('pageTitle')
    消息编辑
@stop

@section('css')
    <link href="{{ asset('/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <style>
        .file-preview-wrap > img {
            height: 150px;
            width: 410px;
            text-align: center;
            line-height: 130px;
            font-size: 20px;
            color: #666666;
        }
        .has-error .input-group-addon {
            color: #3c8dbc;
        }
    </style>
@stop

@section('content')
    <div class="box">

        <div class="padding-top-sm">
            {{ Form::open(['method'=>'post',
            'route' => ['admin.message.'.($data?'update':'store'),'update'=>($data?$data->id:$data)],'enctype'=>'multipart/form-data']) }}
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" name="thumbnail_temp" value="{{ old('thumbnail_temp') }}">
            <div class=" form-group">
                <span class="font-red">*</span>
                {{Form::label('title','消息标题')}}
                {{Form::text('title',old_or_new_field('title',$data),['class'=>'form-input'])}}
            </div>
            <div class=" form-group">
                <span class="font-red">*</span>
                {{Form::label('text','消息简介')}}
                {{Form::text('text',old_or_new_field('text',$data),['class'=>'form-input'])}}
            </div>

            <div class="form-group has-error has-feedback">
                <label  style="color: black;" class="control-label" for="now"><span class="font-red">*</span>发布日期：</label>
                <div style="width:400px;border-color: #ccc;" class="input-group">
                    <div style="border-color: #ccc;" class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input style="border-color: #ccc;"  class="form-control date validate" name="now" id="now" required
                           value="{{old_or_new_field('now',$data)}}">
                    {{--<span class="glyphicon glyphicon-remove form-control-feedback"></span>--}}
                </div>
            </div>
            <div class=" form-group">
                {{Form::label('top','是否置顶:')}}
                {{Form::radio('top',\App\Message::YES,true)}}
                {{Form::label('top','置顶')}}
                {{Form::radio('top',\App\Message::NO)}}
                {{Form::label('top','不置顶')}}
            </div>
            {{ csrf_field() }}
            <div class="form-group">
                <div id="editor" type="text/plain" style="width:1024px;height:500px;"></div>
            </div>
            <br>
            <button class="pretty-btn">@if($data)修改@else 添加 @endif</button>
            {{ Form::close() }}
        </div>

    </div>

@stop

@section('js')
    <script type="text/javascript" charset="utf-8" src="../../../../js/uedit/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="../../../../../js/uedit/ueditor.all.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="../../../../js/uedit/lang/zh-cn/zh-cn.js"></script>
    <script type="text/javascript" charset="utf-8" src="../../../../js/bootstrap-datetimepicker.min.js"></script>

    <script>

        $(document).ready(function () {
            $('#operation').addClass('active');
            $('#operation_message_index').addClass('active');
        });
        var ue = UE.getEditor('editor', {
            textarea: 'content',
            autoHeightEnabled: false,
        });

        @if(old_or_new_field('content',$data)!=null)
        ue.addListener("ready", function () {

            //editor准备好之后才可以使用
            ue.setContent('{!!old_or_new_field('content',$data)!!}');
        });
        @endif
        $(function (){
            $('.date').datetimepicker({
                autoclose: true,
                clearBtn: true,
                todayBtn: true,
                language: 'zh-CN',
                format: "yyyy-mm-dd hh:ii:ss",
            }).on("show", function (ev) {
                window.currentDatePicker = ev.target;
            });
        })
        $("#content-dpi").on('change', function () {
            width = $(this).val();
            $("#edui1").css('width', width);
            $("#edui1_iframeholder").css('width', width);
        })
        $(document).on("change", ".file-preview", function (e) {
            var files = e.target.files;
            for (var i = 0, f; f = files[i]; i++) {
                if (!f.type.match('image.*')) {
                    continue;
                }
                var reader = new FileReader();
                reader.onload = (function (theFile) {
                    return function (e) {
                        document.getElementById('file-preview').src = e.target.result;
                    };
                })(f);
                reader.readAsDataURL(f);
            }
        });
        $(document).on("change", ".file-preview-second", function (e) {
            var files = e.target.files;
            for (var i = 0, f; f = files[i]; i++) {
                if (!f.type.match('image.*')) {
                    continue;
                }
                var reader = new FileReader();
                reader.onload = (function (theFile) {
                    return function (e) {
                        document.getElementById('file-preview-second').src = e.target.result;
                    };
                })(f);
                reader.readAsDataURL(f);
            }
        });
    </script>
@stop