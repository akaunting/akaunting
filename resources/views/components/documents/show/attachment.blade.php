
@if ($attachment)
    <x-show.accordion type="attachment" :open="($accordionActive == 'attachment')">
        <x-slot name="head">
            <x-show.accordion.head
                title="{{ trans_choice('general.attachments', 2) }}"
                description="{{ trans('documents.form_description.attachment', ['type' => $type]) }}"
            />
        </x-slot>

        <x-slot name="body">
            @stack('timeline_attachment_body_start')

            @foreach ($attachment as $file)
                <x-media.file :file="$file" />
            @endforeach

            @if ($transaction_attachment->count())
                <div class="relative mt-4">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>

                    <div class="relative flex justify-center">
                        <span class="bg-white px-2 text-sm text-gray-500">{{ trans_choice('general.transactions', 1) }}</span>
                    </div>
                </div>

                @foreach ($transaction_attachment as $file)
                    <x-media.file :file="$file" />
                @endforeach
            @endif

            @stack('timeline_attachment_body_end')
        </x-slot>
    </x-show.accordion>
@endif
