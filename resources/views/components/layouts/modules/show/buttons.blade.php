@props(['module', 'installed', 'enable'])

@if (! empty($module->plan))
    <x-tooltip message="{{ trans('modules.hosted_on_akaunting') }}" placement="top" width="w-full">
        <x-link
            href="{{ $module->action_url }}"
            target="_blank"
            class="bg-green hover:bg-green-700 rounded-md text-white text-sm text-center w-full flex items-center justify-center px-3 py-2 truncate"
            override="class"
        >
            {{ trans('modules.switch_to_cloud') }}
        </x-link>
    </x-tooltip>
@elseif (in_array('onprime', $module->where_to_use))
    @if ($installed)
        @can('delete-modules-item')
            <x-link
                href="{{ route('apps.app.uninstall', $module->slug) }}"
                class="bg-red rounded-md text-white text-sm text-center w-1/2 py-2 truncate"
                override="class"
            >
                <x-link.loading>
                    {{ trans('modules.button.uninstall') }}
                </x-link.loading>
            </x-link>
        @endcan

        @can('update-modules-item')
            @if ($enable)
                <x-link
                    href="{{ route('apps.app.disable', $module->slug) }}"
                    class="bg-orange rounded-md text-white text-sm text-center w-1/2 py-2 truncate"
                    override="class"
                >
                    <x-link.loading>
                        {{ trans('modules.button.disable') }}
                    </x-link.loading>
                </x-link>
            @else
                <x-link
                    href="{{ route('apps.app.enable', $module->slug) }}"
                    class="bg-green rounded-md text-white text-sm text-center w-1/2 py-2 truncate"
                    override="class"
                >
                    <x-link.loading>
                        {{ trans('modules.button.enable') }}
                    </x-link.loading>
                </x-link>
            @endif
        @endcan
    @else
        @can('create-modules-item')
            @if ($module->install)
                @if (! empty($module->isPurchase) && (! empty($module->purchase_type) && $module->purchase_type == 'monthly'))
                    <x-tooltip message="{!! trans('modules.can_not_install', ['app' => $module->name]) !!}" placement="right">
                        <x-button disabled="disabled">
                            {{ trans('modules.install') }}
                        </x-button>
                    </x-tooltip>
                @else
                    <button type="button"
                        @click="onInstall('{{ $module->action_url }}', '{{ $module->slug }}', '{!! str_replace("'", "\'", $module->name) !!}', '{{ $module->version }}')"
                        class="bg-green hover:bg-green-700 disabled:bg-green-100 rounded-md text-white text-sm text-center w-full py-2 truncate"
                        id="install-module"
                        :disabled="installation.show"
                    >
                        <x-button.loading action="installation.show">
                            {{ trans('modules.install') }}
                        </x-button.loading>
                    </button>
                @endif
            @else
                <x-link
                    href="{{ $module->action_url }}"
                    target="_blank"
                    class="bg-green hover:bg-green-700 rounded-md text-white text-sm text-center w-full py-2 truncate"
                    override="class"
                >
                    {{ trans('modules.use_app') }}
                </x-link>
            @endif
        @endcan
    @endif
@else
    @if ($module->install)
        <x-tooltip message="{{ trans('modules.hosted_on_akaunting') }}" placement="top" width="w-full">
            <x-link
                href="{{ $module->action_url }}"
                target="_blank"
                class="bg-green hover:bg-green-700 rounded-md text-white text-sm text-center w-full flex items-center justify-center px-3 py-2 truncate"
                override="class"
            >
                {{ trans('modules.switch_to_cloud') }}
            </x-link>
        </x-tooltip>
    @else
        <x-tooltip message="{{ trans('modules.hosted_on_akaunting') }}" placement="top" width="w-full">
            <x-link
                href="{{ $module->action_url }}"
                target="_blank"
                class="bg-green hover:bg-green-700 rounded-md text-white text-sm text-center w-full flex items-center justify-center px-3 py-2 truncate"
                override="class"
            >
                {{ trans('modules.switch_to_cloud') }}
            </x-link>
        </x-tooltip>
    @endif
@endif
