@extends('layouts.admin')

@section('title', trans_choice('general.notifications', 2))

@section('new_button')
    <a href="{{ route('notifications.read-all') }}" class="btn btn-outline-success rounded-circle btn-icon-only btn-sm" data-toggle="tooltip" data-placement="right" title="{{ trans('notifications.mark_read_all') }}">
        <span class="btn-inner--icon"><i class="fas fa-check-double pt-2"></i></span>
    </a>
@endsection

@section('content')
    @stack('new_apps')

    <livewire:common.notifications.new-apps />

    @stack('exports')

    <livewire:common.notifications.exports />

    @stack('imports')

    <livewire:common.notifications.imports />

    @stack('invoices_recurring')

    <livewire:common.notifications.recurring type="invoice" text-title="notifications.recurring_invoices" />

    @stack('invoices_reminder')

    <livewire:common.notifications.reminder type="invoice" text-title="widgets.overdue_invoices" />

    @stack('bills_recurring')

    <livewire:common.notifications.recurring type="bill" text-title="notifications.recurring_bills" />

    @stack('bills_reminder')

    <livewire:common.notifications.reminder type="bill" text-title="notifications.upcoming_bills" />

    @stack('end')
@endsection

@push('body_js')
    <script type="text/javascript">
        var hash_split = location.hash.split('#');

        if (hash_split[1] != undefined && document.getElementById(hash_split[1]) != null) {
            document.getElementById(hash_split[1]).scrollIntoView({
                behavior: 'smooth'
            });

            document.getElementById('collapse-' + hash_split[1]).classList.add('show');
            document.getElementById('heading-' + hash_split[1]).ariaExpanded = 'true';
        }
    </script>
@endpush

@push('scripts_start')
    <script src="{{ asset('public/vendor/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
@endpush