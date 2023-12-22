<div wire:click.stop id="menu-notifications" class="relative">
    <input type="text" name="notification_keyword" wire:model.live.debounce.500ms="keyword" placeholder="{{ trans('general.search_placeholder') }}" class="border-t-0 border-l-0 border-r-0 border-b border-gray-300 bg-transparent text-gray-500 text-sm mb-3 focus:outline-none focus:ring-transparent focus:border-purple placeholder-light-gray js-search-action">

    @if ($keyword)
        <button type="button" class="absolute ltr:right-2 rtl:left-2 top-2 clear" wire:click="resetKeyword">
            <span class="material-icons text-sm">close</span>
        </button>
    @endif

    @if ($notifications)
        <div class="flex justify-end mt-1 mb-3">
            <x-tooltip id="notification-all" placement="top" message="{{ trans('notifications.mark_read_all') }}">
                <button type="button" wire:click="markReadAll()">
                    <span id="menu-notification-read-all" class="material-icons text-lg text-purple hover:scale-125">done_all</span>
                </button>
            </x-tooltip>
        </div>

        <ul class="flex flex-col justify-center">
            @foreach ($notifications as $notification)
                @if (empty($notification->data['title']) && empty($notification->data['description']))
                    @continue
                @endif

                <li class="mb-5 border-b pb-2">
                    <div class="flex items-start justify-between font-medium text-sm text-purple mb-1">
                        <div class="flex flex-col">
                            {!! $notification->data['title'] !!}

                            <span class="text-gray-500" style="font-size: 10px;">
                                {{ \Carbon\Carbon::createFromTimeStamp(strtotime($notification->created_at))->diffForHumans() }}
                            </span>
                        </div>

                        @if ($notification->type != 'updates')
                            <x-tooltip id="notification-{{ $notification->id }}" placement="top" message="{{ trans('notifications.mark_read') }}">
                                <button type="button" wire:click="markRead('{{ $notification->type }}', '{{ $notification->id }}')">
                                    <span id="menu-notification-read-one-{{ $notification->id }}" class="material-icons text-lg text-purple hover:scale-125">check_circle_outline</span>
                                </button>
                            </x-tooltip>
                        @endif
                    </div>

                    <div class="lex items-end justify-between">
                        <p class="text-black text-sm">
                            {!! $notification->data['description'] !!}
                        </p>
                    </div>
                </li>
            @endforeach
        </ul>
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

@push('scripts_end')
    <script type="text/javascript">
        window.addEventListener('mark-read', event => {
            if (event.detail.type == 'notifications') {
                $.notify(event.detail.message, {
                    type: 'success',
                });
            }
        });

        window.addEventListener('mark-read-all', event => {
            if (event.detail.type == 'notifications') {
                $.notify(event.detail.message, {
                    type: 'success',
                });
            }
        });
    </script>
@endpush
