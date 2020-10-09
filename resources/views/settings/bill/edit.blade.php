@extends('layouts.admin')

@section('title')
    {{ trans_choice('general.bills', 1) }}
    {{-- In some languages bill and invoice have the same translation --}}
    @if (trans_choice('general.bills', 1) == trans_choice('general.invoices', 1))
        ({{ trans_choice('general.purchases', 1) }})
    @endif
@endsection

@section('content')
    {!! Form::open([
        'id' => 'setting',
        'method' => 'PATCH',
        'route' => 'settings.update',
        '@submit.prevent' => 'onSubmit',
        '@keydown' => 'form.errors.clear($event.target.name)',
        'files' => true,
        'role' => 'form',
        'class' => 'form-loading-button',
        'novalidate' => true,
    ]) !!}

    <div class="card">
        <div class="card-body">
            <div class="row">
                {{ Form::textGroup('number_prefix', trans('settings.invoice.prefix'), 'font', [], setting('bill.number_prefix', 'BIL-')) }}

                {{ Form::textGroup('number_digit', trans('settings.invoice.digit'), 'text-width', [], setting('bill.number_digit', '5')) }}

                {{ Form::textGroup('number_next', trans('settings.invoice.next'), 'chevron-right', [], setting('bill.number_next', '1')) }}
            </div>
        </div>

        @permission('update-settings-settings')
            <div class="card-footer">
                <div class="row save-buttons">
                    {{ Form::saveButtons('settings.index') }}
                </div>
            </div>
        @endpermission
    </div>

    {!! Form::hidden('_prefix', 'bill') !!}

    {!! Form::close() !!}
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/settings/settings.js?v=' . version('short')) }}"></script>
@endpush
