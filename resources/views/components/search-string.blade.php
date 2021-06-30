<div class="position-relative js-search-box-hidden" style="height: 48.5px;">
    <div class="border-bottom-0 w-100 position-absolute left-0 right-0" style="z-index: 9;">
        <input type="text" placeholder="Search or filter results..." class="form-control" />
    </div>
</div>

<akaunting-search
    placeholder="{{ (!empty($filters)) ? trans('general.placeholder.search_and_filter') : trans('general.search_placeholder')}}"
    search-text="{{ trans('general.search_text') }}"
    operator-is-text="{{ trans('general.is') }}"
    operator-is-not-text="{{ trans('general.isnot') }}" 
    no-data-text="{{ trans('general.no_data') }}"
    no-matching-data-text="{{ trans('general.no_matching_data') }}"
    value="{{ request()->get('search', null) }}"
    :filters="{{ json_encode($filters) }}"
    @if($filtered)
    :default-filtered="{{ json_encode($filtered) }}"
    @endif
    :date-config="{
        allowInput: true,
        altInput: true,
        altFormat: '{{ company_date_format() }}',
        dateFormat: '{{ company_date_format() }}',
        @if (!empty($attributes['min-date']))
        minDate: {{ $attributes['min-date'] }}
        @endif
        @if (!empty($attributes['max-date']))
        maxDate: {{ $attributes['max-date'] }}
        @endif
    }"
></akaunting-search>
