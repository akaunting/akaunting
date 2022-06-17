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
            class="relative w-48 bg-green m-auto block whitespace-nowrap px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white text-center js-learn-more js-button-modal-submit hover:bg-green-700 disabled:bg-green-300"
        >
            <x-button.loading action="loadMoreLoading">
                {{ trans('modules.see_more') }}
            </x-button.loading>
        </button>
    </div>

    @stack('pagination_end')
@else
    <div class="flex">
        <small>{{ trans('general.no_records') }}</small>
    </div>
@endif
