<akaunting-item-button
    placeholder="{{ trans('general.placeholder.item_search') }}"
    no-data-text="{{ trans('general.no_data') }}"
    no-matching-data-text="{{ trans('general.no_matching_data') }}"
    type="{{ $type }}"
    price="{{ $price }}"
    :dynamic-currency="currency"
    :items="{{ json_encode($items) }}"
    :search-char-limit="{{ $searchCharLimit }}"
    @item="onSelectedItem($event)"
    add-item-text="{{ trans('general.form.add_an', ['field' => trans_choice('general.items', 1)]) }}"
    create-new-item-text="{{ trans('general.title.create', ['type' =>  trans_choice('general.items', 1)]) }}"
></akaunting-item-button>
