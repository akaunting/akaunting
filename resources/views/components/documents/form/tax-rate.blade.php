@if (! empty($document))
    @php
        // The alert prints its description as raw HTML, which is what lets the
        // toggle sit inside the warning box instead of below it.
        $tax_rate_description = trans('documents.tax_rate.changed_description')
            . '<label class="flex items-center mt-3 font-medium cursor-pointer">'
            . '<input type="checkbox" name="recalculate_taxes" value="1" v-model="recalculate_taxes" class="rounded-sm text-purple border-gray-300 cursor-pointer focus:outline-none focus:ring-transparent ltr:mr-2 rtl:ml-2">'
            . trans('documents.tax_rate.recalculate')
            . '</label>';
    @endphp

    <div v-if="taxes_out_of_date || recalculate_taxes" class="sm:col-span-6">
        <x-alert.warning
            :title="trans('documents.tax_rate.changed_title')"
            :description="$tax_rate_description"
        />
    </div>
@endif
