{!! Form::open([
    'id' => 'form-item-column',
    'method' => 'PATCH',
    'route' => 'modals.documents.item-columns.update',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'files' => true,
    'role' => 'form',
    'class' => 'form-loading-button',
    'novalidate' => true,
]) !!}
    <div class="row">
        {{ Form::invoice_text('item_name', trans('settings.invoice.item_name'), 'font', $item_names, $item_name, [], 'item_name_input', $item_name_input, 'col-md-12') }}

        {{ Form::invoice_text('price_name', trans('settings.invoice.price_name'), 'font', $price_names, $price_name, [], 'price_name_input', $price_name_input, 'col-md-12') }}

        {{ Form::invoice_text('quantity_name', trans('settings.invoice.quantity_name'), 'font', $quantity_names, $quantity_name, [], 'quantity_name_input', $quantity_name_input, 'col-md-12') }}

        {{ Form::radioGroup('hide_item_name', trans('settings.invoice.hide.item_name'), $hide_item_name) }}

        {{ Form::radioGroup('hide_item_description', trans('settings.invoice.hide.item_description'), $hide_item_description) }}

        {{ Form::radioGroup('hide_quantity', trans('settings.invoice.hide.quantity'), $hide_quantity) }}

        {{ Form::radioGroup('hide_price', trans('settings.invoice.hide.price'), $hide_price) }}

        {{ Form::radioGroup('hide_amount', trans('settings.invoice.hide.amount'), $hide_amount) }}

        {!! Form::hidden('type', $type) !!}
    </div>
{!! Form::close() !!}
