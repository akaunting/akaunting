@stack($name . '_input_start')

    <div
        class="form-group {{ $col }}{{ isset($attributes['required']) ? ' required' : '' }}{{ isset($attributes['readonly']) ? ' readonly' : '' }}{{ isset($attributes['disabled']) ? ' disabled' : '' }}"
        :class="[{'has-error': {{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.get("' . $name . '")' }} }]">
        @if (!empty($text))
            {!! Form::label($name, $text, ['class' => 'form-control-label'])!!}
        @endif

        <div class="row">
            @foreach($items as $item)
                <div class="col-md-3">
                    <div class="custom-control custom-checkbox">
                        {{ Form::checkbox($name, $item->$id, null, [
                            'id' => 'checkbox-' . $name . '-' . $item->$id,
                            'class' => 'custom-control-input',
                            'v-model' => !empty($attributes['v-model']) ? $attributes['v-model'] : (!empty($attributes['data-field']) ? 'form.' . $attributes['data-field'] . '.'. $name : 'form.' . $name),
                        ]) }}

                        <label class="custom-control-label" for="checkbox-{{ $name . '-' . $item->$id}}">
                            {{ $item->$value }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="invalid-feedback d-block"
            v-if="{{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.has("' . $name . '")' }}"
            v-html="{{ isset($attributes['v-error-message']) ? $attributes['v-error-message'] : 'form.errors.get("' . $name . '")' }}">
        </div>
    </div>

@stack($name . '_input_end')
