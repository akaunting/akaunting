<div id="{{ $id }}" role="tooltip" class="w-full sm:w-96 inline-block absolute left-0 z-10 text-sm font-medium rounded-lg border border-gray-200 shadow-sm whitespace-nowrap transition-visible bg-lilac-900 border-none text-black p-6 cursor-auto opacity-0 invisible delay-700 information-content">
    <div class="absolute w-2 h-2 sm:inset-y-1/2 sm:-right-1 before:content-[' '] before:absolute before:w-2 before:h-2 before:bg-lilac-900 before:border-gray-200 before:transform before:rotate-45 before:border before:border-t-0 before:border-l-0 data-popper-arrow"></div>

    <ul>
        <li class="relative flex items-center text-sm mb-7">
            <div class="flex flex-col items-center mr-2">
                <span class="material-icons-outlined text-black-300">person</span>
            </div>

            <div class="flex flex-col items-start">
                <div class="font-bold">
                    {{ $document->document_number }}
                </div>

                <div class="absolute ltr:right-0 rtl:left-0">
                    <x-index.status status="{{ $document->status }}" background-color="bg-{{ $document->status_label }}" text-color="text-text-{{ $document->status_label }}" />
                </div>

                @if (! $hideShow)
                    <x-link href="{{ route($showRoute, $document->contact_id) }}" class="font-medium border-b border-black" override="class">
                        {{ $document->contact_name }}
                    </x-link>
                @else
                    <div class="font-medium border-b border-black">
                        {{ $document->contact_name }}
                    </div>
                @endif
            </div>
        </li>

        <li class="relative flex items-center text-sm mb-7">
            <div class="flex flex-col items-center mr-2">
                <span class="material-icons-outlined text-black-300">bookmark_border</span>
            </div>

            <div class="flex flex-col">
                @php $history = $document->last_history; @endphp
                <span class="w-72 font-medium mr-2 truncate">
                    {{ $history->description }}
                </span>

                <span class="flex items-center font-normal">
                    {{ \Date::parse($history->created_at)->diffForHumans() }}
                </span>
            </div>
        </li>

        @if ($document->items->count())
            @foreach ($document->items as $document_item)
                @if ($loop->index > 1)
                    @break
                @endif

                <li class="relative flex items-center text-sm mb-7">
                    <div class="flex flex-col items-center mr-2">
                        <span class="material-icons-outlined text-black-300">sell</span>
                    </div>

                    <div class="w-full flex flex-col">
                        <div class="w-60 font-medium truncate">
                            {{ $document_item->name }}
                        </div>

                        <span class="font-normal">
                            <x-money :amount="$document_item->price" :currency="$document->currency_code" />
                        </span>

                        <div class="w-40 font-normal text-sm truncate">
                            {{ $document_item->description }}
                        </div>
                    </div>
                </li>
            @endforeach
        @endif

        @if ($document->items->count() > 2)
            <li class="ml-10 mb-10">
            @if (! $hideShow)
                <x-link href="{{ route($showDocumentRoute, $document->id) }}" class="border-b" override="class">
                    {{ trans('documents.invoice_detail.more_item', ['count' => $document->items->count() - 2]) }}
                </x-link>
            @else
                <div class="border-b">
                    {{ trans('documents.invoice_detail.more_item', ['count' => $document->items->count() - 2]) }}
                </div>
            @endif
            </li>
        @endif

        <li class="relative flex items-center text-sm">
            <div class="flex flex-col items-center mr-2">
                <span class="material-icons-outlined text-black-300">attach_money</span>
            </div>

            <div class="w-full flex flex-col">
                <div class="flex items-center justify-between font-medium">
                    <span>
                        {{ trans('general.paid') }}
                    </span>

                    @if ($document->paid)
                        <span>
                            <x-money :amount="$document->paid" :currency="$document->currency_code" />
                        </span>
                    @endif
                </div>

                <div class="flex items-center justify-between font-medium">
                    <span>
                        {{ trans('general.due') }}
                    </span>

                    <span>
                        <x-money :amount="$document->amount" :currency="$document->currency_code" />
                    </span>
                </div>
            </div>
        </li>
    </ul>
</div>
