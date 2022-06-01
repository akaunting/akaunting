@props(['active'])

<div x-data="{ active: '{{ $active }}' }">
    <div {{ $attributes }}>
        <div>
            <ul class="flex items-center">
                {!! $navs !!}
            </ul>
        </div>
    </div>

    {!! $content !!}
</div>
