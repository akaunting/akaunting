@if ($transfer->attachment)
    <x-show.accordion type="attachment">
        <x-slot name="head">
            <x-show.accordion.head
                title="{{ trans_choice('general.attachments', 2) }}"
                description="{{ trans('transfers.slider.attachments') }}"
            />
        </x-slot>

        <x-slot name="body">
            @foreach ($transfer->attachment as $file)
                <x-media.file :file="$file" />
            @endforeach
        </x-slot>
    </x-show.accordion>
@endif
