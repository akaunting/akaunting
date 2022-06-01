@if ($searchString && $bulkAction)
    <div :class="[{'bulk-action-container': bulk_action.show}]">
        <x-form method="GET" action="{{ $action }}">
            <div v-if="!bulk_action.show" class="items-center">
                <x-search-string model="{{ $searchString }}" />
            </div>

            <x-index.bulkaction class="{{ $bulkAction }}" />
        </x-form>
    </div>
@elseif ($searchString && (! $bulkAction))
    <div>
        <x-form method="GET" action="{{ $action }}">
            <div v-if="!bulk_action.show" class="items-center">
                <x-search-string model="{{ $searchString }}" />
            </div>
        </x-form>
    </div>
@elseif ((! $searchString) && $bulkAction)
    <div :class="[{'h-12': bulk_action.show}]">
        <x-form method="GET" action="{{ $action }}">
            <x-index.bulkaction class="{{ $bulkAction }}" />
        </x-form>
    </div>
@endif
