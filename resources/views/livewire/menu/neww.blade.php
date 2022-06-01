<div wire:click.stop id="menu-neww">
    <input type="text" name="neww_keyword" wire:model.debounce.500ms="keyword" placeholder="{{ trans('general.search_placeholder') }}" class="border-t-0 border-l-0 border-r-0 border-b border-gray-300 bg-transparent text-gray-500 text-sm mb-3 focus:outline-none focus:ring-transparent focus:border-purple placeholder-light-gray js-search-action">

    {!! menu('neww') !!}
</div>

@push('scripts_end')
<script type="text/javascript">
    window.addEventListener('click', function() {
        if (Livewire.components.getComponentsByName('menu.neww')[0].data.neww.length > 0) {
            Livewire.emit('resetKeyword');
        }
    });
</script>
@endpush
