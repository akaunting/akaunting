<div class="flex items-center justify-center text-black mt-10 mb-10">
    @if (! empty($slot) && $slot->isNotEmpty())
        {!! $slot !!}
    @elseif (! empty($items))
        @foreach ($items as $item)
            <div @class(['w-1/2 sm:w-1/3 text-center'])>
                @php
                    $text_color = (! empty($item['text_color'])) ? $item['text_color'] : 'text-purple group-hover:text-purple-700';
                @endphp

                @if (! empty($item['tooltip']))
                    <x-tooltip id="tooltip-summary-{{ $loop->index }}" placement="top" message="{!! $item['tooltip'] !!}">
                        @if (! empty($item['href']))
                            <x-link href="{{ $item['href'] }}" class="group" override="class">
                                <div @class(['relative text-xl sm:text-6xl', $text_color, 'mb-2'])>
                                    {!! $item['amount'] !!}
                                    <span class="w-8 absolute left-0 right-0 m-auto -bottom-1 bg-gray-200 transition-all group-hover:bg-gray-900" style="height: 1px;"></span>
                                </div>

                                <span class="font-light mt-3">
                                    {!! $item['title'] !!}
                                </span>
                            </x-link>
                        @else
                            <div @class(['relative text-xl sm:text-6xl', $text_color, 'mb-2'])>
                                {!! $item['amount'] !!}
                                <span class="w-8 absolute left-0 right-0 m-auto -bottom-1 bg-gray-200 transition-all group-hover:bg-gray-900" style="height: 1px;"></span>
                            </div>

                            <span class="font-light mt-3">
                                {!! $item['title'] !!}
                            </span>
                        @endif
                    </x-tooltip>
                @else
                    @if (! empty($item['href']))
                        <x-link href="{{ $item['href'] }}" class="group" override="class">
                            <div @class(['relative text-xl sm:text-6xl', $text_color, 'mb-2'])>
                                {!! $item['amount'] !!}
                                <span class="w-8 absolute left-0 right-0 m-auto -bottom-1 bg-gray-200 transition-all group-hover:bg-gray-900" style="height: 1px;"></span>
                            </div>

                            <span class="font-light mt-3">
                                {!! $item['title'] !!}
                            </span>
                        </x-link>
                    @else
                        <div @class(['relative text-xl sm:text-6xl', $text_color, 'mb-2'])>
                            {!! $item['amount'] !!}
                            <span class="w-8 absolute left-0 right-0 m-auto -bottom-1 bg-gray-200 transition-all group-hover:bg-gray-900" style="height: 1px;"></span>
                        </div>

                        <span class="font-light mt-3">
                            {!! $item['title'] !!}
                        </span>
                    @endif
                @endif
            </div>
        @endforeach
    @else
        @if (! empty($first) && $first->isNotEmpty())
            {!! $first !!}
        @elseif (! empty($first))
            <div class="w-1/2 sm:w-1/3 text-center">
                @php
                    $text_color = $first->attributes->has('text-color') ? $first->attributes->get('text-color') : 'text-purple group-hover:text-purple-700';
                @endphp

                @if ($first->attributes->has('tooltip'))
                    <x-tooltip id="tooltip-summary-first" placement="top" message="{!! $first->attributes->get('tooltip') !!}">
                        @if ($first->attributes->has('href'))
                            <x-link href="{{ $first->attributes->get('href') }}" class="group" override="class">
                                <div @class(['relative text-xl sm:text-6xl', $text_color, 'mb-2', $first->attributes->get('class')])>
                                    {!! $first->attributes->get('amount') !!}
                                    <span class="w-8 absolute left-0 right-0 m-auto -bottom-1 bg-gray-200 transition-all group-hover:bg-gray-900" style="height: 1px;"></span>
                                </div>

                                <span class="font-light mt-3">
                                    {!! $first->attributes->get('title') !!}
                                </span>
                            </x-link>
                        @else
                            <div @class(['relative text-xl sm:text-6xl', $text_color, 'mb-2', $first->attributes->get('class')])>
                                {!! $first->attributes->get('amount') !!}
                                <span class="w-8 absolute left-0 right-0 m-auto -bottom-1 bg-gray-200 transition-all group-hover:bg-gray-900" style="height: 1px;"></span>
                            </div>

                            <span class="font-light mt-3">
                                {!! $first->attributes->get('title') !!}
                            </span>
                        @endif
                    </x-tooltip>
                @else
                    @if ($first->attributes->has('href'))
                        <x-link href="{{ $first->attributes->get('href') }}" class="group" override="class">
                            <div @class(['relative text-xl sm:text-6xl', $text_color, 'mb-2', $first->attributes->get('class')])>
                                {!! $first->attributes->get('amount') !!}
                                <span class="w-8 absolute left-0 right-0 m-auto -bottom-1 bg-gray-200 transition-all group-hover:bg-gray-900" style="height: 1px;"></span>
                            </div>

                            <span class="font-light mt-3">
                                {!! $first->attributes->get('title') !!}
                            </span>
                        </x-link>
                    @else
                        <div @class(['relative text-xl sm:text-6xl', $text_color, 'mb-2', $first->attributes->get('class')])>
                            {!! $first->attributes->get('amount') !!}
                            <span class="w-8 absolute left-0 right-0 m-auto -bottom-1 bg-gray-200 transition-all group-hover:bg-gray-900" style="height: 1px;"></span>
                        </div>

                        <span class="font-light mt-3">
                            {!! $first->attributes->get('title') !!}
                        </span>
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
                @php
                    $text_color = $second->attributes->has('text-color') ? $second->attributes->get('text-color') : 'text-purple group-hover:text-purple-700';
                @endphp

                @if ($second->attributes->has('tooltip'))
                    <x-tooltip id="tooltip-summary-second" placement="top" message="{!! $second->attributes->get('tooltip') !!}">
                        @if ($second->attributes->has('href'))
                            <x-link href="{{ $second->attributes->get('href') }}" class="group" override="class">
                                <div @class(['relative text-xl sm:text-6xl', $text_color, 'mb-2', $second->attributes->get('class')])>
                                    {!! $second->attributes->get('amount') !!}
                                    <span class="w-8 absolute left-0 right-0 m-auto -bottom-1 bg-gray-200 transition-all group-hover:bg-gray-900" style="height: 1px;"></span>
                                </div>

                                <span class="font-light mt-3">
                                    {!! $second->attributes->get('title') !!}
                                </span>
                            </x-link>
                        @else
                            <div @class(['relative text-xl sm:text-6xl', $text_color, 'mb-2', $second->attributes->get('class')])>
                                {!! $second->attributes->get('amount') !!}
                                <span class="w-8 absolute left-0 right-0 m-auto -bottom-1 bg-gray-200 transition-all group-hover:bg-gray-900" style="height: 1px;"></span>
                            </div>

                            <span class="font-light mt-3">
                                {!! $second->attributes->get('title') !!}
                            </span>
                        @endif
                    </x-tooltip>
                @else
                    @if ($second->attributes->has('href'))
                        <x-link href="{{ $second->attributes->get('href') }}" class="group" override="class">
                            <div @class(['relative text-xl sm:text-6xl', $text_color, 'mb-2', $second->attributes->get('class')])>
                                {!! $second->attributes->get('amount') !!}
                                <span class="w-8 absolute left-0 right-0 m-auto -bottom-1 bg-gray-200 transition-all group-hover:bg-gray-900" style="height: 1px;"></span>
                            </div>

                            <span class="font-light mt-3">
                                {!! $second->attributes->get('title') !!}
                            </span>
                        </x-link>
                    @else
                        <div @class(['relative text-xl sm:text-6xl', $text_color, 'mb-2', $second->attributes->get('class')])>
                            {!! $second->attributes->get('amount') !!}
                            <span class="w-8 absolute left-0 right-0 m-auto -bottom-1 bg-gray-200 transition-all group-hover:bg-gray-900" style="height: 1px;"></span>
                        </div>

                        <span class="font-light mt-3">
                            {!! $second->attributes->get('title') !!}
                        </span>
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
                @php
                    $text_color = $third->attributes->has('text-color') ? $third->attributes->get('text-color') : 'text-purple group-hover:text-purple-700';
                @endphp

                @if ($third->attributes->has('tooltip'))
                    <x-tooltip id="tooltip-summary-third" placement="top" message="{!! $third->attributes->get('tooltip') !!}">
                        @if ($third->attributes->has('href'))
                            <x-link href="{{ $third->attributes->get('href') }}" class="group" override="class">
                                <div @class(['relative text-xl sm:text-6xl', $text_color, 'mb-2', $third->attributes->get('class')])>
                                    {!! $third->attributes->get('amount') !!}
                                    <span class="w-8 absolute left-0 right-0 m-auto -bottom-1 bg-gray-200 transition-all group-hover:bg-gray-900" style="height: 1px;"></span>
                                </div>

                                <span class="font-light mt-3">
                                    {!! $third->attributes->get('title') !!}
                                </span>
                            </x-link>
                        @else
                            <div @class(['relative text-xl sm:text-6xl', $text_color, 'mb-2', $third->attributes->get('class')])>
                                {!! $third->attributes->get('amount') !!}
                                <span class="w-8 absolute left-0 right-0 m-auto -bottom-1 bg-gray-200 transition-all group-hover:bg-gray-900" style="height: 1px;"></span>
                            </div>

                            <span class="font-light mt-3">
                                {!! $third->attributes->get('title') !!}
                            </span>
                        @endif
                    </x-tooltip>
                @else
                    @if ($third->attributes->has('href'))
                        <x-link href="{{ $third->attributes->get('href') }}" class="group" override="class">
                            <div @class(['relative text-xl sm:text-6xl', $text_color, 'mb-2', $third->attributes->get('class')])>
                                {!! $third->attributes->get('amount') !!}
                                <span class="w-8 absolute left-0 right-0 m-auto -bottom-1 bg-gray-200 transition-all group-hover:bg-gray-900" style="height: 1px;"></span>
                            </div>

                            <span class="font-light mt-3">
                                {!! $third->attributes->get('title') !!}
                            </span>
                        </x-link>
                    @else
                        <div @class(['relative text-xl sm:text-6xl', $text_color, 'mb-2', $third->attributes->get('class')])>
                            {!! $third->attributes->get('amount') !!}
                            <span class="w-8 absolute left-0 right-0 m-auto -bottom-1 bg-gray-200 transition-all group-hover:bg-gray-900" style="height: 1px;"></span>
                        </div>

                        <span class="font-light mt-3">
                            {!! $third->attributes->get('title') !!}
                        </span>
                    @endif
                @endif
            </div>
        @endif
    @endif
</div>
