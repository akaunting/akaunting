<div class="ltr:text-left rtl:text-right">
    <h2 class="lg:text-lg font-medium text-black">
        <x-button.hover group-hover>
            {{ $title }}
        </x-button.hover>
    </h2>

    @if (! empty($description))
        <span class="text-sm font-light text-black block gap-x-1 mt-1">
            {!! $description !!}
        </span>
    @endif
</div>
