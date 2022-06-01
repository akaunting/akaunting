<div>
    <h2 class="lg:text-lg font-medium text-black">
        <span class="border-b border-transparent transition-all group-hover:border-black">
            {{ $title }}
        </span>
    </h2>

    @if (! empty($description))
        <span class="text-sm font-light text-black">
            {!! $description !!}
        </span>
    @endif
</div>
