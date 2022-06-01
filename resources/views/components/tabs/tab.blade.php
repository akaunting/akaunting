@props(['id'])

<div id="tab-{{ $id }}" data-tabs-content="{{ $id }}" x-show="active === '{{ $id }}'">
    {{ $slot }}
</div>
