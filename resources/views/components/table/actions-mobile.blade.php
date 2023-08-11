@php
    $count_buttons = 1;
    $more_actions = [];
@endphp

<div
    data-mobile-actions
    class="absolute w-6 h-6 flex items-center justify-center ltr:right-0 rtl:left-0 -top-3 py-0.5 px-1 bg-white border rounded-full cursor-pointer hover:bg-gray-100"
>
    <span class="material-icons-outlined text-lg">
        more_horiz
    </span>
</div>

<div
    data-mobile-actions-modal
    class="modal w-full h-full fixed flex top-0 left-0 right-0 justify-center items-center flex-wrap overflow-y-auto overflow-hidden z-50 opacity-0 invisible modal-background transition-opacity"
>
    <div class="w-full my-10 m-auto flex flex-col px-2 sm:px-0 max-w-md">
        <div class="p-2 bg-body rounded-lg">
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
                            <button type="button" class="w-full flex items-center text-purple px-2 h-9 leading-9" {!! $action['attributes'] ?? null !!}>
                                <div class="w-full h-full flex items-center rounded-md px-2 text-sm hover:bg-lilac-100">
                                    <span class="material-icons-outlined text-purple text-lg ltr:mr-2 rtl:ml-2 pointer-events-none">
                                        {{ $action['icon'] }}
                                    </span>

                                    {{ $action['title'] }}
                                </div>
                            </button>
                            @break

                        @case('delete')
                            @php
                                $text = $action['text'] ?? null;
                                $title = $action['title'] ?? null;
                                $modelId = ! empty($action['model-id']) ? $action['model-id'] : 'id';
                                $modelName = ! empty($action['model-name']) ? $action['model-name'] : 'name';
                            @endphp

                            <x-delete-button :model="$action['model']" :route="$action['route']" :title="$title" :text="$text" :model-id="$modelId" :model-name="$modelName" />
                            @break

                        @default
                            <a href="{{ $action['url'] }}" class="w-full flex items-center text-purple px-2 h-9 leading-9" {!! $action['attributes'] ?? null !!}>
                                <div class="w-full h-full flex items-center rounded-md px-2 text-sm hover:bg-lilac-100">
                                    <span class="material-icons-outlined text-purple text-lg ltr:mr-2 rtl:ml-2 pointer-events-none">
                                        {{ $action['icon'] }}
                                    </span>

                                    {{ $action['title'] }}
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

                @foreach ($more_actions as $action)
                    @php
                        $type = ! empty($action['type']) ? $action['type'] : 'link';
                    @endphp

                    @switch($type)
                        @case('button')
                            @php $divider = false; @endphp

                            <button type="button" class="w-full flex items-center text-purple px-2 h-9 leading-9" {!! $action['attributes'] ?? null !!}>
                                <div class="w-full h-full flex items-center rounded-md px-2 text-sm hover:bg-lilac-100">
                                    <span class="material-icons-outlined text-purple text-lg ltr:mr-2 rtl:ml-2 pointer-events-none">
                                        {{ $action['icon'] }}
                                    </span>

                                    {{ $action['title'] }}
                                </div>
                            </button>
                            @break

                        @case('delete')
                            @php $divider = false; @endphp

                            @php
                                $text = $action['text'] ?? null;
                                $title = $action['title'] ?? null;
                                $modelId = ! empty($action['model-id']) ? $action['model-id'] : 'id';
                                $modelName = ! empty($action['model-name']) ? $action['model-name'] : 'name';
                            @endphp

                            <x-delete-link :model="$action['model']" :route="$action['route']" :title="$title" :text="$text" :model-id="$modelId" :model-name="$modelName" />
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

                            <a href="{{ $action['url'] }}" class="w-full flex items-center text-purple px-2 h-9 leading-9" {!! $action['attributes'] ?? null !!}>
                                <div class="w-full h-full flex items-center rounded-md px-2 text-sm hover:bg-lilac-100">
                                    <span class="material-icons-outlined text-purple text-lg ltr:mr-2 rtl:ml-2 pointer-events-none">
                                        {{ $action['icon'] }}
                                    </span>

                                    {{ $action['title'] }}
                                </div>
                            </a>
                    @endswitch
                @endforeach
            @endif
        </div>
    </div>
</div>
