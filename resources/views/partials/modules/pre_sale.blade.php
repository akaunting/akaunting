<div class="col-md-3">
    <div class="card">
        <div class="card-header py-2">
            <div class="float-left ml--3">
                <h4 class="mb-0"><a href="{{ route('apps.app.show', $module->slug) }}">{{ $module->name }}</a></h4>
            </div>
            <div class="float-right mr--3">
                <span class="badge badge-pill badge-danger">{{ trans('modules.badge.pre_sale') }}</span>
            </div>
        </div>
        <a href="{{ route('apps.app.show', [module->slug) }}">
            @foreach ($module->files as $file)
                @if (($file->media_type == 'image') && ($file->pivot->zone == 'thumbnail'))
                    <img src="{{ $file->path_string }}" alt="{{ $module->name }}"  class="card-img-top card-border">
                @endif
            @endforeach
        </a>
        <div class="card-footer py-2">
            <div class="float-left ml--3 mt-1">
                <small class="text-sm">
                    {{ trans('modules.pre_sale') }}
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
