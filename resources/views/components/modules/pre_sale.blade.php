@if ($modules)
    <x-modules.items
        title="{{ trans('modules.pre_sale') }}"
        route="apps.pre_sale"
        :model="$modules"
    />
@endif
