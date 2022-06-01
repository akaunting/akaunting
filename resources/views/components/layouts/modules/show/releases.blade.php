@props(['module'])

@php
    $releases = $module->app_releases;
@endphp

<div id="releases" class="clearfix js-releases-content" v-if="releases.status" v-html="releases.html"></div>

<div id="releases" class="clearfix js-releases-content" v-else>
    <x-layouts.modules.releases :releases="$releases" />
</div>

@if (! empty($releases->current_page != $releases->last_page))
    @stack('pagination_start')

    <div class="w-full flex flex-row justify-evenly my-2" v-if="releases.pagination.last_page != releases.pagination.current_page">
        <button
            type="button"
            id="releases-load-more"
            :disabled="loadMoreLoading"
            @click="onModuleLoadMore('releases')"
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
