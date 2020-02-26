<div id="review-items">
    @if (!empty($reviews))
        @foreach($reviews->data as $review)
            <div class="media media-comment">
                <div class="media-body">
                    <div class="media-comment-text">
                        <div class="d-flex">
                            <h5 class="mt-0">{{ $review->author }}</h5>
                            <h5 class="text-right ml-auto">@date($review->created_at)</h5>
                        </div>
                        <p class="text-sm lh-160">{!! nl2br($review->text) !!}</p>
                        <div class="icon-actions">
                            <a href="#" class="like active">
                                <span class="text-yellow">
                                    @for($i = 1; $i <= $review->rating; $i++)
                                        <i class="fa fa-star"></i>
                                    @endfor
                                    @for($i = $review->rating; $i < 5; $i++)
                                        <i class="fa fa-star-o"></i>
                                    @endfor
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @php
            $review_first_item = count($reviews->data) > 0 ? ($reviews->current_page - 1) * $reviews->per_page + 1 : null;
            $review_last_item = count($reviews->data) > 0 ? $review_first_item + count($reviews->data) - 1 : null;
        @endphp
    @endif

    @if (!empty($review_first_item))
        @stack('pagination_start')

        <div class="row d-none">
            <div class="col-md-6">
                <span class="table-text d-none d-lg-block">
                    {{ trans('pagination.showing', ['first' => $review_first_item, 'last' => $review_last_item, 'total' => $reviews->total, 'type' => strtolower(trans('modules.tab.reviews'))]) }}
                </span>
            </div>

            <div class="col-md-6">
                <ul class="pagination float-right">
                    {{-- Previous Page Link --}}
                    @if ($reviews->current_page <= 1)
                        <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                    @else
                        <li><a class="page-link" href="{{ url(request()->path()) }}?page={{ $reviews->current_page - 1 }}" rel="prev">&laquo;</a></li>
                    @endif

                    {{-- Pagination Elements --}}
                    @for ($page = 1; $page <= $reviews->last_page; $page++)
                        @if ($page == $reviews->current_page)
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ url(request()->path()) }}?page={{ $page }}" data-page="{{ $page }}">{{ $page }}</a></li>
                        @endif
                    @endfor

                    {{-- Next Page Link --}}
                    @if ($reviews->current_page != 1)
                        <li class="page-item"><a class="page-link" href="{{ url(request()->path()) }}?page={{ $reviews->current_page + 1 }}" rel="next">&raquo;</a></li>
                    @else
                        <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                    @endif
                </ul>
            </div>
        </div>

        @stack('pagination_end')
    @else
        <div class="row">
            <div class="col-md-12">
                <small>{{ trans('general.no_records') }}</small>
            </div>
        </div>
    @endif
</div>
