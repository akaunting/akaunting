<div id="review-items">
    @if (!empty($reviews))
        @foreach($reviews->data as $review)
            <div class="media media-comment">
                <div class="media-body">
                    <div class="media-comment-text">
                        <div class="d-flex">
                            <h5 class="mb-0">{{ $review->author }}</h5>
                        </div>

                        <div class="d-flex">
                            <p class="h6 text-muted mb-0">@date($review->created_at)</p>
                        </div>

                        <div class="d-flex">
                            <span class="text-yellow position-absolute top-3 right-3">
                                @if ($review->rating)
                                    @for($i = 1; $i <= $review->rating; $i++)
                                        <i class="fa fa-star"></i>
                                    @endfor
                                    @if ($review->rating < 5) 
                                        @for($i = 1; $i <= 5 - $review->rating; $i++)
                                            <i class="far fa-star"></i>
                                        @endfor
                                    @endif
                                @else
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="far fa-star"></i>
                                    @endfor
                                @endif
                            </span>
                        </div>

                        <div class="d-flex">
                            <p class="mt-2 mb-0 text-sm lh-160">{!! nl2br($review->text) !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>