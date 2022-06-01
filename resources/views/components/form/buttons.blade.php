@stack('save_buttons_start')
    <div
        @class([
            'flex items-center justify-end',
            $groupClass,
        ])
    >
        @if (! $withoutCancel)
            <x-link href="{{ $cancel }}" class="{{ $cancelClass }}" override="class">
                {{ $cancelText }}
            </x-link>
        @endif

        <x-button
            type="submit"
            class="relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100"
            ::disabled="form.loading"
            override="class"
        >
            <i v-if="form.loading" class="animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto before:absolute before:w-2 before:h-2 before:-left-3.5 before:rounded-full before:animate-submit before:delay-[0.14s] after:absolute after:w-2 after:h-2 after:-right-3.5 after:rounded-full after:animate-submit after:delay-[0.42s]"></i>
            <span :class="[{'opacity-0': form.loading}]">
                {{ trans('general.save') }}
            </span>
        </x-button>
    </div>
@stack('save_buttons_end')
