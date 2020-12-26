<akaunting-item-button
    placeholder="{{ trans('general.placeholder.item_search') }}"
    no-data-text="{{ trans('general.no_data') }}"
    no-matching-data-text="{{ trans('general.no_matching_data') }}"
    type="{{ $type }}"
    price="{{ $price }}"
    :items="{{ json_encode($items) }}"
    @item="onSelectedItem($event)"
></akaunting-item-button>
