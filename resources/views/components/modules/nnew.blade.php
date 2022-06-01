@if ($modules)
    <x-modules.items
        title="{{ trans('modules.new') }}"
        route="apps.new"
        :model="$modules"
    />
@endif
