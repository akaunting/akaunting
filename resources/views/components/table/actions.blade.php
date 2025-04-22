@php
    $count_buttons = 1;
    $more_actions = [];
@endphp

@mobile
    <x-table.actions-mobile :actions="$actions" :model="$model" />
@else
    <div class="absolute ltr:right-12 rtl:left-12 -top-4 hidden items-center group-hover:flex">
        @foreach ($actions as $action)
            @if ($count_buttons > 3 && $loop->count > 4)
                @break
            @endif

            @if (isset($action['type']) && $action['type'] == 'divider')
                @continue
            @endif

            @if (
                empty($action['permission'])
                || (! empty($action['permission']) && user()->can($action['permission']))
            )
                @php
                    $type = ! empty($action['type']) ? $action['type'] : 'link';
                @endphp

                @switch($type)
                    @case('button')
                        <button type="button" class="relative bg-white hover:bg-gray-100 border py-0.5 px-1 cursor-pointer index-actions group/tooltip" {!! $action['attributes'] ?? null !!}>
                            <span class="material-icons-outlined text-purple text-lg pointer-events-none">
                                {{ $action['icon'] }}
                            </span>

                            <div class="inline-block absolute invisible z-20 py-1 px-2 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 shadow-sm whitespace-nowrap opacity-0 -top-10 -left-2 group-hover/tooltip:opacity-100 group-hover/tooltip:visible" data-tooltip-placement="top">
                                <span>{{ $action['title'] }}</span>
                                <div class="absolute w-2 h-2 -bottom-1 before:content-[' '] before:absolute before:w-2 before:h-2 before:bg-white before:border-gray-200 before:transform before:rotate-45 before:border before:border-t-0 before:border-l-0" data-popper-arrow></div>
                            </div>
                        </button>
                        @break

                    @case('delete')
                        @php
                            $text = $action['text'] ?? null;
                            $title = $action['title'] ?? null;
                            $route = $action['route'] ?? null;
                            $url = $action['url'] ?? null;
                            $modelId = ! empty($action['model-id']) ? $action['model-id'] : 'id';
                            $modelName = ! empty($action['model-name']) ? $action['model-name'] : 'name';
                        @endphp

                        <x-delete-button :model="$action['model']" :route="$route" :url="$url" :title="$title" :text="$text" :model-id="$modelId" :model-name="$modelName" />
                        @break

                    @default
                        <a href="{{ $action['url'] }}" x-data="{ clicked: false }" x-on:click="clicked = true;" x-bind:class="{ 'pointer-events-none': clicked }" class="relative bg-white hover:bg-gray-100 border py-0.5 px-1 cursor-pointer index-actions group/tooltip" {!! $action['attributes'] ?? null !!}>
                            <span class="material-icons-outlined text-purple text-lg pointer-events-none">
                                {{ $action['icon'] }}
                            </span>

                            <div class="inline-block absolute invisible z-20 py-1 px-2 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 shadow-sm whitespace-nowrap opacity-0 -top-10 -left-2 group-hover/tooltip:opacity-100 group-hover/tooltip:visible" data-tooltip-placement="top">
                                <span>{{ $action['title'] }}</span>
                                <div class="absolute w-2 h-2 -bottom-1 before:content-[' '] before:absolute before:w-2 before:h-2 before:bg-white before:border-gray-200 before:transform before:rotate-45 before:border before:border-t-0 before:border-l-0" data-popper-arrow></div>
                            </div>
                        </a>
                @endswitch

                @php
                    array_shift($actions);

                    $count_buttons++;
                @endphp
            @endif
        @endforeach

        @foreach ($actions as $action)
            @if (
                empty($action['permission'])
                || (! empty($action['permission']) && user()->can($action['permission']))
            )
                @php $more_actions[] = $action; @endphp
            @endif
        @endforeach

        @if ($more_actions)
            @php $divider = false; @endphp

            <div class="relative bg-white hover:bg-gray-100 border py-0.5 px-1 cursor-pointer index-actions">
                <button type="button" data-dropdown-toggle="dropdown-actions-{{ $model->id }}-{{ $loop->index }}" data-dropdown-placement="left" class="material-icons-outlined text-purple text-lg">more_horiz</button>

                <div id="dropdown-actions-{{ $model->id }}-{{ $loop->index }}" data-dropdown-actions class="absolute py-2 bg-white rounded-md border border-gray-200 shadow-xl z-20 hidden !mt-[50px]" style="left:auto; min-width:10rem;">    
                    @foreach ($more_actions as $action)
                        @php
                            $type = ! empty($action['type']) ? $action['type'] : 'link';
                        @endphp

                        @switch($type)
                            @case('button')
                                @php $divider = false; @endphp

                                <div class="w-full flex items-center text-purple px-2 h-9 leading-9 whitespace-nowrap" {!! $action['attributes'] ?? null !!}>
                                    <button type="button" class="w-full h-full flex items-center rounded-md px-2 text-sm hover:bg-lilac-100">
                                        {{ $action['title'] }}
                                    </button>
                                </div>
                                @break

                            @case('delete')
                                @php $divider = false; @endphp

                                @php
                                    $text = $action['text'] ?? null;
                                    $title = $action['title'] ?? null;
                                    $modelId = ! empty($action['model-id']) ? $action['model-id'] : 'id';
                                    $modelName = ! empty($action['model-name']) ? $action['model-name'] : 'name';
                                @endphp
                                <x-delete-link :model="$action['model']" :route="$action['route']" :title="$title" :title="$text" :model-id="$modelId" :model-name="$modelName" />
                                @break

                            @case('divider')
                                @if (! $divider)
                                    @php $divider = true; @endphp
                                    <div class="py-2 px-2">
                                        <div class="w-full border-t border-gray-200"></div>
                                    </div>
                                @endif
                                @break

                            @default
                                @php $divider = false; @endphp

                                <div class="w-full flex items-center text-purple px-2 h-9 leading-9 whitespace-nowrap" {!! $action['attributes'] ?? null !!}>
                                    <a href="{{ $action['url'] }}" x-data="{ clicked: false }" x-on:click="clicked = true;" x-bind:class="{ 'pointer-events-none': clicked }" class="w-full h-full flex items-center rounded-md px-2 text-sm hover:bg-lilac-100">
                                        {{ $action['title'] }}
                                    </a>
                                </div>
                        @endswitch
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endmobile
