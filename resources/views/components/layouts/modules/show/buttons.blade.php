@props(['module', 'installed', 'enable'])

@if ($installed)
    @can('delete-modules-item')
        <a href="{{ route('apps.app.uninstall', $module->slug) }}" class="bg-red text-white rounded-md text-sm text-center w-1/2 py-2 truncate">
            {{ trans('modules.button.uninstall') }}
        </a>
    @endcan

    @can('update-modules-item')
        @if ($enable)
            <a href="{{ route('apps.app.disable', $module->slug) }}" class="bg-orange rounded-md text-white  w-1/2 text-center text-sm py-2 truncate">
                {{ trans('modules.button.disable') }}
            </a>
        @else
            <a href="{{ route('apps.app.enable', $module->slug) }}" class="bg-green rounded-md text-white text-sm text-center w-1/2  py-2 truncate">
                {{ trans('modules.button.enable') }}
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
                    class="bg-green hover:bg-green-700 rounded-md text-white text-sm text-center w-full py-2 truncate"
                    id="install-module"
                >
                    {{ trans('modules.install') }}
                </button>
            @endif
        @else
            <a href="{{ $module->action_url }}" class="bg-green hover:bg-green-700 rounded-md text-white text-sm text-center w-full py-2 truncate" target="_blank">
                {{ trans('modules.use_app') }}
            </a>
        @endif
    @endcan
@endif
