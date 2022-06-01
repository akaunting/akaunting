@props(['module'])

@php
    $reviews = $module->app_reviews;
@endphp

<div id="review" class="clearfix js-reviews-content" v-if="reviews.status" v-html="reviews.html"></div>

<div id="review" class="clearfix js-reviews-content" v-else>
    <x-layouts.modules.reviews :reviews="$reviews" />
</div>

@if (! empty($reviews->current_page != $reviews->last_page))
    @stack('pagination_start')

    <div class="w-full flex flex-row justify-evenly my-2" v-if="reviews.pagination.last_page != reviews.pagination.current_page">
        <button
            type="button"
            id="review-load-more"
            :disabled="loadMoreLoading"
            @click="onModuleLoadMore('reviews')"
            class="w-48 bg-green m-auto block whitespace-nowrap px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white text-center js-learn-more js-button-modal-submit hover:bg-green-700 disabled:bg-green-300"
        >
            <i v-if="loadMoreLoading" class="animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto before:absolute before:w-2 before:h-2 before:rounded-full before:animate-submit before:delay-[0.14s] after:absolute after:w-2 after:h-2 after:rounded-full after:animate-submit before:-left-3.5 after:-right-3.5 after:delay-[0.42s]"></i>
            <span :class="[{'opacity-0': loadMoreLoading}]">
                {{ trans('modules.see_more') }}
            </span>
        </button>
    </div>

    @stack('pagination_end')
@else
    <div class="flex">
        <small>{{ trans('general.no_records') }}</small>
    </div>
@endif
