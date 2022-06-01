@if ($modules)
    <x-modules.items
        title="{{ trans('modules.top_free') }}"
        route="apps.free"
        :model="$modules"
    />
@endif
