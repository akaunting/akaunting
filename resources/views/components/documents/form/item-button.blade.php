<akaunting-item-button
    placeholder="{{ trans('general.placeholder.item_search') }}"
    no-data-text="{{ trans('general.no_data') }}"
    no-matching-data-text="{{ trans('general.no_matching_data') }}"
    type="{{ $type }}"
    price="{{ $price }}"
    :dynamic-currency="currency"
    :items="{{ json_encode($items) }}"
    search-url="{{ $searchUrl }}"
    :search-char-limit="{{ $searchCharLimit }}"
    @if (is_array($searchListKey))
    :search-list-key="{{ json_encode($searchListKey) }}"
    @elseif (is_object($searchListKey))
    :search-list-key="{{ $searchListKey }}"
    @else
    search-list-key="{{ $searchListKey }}"
    @endif
    @item="onSelectedItem($event)"
    add-item-text="{{ trans('general.form.add_an', ['field' => trans_choice('general.items', 1)]) }}"
    create-new-item-text="{{ trans('general.title.new', ['type' =>  trans_choice('general.items', 1)]) }}"
></akaunting-item-button>
