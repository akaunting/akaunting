@stack('recurring_message_start')
@if (($recurring = $document->recurring) && ($next = $recurring->getNextRecurring()))
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-info fade show" role="alert">
                <div class="d-flex">
                    @stack('recurring_message_head_start')
                        <h5 class="mt-0 text-white"><strong>{{ trans('recurring.recurring') }}</strong></h5>
                    @stack('recurring_message_head_end')
                </div>

                @stack('recurring_message_body_start')
                    <p class="text-sm lh-160 mb-0">{{ trans('recurring.message', [
                        'type' => mb_strtolower(trans_choice($textRecurringType, 1)),
                        'date' => $next->format($date_format)
                    ]) }}
                    </p>
                @stack('recurring_message_body_end')
            </div>
        </div>
    </div>
@endif
@if ($document->parent)
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-info fade show" role="alert">
                <div class="d-flex">
                    @stack('recurring_parent_message_head_start')
                        <h5 class="mt-0 text-white"><strong>{{ trans('recurring.recurring') }}</strong></h5>
                    @stack('recurring_parent_message_head_end')
                </div>

                @stack('recurring_parent_message_body_start')
                    <p class="text-sm lh-160 mb-0">{!! trans('recurring.message_parent', [
                        'type' => mb_strtolower(trans_choice($textRecurringType, 1)),
                        'link' => '<a href="' . route(mb_strtolower(trans_choice($textRecurringType, 2)) . '.show', $document->parent->id) . '"><u>' . $document->parent->document_number . '</u></a>'
                    ]) !!}
                    </p>
                @stack('recurring_parent_message_body_end')
            </div>
        </div>
    </div>
@endif
@stack('recurring_message_end')
