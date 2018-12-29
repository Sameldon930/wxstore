@extends('layouts.common')

@section('title', '代理后台')

@section('header')
    @include('agent.include.header')
@stop

@section('sidebar')
    @include('agent.include.sidebar')
@stop

@section('footer')
    @include('agent.include.footer')
@stop

@section('module_js')
    <script>
        /*
        $(document.body).append('<audio autoplay id="voice-order"></audio>');

        var waitingPlay = [];
        var voiceOrder = document.getElementById("voice-order");

        setInterval(getRecentOrder, 2000);
        setInterval(playOrder, 1000);

        function getRecentOrder() {
            $.ajax({
                type: "POST",
                url: "agent/voice",
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (data) {
                    if (data.code === 'SUCCESS') {
                        waitingPlay.push(data.data);
                    } else {
                        toastr.error(data.msg)
                    }
                }
            });
        }

        function playOrder(){
            if (voiceOrder.paused){
                var src = waitingPlay.shift();
                if (src){
                    voiceOrder.setAttribute('src', src)
                }
            }
        }
        */

    </script>
@stop