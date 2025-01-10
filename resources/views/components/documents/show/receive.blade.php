<x-show.accordion type="receive" :open="($accordionActive == 'receive')">
    <x-slot name="head">
        <x-show.accordion.head
            title="{{ trans('general.receive') }}"
            description="{!! trans($description, [
                'user' => $user_name,
                'type' => $type_lowercase,
                'date' => $last_received_date,
            ]) !!}"
        />
    </x-slot>

    <x-slot name="body">
        @stack('timeline_receive_body_start')

        <div class="flex flex-wrap space-x-3 rtl:space-x-reverse">
            @stack('timeline_receive_body_button_mark_received_start')

            @if (! $hideMarkReceived)
                @can($permissionUpdate)
                    @if ($document->status == 'draft')
                        <x-link href="{{ route($markReceivedRoute, $document->id) }}" id="show-slider-actions-mark-received-{{ $document->type }}" kind="secondary" @click="e => e.target.classList.add('disabled')">
                            <x-link.loading>
                                {{ trans($textMarkReceived) }}
                            </x-link.loading>
                        </x-link>
                    @else
                        <x-button kind="disabled" disabled="disabled">
                            {{ trans($textMarkReceived) }}
                        </x-button>
                    @endif
                @endcan
            @endif

            @stack('timeline_receive_body_button_mark_received_end')
        </div>

        @stack('timeline_receive_body_end')
    </x-slot>
</x-show.accordion>
