@props(['module'])

@if (! empty($module->plan))
    <div class="text-center text-sm mt-3 mb--2 bg-red-100 rounded-lg p-2 cursor-default">
        <span class="text-sm text-red-700">
            {!! trans('modules.only_premium_plan') !!}
        </span>
    </div>
@elseif (in_array('onprime', $module->where_to_use))
    <div x-show="price_type == 'monthly'" class="text-center text-sm mt-3">
        <span style="font-size: 12px;">
            <span class="text-red">*</span> 
            {!! trans('modules.information_monthly', ['url' => 'https://akaunting.com/features/why-akaunting-cloud?utm_source=software&utm_medium=app_show&utm_campaign=' . str_replace('-', '_', $module->slug)]) !!}
        </span>
    </div>
@else
    <div class="text-center text-sm mt-3 mb--2 bg-red-100 rounded-lg p-2 cursor-default">
        <span class="text-sm text-red-700">
            {!! trans('modules.only_works_cloud') !!}
        </span>
    </div>
@endif
