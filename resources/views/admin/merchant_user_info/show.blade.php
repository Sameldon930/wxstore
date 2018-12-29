@extends('layouts.admin')

@section('pageTitle')
    信息审核列表
@stop

@section('css')
    <style>

    </style>
@stop

@section('content')
    <div class="box shadow">
        <div class="box-body">
            <table class="table table-bordered table-hover dataTable">
                <tbody>
                <tr>
                    <td>商户名</td>
                    <td>{{$data->company_name ?? '暂无数据'}}</td>
                </tr>
                <tr>
                    <td>身份证号码</td>
                    <td>{{$data->identity_num ?? '暂无数据'}}</td>
                </tr>
                <tr>
                    <td>银行卡号</td>
                    <td>{{chunk_string($back->account_no, [4,4,4,4,3])}}</td>
                </tr>
                {{--<tr>--}}
                    {{--<td>开户银行</td>--}}
                    {{--<td>{{$back->opening_account}}</td>--}}
                {{--</tr>--}}
                <tr>
                    <td>开户银行省市区</td>
                    <td>{{$back->opening_account}}</td>
                </tr>
                <tr>
                    <td>申请时间</td>
                    <td>{{$data->created_at}}</td>
                </tr>
                <tr>
                    <td>营业注册号</td>
                    <td>{{$data->registration_number}}</td>
                </tr>
                <tr>
                    <td>法人姓名</td>
                    <td>{{$data->business_person}}</td>
                </tr>
                <tr>
                    <td>商户地址</td>
                    <td>{{$data->merchant_address}}</td>
                </tr>
                <tr>
                    <td>注册者姓名</td>
                    <td>{{$data->registrantname}}</td>
                </tr>
                <tr>
                    <td>手机号</td>
                    <td>{{$data->mobile}}</td>
                </tr>
                <tr>
                    <td>邮箱</td>
                    <td>{{$data->email}}</td>
                </tr>
                <tr>
                    <td colspan="2">身份认证图</td>
                </tr>
                    <tr>
                        <td>
                            <a class="example-image-link revolve"
                               href="{{asset('storage/').$data->identity_front ?? ''}}"
                               data-magnify="gallary" data-caption="身份证正面">
                                <img rel="lightbox" class="example-image"
                                     src="{{asset('storage/').$data->identity_front ?? ''}}"
                                     height="150px" width="210px"/>
                            </a>
                            {{--<input type="file" name="img1"/>编辑该图--}}
                        </td>
                        <td>
                            <a class="example-image-link revolve"
                               href="{{asset('storage/').$data->identity_contrary ?? ''}}"
                               data-magnify="gallary" data-caption="身份证反面">

                                <img src="{{asset('storage/').$data->identity_contrary ?? ''}}" height="150px" width="210px"/>
                            </a>
                            {{--<input type="file" name="img2"/>编辑该图--}}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">营业许可证</td>
                    </tr>
                    <tr>
                        <td>
                            <a class="example-image-link revolve"
                               href="{{asset('storage/').$data->merchant_license ?? ''}}"
                               data-magnify="gallary" data-caption="商户执照">
                                <img rel="lightbox" class="example-image"
                                     src="{{asset('storage/').$data->merchant_license ?? ''}}"
                                     height="150px" width="210px"/>
                            </a>
                            {{--<input type="file" name="img1"/>编辑该图--}}
                        </td>
                        <td>
                            <a class="example-image-link revolve"
                               href="{{asset('storage/').$data->restaurant_license ?? ''}}"
                               data-magnify="gallary" data-caption="餐饮许可证">

                                <img src="{{asset('storage/').$data->restaurant_license ?? ''}}" height="150px" width="210px"/>
                            </a>
                            {{--<input type="file" name="img2"/>编辑该图--}}
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">门店招牌</td>
                    </tr>
                    <tr>
                    @foreach($data->contract_tenancy as $item)
                        <td>
                            <a class="example-image-link revolve"
                               href="{{asset('storage/').$item ?? ''}}"
                               data-magnify="gallary" data-caption="商户执照">
                                <img rel="lightbox" class="example-image"
                                     src="{{asset('storage/').$item ?? ''}}"
                                     height="150px" width="210px"/>
                            </a>
                            {{--<input type="file" name="img1"/>编辑该图--}}
                        </td>
                    @endforeach
                    </tr>
                    <tr>
                        <td colspan="2">门店内景</td>
                    </tr>
                    <tr>
                        @foreach($data->interior_picture as $item)
                            <td>
                                <a class="example-image-link revolve"
                                   href="{{asset('storage/').$item ?? ''}}"
                                   data-magnify="gallary" data-caption="商户执照">
                                    <img rel="lightbox" class="example-image"
                                         src="{{asset('storage/').$item ?? ''}}"
                                         height="150px" width="210px"/>
                                </a>
                                {{--<input type="file" name="img1"/>编辑该图--}}
                            {{--</td>--}}
                        @endforeach

                    <tr class="col-lg-6 col-sm-8  col-xs-12">
                        <td>
                        {{ Form::open(['method'=>'put','route' => ['admin.merchant_user_info.adopt','info_check'=>$data->id],'id'=>'success_form']) }}
                        <button type="button" class="btn pretty-btn  " onclick="success_audit()">审核通过</button>
                        {{Form::text('status',\App\MerchantInfo::SUCCESS_AUDIT,['class'=>'form-input hide'])}}
                        {{ Form::close() }}
                        </td>
                    </tr>
                </tr>
                <tr class="col-lg-4 col-sm-6 col-xs-12">
                    {{ Form::open(['method'=>'put','route' => ['admin.merchant_user_info.adopt','info_check'=>$data->id],'id'=>'fail_form']) }}
                    {{Form::text('status',\App\MerchantInfo::NOT_AUDIT,['class'=>'form-input hide'])}}
                    <td class="form-group " style="margin-top: 10px">
                        {{Form::label('feedback','不通过原因:')}}
                        {{Form::text('feedback','',['class'=>'form-input','id'=>'feedback'])}}
                    </td>
                </tr>
                <tr role="row">
                    <td>
                        <button type="button" class="pretty-btn pretty-btn-danger" style="margin-top: 10px"
                                onclick="fail_audit()">审核不通过
                        </button>
                        {{ Form::close() }}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@stop
@section('js')
    <script src="/js/swiper-4.3.3.min.js"></script>
    <script>

        $(document).ready(function () {
            $('#merchant').addClass('active');
            $('#merchant_info_check_index').addClass('active');

            var mySwiper = new Swiper('.swiper-container', {
                loop: false,
                lazy: true,
                mousewheel: true,

                // 如果需要滚动条
                scrollbar: {
                    el: '.swiper-scrollbar',
                },

                on: {
                    tap: function (e) {
                        console.log(e)
                    },
                },
            })
        });

        function success_audit() {
            if (confirm('确认通过商户认证吗?')) {
                $('#success_form').submit();
            }

        }

        function fail_audit() {
            if ($('#feedback').val() == '') {
                alert('请输出拒绝原因');

            } else {
                if (confirm("你确定此拒绝操作吗？")) {
                    $('#fail_form').submit();
                }
            }
        }

    </script>
@stop