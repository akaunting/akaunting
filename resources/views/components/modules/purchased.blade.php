@if ($modules)
    <x-modules.items
        title="{{ trans('modules.my.purchased') }}"
        :model="$modules"
    />
@endif
