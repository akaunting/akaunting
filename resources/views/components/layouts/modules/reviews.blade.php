<div id="review-items" class="divide-y">
    @if (! empty($reviews))
        @foreach ($reviews->data as $review)
            <div class="flex flex-col lg:flex-row gap-2 py-4 px-2 justify-between items-start">
                <div class="flex flex-col">
                    <h5 class="text-sm mb-0">
                        {{ $review->author }}
                    </h5>

                    <p class="text-xs">
                        <x-date date="{{ $review->created_at }}" />
                    </p>
                </div>

                <span class="flex gap-0">
                    @if ($review->rating)
                        @for ($i = 1; $i <= $review->rating; $i++)
                            <i class="material-icons text-xs text-orange">star</i>
                        @endfor

                        @if ($review->rating < 5)
                            @for ($i = 1; $i <= 5 - $review->rating; $i++)
                                <i class="material-icons text-xs">star_border</i>
                            @endfor
                        @endif
                    @else
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="material-icons text-xs">star_border</i>
                        @endfor
                    @endif
                </span>

                <div class="w-full lg:w-1/2">
                    <p class="text-sm lh-160">
                        {!! nl2br($review->text) !!}
                    </p>
                </div>
            </div>
        @endforeach
    @endif
</div>
