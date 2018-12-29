@extends('layouts.merchant')

@section('pageTitle')
    门店列表
@stop

@section('css')
    <style>

    </style>
@stop

@section('content')
    <div class="box">
        @if($user->isCheckedMerchant())
            <div class="box-body">
                <a class="pretty-btn" href="{{ route('merchant.store.create') }}">添加门店</a>
            </div>
        @endif

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
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
                @foreach($data as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->mobile }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>
                            <a class="pretty-btn" href="{{route('merchant.store.show', ['merchant' => $item->id])}}">详情</a>
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
            $('#member_store').addClass('active');
        });

    </script>
@stop