<div class="border-b-2 border-gray-200 {{ !empty($description) ? ' pb-4' : 'pb-2' }}">
    <h2 class="lg:text-lg font-medium text-black">
        {{ $title }}
    </h2>

    @if (!empty($description))
    <span class="text-sm font-light text-black block gap-x-1 mt-1">
        {!! $description !!}
    </span>
    @endif
</div>