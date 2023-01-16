@stack('bulk_action_all_input_start')

<div class="text-left">
    <input type="checkbox"
        id="table-check-all"
        class="rounded-sm text-purple border-gray-300 cursor-pointer disabled:bg-gray-200 focus:outline-none focus:ring-transparent" 
        v-model="{{ !empty($attributes['v-model']) ? $attributes['v-model'] : 'bulk_action.select_all' }}"
        @click="onSelectAllBulkAction"
    />
    <label for="table-check-all"></label>
</div>

@stack('bulk_action_all_input_end')
