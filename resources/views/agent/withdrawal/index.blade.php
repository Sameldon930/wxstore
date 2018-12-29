@extends('layouts.agent')

@section('pageTitle')
    提现列表
@stop

@section('css')
    <style>
        .account {
            text-align: center;
            border-radius: 3px;
            background-color: #5cb85c;
            color: #fff;
            cursor: pointer;
        }
        .account-tube {
            font-size: 20px;
            padding-top: 6px;
            padding-bottom: 6px;
        }
        .account-balance {
            font-size: 30px;
            padding-bottom: 6px;
            font-weight: bold;
        }
    </style>
@stop

@section('content')
    <div class="box">
        <div class="box-body">
            <div class="">
                <p>账户余额：</p>
            </div>
            <div class="row">
                @foreach($user->user_accounts as $account)
                    <div class="col-xs-6 col-sm-4 col-lg-3">
                        <div data-toggle="modal" data-target="#modal-account-{{ $account->tube->name }}" class="account">
                            <div class="account-tube">{{ $account->tube->display }}</div>
                            <div class="account-balance">{{ $account->balance }}</div>
                        </div>
                    </div>

                    <div class="modal fade" id="modal-account-{{ $account->tube->name }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                    <h4 class="modal-title">提现申请</h4>
                                </div>
                                <form class="form-horizontal" method="POST" action="{{ route('agent.withdrawal.withdrawal') }}">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="tube_id" value="{{ $account->tube_id }}">
                                    <div class="modal-body">

                                        <div class="input-group box-body">
                                            <div class="input-group-btn">
                                                <label for="amount" class="pretty-btn">提现金额</label>
                                            </div>
                                            <input type="text" class="form-control" name="amount" data-balance="{{ removeComma($account->balance) }}"
                                                   value="{{removeComma(old('amount'))}}" required placeholder="可提现 {{ removeComma($account->balance) }} 元">
                                        </div>

                                        <div class="box-body text-right">
                                            <a href="javascript:;" onclick="withdrawalAll(this)">全部提现</a>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="pretty-btn">提交</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                @endforeach
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
                    <th>申请人</th>
                    <th>订单号</th>
                    <th>提现金额</th>
                    <th>实际金额</th>
                    <th>提现类型</th>
                    <th>提现状态</th>
                    <th>申请时间</th>
                    <th>操作</th>
                </tr>
                @foreach($data as $item)
                    <tr>
                        <td>{{$item->user->name}}（{{$item->user->mobile}}）</td>
                        <td>{{$item->order_no}}</td>
                        <td>{{$item->trade_amount}}</td>
                        <td>{{$item->real_amount}}</td>
                        <td>{{App\Withdrawal::TYPES[$item->type]}}</td>
                        <td>{{App\Withdrawal::STATUS[$item->status]}}</td>
                        <td>{{$item->created_at}}</td>
                        {{--<td>
                            <a href="{{route('agent.withdrawal.detail',['id'=>$item->id])}}" class="pretty-btn">查看</a>
                        </td>--}}
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
            $('#account_log').addClass('active');
            $('#withdrawal_index').addClass('active');
        });

        function withdrawalAll(that){
            $this = $(that);
            $form = $this.parents('form');
            $amount = $form.find("[name=amount]");
            $amount.val($amount.data('balance'))
        }
    </script>
@stop
