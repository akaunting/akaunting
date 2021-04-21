@if (!in_array($document->status, $hideTimelineStatuses))
@stack('timeline_body_start')
    <div class="card">
        <div class="card-body">
            <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
                @stack('timeline_create_start')
                    @if (!$hideTimelineCreate)
                        <div class="timeline-block">
                            <span class="timeline-step badge-primary">
                                <i class="fas fa-plus"></i>
                            </span>

                            <div class="timeline-content">
                                @stack('timeline_create_head_start')
                                <h2 class="font-weight-500">
                                    {{ trans($textTimelineCreateTitle) }}
                                </h2>
                                @stack('timeline_create_head_end')

                                @stack('timeline_create_body_start')
                                    @stack('timeline_create_body_message_start')
                                        <small>
                                            {{ trans_choice('general.statuses', 1) .  ':'  }}
                                        </small>
                                        <small>
                                            {{ trans($textTimelineCreateMessage, ['date' => Date::parse($document->created_at)->format($date_format)]) }}
                                        </small>
                                    @stack('timeline_create_body_message_end')

                                    <div class="mt-3">
                                        @stack('timeline_create_body_button_edit_start')
                                            @if (!$hideButtonEdit)
                                                @can($permissionUpdate)
                                                    <a href="{{ route($routeButtonEdit, $document->id) }}" class="btn btn-primary btn-sm btn-alone header-button-top">
                                                        {{ trans('general.edit') }}
                                                    </a>
                                                @endcan
                                            @endif
                                        @stack('timeline_create_body_button_edit_end')
                                    </div>
                                @stack('timeline_create_body_end')
                            </div>
                        </div>
                    @endif
                @stack('timeline_create_end')

                @stack('timeline_sent_start')
                    @if (!$hideTimelineSent)
                        <div class="timeline-block">
                            <span class="timeline-step badge-danger">
                                <i class="far fa-envelope"></i>
                            </span>

                            <div class="timeline-content">
                                @stack('timeline_sent_head_start')
                                    <h2 class="font-weight-500">
                                        {{ trans($textTimelineSentTitle) }}
                                    </h2>
                                @stack('timeline_sent_head_end')

                                @stack('timeline_sent_body_start')
                                    @if ($document->status != 'sent' && $document->status != 'partial' && $document->status != 'viewed' && $document->status != 'received')
                                        @stack('timeline_sent_body_message_start')
                                            <small>
                                                {{ trans_choice('general.statuses', 1) . ':' }}
                                            </small>
                                            <small>
                                                {{ trans($textTimelineSentStatusDraft) }}
                                            </small>
                                        @stack('timeline_sent_body_message_end')

                                        <div class="mt-3">
                                            @stack('timeline_sent_body_button_sent_start')
                                                @if (!$hideButtonSent)
                                                    @can($permissionUpdate)
                                                        @if($document->status == 'draft')
                                                            <a href="{{ route($routeButtonSent, $document->id) }}" class="btn btn-white btn-sm header-button-top">
                                                                {{ trans($textTimelineSentStatusMarkSent) }}
                                                            </a>
                                                        @else
                                                            <button type="button" class="btn btn-secondary btn-sm header-button-top" disabled="disabled">
                                                                {{ trans($textTimelineSentStatusMarkSent) }}
                                                            </button>
                                                        @endif
                                                    @endcan
                                                @endif
                                            @stack('timeline_sent_body_button_sent_end')

                                            @stack('timeline_receive_body_button_received_start')
                                                @if (!$hideButtonReceived)
                                                    @can($permissionUpdate)
                                                        @if ($document->status == 'draft')
                                                            <a href="{{ route($routeButtonReceived, $document->id) }}" class="btn btn-danger btn-sm btn-alone header-button-top">
                                                                {{ trans($textTimelineSentStatusReceived) }}
                                                            </a>
                                                        @else
                                                            <button type="button" class="btn btn-secondary btn-sm header-button-top" disabled="disabled">
                                                                {{ trans($textTimelineSentStatusReceived) }}
                                                            </button>
                                                        @endif
                                                    @endcan
                                                @endif
                                            @stack('timeline_receive_body_button_received_end')
                                    @elseif($document->status == 'viewed')
                                        @stack('timeline_viewed_invoice_body_message_start')
                                            <small>{{ trans_choice('general.statuses', 1) . ':' }}</small>
                                            <small>{{ trans('invoices.messages.status.viewed') }}</small>
                                        @stack('timeline_viewed_invoice_body_message_end')
                                    @elseif($document->status == 'received')
                                        @stack('timeline_receive_bill_body_message_start')
                                            <small>{{ trans_choice('general.statuses', 1) .  ':'  }}</small>
                                            <small>{{ trans('bills.messages.status.receive.received', ['date' => Date::parse($document->received_at)->format($date_format)]) }}</small>
                                        @stack('timeline_receive_bill_body_message_end')
                                    @else
                                        @stack('timeline_sent_body_message_start')
                                            <small>{{ trans_choice('general.statuses', 1) . ':' }}</small>
                                            <small>{{ trans('invoices.messages.status.send.sent', ['date' => Date::parse($document->sent_at)->format($date_format)]) }}</small>
                                        @stack('timeline_sent_body_message_end')
                                    @endif

                                    @if (!($document->status != 'sent' && $document->status != 'partial' && $document->status != 'viewed' && $document->status != 'received'))
                                    <div class="mt-3">
                                    @endif

                                    @stack('timeline_sent_body_button_email_start')
                                        @if (!$hideButtonEmail)
                                            @if($document->contact_email)
                                                <a href="{{ route($routeButtonEmail, $document->id) }}" class="btn btn-danger btn-sm header-button-top">
                                                    {{ trans($textTimelineSendStatusMail) }}
                                                </a>
                                            @else
                                                <el-tooltip content="{{ trans('invoices.messages.email_required') }}" placement="top">
                                                    <button type="button" class="btn btn-danger btn-sm btn-tooltip disabled header-button-top">
                                                        {{ trans($textTimelineSendStatusMail) }}
                                                    </button>
                                                </el-tooltip>
                                            @endif
                                        @endif
                                    @stack('timeline_sent_body_button_email_end')

                                    @stack('timeline_sent_body_button_share_start')
                                        @if (!$hideButtonShare)
                                            @if ($document->status != 'cancelled')
                                                <a href="{{ $signedUrl }}" target="_blank" class="btn btn-white btn-sm header-button-top">
                                                    {{ trans('general.share') }}
                                                </a>
                                            @endif
                                        @endif
                                    @stack('timeline_sent_body_button_share_end')

                                    </div>

                                @stack('timeline_sent_body_end')
                            </div>
                        </div>
                    @endif
                @stack('timeline_sent_end')

                @stack('timeline_get_paid_start')
                    @if (!$hideTimelinePaid)
                        <div class="timeline-block">
                            <span class="timeline-step badge-success">
                                <i class="far fa-money-bill-alt"></i>
                            </span>

                            <div class="timeline-content">
                                @stack('timeline_get_paid_head_start')
                                    <h2 class="font-weight-500">
                                        {{ trans($textTimelineGetPaidTitle) }}
                                    </h2>
                                @stack('timeline_get_paid_head_end')

                                @stack('timeline_get_paid_body_start')
                                    @stack('timeline_get_paid_body_message_start')
                                        @if($document->status != 'paid' && empty($document->transactions->count()))
                                            <small>
                                                {{ trans_choice('general.statuses', 1) . ':' }}
                                            </small>
                                            <small>
                                                {{ trans($textTimelineGetPaidStatusAwait) }}
                                            </small>
                                        @else
                                            <small>
                                                {{ trans_choice('general.statuses', 1) . ':' }}
                                            </small>
                                            <small>
                                                {{ trans($textTimelineGetPaidStatusPartiallyPaid) }}
                                            </small>
                                        @endif
                                    @stack('timeline_get_paid_body_message_end')

                                    <div class="mt-3">
                                        @stack('timeline_get_paid_body_button_pay_start')
                                            @if (!$hideButtonPaid)
                                                @can($permissionUpdate)
                                                    <a href="{{ route($routeButtonPaid, $document->id) }}" class="btn btn-white btn-sm header-button-top">
                                                        {{ trans($textTimelineGetPaidMarkPaid) }}
                                                    </a>
                                                @endcan
                                            @endif
                                        @stack('timeline_get_paid_body_button_pay_end')

                                        @stack('timeline_get_paid_body_button_payment_start')
                                            @if (!$hideButtonAddPayment)
                                                @if(empty($document->transactions->count()) || (!empty($document->transactions->count()) && $document->paid != $document->amount))
                                                    <button @click="onPayment" id="button-payment" class="btn btn-success btn-sm header-button-bottom">
                                                        {{ trans($textTimelineGetPaidAddPayment) }}
                                                    </button>
                                                @endif
                                            @endif
                                        @stack('timeline_get_paid_body_button_payment_end')
                                    </div>
                                @stack('timeline_get_paid_body_end')
                            </div>
                        </div>
                    @endif
                @stack('timeline_get_paid_end')
            </div>
        </div>
    </div>
@stack('timeline_body_end')
@endif
