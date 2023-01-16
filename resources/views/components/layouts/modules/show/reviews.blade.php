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
            class="relative w-48 bg-green m-auto block whitespace-nowrap px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white text-center js-learn-more js-button-modal-submit hover:bg-green-700 disabled:bg-green-300"
        >
            <x-button.loading action="loadMoreLoading">
                {{ trans('modules.see_more') }}
            </x-button.loading>
        </button>
    </div>

    @stack('pagination_end')
@elseif (! count($reviews->data))
    <div class="flex">
        <small>{{ trans('general.no_records') }}</small>
    </div>
@endif
