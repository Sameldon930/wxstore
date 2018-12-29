{{--
    下拉选择组件
    合适的文档中 @include('compoment.search_select')
--}}
<div class="col-xs-3 col-lg-2">
    <div class="form-group">
        <label for="{{$item}}">{{$label}}：</label>
        <select class="form-control" name="{{$item}}" id="{{$item}}">
            <option value="">全部</option>
            @foreach($options as $value => $label)
                <option value="{{ $value }}" @if( isset($$item) && (intval($$item ?? "") === $value)) selected @endif >
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>
</div>