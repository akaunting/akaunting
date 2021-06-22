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

        <div class="row">
            @foreach($items as $item)
            @php 
                $item_attributes = $attributes;

                if (!empty($attributes[':id'])) {
                    $item_attributes[':id'] = str_replace(':item_id', $item->$id, $attributes[':id']);
                }
            @endphp
                <div class="col-md-3">
                    <div class="custom-control custom-checkbox">
                        {{ Form::checkbox($name, $item->$id, (is_array($selected) && count($selected) ? (in_array($item->$id, $selected) ? true : false) : null), array_merge([
                            'id' => 'checkbox-' . $name . '-' . $item->$id,
                            'class' => 'custom-control-input',
                            'data-type' => (is_array($selected)) ? 'multiple' : 'single',
                            'v-model' => !empty($attributes['v-model']) ? $attributes['v-model'] : (!empty($attributes['data-field']) ? 'form.' . $attributes['data-field'] . '.'. $name : 'form.' . $name),
                        ], $item_attributes)) }}

                        <label class="custom-control-label" :for="{{ !empty($item_attributes[':id']) ? $item_attributes[':id'] : '"checkbox-' . $name . '-' . $item->$id . '"' }}">
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
