{!! Form::open([
    'route' => 'items.store',
    'id' => 'item',
    '@submit.prevent' => 'onSubmit',
    '@keydown' => 'form.errors.clear($event.target.name)',
    'role' => 'form',
    'class' => 'form-loading-button',
    'novalidate' => true
]) !!}
    <div class="row">
        {{ Form::textGroup('name', trans('general.name'), 'tag') }}

        {{ Form::multiSelectGroup('tax_ids', trans_choice('general.taxes', 1), 'percentage', $taxes, (setting('default.tax')) ? [setting('default.tax')] : null) }}

        {{ Form::textareaGroup('description', trans('general.description')) }}

        {{ Form::textGroup('sale_price', trans('items.sales_price'), 'money-bill-wave') }}

        {{ Form::textGroup('purchase_price', trans('items.purchase_price'), 'money-bill-wave-alt') }}

        {{ Form::selectRemoteGroup('category_id', trans_choice('general.categories', 1), 'folder', $categories, null, ['remote_action' => route('categories.index'). '?search=type:item']) }}

        {!! Form::hidden('enabled', '1', []) !!}
    </div>
{!! Form::close() !!}
