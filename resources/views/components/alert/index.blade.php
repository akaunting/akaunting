<div 
    @class([
        'rounded-md' => $rounded,
        'border-l-4 border-' . $color . '-400' => $border,
        'bg-' . $color . '-50' => $color != 'green',
        'bg-' . $color . '-100' => $color == 'green',
        'p-4 my-4',
    ])
    x-data
>
    <div class="flex">
        <div class="flex-shrink-0">
            <x-icon icon="{{ $icon }}" sharp class="h-5 w-5 text-{{ $color }}-400" />
        </div>

        <div class="ml-3">
            @if ($title)
                <h3 class="text-sm font-medium text-{{ $color }}-800">
                    {!! $title !!}
                </h3>
            @endif

            @if ($description || $list)
                <div class="mt-2 text-sm text-{{ $color }}-700">
                    <p>{!! $description !!}</p>

                    @if ($list)
                        <ul role="list" class="list-disc space-y-1 pl-5">
                            @foreach ($list as $message)
                                <li>{!! $message !!}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

            @if ($message)
                <p class="text-sm font-medium text-{{ $color }}-800 inline-block align-middle">
                    {!! $message !!}
                </p>
            @endif

            @if ($actions) 
                <div class="mt-4">
                    <div class="-mx-2 -my-1.5 flex">
                        @foreach ($actions as $action)
                            @if ($action['type'] == 'button')
                                <button type="button"
                                    {{ $action['attributes'] }}
                                    @class([
                                        'ml-3' => ! $loop->first,
                                        'rounded-md',
                                        'bg-' . $color . '-50',
                                        'px-2 py-1.5',
                                        'text-sm font-medium',
                                        'text-' . $color . '-800 hover:bg-' . $color . '-100',
                                        'focus:outline-none focus:ring-2',
                                        'focus:ring-' . $color . '-600',
                                        'focus:ring-offset-2',
                                        'focus:ring-offset-' . $color . '-50',
                                    ])
                                >
                                    {{ $action['text'] }}
                                </button>
                            @else
                                <a href="{{ $action['url'] }}"
                                    @class([
                                        'ml-3' => ! $loop->first,
                                        'rounded-md',
                                        'bg-' . $color . '-50',
                                        'px-2 py-1.5',
                                        'text-sm font-medium',
                                        'text-' . $color . '-800 hover:bg-' . $color . '-100',
                                        'focus:outline-none focus:ring-2',
                                        'focus:ring-' . $color . '-600',
                                        'focus:ring-offset-2',
                                        'focus:ring-offset-' . $color . '-50',
                                    ])
                                >
                                    {{ $action['text'] }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        @if ($dismiss)
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button type="button"
                        x-on:click="$el.remove()"
                        @class([
                            'inline-flex',
                            'rounded-md',
                            'bg-' . $color . '-50' => $color != 'green',
                            'bg-' . $color . '-100' => $color == 'green',
                            'p-1.5',
                            'text-sm font-medium',
                            'text-' . $color . '-500 hover:bg-' . $color . '-100',
                            'focus:outline-none focus:ring-2',
                            'focus:ring-' . $color . '-600',
                            'focus:ring-offset-2',
                            'focus:ring-offset-' . $color . '-50',
                        ])
                    >
                        <span class="sr-only">{{ trans('general.dismiss') }}</span>

                        <x-icon icon="close" sharp class="h-5 w-5 text-{{ $color }}-400" />
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
