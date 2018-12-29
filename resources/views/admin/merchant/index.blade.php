@extends('layouts.admin')

@section('pageTitle')
    商户列表
@stop

@section('css')
    <style>

    </style>
@stop

@section('content')
    <div class="box">
        <div class="box-body">
            <button type="button" class="pretty-btn" data-toggle="modal" data-target="#modal-user-add">
                添加商户
            </button>

            <div class="modal fade" id="modal-user-add" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">添加商户</h4>
                        </div>
                        <form class="form-horizontal" method="POST" action="{{ route('admin.merchant.store') }}">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="modal-body">

                                <div class="input-group box-body">
                                    <div class="input-group-btn">
                                        <label for="name" class="pretty-btn">名称</label>
                                    </div>
                                    <input type="text" class="form-control" name="name"
                                           value="{{old('name')}}" required>
                                </div>

                                <div class="input-group box-body">
                                    <div class="input-group-btn">
                                        <label for="real_mobile" class="pretty-btn" >手机号</label>
                                    </div>
                                    <input type="text" class="form-control" name="real_mobile"
                                           value="{{old('real_mobile')}}" required >
                                </div>

                                <div class="input-group box-body">
                                    <div class="input-group-btn">
                                        <label for="a_mobile" class="pretty-btn">上级代理账号</label>
                                    </div>
                                    <input type="text" class="form-control" name="a_mobile"
                                           value="{{old('a_mobile')}}">
                                </div>

                                <div class="input-group box-body">
                                    <div class="input-group-btn">
                                        <label for="password" class="pretty-btn">密码</label>
                                    </div>
                                    <input type="password" class="form-control" name="password"
                                           value="{{old('password')}}"
                                           required>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="pretty-btn">创建</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <div class="box-body">
            @include('compoment.search_form')
        </div>
        <div class="box-body">
            <p>当前条件数量总计：{{ $data->total()}}</p>
            <table class="table table-bordered text-center table-hover table-responsive">
                <tbody>
                <tr>
                    <th>ID</th>
                    <th>名称（账号）</th>
                    <th>手机号</th>
                    <th>上级代理名称</th>
                    <th>审核状态</th>
                    <th>创建时间</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                <?php
                $aaaa=\App\MerchantInfo::STATUS;
                ?>
                @foreach($data as $item)
                    <tr>
                        <td>{{ $item->id}}</td>
                        <td>{{ $item->name }}（{{ $item->mobile }}）</td>
                        <td>{{$item->real_mobile??'未填写'}}</td>
                        <td>{{ optional($item->a_user)->name }}</td>
                        <td>@if(!empty($item->merchantInfo->status) )
                                {{$aaaa[$item->merchantInfo->status]}}
                            @else
                                未填写
                            @endif
                        </td>
                        <td>{{ $item->created_at??''}}</td>
                        <td>
                            <div class="switch" data-target="switch-status"
                                 data-url="{{route('admin.merchant.switch', ['merchant' => $item->id])}}">
                                <input type="checkbox" name="status" value="{{ $item->status }}"
                                       @if( $item->status == \App\User::STATUS_ENABLED) checked @endif/>
                            </div>
                        </td>
                        <td>
                            <a class="pretty-btn" href="{{route('admin.merchant.show', ['merchant' => $item->id])}}">详情</a>
                            <a class="pretty-btn" href="{{route('admin.merchant.edit', ['merchant' => $item->id])}}">修改</a>

                            {{--@if($item->isUncheckedMerchant())--}}
                                {{--<a class="pretty-btn pretty-btn-danger" href="{{route('admin.merchant_user_info.create', ['merchant' => $item->id])}}">审核</a>--}}
                            {{--@endif--}}
                            {{--<form action="{{route('admin.merchant.destroy', ['merchant' => $item->id])}}" method="POST" class="inline-block">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                <button class="pretty-btn pretty-btn-danger">删除</button>
                            </form>--}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-center">{{ links_custom($data, $search_items) }}</div>
@stop

@section('js')
    @yield('date_search')
    <script>

        $(document).ready(function () {
            $('#merchant').addClass('active');
            $('#merchant_merchant_index').addClass('active');
        });

    </script>
@stop