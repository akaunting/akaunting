@stack($name . '_input_start')

    <div
        class="form-group {{ $col }}{{ isset($attributes['required']) ? ' required' : '' }}{{ isset($attributes['readonly']) ? ' readonly' : '' }}{{ isset($attributes['disabled']) ? ' disabled' : '' }}"
        :class="[{'has-error': {{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.get("' . $name . '")' }} }]"
        @if (isset($attributes['show']))
        v-if="{{ $attributes['show'] }}"
        @endif
        >
        @if (!empty($text))
            {!! Form::label($name, $text, ['class' => 'form-control-label'])!!}
        @endif

        <div class="tab-pane tab-example-result fade show active" role="tabpanel" aria-labelledby="-component-tab">
            <div class="btn-group btn-group-toggle radio-yes-no" data-toggle="buttons">
                @if (empty($attributes['disabled']))
                <label class="btn btn-success" @click="form.{{ $name }}=1" v-bind:class="{ active: form.{{ $name }} == 1 }">
                    {{ trans('general.yes') }}
                    <input type="radio" name="{{ $name }}" id="{{ $name }}-1" v-model="{{ !empty($attributes['v-model']) ? $attributes['v-model'] : (!empty($attributes['data-field']) ? 'form.' . $attributes['data-field'] . '.'. $name : 'form.' . $name) }}">
                </label>
                @else
                <label class="btn btn-success{{ ($value) ? ' active-disabled disabled' : ' disabled' }}">
                    {{ trans('general.yes') }}
                    <input type="radio" name="{{ $name }}" id="{{ $name }}-1" disabled>
                </label>
                @endif

                @if (empty($attributes['disabled']))
                <label class="btn btn-danger" @click="form.{{ $name }}=0" v-bind:class="{ active: form.{{ $name }} == 0 }">
                    {{ trans('general.no') }}
                    <input type="radio" name="{{ $name }}" id="{{ $name }}-0" v-model="{{ !empty($attributes['v-model']) ? $attributes['v-model'] : (!empty($attributes['data-field']) ? 'form.' . $attributes['data-field'] . '.'. $name : 'form.' . $name) }}">
                </label>
                @else
                <label class="btn btn-danger{{ ($value) ? ' disabled' : ' active-disabled disabled' }}">
                    {{ trans('general.no') }}
                    <input type="radio" name="{{ $name }}" id="{{ $name }}-0" disabled>
                </label>
                @endif
            </div>

            <input type="hidden" name="{{ $name }}" value="{{ ($value) ? 1 : 0 }}" />
        </div>

        <div class="invalid-feedback d-block"
            v-if="{{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.has("' . $name . '")' }}"
            v-html="{{ isset($attributes['v-error-message']) ? $attributes['v-error-message'] : 'form.errors.get("' . $name . '")' }}">
        </div>
    </div>

@stack($name . '_input_end')
