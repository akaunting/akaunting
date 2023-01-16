<x-form.section>
    <x-slot name="foot">
        <div class="flex justify-end">
            <x-form.buttons cancel-route="{{ $cancelRoute }}" save-loading="! send_to && form.loading" />

            @if (! $hideSendTo)
            <x-button
                id="invoice-send-to"
                class="relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 ltr:ml-2 rtl:mr-2 text-base rounded-lg disabled:bg-green-100"
                override="class"
                ::disabled="form.loading"
                @click="onSubmitViaSendEmail"
            >
                <i v-if="send_to && form.loading" class="animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto before:absolute before:w-2 before:h-2 before:-left-3.5 before:rounded-full before:animate-submit before:delay-[0.14s] after:absolute after:w-2 after:h-2 after:-right-3.5 after:rounded-full after:animate-submit after:delay-[0.42s]"></i>
                <span :class="[{'opacity-0': send_to && form.loading}]">
                    {{ trans('general.send_to') }}
                </span>
            </x-button>
            @endif
        </div>
    </x-slot>
</x-form.section>
