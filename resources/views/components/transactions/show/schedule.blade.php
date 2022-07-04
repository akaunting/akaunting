@php
    $started_date = '<span class="font-medium">' . company_date($transaction->recurring->started_at) . '</span>';
    $frequency = Str::lower(trans('recurring.' . str_replace('ly', 's', $transaction->recurring->frequency)));
@endphp

<div class="border-b pb-4" x-data="{ schedule : null }">
    <button class="relative w-full text-left cursor-pointer group"
        x-on:click="schedule !== 1 ? schedule = 1 : schedule = null"
    >
        <span class="font-medium">
            <x-button.hover group-hover>
                {{ trans_choice('general.schedules', 1) }}
            </x-button.hover>
        </span>

        <div class="text-black-400 text-sm">
            {!! trans('transactions.slider.schedule', ['frequency' => $frequency, 'interval' => $transaction->recurring->interval, 'date' => $started_date]) !!}
        </div>

        <span class="material-icons absolute right-0 top-0 transition-all transform" x-bind:class="schedule === 1 ? 'rotate-180' : ''">expand_more</span>
    </button>

    <div class="overflow-hidden transition-transform origin-top-left ease-linear duration-100"
        x-ref="container1"
        x-bind:class="schedule == 1 ? 'h-auto ' : 'scale-y-0 h-0'"
    >
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
    </div>
</div>
