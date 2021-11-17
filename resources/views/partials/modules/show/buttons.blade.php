@if ($installed)
    @can('delete-modules-item')
        <a href="{{ route('apps.app.uninstall', $module->slug) }}" class="btn btn-block btn-danger">
            {{ trans('modules.button.uninstall') }}
        </a>
    @endcan

    @can('update-modules-item')
        @if ($enable)
            <a href="{{ route('apps.app.disable', $module->slug) }}" class="btn btn-block btn-warning">
                {{ trans('modules.button.disable') }}
            </a>
        @else
            <a href="{{ route('apps.app.enable', $module->slug) }}" class="btn btn-block btn-success">
                {{ trans('modules.button.enable') }}
            </a>
        @endif
    @endcan
@else
    @can('create-modules-item')
        @if ($module->install)
            @if (!empty($module->isPurchase) && (!empty($module->purchase_type) && $module->purchase_type == 'monthly'))
                <el-tooltip placement="right">
                    <div slot="content">{!! trans('modules.can_not_install') !!}</div>

                    <button type="button" class="btn btn-success btn-block btn-tooltip disabled">
                        <span class="text-disabled">{{ trans('modules.install') }}</span>
                    </button>
                </el-tooltip>
            @else
                <button type="button" 
                    id="install-module"
                    class="btn btn-success btn-block"
                    @click="onInstall('{{ $module->action_url }}', '{{ $module->slug }}', '{{ $module->name }}', '{{ $module->version }}')"
                >
                    {{ trans('modules.install') }}
                </button>
            @endif
        @else
            <a href="{{ $module->action_url }}" class="btn btn-success btn-block" target="_blank">
                {{ trans('modules.buy_now') }}
            </a>
        @endif
    @endcan
@endif

@if (!empty($module->purchase_desc))
    <div class="text-center mt-3 d-none">
        {!! $module->purchase_desc !!}
    </div>
@endif
