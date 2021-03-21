@stack($name . '_input_start')

    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="bulk-action-{{ $id }}"
            @if (isset($attributes['disabled']))
            :disabled="{{ ($attributes['disabled']) ? true : false }}"
            @else
            data-bulk-action="{{ $id }}"
            @endif
            :value="{{ $id }}"
            v-model="{{ !empty($attributes['v-model']) ? $attributes['v-model'] : 'bulk_action.selected' }}"
            v-on:change="onSelect">
        <label class="custom-control-label" for="bulk-action-{{ $id }}"></label>
    </div>

@stack($name . '_input_end')
