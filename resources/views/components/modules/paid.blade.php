@if ($modules)
    <x-modules.items
        title="{{ trans('modules.top_paid') }}"
        route="apps.paid"
        :model="$modules"
    />
@endif
