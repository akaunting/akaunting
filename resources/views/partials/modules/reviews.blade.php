<div id="review-items">
    @foreach($reviews->data as $review)
    <div class="post">
        <div class="user-block">
            <img class="img-circle img-bordered-sm" src="{{ $review->thumb }}" alt="{{ $review->author }}">
            <span class="username">
                {{ $review->author }}
                <span class="pull-right">
                    @for($i = 1; $i <= $review->rating; $i++)
                    <i class="fa fa-star"></i>
                    @endfor
                    @for($i = $review->rating; $i < 5; $i++)
                    <i class="fa fa-star-o"></i>
                    @endfor
                </span>
            </span>
            <span class="description">{{ Date::parse($review->created_at)->format($date_format) }}</span>
        </div>
        <p>
            {!! nl2br($review->text) !!}
        </p>
    </div>
    @endforeach

    @stack('pagination_start')

    @php
    $review_first_item = count($reviews->data) > 0 ? ($reviews->current_page - 1) * $reviews->per_page + 1 : null;
    $review_last_item = count($reviews->data) > 0 ? $review_first_item + count($reviews->data) - 1 : null;
    @endphp

    @if ($review_first_item)
        <div class="pull-left" style="margin-top: 7px;">
            <small>{{ trans('pagination.showing', ['first' => $review_first_item, 'last' => $review_last_item, 'total' => $reviews->total, 'type' => strtolower(trans('modules.tab.reviews'))]) }}</small>
        </div>

        <div class="pull-right">
            <ul class="pagination pagination-sm no-margin">
                {{-- Previous Page Link --}}
                @if ($reviews->current_page <= 1)
                <li class="disabled"><span>&laquo;</span></li>
                @else
                <li><a href="{{ url(request()->path()) }}?page={{ $reviews->current_page - 1 }}" rel="prev">&laquo;</a></li>
                @endif

                {{-- Pagination Elements --}}
                @for ($page = 1; $page <= $reviews->last_page; $page++)
                    @if ($page == $reviews->current_page)
                    <li class="active"><span>{{ $page }}</span></li>
                    @else
                    <li><a href="{{ url(request()->path()) }}?page={{ $page }}" data-page="{{ $page }}">{{ $page }}</a></li>
                    @endif
                @endfor

                {{-- Next Page Link --}}
                @if ($reviews->current_page != 1)
                <li><a href="{{ url(request()->path()) }}?page={{ $reviews->current_page + 1 }}" rel="next">&raquo;</a></li>
                @else
                <li class="disabled"><span>&raquo;</span></li>
                @endif
            </ul>
        </div>
    @else
        <div class="pull-left">
            <small>{{ trans('general.no_records') }}</small>
        </div>
    @endif

    @stack('pagination_end')
</div>