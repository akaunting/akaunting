<div wire:click.stop id="menu-settings" class="relative">
    <input type="text" name="settings_keyword" wire:model.live.debounce.500ms="keyword" placeholder="{{ trans('general.search_placeholder') }}" class="border-t-0 border-l-0 border-r-0 border-b border-gray-300 bg-transparent text-gray-500 text-sm mb-3 focus:outline-none focus:ring-transparent focus:border-purple placeholder-light-gray js-search-action">

    @if ($keyword)
        <button type="button" class="absolute ltr:right-2 rtl:left-2 top-2 clear" wire:click="resetKeyword">
            <span class="material-icons text-sm">close</span>
        </button>
    @endif

    @php $settings_menu = app('menu')->instance('settings'); @endphp

    @if ($settings_menu->count() > 0)
        {!! $settings_menu->render() !!}
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

@push('scripts_start')
<script type="text/javascript">
    var is_settings_menu = {{ $active_menu }};
</script>
@endpush
