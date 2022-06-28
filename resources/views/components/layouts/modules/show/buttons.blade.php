@props(['module', 'installed', 'enable'])

@if (! empty($module->plan))
    <a href="{{ $module->action_url }}" class="relative bg-green hover:bg-green-700 rounded-md text-white text-sm text-center w-full py-2 truncate" target="_blank">
        <x-link.loading>    
            {{ trans('modules.get_premium_cloud') }}
        </x-link.loading>
    </a>
@elseif (in_array('onprime', $module->where_to_use))
    @if ($installed)
        @can('delete-modules-item')
            <a href="{{ route('apps.app.uninstall', $module->slug) }}" class="relative bg-red text-white rounded-md text-sm text-center w-1/2 py-2 truncate">
                <x-link.loading>    
                    {{ trans('modules.button.uninstall') }}
                </x-link.loading>
            </a>
        @endcan

        @can('update-modules-item')
            @if ($enable)
                <a href="{{ route('apps.app.disable', $module->slug) }}" class="relative bg-orange rounded-md text-white  w-1/2 text-center text-sm py-2 truncate">
                    <x-link.loading>    
                        {{ trans('modules.button.disable') }}
                    </x-link.loading>
                </a>
            @else
                <a href="{{ route('apps.app.enable', $module->slug) }}" class="relative bg-green rounded-md text-white text-sm text-center w-1/2  py-2 truncate">
                    <x-link.loading>    
                        {{ trans('modules.button.enable') }}
                    </x-link.loading>
                </a>
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
                <a href="{{ $module->action_url }}" class="relative bg-green hover:bg-green-700 rounded-md text-white text-sm text-center w-full py-2 truncate" target="_blank">
                    <x-link.loading>    
                        {{ trans('modules.use_app') }}
                    </x-link.loading>
                </a>
            @endif
        @endcan
    @endif
@else
    @if ($module->install)
        <a href="{{ $module->action_url }}" class="relative bg-green hover:bg-green-700 rounded-md text-white text-sm text-center w-full py-2 truncate" target="_blank">
            <x-link.loading>
                {{ trans('modules.install_cloud') }}
            </x-link.loading>
        </a>
    @else
        <a href="{{ $module->action_url }}" class="relative bg-green hover:bg-green-700 rounded-md text-white text-sm text-center w-full py-2 truncate" target="_blank">
            <x-link.loading>    
                {{ trans('modules.get_cloud') }}
            </x-link.loading>
        </a>
    @endif
@endif
