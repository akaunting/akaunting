<div>
    <div class="overflow-hidden rounded-md">
        @foreach ($module->files as $file)
            <x-link href="{{ route('apps.app.show', $module->slug) }}" override="class">
                @if (($file->media_type == 'image') && ($file->pivot->zone == 'thumbnail'))
                    <img src="{{ $file->path_string }}" alt="{{ $module->name }}" class="rounded-md transform transition-all hover:scale-125" />
                @endif
            </x-link>
        @endforeach
    </div>

    <div class="flex flex-col py-2 justify-between align-bottom">
        <div class="flex items-baseline justify-between">
            <h4 class="w-32 truncate">
                <x-link href="{{ route('apps.app.show', $module->slug) }}" override="class">
                    {!! $module->name !!}
                </x-link>
            </h4>

            @if (! empty($module->subscription_type))
                <span class="mr--3 float-right">
                    @php $subscription_color = 'bg-info'; @endphp

                    @if ($module->subscription_type == 'monthly')
                        @php $subscription_color = 'bg-warning'; @endphp
                    @endif

                    <span class="badge {{ $subscription_color }} text-white">
                        @if ($module->subscription_type == 'yearly')
                            {{ trans('modules.yearly') }}
                        @else
                            {{ trans('modules.monthly') }}
                        @endif
                    </span>
                </span>
            @endif

            @if ($module->status_type == 'pre_sale')
                <span class="mr--3 float-right">
                    <span class="badge bg-danger text-white">
                        {{ trans('modules.badge.pre_sale') }}
                    </span>
                </span>
            @endif

            @if (isset($installedStatus[$module->slug]))
                @php $color = 'bg-green'; @endphp

                @if (!$installed[$module->slug])
                    @php $color = 'bg-warning'; @endphp
                @endif

                <span class="{{ !empty($module->subscription_type) ? 'mr-2' : 'mr--3' }} float-right">
                    <span class="badge {{ $color }} text-white">
                        {{ trans('modules.badge.installed') }}
                    </span>
                </span>
            @endif

            <div class="text-xs">
                @if ($module->price == '0.0000')
                    <span class="font-bold text-purple">
                        {{ trans('modules.free') }}
                    </span>
                @else
                    @if (! empty($module->is_discount))
                        {!! trans('modules.monthly_price', ['price' => '<del class="text-danger">' . $module->yearly_per_monthly_price . '</del> ' . $module->yearly_per_monthly_special_price]) !!}
                    @else
                        {!! trans('modules.monthly_price', ['price' => $module->yearly_per_monthly_price]) !!}
                    @endif
                @endif
            </div>
        </div>

        <div class="flex items-baseline justify-between">
            @if ($module->status_type != 'pre_sale')
                <div class="flex">
                    @if ($module->vote > 0)
                        @for ($i = 1; $i <= $module->vote; $i++)
                            <i class="material-icons text-xs text-orange">star</i>
                        @endfor

                        @for ($i = $module->vote; $i < 5; $i++)
                        <i class="material-icons text-xs">star_border</i>
                        @endfor
                    @endif
                 </div>

                <small class="text-xs">
                    @if ($module->total_review)
                      {{ $module->total_review }} {{ trans('modules.tab.reviews') }}
                    @endif
                </small>
            @else
                <small class="text-sm">
                    {{ trans('modules.pre_sale') }}
                </small>
            @endif
        </div>
    </div>
</div>
