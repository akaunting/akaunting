
@if ($attachment)
    <x-show.accordion type="attachment" :open="($accordionActive == 'attachment')">
        <x-slot name="head">
            <x-show.accordion.head
                title="{{ trans_choice('general.attachments', 2) }}"
                description="{{ trans('documents.form_description.attachment', ['type' => $type]) }}"
            />
        </x-slot>

        <x-slot name="body">
            @foreach ($attachment as $file)
                <x-media.file :file="$file" />
            @endforeach
        </x-slot>
    </x-show.accordion>
@endif
