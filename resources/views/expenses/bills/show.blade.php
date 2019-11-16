@extends('layouts.admin')

@section('title', trans_choice('general.bills', 1) . ': ' . $bill->bill_number)

@section('content')
    @stack('recurring_message_start')
        @if (($recurring = $bill->recurring) && ($next = $recurring->next()))
            <div class="callout callout-info">
                @stack('recurring_message_head_start')
                    <h4>{{ trans('recurring.recurring') }}</h4>
                @stack('recurring_message_head_end')

                @stack('recurring_message_body_start')
                    <p>{{ trans('recurring.message', [
                        'type' => mb_strtolower(trans_choice('general.bills', 1)),
                        'date' => $next->format($date_format)
                    ]) }}
                    </p>
                @stack('recurring_message_body_end')
            </div>
        @endif
    @stack('recurring_message_end')

    @stack('status_message_start')
        @if ($bill->status->code == 'draft')
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="alert alert-warning fade show" role="alert">
                        @stack('status_message_body_start')
                            <span class="alert-text">
                                <strong>{!! trans('bills.messages.draft') !!}</strong>
                            </span>
                        @stack('status_message_body_end')
                    </div>
                </div>
            </div>
        @endif
    @stack('status_message_end')

    @stack('timeline_start')
        @if ($bill->status->code != 'paid')
            <div class="row justify-content-center">
                @stack('timeline_body_start')
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
                                    @stack('timeline_body_create_bill_start')
                                        <div class="timeline-block">
                                            <span class="timeline-step badge-info">
                                               <i class="fas fa-plus"></i>
                                            </span>
                                            <div class="timeline-content">
                                                @stack('timeline_body_create_bill_head_start')
                                                    <h3 class="font-weight-bold">{{ trans('bills.create_bill') }}</h3>
                                                @stack('timeline_body_create_bill_head_end')

                                                @stack('timeline_body_create_bill_body_start')

                                                    @stack('timeline_body_create_bill_body_message_start')
                                                        <span> {{ trans_choice('general.statuses', 1) .  ' :'  }}</span> <span class=" text-sm font-weight-300">{{ trans('bills.messages.status.created', ['date' => Date::parse($bill->created_at)->format($date_format)]) }}</span>
                                                    @stack('timeline_body_create_bill_body_message_end')

                                                    <div class="mt-3">
                                                        @stack('timeline_body_create_bill_body_button_edit_start')
                                                            <a href="{{ url('expenses/bills/' . $bill->id . '/edit') }}" class="btn btn-info btn-sm btn-alone">
                                                                {{ trans('general.edit') }}
                                                            </a>
                                                        @stack('timeline_body_create_bill_body_button_edit_end')
                                                    </div>
                                                @stack('timeline_body_create_bill_body_end')
                                            </div>
                                        </div>
                                    @stack('timeline_body_create_bill_end')

                                    @stack('timeline_body_receive_bill_start')
                                        <div class="timeline-block">
                                            <span class="timeline-step badge-warning">
                                                <i class="far fa-envelope"></i>
                                            </span>
                                          <div class="timeline-content">
                                              @stack('timeline_body_receive_bill_head_start')
                                                 <h3 class="font-weight-bold">{{ trans('bills.receive_bill') }}</h3>
                                              @stack('timeline_body_receive_bill_head_end')

                                              @stack('timeline_body_receive_bill_body_start')
                                                @if ($bill->status->code == 'draft')
                                                    @stack('timeline_body_receive_bill_body_message_start')
                                                        <span> {{ trans_choice('general.statuses', 1) .  ' :'  }}</span> <span class=" text-sm font-weight-300">{{ trans('bills.messages.status.receive.draft') }}</span>
                                                    @stack('timeline_body_receive_bill_body_message_end')

                                                    <div class="mt-3">
                                                        @stack('timeline_body_receive_bill_body_button_sent_start')
                                                            @permission('update-expenses-bills')
                                                                <a href="{{ url('expenses/bills/' . $bill->id . '/received') }}" class="btn btn-warning btn-sm btn-alone">{{ trans('bills.mark_received') }}</a>
                                                            @endpermission
                                                        @stack('timeline_body_receive_bill_body_button_sent_end')
                                                    </div>
                                                @else
                                                    @stack('timeline_body_receive_bill_body_message_start')
                                                        <span> {{ trans_choice('general.statuses', 1) .  ' :'  }}</span> <span class=" text-sm font-weight-300">{{ trans('bills.messages.status.receive.received', ['date' => Date::parse($bill->created_at)->format($date_format)]) }}</span>
                                                    @stack('timeline_body_receive_bill_body_message_end')
                                                @endif
                                              @stack('timeline_body_receive_bill_body_end')
                                          </div>
                                        </div>
                                    @stack('timeline_body_receive_bill_end')

                                    @stack('timeline_body_make_payment_start')
                                        <div class="timeline-block">
                                            <span class="timeline-step badge-success">
                                                <i class="far fa-money-bill-alt"></i>
                                            </span>

                                            <div class="timeline-content">
                                                @stack('timeline_body_make_payment_head_start')
                                                    <h3 class="font-weight-bold">{{ trans('bills.make_payment') }}</h3>
                                                @stack('timeline_body_make_payment_head_end')

                                                @stack('timeline_body_make_payment_body_start')
                                                    @stack('timeline_body_get_paid_body_message_start')
                                                        @if($bill->status->code != 'paid' && empty($bill->payments->count()))
                                                            <span> {{ trans_choice('general.statuses', 1) .  ' :'  }}</span> <span class=" text-sm font-weight-300">{{ trans('bills.messages.status.paid.await') }}</span>
                                                        @else
                                                            <p class=" text-sm mt-1 mb-0">{{ trans_choice('general.statuses', 1) . ': ' . trans('general.partially_paid') }}</p>
                                                        @endif
                                                    @stack('timeline_body_make_payment_body_message_end')

                                                    <div class="mt-3">
                                                        @stack('timeline_body_make_payment_body_button_payment_start')
                                                            @if(empty($bill->payments->count()) || (!empty($bill->payments->count()) && $bill->paid != $bill->amount))
                                                                <a href="{{ url('expenses/bills/' . $bill->id . '/pay') }}" class="btn btn-success btn-sm btn-alone">{{ trans('bills.add_payment') }}</a>
                                                            @endif
                                                        @stack('timeline_body_make_payment_body_button_payment_end')
                                                    </div>
                                                @stack('timeline_body_make_payment_body_end')
                                            </div>
                                        </div>
                                    @stack('timeline_body_make_payment_end')
                                </div>
                            </div>
                        </div>
                    </div>
                @stack('timeline_body_end')
            </div>
        @endif
    @stack('timeline_end')

    @stack('bill_start')
        <div class="card">
            @stack('bill_status_start')
                <div class="card-header status-{{ $bill->status->label }}">
                    <h3 class="text-white mb-0 float-right">{{ trans('bills.status.' . $bill->status->code) }}</h3>
                </div>
            @stack('bill_status_end')

            <div class="card-body">
                @stack('bill_header_start')
                    <div class="row mx--4">
                        <div class="col-md-7">
                            <div class="table-responsive mt-2">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <th>
                                                @if (isset($bill->contact->logo) && !empty($bill->contact->logo->id))
                                                    <img src="{{ Storage::url($bill->contact->logo->id) }}"/>
                                                @else
                                                    <span class="avatar avatar-size rounded-circle bg-primary">
                                                        <i class="fas fa-building"></i>
                                                    </span>
                                                @endif
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-5 border-bottom-1">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <th>
                                                {{ setting('company.name') }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                {!! nl2br(setting('company.address')) !!}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                @if (setting('company.tax_number'))
                                                    {{ trans('general.tax_number') }}: {{ setting('company.tax_number') }}
                                                @endif
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                @if (setting('company.phone'))
                                                    {{ setting('company.phone') }}
                                                @endif
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                {{ setting('company.email') }}
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @stack('bill_header_end')

                @stack('bill_information_start')
                    <div class="row">
                        <div class="col-md-7">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <th>
                                                {{ trans('bills.bill_from') }}
                                                @stack('name_input_start')
                                                    <strong class="d-block">{{ $bill->contact_name }}</strong>
                                                @stack('name_input_end')
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                @stack('address_input_start')
                                                    {!! nl2br($bill->contact_address) !!}
                                                @stack('address_input_end')
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                @stack('tax_number_input_start')
                                                    @if ($bill->contact_tax_number)
                                                        {{ trans('general.tax_number') }}: {{ $bill->contact_tax_number }}
                                                    @endif
                                                @stack('tax_number_input_end')
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                @stack('phone_input_start')
                                                    @if ($bill->contact_phone)
                                                        {{ $bill->contact_phone }}
                                                    @endif
                                                @stack('phone_input_end')
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                @stack('email_start')
                                                    {{ $bill->contact_email }}
                                                @stack('email_input_end')
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        @stack('bill_number_input_start')
                                            <tr>
                                                <th>{{ trans('bills.bill_number') }}:</th>
                                                <td class="text-right">{{ $bill->bill_number }}</td>
                                            </tr>
                                        @stack('bill_number_input_end')

                                        @stack('order_number_input_start')
                                            @if ($bill->order_number)
                                                <tr>
                                                    <th>{{ trans('bills.order_number') }}:</th>
                                                    <td class="text-right">{{ $bill->order_number }}</td>
                                                </tr>
                                            @endif
                                        @stack('order_number_input_end')

                                        @stack('billed_at_input_start')
                                            <tr>
                                                <th>{{ trans('bills.bill_date') }}:</th>
                                                <td class="text-right">@date($bill->billed_at)</td>
                                            </tr>
                                        @stack('billed_at_input_end')

                                        @stack('due_at_input_start')
                                            <tr>
                                                <th>{{ trans('bills.payment_due') }}:</th>
                                                <td class="text-right">@date($bill->due_at)</td>
                                            </tr>
                                        @stack('due_at_input_end')
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @stack('bill_information_end')

                @stack('bill_item_start')
                    <div class="row show-table">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr class="row">
                                            @stack('name_th_start')
                                                <th class="col-xs-4 col-sm-3 pl-5">{{ trans_choice('general.items', 1) }}</th>
                                            @stack('name_th_end')

                                            @stack('quantity_th_start')
                                                <th class="col-xs-4 col-sm-3 text-center">{{ trans('bills.quantity') }}</th>
                                            @stack('quantity_th_end')

                                            @stack('price_th_start')
                                                <th class="col-sm-3 text-center hidden-sm pl-5">{{ trans('bills.price') }}</th>
                                            @stack('price_th_end')

                                            @stack('total_th_start')
                                                <th class="col-xs-4 col-sm-3 text-right pr-5">{{ trans('bills.total') }}</th>
                                            @stack('total_th_end')
                                        </tr>
                                        @foreach($bill->items as $item)
                                            <tr class="row">
                                                @stack('name_td_start')
                                                    <td class="col-xs-4 col-sm-3 pl-5">{{ $item->name }}</td>
                                                @stack('name_td_end')

                                                @stack('quantity_td_start')
                                                    <td class="col-xs-4 col-sm-3 text-center">{{ $item->quantity }}</td>
                                                @stack('quantity_td_end')

                                                @stack('price_td_start')
                                                    <td class="col-sm-3 text-center hidden-sm pl-5">@money($item->price, $bill->currency_code, true)</td>
                                                @stack('price_td_end')

                                                @stack('total_td_start')
                                                    <td class="col-xs-4 col-sm-3 text-right pr-5">@money($item->total, $bill->currency_code, true)</td>
                                                @stack('total_td_end')
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @stack('bill_item_end')

                @stack('bill_total_start')
                    <div class="row">
                        <div class="col-md-7">
                            @stack('notes_input_start')
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tbody>
                                            @if ($bill->notes)
                                                <tr>
                                                    <th>
                                                        <p class="form-control-label">{{ trans_choice('general.notes', 2) }}:</p>
                                                        <p class="form-control text-muted show-note">{{ $bill->notes }}</p>
                                                    </th>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            @stack('notes_input_end')
                        </div>
                        <div class="col-md-5">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        @foreach ($bill->totals as $total)
                                            @if ($total->code != 'total')
                                                @stack($total->code . '_td_start')
                                                    <tr>
                                                        <th>{{ trans($total->title) }}:</th>
                                                        <td class="text-right">@money($total->amount, $bill->currency_code, true)</td>
                                                    </tr>
                                                @stack($total->code . '_td_end')
                                            @else
                                                @if ($bill->paid)
                                                    <tr class="text-success">
                                                        <th>{{ trans('bills.paid') }}:</th>
                                                        <td class="text-right">- @money($bill->paid, $bill->currency_code, true)</td>
                                                    </tr>
                                                @endif
                                                @stack('grand_total_td_start')
                                                    <tr>
                                                        <th>{{ trans($total->name) }}:</th>
                                                        <td class="text-right">@money($total->amount - $bill->paid, $bill->currency_code, true)</td>
                                                    </tr>
                                                @stack('grand_total_td_end')
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @stack('bill_total_end')
            </div>

            @stack('box_footer_start')
                <div class="card-footer">
                    <div class="float-right">
                        @stack('button_edit_start')
                            @if(!$bill->reconciled)
                                <a href="{{ url('expenses/bills/' . $bill->id . '/edit') }}" class="btn btn-info header-button-top">
                                    <i class="fas fa-edit"></i>&nbsp; {{ trans('general.edit') }}
                                </a>
                            @endif
                        @stack('button_edit_end')

                        @stack('button_print_start')
                            <a href="{{ url('expenses/bills/' . $bill->id . '/print') }}" target="_blank" class="btn btn-success header-button-top">
                                <i class="fa fa-print"></i>&nbsp; {{ trans('general.print') }}
                            </a>
                        @stack('button_print_end')

                        @stack('button_group_start')
                            <div class="dropup">
                                <button type="button" class="btn btn-primary header-button-top" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-chevron-up"></i>&nbsp; {{ trans('general.more_actions') }}</button>
                                <div class="dropdown-menu" role="menu">
                                    @stack('button_pay_start')
                                        @if($bill->status->code != 'paid')
                                            @if(empty($bill->paid) || ($bill->paid != $bill->amount))
                                                <a class="dropdown-item" href="#" id="button-payment">{{ trans('bills.add_payment') }}</a>
                                            @endif
                                            @permission('update-expenses-bills')
                                                @if($bill->bill_status_code == 'draft')
                                                    <a class="dropdown-item" href="{{ url('expenses/bills/' . $bill->id . '/received') }}">{{ trans('bills.mark_received') }}</a></a>
                                                @else
                                                    <button type="button" class="dropdown-item" disabled="disabled">{{ trans('bills.mark_received') }}</button>
                                                @endif
                                            @endpermission
                                            <div class="dropdown-divider"></div>
                                        @endif
                                    @stack('button_pay_end')

                                    @stack('button_pdf_start')
                                        <a class="dropdown-item" href="{{ url('expenses/bills/' . $bill->id . '/pdf') }}">{{ trans('bills.download_pdf') }}</a>
                                    @stack('button_pdf_end')

                                    @stack('button_delete_start')
                                        @permission('delete-expenses-bills')
                                            @if(!$bill->reconciled)
                                                {!! Form::deleteLink($bill, 'expenses/bills') !!}
                                            @endif
                                        @endpermission
                                    @stack('button_delete_end')
                                </div>
                            </div>
                        @stack('button_group_end')

                        @if($bill->attachment)
                            @php $file = $bill->attachment; @endphp
                            @include('partials.media.file')
                        @endif
                    </div>
                </div>
            @stack('box_footer_end')
        </div>
    @stack('bill_end')

    @stack('row_footer_start')
        <div class="row">
            @stack('row_footer_history_start')
                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <div class="accordion">
                        <div class="card">
                            <div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                <h4 class="mb-0">{{ trans('bills.histories') }}</h4>
                            </div>
                            <div id="collapseOne" class="collapse hide" aria-labelledby="headingOne">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead class="thead-light">
                                                <tr class="table-head-line">
                                                    <th>{{ trans('general.date') }}</th>
                                                    <th>{{ trans_choice('general.statuses', 1) }}</th>
                                                    <th>{{ trans('general.description') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($bill->histories as $history)
                                                    <tr>
                                                        <td>@date($history->created_at)</td>
                                                        <td class="text-center">{{ $history->status->name }}</td>
                                                        <td class="text-center">{{ $history->description }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @stack('row_footer_history_end')

            @stack('row_footer_payment_start')
                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <div class="accordion">
                        <div class="card">
                            <div class="card-header" id="headingTwo" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                <h4 class="mb-0">{{ trans('bills.payments') }}</h4>
                            </div>
                            <div id="collapseTwo" class="collapse hide" aria-labelledby="headingTwo">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead class="thead-light">
                                                <tr class="table-head-line">
                                                    <th>{{ trans('general.date') }}</th>
                                                    <th>{{ trans('general.amount') }}</th>
                                                    <th>{{ trans_choice('general.accounts', 1) }}</th>
                                                    <th>{{ trans('general.actions') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($bill->payments->count())
                                                    @foreach($bill->payments as $payment)
                                                        <tr>
                                                            <td>@date($item->paid_at)</td>
                                                            <td>@money($payment->amount, $payment->currency_code, true)</td>
                                                            <td>{{ $payment->account->name }}</td>
                                                            <td>
                                                                @if ($payment->reconciled)
                                                                    <button type="button" class="btn btn-secondary btn-sm">
                                                                        <i class="fa fa-check"></i> {{ trans('reconciliations.reconciled') }}
                                                                    </button>
                                                                @else
                                                                    {!! Form::open([
                                                                        'id' => 'bill-payment-' . $payment->id,
                                                                        'method' => 'DELETE',
                                                                        'route' => ['payments.destroy', $payment->id],
                                                                        'style' => 'display:inline'
                                                                    ]) !!}
                                                                    {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> ' . trans('general.delete'), array(
                                                                        'type'    => 'button',
                                                                        'class'   => 'btn btn-danger btn-xs',
                                                                        'title'   => trans('general.delete'),
                                                                        'onclick' => 'confirmDelete("' . '#bill-payment-' . $payment->id . '", "' . trans_choice('general.payments', 2) . '", "' . trans('general.delete_confirm', ['name' => '<strong>' . Date::parse($payment->paid_at)->format($date_format) . ' - ' . money($payment->amount, $payment->currency_code, true) . ' - ' . $payment->account->name . '</strong>', 'type' => strtolower(trans_choice('general.payments', 1))]) . '", "' . trans('general.cancel') . '", "' . trans('general.delete') . '")'
                                                                    )) !!}
                                                                    {!! Form::close() !!}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="4">
                                                            <div class="text-muted" id="datatable-basic_info" role="status" aria-live="polite">
                                                               {{ trans('general.no_records') }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @stack('row_footer_payment_end')
        </div>
    @stack('row_footer_end')
@endsection
