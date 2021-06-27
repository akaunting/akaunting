@extends('layouts.portal')

@section('title', setting('invoice.title', trans_choice('general.invoices', 1)) . ': ' . $invoice->document_number)

@section('new_button')
    @stack('button_print_start')
    <a href="{{ route('portal.invoices.print', $invoice->id) }}" target="_blank" class="btn btn-white btn-sm">
        {{ trans('general.print') }}
    </a>
    @stack('button_print_end')

    @stack('button_pdf_start')
    <a href="{{ route('portal.invoices.pdf', $invoice->id) }}" class="btn btn-white btn-sm">
        {{ trans('general.download') }}
    </a>
    @stack('button_pdf_end')
@endsection

@section('content')
    <x-documents.show.header
        type="invoice"
        :document="$invoice"
        hide-header-contact
        class-header-status="col-md-8"
    />

    @if (!empty($payment_methods) && !in_array($invoice->status, ['paid', 'cancelled']))
    <div class="row">
        <div class="col-md-12">
            {!! Form::open([
                'id' => 'invoice-payment',
                'role' => 'form',
                'autocomplete' => "off",
                'novalidate' => 'true',
                'class' => 'mb-0'
            ]) !!}
                {{ Form::selectGroup('payment_method', '', 'money el-icon-money', $payment_methods, array_key_first($payment_methods), ['change' => 'onChangePaymentMethod', 'id' => 'payment-method', 'class' => 'form-control d-none', 'placeholder' => trans('general.form.select.field', ['field' => trans_choice('general.payment_methods', 1)])], 'col-sm-12 d-none') }}
                {!! Form::hidden('document_id', $invoice->id, ['v-model' => 'form.document_id']) !!}
            {!! Form::close() !!}

            <div class="nav-wrapper">
                <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-payment-method" role="tablist">
                    @php $is_active = true; @endphp

                    @foreach ($payment_methods as $key => $name)
                        @stack('invoice_{{ $key }}_tab_start')
                        <li class="nav-item">
                            <a @click="onChangePaymentMethod('{{ $key }}')" class="nav-link mb-sm-3 mb-md-0{{ ($is_active) ? ' active': '' }}" id="tabs-payment-method-{{ $key }}-tab" data-toggle="tab" href="#tabs-payment-method-{{ $key }}" role="tab" aria-controls="tabs-payment-method-{{ $key }}" aria-selected="true">
                                {{ $name }}
                            </a>
                        </li>
                        @stack('invoice_{{ $key }}_tab_end')

                        @php $is_active = false; @endphp
                    @endforeach
                </ul>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <div class="tab-content" id="myTabContent">
                        @php $is_active = true; @endphp

                        @foreach ($payment_methods as $key => $name)
                            @stack('invoice_{{ $key }}_content_start')
                            <div class="tab-pane fade{{ ($is_active) ? ' show active': '' }}" id="tabs-payment-method-{{ $key }}" role="tabpanel" aria-labelledby="tabs-payment-method-{{ $key }}-tab">
                                <component v-bind:is="method_show_html" @interface="onRedirectConfirm"></component>
                            </div>
                            @stack('invoice_{{ $key }}_content_end')

                            @php $is_active = false; @endphp
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <x-documents.show.document
        type="invoice"
        :document="$invoice"
        document-template="{{ setting('invoice.template', 'default') }}"
    />
@endsection

@push('scripts_start')
    <link rel="stylesheet" href="{{ asset('public/css/print.css?v=' . version('short')) }}" type="text/css">
    <script src="{{ asset('public/js/portal/invoices.js?v=' . version('short')) }}"></script>
@endpush
