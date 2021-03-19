@stack($name . '_input_start')

    <label class="custom-toggle d-inline-block">
        <input type="checkbox"
            name="status[{{ $id }}]"
            @input="bulk_action.path='{{ request()->path() }}'; onStatus({{ $id }}, $event)"
                {{ ($value) ? 'checked' :'' }}>

        <span class="custom-toggle-slider rounded-circle status-green"
            data-label-off="{{ trans('general.no') }}"
            data-label-on="{{ trans('general.yes') }}">
        </span>
    </label>

@stack($name . '_input_end')
