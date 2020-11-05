
<akaunting-search
    placeholder="{{ trans('general.search_placeholder') }}"
    text-search="{{ trans('Search for this text') }}"
    value="{{ request()->get('search', null) }}"
    :filters="{{ json_encode($filters) }}"
></akaunting-search>
