<div>
    <div class="flex justify-end py-2">
        @if ($module->payment_type)
            @if ($module->payment_type == 'yearly')
                @php
                    $color = 'bg-purple-100'; 
                    $text = trans('general.yearly');
                @endphp
            @else
                @php
                    $color = 'bg-purple-100';
                    $text = trans('general.monthly');
                @endphp
            @endif
        @endif

        <span class="inline-flex items-center justify-center text-xs px-4 py-1 font-semibold leading-none rounded-md {{ $color }}">
            {{ $text }}
        </span>
    </div>

    <div>
        @foreach ($module->files as $file)
            <a href="{{ route('apps.app.show', $module->slug) }}">
                @if (($file->media_type == 'image') && ($file->pivot->zone == 'thumbnail'))
                    <img src="{{ $file->path_string }}" alt="{{ $module->name }}" class="rounded-md" />
                @endif
            </a>
        @endforeach
    </div>

    <div class="flex py-2 justify-between items-center">
        <div class="py-2">
            <h4 class="truncate font-bold text-sm">
                <a href="{{ route('apps.app.show', $module->slug) }}">
                    {{ $module->name }}
                </a>
            </h4>
        </div>

        <div>
            @if ($module->status_type == 'active')
                @php
                    $color = 'bg-green-300';
                    $text = trans('general.enabled');
                @endphp
            @else
                @php
                    $color = 'bg-red';
                    $text = trans('general.disabled');
                @endphp
            @endif

            <span class="inline-flex items-center justify-center text-xs px-4 py-1 font-semibold leading-none rounded-md {{ $color }} text-white">
                {{ $text }}
            </span>
        </div>
    </div>
</div>
