<div class="w-full lg:w-7/12 flex items-center mt-5 lg:mt-0">
    @if (! empty($slot) && $slot->isNotEmpty())
        {!! $slot !!}
    @elseif (! empty($items))
        @foreach ($items as $item)
            <div @class(['w-1/2 sm:w-1/3 text-center'])>
                @if (! empty($item['tooltip']))
                    <x-tooltip id="tooltip-summary-first" placement="top" message="{!! $first->attributes->get('tooltip') !!}">
                        <a href="{{ $item['href'] }}" class="group">
                            @php $text_color = (! empty($item['text_color'])) ? $item['text_color'] : 'text-purple group-hover:text-purple-700'; @endphp
                            <div @class(['relative text-xl sm:text-6xl', $text_color, 'mb-2'])>
                                {!! $item['amount'] !!}
                                <span class="w-8 absolute left-0 right-0 m-auto -bottom-1 bg-gray-200 transition-all group-hover:bg-gray-900" style="height: 1px;"></span>
                            </div>

                            <span class="font-light mt-3">
                                {!! $item['title'] !!}
                            </span>
                        </a>
                    </x-tooltip>
                @else
                    <a href="{{ $item['href'] }}" class="group">
                        @php $text_color = (! empty($item['text_color'])) ? $item['text_color'] : 'text-purple group-hover:text-purple-700'; @endphp
                        <div @class(['relative text-xl sm:text-6xl', $text_color, 'mb-2'])>
                            {!! $item['amount'] !!}
                            <span class="w-8 absolute left-0 right-0 m-auto -bottom-1 bg-gray-200 transition-all group-hover:bg-gray-900" style="height: 1px;"></span>
                        </div>

                        <span class="font-light mt-3">
                            {!! $item['title'] !!}
                        </span>
                    </a>
                @endif
            </div>
        @endforeach
    @else
        @if (! empty($first) && $first->isNotEmpty())
            {!! $first !!}
        @elseif (! empty($first))
            <div class="w-1/2 sm:w-1/3 text-center">
                @if ($first->attributes->has('tooltip'))
                    <x-tooltip id="tooltip-summary-first" placement="top" message="{!! $first->attributes->get('tooltip') !!}">

                        @if ($first->attributes->has('href'))
                        <a href="{{ $first->attributes->get('hef') }}" class="group">
                        @endif
                            @php $text_color = $first->attributes->has('text-color') ? $first->attributes->get('text-color') : 'text-purple group-hover:text-purple-700'; @endphp
                            <div @class(['relative text-xl sm:text-6xl', $text_color, 'mb-2'])>
                                {!! $first->attributes->get('amount') !!}
                                <span class="w-8 absolute left-0 right-0 m-auto -bottom-1 bg-gray-200 transition-all group-hover:bg-gray-900" style="height: 1px;"></span>
                            </div>

                            <span class="font-light mt-3">
                                {!! $first->attributes->get('title') !!}
                            </span>
                        @if ($first->attributes->has('href'))
                        </a>
                        @endif

                    </x-tooltip>
                @else
                    @if ($first->attributes->has('href'))
                    <a href="{{ $first->attributes->get('hef') }}" class="group">
                    @endif
                        @php $text_color = $first->attributes->has('text-color') ? $first->attributes->get('text-color') : 'text-purple group-hover:text-purple-700'; @endphp
                        <div @class(['relative text-xl sm:text-6xl', $text_color, 'mb-2'])>
                            {!! $first->attributes->get('amount') !!}
                            <span class="w-8 absolute left-0 right-0 m-auto -bottom-1 bg-gray-200 transition-all group-hover:bg-gray-900" style="height: 1px;"></span>
                        </div>

                        <span class="font-light mt-3">
                            {!! $first->attributes->get('title') !!}
                        </span>
                    @if ($first->attributes->has('href'))
                    </a>
                    @endif
                @endif
            </div>

            @if ($first->attributes->has('divider'))
                <span class="material-icons text-4xl -mt-8 hidden lg:block">
                    {{ $first->attributes->get('divider') }}
                </span>
            @endif
        @endif

        @if (! empty($second) && $second->isNotEmpty())
            {!! $second !!}
        @elseif (! empty($second))
            <div class="w-1/2 sm:w-1/3 text-center">
                @if ($second->attributes->has('tooltip'))
                    <x-tooltip id="tooltip-summary-second" placement="top" message="{!! $second->attributes->get('tooltip') !!}">

                        @if ($second->attributes->has('href'))
                        <a href="{{ $second->attributes->get('hef') }}" class="group">
                        @endif
                            @php $text_color = $second->attributes->has('text-color') ? $second->attributes->get('text-color') : 'text-purple group-hover:text-purple-700'; @endphp
                            <div @class(['relative text-xl sm:text-6xl', $text_color, 'mb-2'])>
                                {!! $second->attributes->get('amount') !!}
                                <span class="w-8 absolute left-0 right-0 m-auto -bottom-1 bg-gray-200 transition-all group-hover:bg-gray-900" style="height: 1px;"></span>
                            </div>

                            <span class="font-light mt-3">
                                {!! $second->attributes->get('title') !!}
                            </span>
                        @if ($second->attributes->has('href'))
                        </a>
                        @endif

                    </x-tooltip>
                @else
                    @if ($second->attributes->has('href'))
                    <a href="{{ $second->attributes->get('hef') }}" class="group">
                    @endif
                        @php $text_color = $second->attributes->has('text-color') ? $second->attributes->get('text-color') : 'text-purple group-hover:text-purple-700'; @endphp
                        <div @class(['relative text-xl sm:text-6xl', $text_color, 'mb-2'])>
                            {!! $second->attributes->get('amount') !!}
                            <span class="w-8 absolute left-0 right-0 m-auto -bottom-1 bg-gray-200 transition-all group-hover:bg-gray-900" style="height: 1px;"></span>
                        </div>

                        <span class="font-light mt-3">
                            {!! $second->attributes->get('title') !!}
                        </span>
                    @if ($second->attributes->has('href'))
                    </a>
                    @endif
                @endif
            </div>

            @if ($second->attributes->has('divider'))
                <span class="material-icons text-4xl -mt-8 hidden lg:block">
                    {{ $second->attributes->get('divider') }}
                </span>
            @endif
        @endif

        @if (! empty($third) && $third->isNotEmpty())
            {!! $third !!}
        @elseif (! empty($third))
            <div class="w-1/2 sm:w-1/3 text-center">
                @if ($third->attributes->has('tooltip'))
                    <x-tooltip id="tooltip-summary-third" placement="top" message="{!! $third->attributes->get('tooltip') !!}">
                        @if ($third->attributes->has('href'))
                        <a href="{{ $third->attributes->get('hef') }}" class="group">
                        @endif
                            @php $text_color = $third->attributes->has('text-color') ? $third->attributes->get('text-color') : 'text-purple group-hover:text-purple-700'; @endphp
                            <div @class(['relative text-xl sm:text-6xl', $text_color, 'mb-2'])>
                                {!! $third->attributes->get('amount') !!}
                                <span class="w-8 absolute left-0 right-0 m-auto -bottom-1 bg-gray-200 transition-all group-hover:bg-gray-900" style="height: 1px;"></span>
                            </div>

                            <span class="font-light mt-3">
                                {!! $third->attributes->get('title') !!}
                            </span>
                        @if ($third->attributes->has('href'))
                        </a>
                        @endif
                    </x-tooltip>
                @else
                    @if ($third->attributes->has('href'))
                    <a href="{{ $third->attributes->get('hef') }}" class="group">
                    @endif
                        @php $text_color = $third->attributes->has('text-color') ? $third->attributes->get('text-color') : 'text-purple group-hover:text-purple-700'; @endphp
                        <div @class(['relative text-xl sm:text-6xl', $text_color, 'mb-2'])>
                            {!! $third->attributes->get('amount') !!}
                            <span class="w-8 absolute left-0 right-0 m-auto -bottom-1 bg-gray-200 transition-all group-hover:bg-gray-900" style="height: 1px;"></span>
                        </div>

                        <span class="font-light mt-3">
                            {!! $third->attributes->get('title') !!}
                        </span>
                    @if ($third->attributes->has('href'))
                    </a>
                    @endif
                @endif
            </div>
        @endif
    @endif
</div>
