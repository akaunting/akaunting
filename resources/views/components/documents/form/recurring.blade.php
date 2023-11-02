<x-form.section class="-mt-10 mb-14" override="class">
    <x-slot name="head">
        <x-form.section.head title="{{ trans_choice('general.schedules', 1) }}" description="{{ trans('recurring.form_description.schedule', ['type' => Str::lower(trans_choice('general.invoices', 1))]) }}" />
    </x-slot>

    <x-slot name="body">
        @if (empty($document))
            <x-form.group.recurring :type="$type" @started="onChangeRecurringDate()" />
        @else
            <x-form.group.recurring
                :type="$type"
                @started="onChangeRecurringDate()"
                :interval="$document ? $document->recurring->interval : null"
                :frequency="$document ? $document->recurring->frequency : null"
                :custom-frequency="$document ? $document->recurring->custom_frequency : null"
                :limit="$document ? $document->recurring->limit_by : null"
                :started-value="$document ? $document->recurring->started_at : null"
                :limit-count="$document ? $document->recurring->limit_count : null"
                :limit-date-value="$document ? $document->recurring->limit_date : null"
                :send-email="$document ? $document->recurring->auto_send : null"
            />
        @endif
    </x-slot>
</x-form.section>
