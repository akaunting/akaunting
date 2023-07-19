<x-show.accordion type="create" :open="($accordionActive == 'create')">
    <x-slot name="head">
        <x-show.accordion.head
            title="{{ trans('general.create') }}"
            description="{!! trans($description, [
                'user' => $document->owner->name,
                'date' => $created_date,
            ]) !!}"
        />
    </x-slot>

    <x-slot name="body">
        <div class="flex">
            @if (! $hideEdit)
                @can($permissionUpdate)
                    <x-link href="{{ route($editRoute, $document->id) }}" id="show-slider-actions-edit-{{ $document->type }}" @click="e => e.target.classList.add('disabled')">
                        {{ trans('general.edit') }}
                    </x-link>
                @endcan
            @endif
        </div>
    </x-slot>
</x-show.accordion>
