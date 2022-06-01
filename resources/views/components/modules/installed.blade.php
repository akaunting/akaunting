@if ($modules)
    <x-modules.items
        title="{{ trans('modules.my.installed') }}"
        :model="$modules"
    />
@endif
