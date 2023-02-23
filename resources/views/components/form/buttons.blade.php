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
            class="relative flex items-center justify-center bg-green hover:bg-green-500 text-white px-6 py-1.5 text-base disabled:bg-green-300 transition duration-150 ease-in-out rounded-full"
            ::disabled="{!! $saveDisabled !!}"
            override="class"
        >
            <x-button.loading action="{!! $saveLoading !!}">
                {{ trans('general.save') }}
            </x-button.loading>
        </x-button>
    </div>
@stack('save_buttons_end')
