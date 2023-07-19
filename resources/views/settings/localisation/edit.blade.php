<x-layouts.admin>
    <x-slot name="title">{{ trans_choice('general.localisations', 1) }}</x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="setting" method="PATCH" route="settings.localisation.update">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.financial_year') }}" description="{{ trans('settings.localisation.form_description.fiscal') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.date name="financial_start" label="{{ trans('settings.localisation.financial_start') }}" icon="calendar_today" value="{{ setting('localisation.financial_start') }}" show-date-format="j F" date-format="d-m" autocomplete="off" hidden_year />

                        <x-form.group.select name="financial_denote" label="{{ trans('settings.localisation.financial_denote.title') }}" :options="$financial_denote_options" :clearable="'false'" :selected="setting('localisation.financial_denote')" not-required />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('settings.localisation.preferred_date') }}" description="{{ trans('settings.localisation.form_description.date') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.select name="date_format" label="{{ trans('settings.localisation.date.format') }}" :options="$date_formats" :clearable="'false'" :selected="setting('localisation.date_format')" autocomplete="off" />

                        <x-form.group.select name="date_separator" label="{{ trans('settings.localisation.date.separator') }}" :options="$date_separators" :clearable="'false'" :selected="setting('localisation.date_separator')" />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans_choice('general.others', 1) }}" description="{{ trans('settings.localisation.form_description.other') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.select group name="timezone" label="{{ trans('settings.localisation.timezone') }}" :options="$timezones" :clearable="'false'" :selected="setting('localisation.timezone')" />

                        <x-form.group.select name="percent_position" label="{{ trans('settings.localisation.percent.title') }}" :options="$percent_positions" :clearable="'false'" :selected="setting('localisation.percent_position')" not-required />

                        <x-form.group.select name="discount_location" label="{{ trans('settings.localisation.discount_location.name') }}" :options="$discount_locations" :clearable="'false'" :selected="setting('localisation.discount_location')" not-required />
                    </x-slot>
                </x-form.section>

                @can('update-settings-localisation')
                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons :cancel="url()->previous()" />
                    </x-slot>
                </x-form.section>
                @endcan

                <x-form.input.hidden name="_prefix" value="localisation" />
            </x-form>
        </x-form.container>
    </x-slot>

    <x-script folder="settings" file="settings" />
</x-layouts.admin>
