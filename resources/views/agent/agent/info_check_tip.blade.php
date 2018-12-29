@extends('layouts.agent')

@section('pageTitle')
    前往审核
@stop

@section('css')
    <style>

    </style>
@stop

@section('content')
    <div class="box">
        <div class="box-body">
            <div>
                <div class="text-danger h3 margin-bottom">
                    必须通过代理审核才能添加子商户和子代理。
                </div>

                @if($userAgentInfo)
                    @if($userAgentInfo->status === \App\UserAgentInfo::CHECKING)
                        <div class="text-info h4">
                            资料审核中，请耐心等待。
                        </div>
                        <a href="{{ route('agent.agent.info_check') }}" class="pretty-btn margin-top-xs">
                            修改信息
                        </a>
                    @endif

                    @if($userAgentInfo->status === \App\UserAgentInfo::REJECTED)
                        <div class="text-danger h4">
                            <div>审核未通过：{{ $userAgentInfo->reject_reason }}</div>
                        </div>
                        <a href="{{ route('agent.agent.info_check') }}" class="pretty-btn margin-top-xs">
                            重新申请
                        </a>
                    @endif
                @else
                    <a href="{{ route('agent.agent.info_check') }}" class="pretty-btn margin-top-xs">
                        立即前往
                    </a>
                @endif


            </div>
        </div>
    </div>
@stop

@section('js')

    <script>

        $(document).ready(function () {
            $('#agent').addClass('active');
            $('#agent_agent_index').addClass('active');
        });

    </script>
@stop