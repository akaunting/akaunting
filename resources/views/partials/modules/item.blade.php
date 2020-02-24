<div class="col-md-3">
    <div class="card">
        <div class="card-header py-2">
            <h4 class="ml--3 mb-0 float-left">
                <a href="{{ route('apps.app.show', $module->slug) }}">{{ $module->name }}</a>
            </h4>
            @if (isset($installed[$module->slug]))
                @php $color = 'bg-green'; @endphp

                @if (!$installed[$module->slug])
                    @php $color = 'bg-yellow'; @endphp
                @endif
                <span class="mr--3 float-right">
                    <span class="badge {{ $color }} text-white">{{ trans('modules.badge.installed') }}</span>
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
                            {!! $module->price_prefix !!}
                        @if (isset($module->special_price))
                            <del class="text-danger">{{ $module->price }}</del>
                            {{ $module->special_price }}
                        @else
                            {{ $module->price }}
                        @endif
                            {!! $module->price_suffix !!}
                        @endif
                    </strong>
                </small>
            </div>
        </div>
    </div>
</div>
