@if ($attachment)
    <div class="border-b border-gray-200 pb-4"
        x-data="{ attachment : null }"
    >
        <div class="relative w-full text-left cursor-pointer group"
            x-on:click="attachment !== 1 ? attachment = 1 : attachment = null"
        >
            <span class="font-medium">
                <x-button.hover group-hover>
                    {{ trans_choice('general.attachments', 2) }}
                </x-button.hover>
            </span>

            <div class="text-black-400 text-sm">
                {{ trans('transactions.slider.attachments') }}
            </div>

            <span class="material-icons absolute right-0 top-0 transition-all transform" x-bind:class="attachment === 1 ? 'rotate-180' : ''">expand_more</span>
        </div>

        <div class="overflow-hidden transition-transform origin-top-left ease-linear duration-100"
            x-ref="container1"
            x-bind:class="attachment == 1 ? 'h-auto ' : 'scale-y-0 h-0'"
        >
            @foreach ($attachment as $file)
                <x-media.file :file="$file" />
            @endforeach
        </div>
    </div>
@endif
