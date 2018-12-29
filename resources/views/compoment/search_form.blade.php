{{--
    搜索组件
    合适的文档中 @include('compoment.search_form')
--}}

<form action=""  method="get" class="form" @if(isset($search)) name="search_action" @endif>
    <div class="row">
        @foreach($search_items as $search_item => $search_params)
            <?php
            $_type = $search_params['type'] ?? 'equal';
            $_form = $search_params['form'] ?? 'text';
            $_label = $search_params['label'] ?? '';
            $_options = ($_form === 'select') ? $search_params['options'] : [];
            ?>
            @if($_type === 'date')
                @include('compoment.search_date')
            @elseif($_form === 'select')
                @include('compoment.search_select', ['item'=>$search_item,'label'=>$_label,'options'=>$_options])
            @else
                @include('compoment.search_text', ['item'=>$search_item,'label'=>$_label])
            @endif
        @endforeach
    </div>


    <div class="row">
        <div class="col-xs-12">
            <button class="pretty-btn pull-left" >搜索</button>
            <a href="{{ url()->current() }}" class="pretty-btn">重置</a>
            @if(isset($export))
                <button name="export" value="export" class="pretty-btn pull-left">
                    <i class="fa fa-share-square-o"></i>导出
                </button>
            @endif
        </div>
    </div>



</form>
