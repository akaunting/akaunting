@props(['module'])

@if ($module->price != '0.0000')
    <div class="flex gap-2 items-baseline">
        {!! $module->price_prefix !!}

        <div x-show="price_type == true">
            @if (! empty($module->is_discount))
                <del class="text-red">
                    {!! $module->yearly_per_monthly_price !!}
                </del>

                <span class="text-5xl font-bold text-purple">
                    {!! $module->yearly_per_monthly_special_price !!}
                </span>
            @else
                <span class="text-5xl font-bold text-purple">
                    {!! $module->yearly_per_monthly_price !!}
                </span>
            @endif

            <div class="text-center text-sm mt-3 mb--2">
                <span style="height: 18px;display: block;"></span>
            </div>
        </div>

        <div x-show="price_type == false">
            @if (! empty($module->is_discount))
                <del class="text-red">
                    {!! $module->monthly_price !!}
                </del>

                <span class="text-5xl font-bold text-purple">
                    {!! $module->monthly_special_price !!}
                </span>
            @else
                <span class="text-5xl font-bold text-purple">
                    {!! $module->monthly_price !!} 
                </span>
            @endif

            <div class="text-center text-sm mt-3 mb--2">
                <span style="font-size: 12px;">
                    <span class="text-danger">*</span> {!! trans('modules.information_monthly') !!}
                </span>
            </div>
        </div>

        {!! $module->price_suffix !!}

        <span class="font-thin">
            {{ trans('modules.per_month') }}
        </span>
    </div>
@else
    <span class="text-4xl font-bold text-purple">
        {{ trans('modules.free') }}
    </span>
@endif
