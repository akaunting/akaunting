@if ($hideEmptyPage || ($accounts->count() || request()->get('search', false)))
<x-index.container>
    @if ((! $hideSearchString) && (! $hideBulkAction))
        <x-index.search
            search-string="{{ $searchStringModel }}"
            bulk-action="{{ $bulkActionClass }}"
        />
    @elseif ((! $hideSearchString) && $hideBulkAction)
        <x-index.search
            search-string="{{ $searchStringModel }}"
        />
    @elseif ($hideSearchString && (! $hideBulkAction))
        <x-index.search
            bulk-action="{{ $bulkActionClass }}"
        />
    @endif

    <x-accounts.index.accounts :accounts="$accounts" />

    @if (! $hidePagination)
        <x-pagination :items="$accounts" />
    @endif
</x-index.container>
@else
    {{-- Accounts are not imported, and the first one is made where the page's own button leads --}}
    <x-empty-page
        group="banking"
        page="accounts"
        hide-button-import
        :buttons="[
            [
                'url'           => route($createRoute),
                'permission'    => $permissionCreate,
                'text'          => trans('general.title.new', ['type' => trans_choice('general.accounts', 1)]),
                'description'   => trans('general.empty.actions.new', ['type' => strtolower(trans_choice('general.accounts', 1))]),
                'active_badge'  => true,
                'stack'         => 'create_button',
            ],
        ]"
    />
@endif
