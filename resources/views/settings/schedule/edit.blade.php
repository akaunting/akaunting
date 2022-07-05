<x-layouts.admin>
    <x-slot name="title">{{ trans('settings.scheduling.name') }}</x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="setting" method="PATCH" route="settings.schedule.update">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans_choice('general.invoices', 1) }}" description="{{ trans('settings.scheduling.form_description.invoice') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.toggle name="send_invoice_reminder" label="{{ trans('settings.scheduling.send_invoice') }}" :value="setting('schedule.send_invoice_reminder')" not-required />

                        <x-form.group.text name="invoice_days" label="{{ trans('settings.scheduling.invoice_days') }}" value="{{ setting('schedule.invoice_days') }}" not-required />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans_choice('general.bills', 1) }}" description="{{ trans('settings.scheduling.form_description.bill') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.toggle name="send_bill_reminder" label="{{ trans('settings.scheduling.send_bill') }}" :value="setting('schedule.send_bill_reminder')" not-required />

                        <x-form.group.text name="bill_days" label="{{ trans('settings.scheduling.bill_days') }}" value="{{ setting('schedule.bill_days') }}" not-required />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('settings.scheduling.cron_command') }}" description="{{ trans('settings.scheduling.form_description.cron') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <div class="sm:col-span-6">
                            <x-form.label for="cron_command">
                                {{ trans('settings.scheduling.command') }}
                            </x-form.label>
                            <input type="text" class="w-full text-sm px-3 py-2.5 mt-1 rounded-lg border border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple" disabled value="php {{ base_path('artisan') }} schedule:run >> /dev/null 2>&1">
                        </div>

                        <x-form.group.text name="time" label="{{ trans('settings.scheduling.schedule_time') }}" value="{{ setting('schedule.time') }}" not-required />
                    </x-slot>
                </x-form.section>

                @can('update-settings-schedule')
                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons :cancel="url()->previous()" />
                    </x-slot>
                </x-form.section>
                @endcan

                <x-form.input.hidden name="_prefix" value="schedule" />
            </x-form>
        </x-form.container>
    </x-slot>

    <x-script folder="settings" file="settings" />
</x-layouts.admin>
