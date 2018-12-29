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
            @include('compoment.search_form')
        </div>
        <div class="box-body">
            <p>当前条件数量总计：{{ $data->total()}}</p>
            <table class="table table-bordered text-center table-hover table-responsive">
                <tbody>
                <tr>
                    <th>ID</th>
                    <th>门店</th>
                    <th>门店账号</th>
                    <th>商户名称（账号）</th>
                    <th>上级代理名称</th>
                    <th>创建时间</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                @foreach($data as $item)
                    <tr>
                        <td>{{ $item->id}}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->mobile }}</td>
                        <td>{{ $item->payable_user->name }}（{{ $item->payable_user->mobile }}）</td>
                        <td>{{ optional($item->a_user)->name }}</td>
                        <td>{{ $item->created_at}}</td>
                        <td>
                            <div class="switch" data-target="switch-status"
                                 data-url="{{route('admin.store.switch', ['store' => $item->id])}}">
                                <input type="checkbox" name="status" value="{{ $item->status }}"
                                       @if( $item->status == \App\User::STATUS_ENABLED ) checked @endif/>
                            </div>
                        </td>
                        <td>
                            <a class="pretty-btn" href="{{route('admin.store.show', ['store' => $item->id])}}">详情</a>
                            <a class="pretty-btn" href="{{route('admin.store.edit', ['store' => $item->id])}}">修改</a>

                            {{--<form action="{{route('admin.store.destroy', ['store' => $item->id])}}" method="POST" class="inline-block">
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
            $('#merchant_store_index').addClass('active');
        });

    </script>
@stop