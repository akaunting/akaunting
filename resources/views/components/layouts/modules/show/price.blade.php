@props(['module'])

@if ($module->price != '0.0000')
    <div class="flex flex-col 2xl:flex-row gap-2 items-baseline cursor-default">
        {!! $module->price_prefix !!}

        <div x-show="price_type == 'monthly'">
            @if (! empty($module->is_discount))
                <del class="text-red mr-2">
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
        </div>

        <div x-show="price_type == 'yearly'">
            @if (! empty($module->is_discount))
                <del class="text-red mr-2">
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
        </div>

        <div x-show="price_type == 'lifetime'">
            @if (! empty($module->is_discount))
                <del class="text-red mr-2">
                    {!! $module->lifetime_price !!}
                </del>

                <span class="text-5xl font-bold text-purple">
                    {!! $module->lifetime_special_price !!}
                </span>
            @else
                <span class="text-5xl font-bold text-purple">
                    {!! $module->lifetime_price !!}
                </span>
            @endif
        </div>

        {!! $module->price_suffix !!}

        <span x-show="price_type != 'lifetime'" class="font-thin">
            {{ trans('modules.per_month') }}
        </span>

        <span x-show="price_type == 'lifetime'" class="font-thin lowercase">
            {{ trans('modules.once') }}
        </span>
    </div>
@else
    <span class="text-4xl font-bold text-purple">
        {{ trans('modules.free') }}
    </span>
@endif
