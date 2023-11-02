<x-layouts.admin>
    <x-slot name="title">{{ trans('general.title.edit', ['type' => trans_choice('general.reports', 1)]) }}</x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="report" method="PATCH" :route="['reports.update', $report->id]" :model="$report">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.general') }}" description="" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.text name="name" label="{{ trans('general.name') }}" />

                        <x-form.group.text name="class_disabled" label="{{ trans_choice('general.types', 1) }}" value="{{ $classes[$report->class] }}" disabled />

                        <x-form.input.hidden name="class" :value="$report->class" />

                        <x-form.group.textarea name="description" label="{{ trans('general.description') }}" />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans_choice('reports.preferences', 2) }}" description="" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.input.hidden name="report" value="invalid" data-field="settings" />

                        @foreach($class->getFields() as $field)
                            @php $type = $field['type']; @endphp

                            @switch($type)
                                @case('text')
                                @case('textGroup')
                                    <x-form.group.text
                                        name="{{ $field['name'] }}"
                                        label="{{ $field['title'] }}"
                                        data-field="settings"
                                        :attributes="$field['attributes']"
                                        :dynamic-attributes="$field['attributes']"
                                    />
                                    @break

                                @case('email')
                                @case('emailGroup')
                                    <x-form.group.email
                                        name="{{ $field['name'] }}"
                                        label="{{ $field['title'] }}"
                                        data-field="settings"
                                        :attributes="$field['attributes']"
                                        :dynamic-attributes="$field['attributes']"
                                    />
                                    @break

                                @case('password')
                                @case('passwordGroup')
                                    <x-form.group.password
                                        name="{{ $field['name'] }}"
                                        label="{{ $field['title'] }}"
                                        data-field="settings"
                                        :attributes="$field['attributes']"
                                        :dynamic-attributes="$field['attributes']"
                                    />
                                    @break

                                @case('textarea')
                                @case('textareaGroup')
                                    <x-form.group.textarea
                                        name="{{ $field['name'] }}"
                                        label="{{ $field['title'] }}"
                                        data-field="settings"
                                        :attributes="$field['attributes']"
                                        :dynamic-attributes="$field['attributes']"
                                    />
                                    @break

                                @case('date')
                                @case('dateGroup')
                                    <x-form.group.date
                                        name="{{ $field['name'] }}"
                                        label="{{ $field['title'] }}"
                                        :value="isset($report->settings->{$field['name']}) ? $report->settings->{$field['name']}: null"
                                        data-field="settings"
                                        :attributes="array_merge([
                                            'model' => 'form.settings'.'.'.$field['name'],
                                            'show-date-format' => company_date_format(),
                                        ], $field['attributes'])"
                                        :dynamic-attributes="$field['attributes']"
                                    />
                                    @break

                                @case('select')
                                @case('selectGroup')
                                    <x-form.group.select
                                        name="{{ $field['name'] }}"
                                        label="{{ $field['title'] }}"
                                        :options="$field['values']"
                                        sort-options="false"
                                        :selected="isset($report->settings->{$field['name']}) ? $report->settings->{$field['name']} : $field['selected']"
                                        data-field="settings"
                                        :attributes="$field['attributes']"
                                        :dynamic-attributes="$field['attributes']"
                                    />
                                    @break

                                @case('radio')
                                @case('radioGroup')
                                    <x-form.group.radio
                                        name="{{ $field['name'] }}"
                                        label="{{ $field['title'] }}"
                                        :attributes="array_merge([
                                            'data-field' => 'settings'
                                        ], $field['attributes'])"
                                        :dynamic-attributes="$field['attributes']"
                                    />
                                    @break

                                @case('checkbox')
                                @case('checkboxGroup')
                                    <x-form.group.checkbox
                                        name="{{ $field['name'] }}"
                                        label="{{ $field['title'] }}"
                                        :attributes="array_merge([
                                            'data-field' => 'settings'
                                        ], $field['attributes'])"
                                        :dynamic-attributes="$field['attributes']"
                                    />
                                    @break

                                @default
                                    <x-form.group.text
                                        name="{{ $field['name'] }}"
                                        label="{{ $field['title'] }}"
                                        :attributes="array_merge([
                                            'data-field' => 'settings'
                                        ], $field['attributes'])"
                                        :dynamic-attributes="$field['attributes']"
                                    />
                            @endswitch
                        @endforeach
                    </x-slot>
                </x-form.section>

                @can('update-common-reports')
                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons cancel-route="reports.index" />
                    </x-slot>
                </x-form.section>
                @endcan
            </x-form>
        </x-form.container>
    </x-slot>

    <x-script folder="common" file="reports" />
</x-layouts.admin>
