<x-layouts.admin>
    <x-slot name="title">
        {{ trans('modules.api_key') }}
    </x-slot>

    <x-slot name="content">
        <x-form id="form-app" route="apps.api-key.store">
            <x-form.section spacing-vertical="gap-y-2">
                <x-slot name="body">
                    <x-form.group.text name="api_key" placeholder="{{ trans('general.form.enter', ['field' => trans('modules.api_key')]) }}" value="{{ setting('apps.api_key', null) }}" />

                    <div class="sm:col-span-6">
                        <div class="text-xs">
                            {!! trans('modules.get_api_key', ['url' => 'https://akaunting.com/dashboard']) !!}
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <x-form.buttons cancel-route="apps.home.index" without-cancel />
                    </div>
                </x-slot>
            </x-form.section>
        </x-form>
    </x-slot>

    <x-script folder="modules" file="apps" />
</x-layouts.admin>
