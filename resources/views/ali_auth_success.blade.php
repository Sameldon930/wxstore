<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

    <style>

    </style>
</head>
<body>

<div style="text-align: center;padding-top: 20px">
    <h3>授权成功！</h3>
</div>


</body>


<script src="/js/jquery.min.js"></script>
@yield('payment')
<script>
    function pay(){
//        console.log(1)
    }
</script>


</html>
