@props(['module'])

@if (! empty($module->plan))
    <div x-show="price_type == 'monthly' || price_type == 'yearly'" class="text-center text-sm mt-3 mb--2 bg-red-100 rounded-lg p-2 cursor-default">
        <span x-show="price_type == 'monthly'" class="text-sm text-red-700">
            {!! trans('modules.information_on_preme', [
                    'period'    => trans('modules.monthly'),
                    'url'       => 'https://akaunting.com/features/why-akaunting-cloud?utm_source=software&utm_medium=app_show&utm_campaign=' . str_replace('-', '_', $module->slug) 
            ]) !!}
        </span>

        <span x-show="price_type == 'yearly'" class="text-sm text-red-700">
            {!! trans('modules.information_on_preme', [
                    'period'    => trans('modules.yearly'),
                    'url'       => 'https://akaunting.com/features/why-akaunting-cloud?utm_source=software&utm_medium=app_show&utm_campaign=' . str_replace('-', '_', $module->slug) 
            ]) !!}
        </span>
    </div>
@elseif (in_array('onprime', $module->where_to_use))
    <div x-show="price_type == 'monthly'" class="text-center text-sm mt-3 mb--2 bg-red-100 rounded-lg p-2 cursor-default">
        <span x-show="price_type == 'monthly'" class="text-sm text-red-700">
            {!! trans('modules.information_on_preme', [
                    'period'    => trans('modules.monthly'),
                    'url'       => 'https://akaunting.com/features/why-akaunting-cloud?utm_source=software&utm_medium=app_show&utm_campaign=' . str_replace('-', '_', $module->slug) 
            ]) !!}
        </span>
    </div>
@else
    <div x-show="price_type == 'monthly' || price_type == 'yearly'" class="text-center text-sm mt-3 mb--2 bg-red-100 rounded-lg p-2 cursor-default">
        <span x-show="price_type == 'monthly'" class="text-sm text-red-700">
            {!! trans('modules.information_on_preme', [
                    'period'    => trans('modules.monthly'),
                    'url'       => 'https://akaunting.com/features/why-akaunting-cloud?utm_source=software&utm_medium=app_show&utm_campaign=' . str_replace('-', '_', $module->slug) 
            ]) !!}
        </span>

        <span x-show="price_type == 'yearly'" class="text-sm text-red-700">
            {!! trans('modules.information_on_preme', [
                    'period'    => trans('modules.yearly'),
                    'url'       => 'https://akaunting.com/features/why-akaunting-cloud?utm_source=software&utm_medium=app_show&utm_campaign=' . str_replace('-', '_', $module->slug) 
            ]) !!}
        </span>
    </div>
@endif
