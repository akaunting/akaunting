<h2>{{ $gateway['name'] }}</h2>

@if($gateway['mode'] == 'sandbox')
<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> {{ trans('paypalstandard::general.test_mode') }}</div>
@endif

<div class="well well-sm">
    {{ trans('paypalstandard::general.description') }}
</div>

<form action="{{ $gateway['action'] }}" method="post">
    <input type="hidden" name="cmd" value="_cart" />
    <input type="hidden" name="upload" value="1" />
    <input type="hidden" name="business" value="{{ $gateway['email'] }}" />
    <?php $i = 1; ?>
    @foreach ($invoice->items as $item)
    <input type="hidden" name="item_name_{{ $i }}" value="{{ $item->name }}" />
    @if($item->sku)
    <input type="hidden" name="item_number_{{ $i }}" value="{{ $item->sku }}" />
    @endif
    <input type="hidden" name="amount_{{ $i }}" value="{{ $item->price }}" />
    <input type="hidden" name="quantity_{{ $i }}" value="{{ $item->quantity }}" />
    <?php $i++; ?>
    @endforeach
    <input type="hidden" name="currency_code" value="{{ $invoice->currency_code}}" />
    <input type="hidden" name="first_name" value="{{ $invoice->first_name }}" />
    <input type="hidden" name="last_name" value="{{ $invoice->last_name }}" />
    <input type="hidden" name="address1" value="{{ $invoice->customer_address }}" />
    <input type="hidden" name="address_override" value="0" />
    <input type="hidden" name="email" value="{{ $invoice->customer_email }}" />
    <input type="hidden" name="invoice" value="{{ $invoice->id . '-' . $invoice->customer_name }}" />
    <input type="hidden" name="lc" value="{{ $gateway['language'] }}" />
    <input type="hidden" name="rm" value="2" />
    <input type="hidden" name="no_note" value="1" />
    <input type="hidden" name="no_shipping" value="1" />
    <input type="hidden" name="charset" value="utf-8" />
    <input type="hidden" name="return" value="{{ url('customers/invoices/' . $invoice->id . '/paypalstandard/result') }}" />
    <input type="hidden" name="notify_url" value="{{ url('customers/invoices/' . $invoice->id . '/paypalstandard/callback') }}" />
    <input type="hidden" name="cancel_return" value="{{ url('customers/invoices/' . $invoice->id) }}" />
    <input type="hidden" name="paymentaction" value="{{ $gateway['transaction'] }}" />
    <input type="hidden" name="custom" value="{{ $invoice->id }}" />
    <input type="hidden" name="bn" value="Akaunting_1.0_WPS" />
    <div class="buttons">
        <div class="pull-right">
            <input type="submit" value="{{ trans('paypalstandard::general.confirm') }}" class="btn btn-success" />
        </div>
    </div>
</form>
