<x-show.accordion type="schedule" :open="($accordionActive == 'schedule')">
    <x-slot name="head">
        <x-show.accordion.head
            title="{{ trans_choice('general.schedules', 1) }}"
            description="{!! $description !!}"
        />
    </x-slot>

    <x-slot name="body" class="block" override="class">
        @stack('timeline_schedule_body_start')

        <div class="flex my-3 space-x-2 rtl:space-x-reverse">
            @stack('timeline_schedule_body_description_start')

            @if ($document->recurring && ($next = $document->recurring->getNextRecurring()))
                {{ trans('recurring.next_date', ['date' => $next->format(company_date_format())]) }}
                <br>
                @if (($document->recurring->limit_by == 'count'))
                    @if ($document->recurring->limit_count == 0)
                        {{ trans('recurring.ends_never') }}
                    @else
                        {{ trans('recurring.ends_after', ['times' => $document->recurring->limit_count]) }}
                    @endif
                @else
                    {{ trans('recurring.ends_date', ['date' => company_date($document->recurring->limit_date)]) }}
                @endif
            @else
                {{ trans('documents.statuses.ended') }}
            @endif

            @stack('timeline_schedule_body_description_end')
        </div>

        @stack('timeline_schedule_body_end')
    </x-slot>
</x-show.accordion>
