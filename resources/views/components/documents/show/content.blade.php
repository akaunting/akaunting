<div class="flex flex-col lg:flex-row my-10 lg:space-x-24 rtl:space-x-reverse space-y-12 lg:space-y-0">
    <div class="w-full lg:w-5/12 space-y-12">
        @stack('recurring_message_start')

        @if (! $hideRecurringMessage)
            @if (($recurring = $document->recurring) && ($next = $recurring->getNextRecurring()))
                @php
                    $recurring_message = trans('recurring.message', [
                        'type' => mb_strtolower(trans_choice($textRecurringType, 1)),
                        'date' => $next->format(company_date_format())
                    ]);
                @endphp

                <x-documents.show.message type="recurring" background-color="bg-blue-100" text-color="text-blue-600" message="{{ $recurring_message }}" />
            @endif

            @if ($parent = $document->parent)
                @php
                    $recurring_message = trans('recurring.message_parent', [
                        'type' => mb_strtolower(trans_choice($textRecurringType, 1)),
                        'link' => '<a href="' . route(config('type.document.' . $document->parent->type . '.route.prefix', 'invoices') . '.show', $parent->id) . '"><u>' . $parent->document_number . '</u></a>'
                    ]);
                @endphp

                <x-documents.show.message type="recurring" background-color="bg-blue-100" text-color="text-blue-600" message="{{ $recurring_message }}" />
            @endif
        @endif

        @stack('recurring_message_end')

        @stack('status_message_start')

        @if (! $hideStatusMessage)
            @if ($document->status == 'draft')
                <x-documents.show.message type="status" background-color="bg-red-100" text-color="text-red-600" message="{!! trans($textStatusMessage) !!}" />
            @endif

            @if (! $document->totals->count())
                <x-documents.show.message type="status" background-color="bg-red-100" text-color="text-red-600" message="{!! trans('invoices.messages.totals_required', ['type' => $type]) !!}" />
            @endif
        @endif

        @stack('status_message_end')

        @stack('create_start')

        @if (! $hideCreated)
            <x-documents.show.create type="{{ $type }}" :document="$document" />
        @endif

        @stack('create_end')

        @stack('send_start')

        @if (! $hideSend)
            <x-documents.show.send type="{{ $type }}" :document="$document" />
        @endif

        @stack('send_end')

        @stack('receive_start')

        @if (! $hideReceive)
            <x-documents.show.receive type="{{ $type }}" :document="$document" />
        @endif

        @stack('receive_end')

        @stack('get_paid_start')

        @if (! $hideGetPaid)
            <x-documents.show.get-paid type="{{ $type }}" :document="$document" />
        @endif

        @stack('get_paid_end')

        @stack('make_paid_start')

        @if (! $hideMakePayment)
            <x-documents.show.make-payment type="{{ $type }}" :document="$document" />
        @endif

        @stack('make_paid_end')

        @stack('restore_start')

        @if (! $hideRestore)
            <x-documents.show.restore type="{{ $type }}" :document="$document" />
        @endif

        @stack('restore_end')

        @stack('schedule_start')
        @if (! $hideSchedule)
            <x-documents.show.schedule type="{{ $type }}" :document="$document" />
        @endif
        @stack('schedule_end')

        @stack('children_start')
        @if (! $hideChildren)
            <x-documents.show.children type="{{ $type }}" :document="$document" />
        @endif
        @stack('children_end')

        @stack('attachment_start')

        @if (! $hideAttachment)
            <x-documents.show.attachment type="{{ $type }}" :document="$document" :attachment="$attachment" />
        @endif

        @stack('attachment_end')
    </div>

    <div class="w-full lg:w-7/12">
        @stack('document_start')

        <x-documents.show.template type="{{ $type }}" :document="$document" />

        @stack('document_end')
    </div>

    <x-form.input.hidden name="senddocument_route" id="senddocument_route" value="{{ route($emailRoute, $document->id) }}" />
    @if ($document->transactions->count())
    <x-form.input.hidden name="sendtransaction_route" id="sendtransaction_route" value="{{ route($transactionEmailRoute, $document->transactions->last()->id) }}" />
    <x-form.input.hidden name="sendtransaction_template" id="sendtransaction_template" value="{{ $transactionEmailTemplate }}" />
    @endif
    <x-form.input.hidden name="document_id" :value="$document->id" />
    <x-form.input.hidden name="{{ $type . '_id' }}" :value="$document->id" />
</div>
