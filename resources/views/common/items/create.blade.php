@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('general.items', 1)]))

@section('content')
    <div class="card">
        {!! Form::open([
            'route' => 'items.store',
            'id' => 'item',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button',
            'novalidate' => true
        ]) !!}

            <div class="card-body">
                <div class="row">
                    {{ Form::textGroup('name', trans('general.name'), 'tag') }}

                    {{ Form::selectAddNewGroup('tax_id', trans_choice('general.taxes', 1), 'percentage', $taxes, setting('default.tax'), ['path' => route('modals.taxes.create')]) }}

                    {{ Form::textareaGroup('description', trans('general.description')) }}

                    {{ Form::textGroup('sale_price', trans('items.sales_price'), 'money-bill-wave') }}

                    {{ Form::textGroup('purchase_price', trans('items.purchase_price'), 'money-bill-wave-alt') }}

                    {{ Form::selectAddNewGroup('category_id', trans_choice('general.categories', 1), 'folder', $categories, null, ['path' => route('modals.categories.create') . '?type=item']) }}

                    {{ Form::fileGroup('picture', trans_choice('general.pictures', 1), 'plus') }}

                    {{ Form::radioGroup('enabled', trans('general.enabled'), true) }}
                </div>
            </div>

            <div class="card-footer">
                <div class="row save-buttons">
                    {{ Form::saveButtons('items.index') }}
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/common/items.js?v=' . version('short')) }}"></script>
@endpush
