<div>
    <h2 class="lg:text-lg font-medium text-black">
        <x-link.hover  group-hover>   
            {{ $title }}
        </x-link.hover>
    </h2>

    @if (! empty($description))
        <span class="text-sm font-light text-black">
            {!! $description !!}
        </span>
    @endif
</div>
