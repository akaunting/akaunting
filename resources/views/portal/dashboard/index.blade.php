<x-layouts.portal>
    <x-slot name="title">
        {{ trans_choice('general.dashboards', 1) }}
    </x-slot>

    <x-slot name="content">
        <div class="grid sm:grid-cols-3 my-10 divide-y lg:divide-y-0 lg:divide-x space-y-10 lg:space-y-0">
            <div class="lg:ltr:pr-12 lg:rtl:pl-12">
                <x-widgets.contact />

                @stack('column_left')
            </div>

            <div class="lg:px-12 pt-10 lg:pt-0 space-y-12">
                <x-widgets.last-payment />

                <x-widgets.outstanding-balance />

                @stack('column_center')
            </div>

            <div class="lg:ltr:pl-12 lg:rtl:pr-12 pt-10 lg:pt-0 space-y-12">
                <x-widgets.latest-invoices />

                <x-widgets.invoice-history />

                <x-widgets.payment-history />

                @stack('column_right')
            </div>
        </div>
    </x-slot>
</x-layouts.portal>
