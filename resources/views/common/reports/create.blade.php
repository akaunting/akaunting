<x-layouts.admin>
    <x-slot name="title">{{ trans('general.title.new', ['type' => trans_choice('general.reports', 1)]) }}</x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="report" route="reports.store">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.general') }}" description="" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.text name="name" label="{{ trans('general.name') }}" />

                        <x-form.group.select name="class" label="{{ trans_choice('general.types', 1) }}" :options="$classes" change="onChangeClass" />

                        <x-form.group.textarea name="description" label="{{ trans('general.description') }}" />
                    </x-slot>
                </x-form.section>

                <x-form.section v-if="showPreferences">
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans_choice('reports.preferences', 2) }}" description="" />
                    </x-slot>

                    <x-slot name="body">
                        <component v-bind:is="report_fields" @change="onChangeReportFields"></component>
                    </x-slot>
                </x-form.section>

                <x-form.input.hidden name="report" value="invalid" data-field="settings" />

                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons cancel-route="reports.index" />
                    </x-slot>
                </x-form.section>
            </x-form>
        </x-form.container>
    </x-slot>

    <x-script folder="common" file="reports" />
</x-layouts.admin>
