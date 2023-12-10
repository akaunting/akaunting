<div wire:click.stop id="menu-neww" class="relative">
    <input type="text" name="neww_keyword" wire:model.live.debounce.500ms="keyword" placeholder="{{ trans('general.search_placeholder') }}" class="border-t-0 border-l-0 border-r-0 border-b border-gray-300 bg-transparent text-gray-500 text-sm mb-3 focus:outline-none focus:ring-transparent focus:border-purple placeholder-light-gray js-search-action">

    @if ($keyword)
        <button type="button" class="absolute ltr:right-2 rtl:left-2 top-2 clear" wire:click="resetKeyword">
            <span class="material-icons text-sm">close</span>
        </button>
    @endif

    @php $neww_menu = app('menu')->instance('neww'); @endphp

    @if ($neww_menu->count() > 0)
        {!! $neww_menu->render() !!}
    @else
        <ul class="flex flex-col justify-center">
            <li class="text-sm mb-5">
                <div class="flex items-start">
                    <p class="text-black">
                        {{ trans('notifications.empty') }}
                    </p>
                </div>
            </li>
        </ul>
    @endif
</div>
