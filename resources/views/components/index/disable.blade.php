<div data-index-icon>
    <x-tooltip id="{{ $id }}" placement="{{ $position }}" message="{{ $disableText }}">
        <span class="material-icons{{ $iconType }} text-red text-sm ltr:ml-2 rtl:mr-2">
            {{ $icon }}
        </span>
    </x-tooltip>
</div>
