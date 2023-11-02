@stack($name . '_input_start')

    <div
        class="sm:col-span-3 {{ isset($attributes['required']) ? ' required' : '' }}{{ isset($attributes['readonly']) ? ' readonly' : '' }}{{ isset($attributes['disabled']) ? ' disabled' : '' }}"
        :class="[{'has-error': {{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.get("' . $name . '")' }} }]"
        @if (isset($attributes['show']))
        v-if="{{ $attributes['show'] }}"
        @endif
        >
        <x-form.label for="{{ $name }}">
            {!! $label !!}
        </x-form.label>

        <div class="flex items-center mt-1">
            @if (empty($attributes['disabled']))
                <label class="relative w-10 ltr:rounded-tl-lg ltr:rounded-bl-lg rtl:rounded-tr-lg rtl:rounded-br-lg py-2 px-1 text-sm text-center transition-all cursor-pointer" @click="form.{{ $name }}=1; @if(isset($attributes['change'])) {!! $attributes['change'] !!} @endif" v-bind:class="[form.{{ $name }} == 1 ? ['bg-green-500','text-white'] : 'bg-black-100']">
                    {{ empty($enable) ? trans('general.yes') : $enable }}
                    <input type="radio" name="{{ $name }}" id="{{ $name }}-1" class="absolute left-0 opacity-0">
                </label>
            @else
                <label class="relative w-10 ltr:rounded-tl-lg ltr:rounded-bl-lg rtl:rounded-tr-lg rtl:rounded-br-lg py-2 px-1 text-sm text-center transition-all cursor-not-allowed{{ ($value) ? ' bg-green-500 text-white opacity-20 disabled' : ' disabled' }}">
                    {{ empty($enable) ? trans('general.yes') : $enable }}
                    <input type="radio" name="{{ $name }}" id="{{ $name }}-1" class="absolute left-0 opacity-0" disabled>
                </label>
            @endif

            @if (empty($attributes['disabled']))
                <label class="relative w-10 ltr:rounded-tr-lg ltr:rounded-br-lg rtl:rounded-tl-lg rtl:rounded-bl-lg py-2 px-1 text-sm text-center transition-all cursor-pointer" @click="form.{{ $name }}=0; @if(isset($attributes['change'])) {!! $attributes['change'] !!} @endif" v-bind:class="[form.{{ $name }} == 0 ? ['bg-red-500','text-white'] : 'bg-black-100']">
                    {{ empty($disable) ? trans('general.no') : $disable }}
                    <input type="radio" name="{{ $name }}" id="{{ $name }}-0" class="absolute left-0 opacity-0">
                </label>
            @else
                <label class="relative w-10 ltr:rounded-tr-lg ltr:rounded-br-lg rtl:rounded-tl-lg rtl:rounded-bl-lg py-2 px-1 text-sm text-center transition-all cursor-not-allowed{{ ($value) ? ' disabled' : 'bg-red-500 text-white opacity-20 disabled disabled' }}">
                    {{ empty($disable) ? trans('general.no') : $disable }}
                    <input type="radio" name="{{ $name }}" id="{{ $name }}-0" class="absolute left-0 opacity-0" disabled>
                </label>
            @endif
        </div>

        <input type="hidden" name="{{ $name }}" value="{{ ($value) ? 1 : 0 }}" />

        <div class="text-red text-sm mt-1 block"
            v-if="{{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.has("' . $name . '")' }}"
            v-html="{{ isset($attributes['v-error-message']) ? $attributes['v-error-message'] : 'form.errors.get("' . $name . '")' }}">
        </div>
    </div>

@stack($name . '_input_end')
