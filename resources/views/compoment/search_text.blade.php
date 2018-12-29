{{--
    文本组件
    合适的文档中 @include('compoment.search_text')
--}}
<div class="col-xs-3 col-lg-2">
    <div class="form-group">
        <label for="{{$item}}">{{$label}}：</label>
        <input class="form-control" name="{{$item}}" id="{{$item}}" value="{{ $$item or '' }}">
    </div>
</div>