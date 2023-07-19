<x-layouts.admin>
    <x-slot name="title">{{ $module->getName() }}</x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="setting" method="PATCH" :route="['settings.module.update', $module->getAlias()]" :model="$setting">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.general') }}" description="{!! trans($module->getAlias() . '::general.description') !!}" />
                    </x-slot>

                    <x-slot name="body">
                        @foreach($module->get('settings') as $field)
                            @php $type = $field['type']; @endphp

                            @switch($type)
                                @case('textarea')
                                @case('textareaGroup')
                                    <x-form.group.textarea
                                        name="{{ $field['name'] }}"
                                        label="{{ trans($field['title']) }}"
                                        :dynamic-attributes="$field['attributes']"
                                    />
                                    @break
                                @case('select')
                                @case('selectGroup')
                                    <x-form.group.select
                                        name="{{ $field['name'] }}"
                                        label="{{ trans($field['title']) }}"
                                        :options="$field['values']"
                                        :clearable="'false'"
                                        :selected="setting($module->getAlias() . '.' . $field['name'], $field['selected'])"
                                        :dynamic-attributes="$field['attributes']"
                                    />
                                    @break
                                @case('radio')
                                @case('radioGroup')
                                    <x-form.group.radio
                                        name="{{ $field['name'] }}"
                                        label="{{ trans($field['title']) }}"
                                        :dynamic-attributes="$field['attributes']"
                                    />
                                    @break
                                @case('checkbox')
                                @case('checkboxGroup')
                                    <x-form.group.checkbox
                                        name="{{ $field['name'] }}"
                                        label="{{ trans($field['title']) }}"
                                        :dynamic-attributes="$field['attributes']"
                                    />
                                    @break
                                @case('file')
                                @case('fileGroup')
                                    <x-form.group.file
                                        name="{{ $field['name'] }}"
                                        label="{{ trans($field['title']) }}"
                                        :dynamic-attributes="$field['attributes']"
                                    />
                                    @break
                                @case('date')
                                @case('dateGroup')
                                    <x-form.group.date
                                        name="{{ $field['name'] }}"
                                        label="{{ trans($field['title']) }}"
                                        :value="Date::parse($setting[$field['name']] ?? now())->toDateString()"
                                        :dynamic-attributes="$field['attributes']"
                                    />
                                    @break
                                @case('account')
                                @case('accountSelectGroup')
                                    @php $account = setting($module->getAlias() . '.' . $field['name']); @endphp

                                    <x-form.group.account
                                        :selected="$account"
                                        :clearable="'false'"
                                        :dynamic-attributes="$field['attributes']"
                                        without-add-new
                                    />
                                    @break
                                @case('category')
                                @case('categorySelectGroup')
                                    @php $category = setting($module->getAlias() . '.' . $field['name']); @endphp

                                    <x-form.group.category
                                        :value="$category"
                                        :clearable="'false'"
                                        :dynamic-attributes="$field['attributes']"
                                        without-add-new
                                    />
                                    @break
                                @default
                                    @php
                                        $type = str_replace('Group', '', $type);
                                        $componentName = 'form.group.' . $type;
                                    @endphp

                                    <x-dynamic-component :component="$componentName" name="{{ $field['name'] }}" label="{{ trans($field['title']) }}" :dynamic-attributes="$field['attributes']" />
                            @endswitch
                        @endforeach

                        <x-form.input.hidden name="module_alias" :value="$module->getAlias()" />
                    </x-slot>
                </x-form.section>

                @can('update-' . $module->getAlias() . '-settings')
                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons :cancel="url()->previous()" />
                    </x-slot>
                </x-form.section>
                @endcan
            </x-form>
        </x-form.container>
    </x-slot>

    <x-script folder="settings" file="settings" />
</x-layouts.admin>
