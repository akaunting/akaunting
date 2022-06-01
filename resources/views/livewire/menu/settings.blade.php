<div wire:click.stop id="menu-settings">
    <input type="text" name="settings_keyword" wire:model.debounce.500ms="keyword" placeholder="{{ trans('general.search_placeholder') }}" class="border-t-0 border-l-0 border-r-0 border-b border-gray-300 bg-transparent text-gray-500 text-sm mb-3 focus:outline-none focus:ring-transparent focus:border-purple placeholder-light-gray js-search-action">

    {!! menu('settings') !!}
</div>

@push('scripts_start')
<script type="text/javascript">
    var is_settings_menu = {{ $active_menu }};
</script>
@endpush

@push('scripts_end')
<script type="text/javascript">
    window.addEventListener('click', function() {
        if (Livewire.components.getComponentsByName('menu.settings')[0].data.settings.length > 0) {
            Livewire.emit('resetKeyword');
        }
    });
</script>
@endpush
