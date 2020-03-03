@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('general.invoices', 1)]))

@section('content')
    <div class="card">
        {!! Form::open([
            'route' => 'invoices.store',
            'id' => 'invoice',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button',
            'novalidate' => true
        ]) !!}

            <div class="card-body">
                <div class="row">
                    {{ Form::selectAddNewGroup('contact_id', trans_choice('general.customers', 1), 'user', $customers, config('general.customers'), ['required' => 'required', 'path' => route('modals.customers.create'), 'change' => 'onChangeContact']) }}

                    {{ Form::selectAddNewGroup('currency_code', trans_choice('general.currencies', 1), 'exchange-alt', $currencies, setting('default.currency'), ['required' => 'required', 'path' => route('modals.currencies.create'), 'change' => 'onChangeCurrency']) }}

                    {{ Form::dateGroup('invoiced_at', trans('invoices.invoice_date'), 'calendar', ['id' => 'invoiced_at', 'class' => 'form-control datepicker', 'required' => 'required', 'date-format' => 'Y-m-d', 'autocomplete' => 'off'], request()->get('invoiced_at', Date::now()->toDateString())) }}

                    {{ Form::dateGroup('due_at', trans('invoices.due_date'), 'calendar', ['id' => 'due_at', 'class' => 'form-control datepicker', 'required' => 'required', 'date-format' => 'Y-m-d', 'autocomplete' => 'off'], request()->get('due_at', Date::parse(request()->get('invoiced_at', Date::now()->toDateString()))->addDays(setting('invoice.payment_terms', 0))->toDateString())) }}

                    {{ Form::textGroup('invoice_number', trans('invoices.invoice_number'), 'file', ['required' => 'required'], $number) }}

                    {{ Form::textGroup('order_number', trans('invoices.order_number'), 'shopping-cart', []) }}

                    <div class="col-sm-12 mb-4">
                        {!! Form::label('items', trans_choice($text_override['items'], 2), ['class' => 'form-control-label']) !!}
                        <div class="table-responsive overflow-x-scroll overflow-y-hidden">
                            <table class="table table-bordered" id="items">
                                <thead class="thead-light">
                                    <tr>
                                        @stack('actions_th_start')
                                            <th class="text-center border-right-0 border-bottom-0">{{ trans('general.actions') }}</th>
                                        @stack('actions_th_end')

                                        @stack('name_th_start')
                                            <th class="text-left border-right-0 border-bottom-0">{{ trans('general.name') }}</th>
                                        @stack('name_th_end')

                                        @stack('quantity_th_start')
                                            <th class="text-center border-right-0 border-bottom-0 w-10">{{ trans($text_override['quantity']) }}</th>
                                        @stack('quantity_th_end')

                                        @stack('price_th_start')
                                            <th class="text-right border-right-0 border-bottom-0">{{ trans($text_override['price']) }}</th>
                                        @stack('price_th_end')

                                        @stack('taxes_th_start')
                                            <th class="text-right border-right-0 border-bottom-0">{{ trans_choice('general.taxes', 1) }}</th>
                                        @stack('taxes_th_end')

                                        @stack('total_th_start')
                                            <th class="text-right border-bottom-0 item-total">{{ trans('invoices.total') }}</th>
                                        @stack('total_th_end')
                                    </tr>
                                </thead>
                                <tbody id="invoice-item-rows">
                                    @include('sales.invoices.item')

                                    @stack('add_item_td_start')
                                        <tr id="addItem">
                                            <td class="text-center border-right-0 border-bottom-0">
                                                <button type="button" @click="onAddItem" id="button-add-item" data-toggle="tooltip" title="{{ trans('general.add') }}" class="btn btn-icon btn-outline-success btn-lg" data-original-title="{{ trans('general.add') }}"><i class="fa fa-plus"></i>
                                                </button>
                                            </td>
                                            <td class="text-right border-bottom-0" colspan="5"></td>
                                        </tr>
                                    @stack('add_item_td_end')

                                    @stack('sub_total_td_start')
                                        <tr id="tr-subtotal">
                                            <td class="text-right border-right-0 border-bottom-0" colspan="5">
                                                <strong>{{ trans('invoices.sub_total') }}</strong>
                                            </td>
                                            <td class="text-right border-bottom-0 long-texts">
                                                {{ Form::moneyGroup('sub_total', '', '', ['disabled' => true, 'required' => 'required', 'v-model' => 'totals.sub', 'currency' => $currency, 'masked' => 'true'], 0.00, 'text-right d-none') }}
                                                <span id="sub-total" v-if="totals.sub" v-html="totals.sub"></span>
                                                <span v-else>@money(0, $currency->code, true)</span>
                                            </td>
                                        </tr>
                                    @stack('sub_total_td_end')

                                    @stack('add_discount_td_start')
                                        <tr id="tr-discount">
                                            <td class="text-right border-right-0 border-bottom-0" colspan="5">
                                                <el-popover
                                                    popper-class="p-0 h-0"
                                                    placement="bottom"
                                                    width="300"
                                                    v-model="discount">
                                                    <div class="card d-none" :class="[{'show' : discount}]">
                                                        <div class="discount card-body">
                                                            <div class="row align-items-center">
                                                                <div class="col-sm-6">
                                                                    <div class="input-group input-group-merge">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text" id="input-discount">
                                                                                <i class="fa fa-percent"></i>
                                                                            </span>
                                                                        </div>
                                                                        {!! Form::number('pre_discount', null, ['id' => 'pre-discount', 'class' => 'form-control']) !!}
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="discount-description">
                                                                        <strong>{{ trans('invoices.discount_desc') }}</strong>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="discount card-footer">
                                                            <div class="row float-right">
                                                                <div class="col-xs-12 col-sm-12">
                                                                    <a href="javascript:void(0)" @click="discount = false" class="btn btn-outline-secondary header-button-top" @click="closePayment">
                                                                        {{ trans('general.cancel') }}
                                                                    </a>
                                                                    {!! Form::button(trans('general.save'), ['type' => 'button', 'id' => 'save-discount', '@click' => 'onAddDiscount', 'class' => 'btn btn-success header-button-top']) !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <el-link class="cursor-pointer text-info" slot="reference" type="primary" v-if="!totals.discount_text">{{ trans('invoices.add_discount') }}</el-link>
                                                    <el-link slot="reference" type="primary" v-if="totals.discount_text" v-html="totals.discount_text"></el-link>
                                                </el-popover>
                                            </td>
                                            <td class="text-right border-bottom-0">
                                                {{ Form::moneyGroup('discount_total', '', '', ['disabled' => true, 'required' => 'required', 'v-model' => 'totals.discount', 'currency' => $currency, 'masked' => 'true'], 0.00, 'text-right d-none') }}
                                                <span id="discount-total" v-if="totals.discount" v-html="totals.discount"></span>
                                                <span v-else>@money(0, $currency->code, true)</span>
                                                {!! Form::hidden('discount', null, ['id' => 'discount', 'class' => 'form-control text-right', 'v-model' => 'form.discount']) !!}
                                            </td>
                                        </tr>
                                    @stack('add_discount_td_end')

                                    @stack('tax_total_td_start')
                                        <tr id="tr-tax">
                                            <td class="text-right border-right-0 border-bottom-0" colspan="5">
                                                <strong>{{ trans_choice('general.taxes', 1) }}</strong>
                                            </td>
                                            <td class="text-right border-bottom-0 long-texts">
                                                {{ Form::moneyGroup('tax_total', '', '', ['disabled' => true, 'required' => 'required', 'v-model' => 'totals.tax', 'currency' => $currency, 'masked' => 'true'], 0.00, 'text-right d-none') }}
                                                <span id="tax-total" v-if="totals.tax" v-html="totals.tax"></span>
                                                <span v-else>@money(0, $currency->code, true)</span>
                                            </td>
                                        </tr>
                                    @stack('tax_total_td_end')

                                    @stack('grand_total_td_start')
                                        <tr id="tr-total">
                                            <td class="text-right border-right-0" colspan="5">
                                                <strong>{{ trans('invoices.total') }}</strong>
                                            </td>
                                            <td class="text-right long-texts">
                                                {{ Form::moneyGroup('grand_total', '', '', ['disabled' => true, 'required' => 'required', 'v-model' => 'totals.total', 'currency' => $currency, 'masked' => 'true'], 0.00, 'text-right d-none') }}
                                                <span id="grand-total" v-if="totals.total" v-html="totals.total"></span>
                                                <span v-else>@money(0, $currency->code, true)</span>
                                            </td>
                                        </tr>
                                    @stack('grand_total_td_end')
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{ Form::textareaGroup('notes', trans_choice('general.notes', 2), '', setting('invoice.notes'), ['rows' => '3'], 'col-md-6') }}

                    {{ Form::textareaGroup('footer', trans('general.footer'), '', setting('invoice.footer'), ['rows' => '3'], 'col-md-6') }}

                    {{ Form::selectAddNewGroup('category_id', trans_choice('general.categories', 1), 'folder', $categories, setting('defaults.category'), ['required' => 'required', 'path' => route('modals.categories.create') . '?type=income']) }}

                    {{ Form::recurring('create') }}

                    {{ Form::fileGroup('attachment', trans('general.attachment')) }}

                    {{ Form::hidden('contact_name', old('contact_name'), ['id' => 'contact_name', 'v-model' => 'form.contact_name']) }}
                    {{ Form::hidden('contact_email', old('contact_email'), ['id' => 'contact_email', 'v-model' => 'form.contact_email']) }}
                    {{ Form::hidden('contact_tax_number', old('contact_tax_number'), ['id' => 'contact_tax_number', 'v-model' => 'form.contact_tax_number']) }}
                    {{ Form::hidden('contact_phone', old('contact_phone'), ['id' => 'contact_phone', 'v-model' => 'form.contact_phone']) }}
                    {{ Form::hidden('contact_address', old('contact_address'), ['id' => 'contact_address', 'v-model' => 'form.contact_address']) }}
                    {{ Form::hidden('currency_rate', old('currency_rate', 1), ['id' => 'currency_rate', 'v-model' => 'form.contact_rate']) }}
                    {{ Form::hidden('status', old('status', 'draft'), ['id' => 'status', 'v-model' => 'form.status']) }}
                    {{ Form::hidden('amount', old('amount', '0'), ['id' => 'amount', 'v-model' => 'form.amount']) }}
                </div>
            </div>

            <div class="card-footer">
                <div class="row save-buttons">
                    {{ Form::saveButtons('invoices.index') }}
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script type="text/javascript">
        var invoice_items = false;
    </script>

    <script src="{{ asset('public/js/sales/invoices.js?v=' . version('short')) }}"></script>
@endpush
