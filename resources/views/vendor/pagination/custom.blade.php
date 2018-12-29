@if ($paginator->hasPages())

    <style>
        .pagination label[for="link-page"] {
            margin-bottom: 0;
            font-weight: 400;
        }
        .pagination input[name="page"] {
            width: 50px;
            height: 20px;
        }
        .pagination button[type="submit"] {
            border-left: 0;
            border-color: #e7e7e7;
            border-radius: 0;
        }
    </style>

    <form action="{{ $paginator->url('') }}">
        @if(isset($search_item))
            @foreach($search_items as $key=>$value)
                @if(isset($$key))
                    <input hidden name="{{$key}}" value="{{ $$key }}">
                @endif
            @endforeach
        @endif

        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled"><span>&laquo;</span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="disabled"><span>{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active"><span>{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></li>
            @else
                <li class="disabled"><span>&raquo;</span></li>
            @endif
            {{-- 增加输入框，跳转任意页码和显示任意条数 --}}
            <li>
                <a>
                    <label for="link-page">跳转到第</label>
                    <input class="text-center" value="{{ $paginator->currentPage() }}" id="link-page" name="page" onclick="select()">
                    <label for="link-page">页 / 共 {{ $paginator->lastPage() }} 页</label>
                </a>
            </li>
            <li>
                <button class="btn btn-primary" type="submit">确认</button>
            </li>
        </ul>
    </form>

    <script>

    </script>


@endif
