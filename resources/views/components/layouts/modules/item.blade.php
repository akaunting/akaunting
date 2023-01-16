<div>
    <div>
        @foreach ($module->files as $file)
            <x-link href="{{ route('apps.app.show', $module->slug) }}" override="class">
                @if (($file->media_type == 'image') && ($file->pivot->zone == 'thumbnail'))
                    <img src="{{ $file->path_string }}" alt="{{ $module->name }}" class="rounded-md" />
                @endif
            </x-link>
        @endforeach
    </div>

    <div class="flex flex-col py-2 justify-between align-bottom">
        <div class="flex items-baseline justify-between">
            <h4 class="w-32 truncate">
                <x-link href="{{ route('apps.app.show', $module->slug) }}" override="class">
                    {{ $module->name }}
                </x-link>
            </h4>

            <div class="text-xs">
                @if ($module->price == '0.0000')
                    <span class="font-bold text-purple">
                        {{ trans('modules.free') }}
                    </span>
                @else
                    {!! $module->price_prefix !!}

                    @if (isset($module->special_price))
                        <del class="text-danger">
                            {{ $module->price }}
                        </del>

                        {{ $module->special_price }}
                    @else
                        {{ $module->price }}
                    @endif

                    {!! $module->price_suffix !!}
                @endif
            </div>
        </div>

        <div class="flex items-baseline justify-between">
            <div class="flex">
                @if($module->vote > 0)
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
        </div>
    </div>
</div>
