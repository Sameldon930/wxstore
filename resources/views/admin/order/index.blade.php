@extends('layouts.admin')

@section('pageTitle')
    订单列表
@stop

@section('css')
    <style>

    </style>
@stop

@section('content')
    <div class="box">
        @if(env('APP_DEBUG'))
            <div class="box-body">
                <form action="{{ route('admin.order.simulate_create') }}" method="POST" class="form-inline">
                    {{ csrf_field() }}
                    <div class="input-group padding-right">
                        <label for="">账号：</label>
                        <input class="form-control" name="mobile" value="{{ old('mobile') }}" required>
                    </div>
                    <div class="input-group padding-right">
                        <label for="">金额：</label>
                        <input class="form-control" name="amount" required>
                    </div>
                    <div class="input-group padding-right">
                        <label for="">通道：</label>
                        <select name="channel" id="" class="form-control" required>
                            <option value="WECHAT_JS" @if(old('channel') == 'WECHANT_JS') selected @endif>微信</option>
                            <option value="ALI_JS" @if(old('channel') == 'ALI_JS') selected @endif>支付宝</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <button class="pretty-btn">模拟下单</button>
                    </div>
                </form>


            </div>

            <div class="box-body  margin-bottom-lg">
                <a href="{{ route('admin.order.simulate_settle_log') }}" class="pretty-btn">模拟生成结算单</a>
            </div>
        @endif

        <div class="box-body">
            @include('compoment.search_form',['export'=>'list_export'])
        </div>
        <div class="box-body">
            <p>当前条件数量总计：{{ $data->total()}}</p>
            <p>实际金额总计：{{ $money}}</p>

            <table class="table table-bordered text-center table-hover table-responsive">
                <tbody>

                @foreach ($table as $key=>$item)
                    <tr>
                        @if($key === 0)
                            @foreach($item as $th)
                                <th>{{$th}}</th>
                            @endforeach
                            <th>操作</th>
                        @else
                            @foreach($item as $k =>$td)
                                <td>{{$td}}</td>
                            @endforeach
                                <td>
                                @if(env('APP_DEBUG') && !$item[8])
                                    <a href="{{ route('admin.order.simulate_pay', ['id' => $item[0]]) }}" class="pretty-btn">模拟支付</a>
                                @endif
                                </td>
                        @endif
                    </tr>
                @endforeach

                {{--<tr>
                    @foreach($th as $v)
                        <th>{{$v}}</th>
                        @endforeach
                </tr>
                @foreach($data as $item)
                    <tr>
                        @foreach($item as $v)
                            <td>{{$v}}</td>
                            @endforeach
                        --}}{{--<td>
                            @if(env('APP_DEBUG') && !$item->isPaid())
                                <a href="{{ route('admin.order.simulate_pay', ['id' => $item->id]) }}" class="pretty-btn">模拟支付</a>
                            @endif
                        </td>--}}{{--
                    </tr>
                @endforeach--}}
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
            $('#transaction').addClass('active');
            $('#transaction_order_index').addClass('active');

            $("[name=mobile]").focus()
        });

    </script>
@stop
