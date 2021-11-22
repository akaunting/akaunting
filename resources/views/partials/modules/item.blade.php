<div class="col-md-3">
    <div class="card">
        <div class="card-header py-2">
            <h4 class="ml--3 mb-0 float-left">
                <a href="{{ route('apps.app.show', $module->slug) }}">{!! $module->name !!}</a>
            </h4>

            @if (!empty($module->subscription_type))
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

            @if (isset($installed[$module->slug]))
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
        </div>

        <a href="{{ route('apps.app.show', $module->slug) }}">
            @foreach ($module->files as $file)
                @if (($file->media_type == 'image') && ($file->pivot->zone == 'thumbnail'))
                    <img src="{{ $file->path_string }}" alt="{{ $module->name }}" class="card-img-top border-radius-none">
                @endif
            @endforeach
        </a>

        <div class="card-footer py-2">
            <div class="float-left ml--3 mt--1">
                @for($i = 1; $i <= $module->vote; $i++)
                    <i class="fa fa-star text-xs text-yellow"></i>
                @endfor

                @for($i = $module->vote; $i < 5; $i++)
                    <i class="far fa-star text-xs"></i>
                @endfor

                <small class="text-xs">
                    @if ($module->total_review)
                        ({{ $module->total_review }})
                    @endif
                </small>
            </div>

            <div class="float-right mr--3">
                <small>
                    <strong>
                        @if ($module->price == '0.0000')
                            {{ trans('modules.free') }}
                        @else
                            @if (isset($module->is_discount))
                                {!! trans('modules.monthly_price', ['price' => '<del class="text-danger">' . $module->yearly_per_monthly_price . '</del>' . $module->yearly_per_monthly_special_price]) !!}
                            @else
                                {!! trans('modules.monthly_price', ['price' => $module->yearly_per_monthly_price]) !!}
                            @endif
                        @endif
                    </strong>
                </small>
            </div>
        </div>
    </div>
</div>
