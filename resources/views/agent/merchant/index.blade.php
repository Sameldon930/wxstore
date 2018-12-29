
@extends('layouts.agent')

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
            @include('compoment.search_form')
        </div>
        <div class="box-body">
            <p>当前条件数量总计：{{ $data->total()}}</p>
            <table class="table table-bordered text-center table-hover table-responsive">
                <tbody>
                <tr>
                    <th>姓名</th>
                    <th>账号</th>
                    <th>审核状态</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
                @foreach($data as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->mobile }}</td>
                        <td>@if($item->isCheckedMerchant())<span>商户已审核</span>@else<span class="text-danger">商户未审核</span>@endif</td>
                        <td>{{ $item->created_at}}</td>
                        <td>
                            <a class="pretty-btn" href="{{route('agent.merchant.show', ['merchant' => $item->id])}}">详情</a>
                            {{--<a class="pretty-btn" href="{{route('agent.merchant.edit', ['merchant' => $item->id])}}">修改</a>--}}
                            {{--<form action="{{route('agent.merchant.destroy', ['merchant' => $item->id])}}" method="POST" class="inline-block">
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
            $('#member').addClass('active');
            $('#member_merchant').addClass('active');
        });

    </script>
@stop