@props(['id', 'name'])

@stack($name . '_input_start')

<div data-bulkaction>
    <input type="checkbox"
        id="bulk-action-{{ $id }}"
        class="rounded-sm text-purple border-gray-300 cursor-pointer disabled:bg-gray-200 focus:outline-none focus:ring-transparent"
        @if (!empty($attributes['disabled']))
        :disabled="{{ ($attributes['disabled']) ? true : false }}"
        @else
        data-bulk-action="{{ $id }}"
        @endif
        :value="{{ $id }}"
        v-model="{{ !empty($attributes['v-model']) ? $attributes['v-model'] : 'bulk_action.selected' }}"
        @change="onSelectBulkAction"
    >
    <label for="bulk-action-{{ $id }}"></label>
</div>

@stack($name . '_input_end')
