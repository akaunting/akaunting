@php
    $started_date = '<span class="font-medium">' . company_date($transaction->recurring->started_at) . '</span>';
    $frequency = Str::lower(trans('recurring.' . str_replace('ly', 's', $transaction->recurring->frequency)));
@endphp

<x-show.accordion type="schedule">
    <x-slot name="head">
        <x-show.accordion.head
            title="{{ trans_choice('general.schedules', 1) }}"
            description="{!! trans('transactions.slider.schedule', ['frequency' => $frequency, 'interval' => $transaction->recurring->interval, 'date' => $started_date]) !!}"
        />
    </x-slot>

    <x-slot name="body">
        <div class="flex my-3 space-x-2 rtl:space-x-reverse">
            @if ($next = $transaction->recurring->getNextRecurring())
                {{ trans('recurring.next_date', ['date' => $next->format(company_date_format())]) }}
                <br>
                @if ($transaction->recurring->limit_by == 'count')
                    @if ($transaction->recurring->limit_count == 0)
                        {{ trans('recurring.ends_never') }}
                    @else
                        {{ trans('recurring.ends_after', ['times' => $transaction->recurring->limit_count]) }}
                    @endif
                @else
                    {{ trans('recurring.ends_date', ['date' => company_date($transaction->recurring->limit_date)]) }}
                @endif
            @else
                {{ trans('documents.statuses.ended') }}
            @endif
        </div>
    </x-slot>
</x-show.accordion>
