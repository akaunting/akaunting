<x-form.section>
    <x-slot name="foot">
        <div class="flex justify-end">
            <x-form.buttons cancel-route="{{ $cancelRoute }}" />

            @if (! $hideSendTo)
            <x-button
                id="invoice-send-to"
                class="relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 ltr:ml-2 rtl:mr-2 text-base rounded-lg disabled:bg-green-100"
                override="class"
            >
                {{ trans('general.send_to') }}
            </x-button>
            @endif
        </div>
    </x-slot>
</x-form.section>
