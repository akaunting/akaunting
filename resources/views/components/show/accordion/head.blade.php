<div>
    <h2 class="lg:text-lg font-medium text-black">
        <x-text.hover text="{{ $title }}" color="to-black" />   
    </h2>

    @if (! empty($description))
        <span class="text-sm font-light text-black">
            {!! $description !!}
        </span>
    @endif
</div>
