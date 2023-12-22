<form wire:click.stop class="navbar-search navbar-search-light form-inline mb-0" id="navbar-search-main" autocomplete="off">
    <div class="mb-0 mr-sm-3">
        <div class="input-group input-group-alternative input-group-merge">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="material-icons">search</span>
                </span>
            </div>

            <input type="text" name="search" wire:model.live.debounce.500ms="keyword" class="text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple" autocomplete="off" placeholder="{{ trans('general.search') }}">

            @if ($results)
            <div class="dropdown-menu dropdown-menu-xl dropdown-menu-center show" ref="menu">
                <div class="list-group list-group-flush">
                @foreach($results as $result)
                <a class="list-group-item list-group-item-action" href="{{ $result->href }}">
                    <div class="items-center">
                        <div class="col ml--2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="name">{{ $result->name }}</div>
                                </div>
                                <div class="text-muted">
                                    <span class="type">{{ $result->type }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</form>

@push('scripts_end')
<script type="text/javascript">
    window.addEventListener('click', function() {
        if (Livewire.components.getComponentsByName('common.search')[0].data.results.length > 0) {
            Livewire.emit('resetKeyword');
        }
    });
</script>
@endpush
