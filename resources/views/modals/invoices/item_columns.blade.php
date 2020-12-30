{!! Form::open([
    'id' => 'form-item-column',
    'method' => 'PATCH',
    'route' => 'modals.invoices.item-columns.update',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'files' => true,
    'role' => 'form',
    'class' => 'form-loading-button',
    'novalidate' => true,
]) !!}
    <div class="row">
        {{ Form::invoice_text('item_name', trans('settings.invoice.item_name'), 'font', $item_names, setting('invoice.item_name'), [], 'item_name_input', setting('invoice.item_name_input', null), 'col-md-12') }}

        {{ Form::invoice_text('price_name', trans('settings.invoice.price_name'), 'font', $price_names, setting('invoice.price_name'), [], 'price_name_input', setting('invoice.price_name_input', null), 'col-md-12') }}

        {{ Form::invoice_text('quantity_name', trans('settings.invoice.quantity_name'), 'font', $quantity_names, setting('invoice.quantity_name'), [], 'quantity_name_input', setting('invoice.quantity_name_input', null), 'col-md-12') }}

        {{ Form::radioGroup('hide_item_name', trans('settings.invoice.hide.item_name'), setting('invoice.hide_item_name', null)) }}

        {{ Form::radioGroup('hide_item_description', trans('settings.invoice.hide.item_description'), setting('invoice.hide_item_description', null)) }}

        {{ Form::radioGroup('hide_quantity', trans('settings.invoice.hide.quantity'), setting('invoice.hide_quantity', null)) }}

        {{ Form::radioGroup('hide_price', trans('settings.invoice.hide.price'), setting('invoice.hide_price', null)) }}

        {{ Form::radioGroup('hide_amount', trans('settings.invoice.hide.amount'), setting('invoice.hide_amount', null)) }}

        {!! Form::hidden('_prefix', 'invoice') !!}
    </div>
{!! Form::close() !!}
