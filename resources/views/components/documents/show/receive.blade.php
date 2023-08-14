<x-show.accordion type="receive" :open="($accordionActive == 'receive')">
    <x-slot name="head">
        <x-show.accordion.head
            title="{{ trans('general.receive') }}"
            description="{!! trans($description, [
                'user' => $document->owner->name,
                'date' => $sent_date,
            ]) !!}"
        />
    </x-slot>

    <x-slot name="body">
        <div class="flex flex-wrap space-x-3 rtl:space-x-reverse">
            @if (! $hideMarkReceived)
                @can($permissionUpdate)
                    @if ($document->status == 'draft')
                        <x-link href="{{ route($markReceivedRoute, $document->id) }}" id="show-slider-actions-mark-received-{{ $document->type }}" kind="secondary" @click="e => e.target.classList.add('disabled')">
                            <x-link.loading>
                                {{ trans($textMarkReceived) }}
                            </x-link.loading>
                        </x-link>
                    @else
                        <x-button kind="secondary" disabled="disabled">
                            {{ trans($textMarkReceived) }}
                        </x-button>
                    @endif
                @endcan
            @endif
        </div>
    </x-slot>
</x-show.accordion>
