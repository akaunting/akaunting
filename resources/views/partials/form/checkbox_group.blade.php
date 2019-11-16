@stack($name . '_input_start')

<div
    class="form-group {{ $col }} {{ isset($attributes['required']) ? 'required' : '' }}"
    :class="[{'has-error': {{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.get(' . $name . ')' }} }]">
    {!! Form::label($name, $text, ['class' => 'form-control-label']) !!}

    <br/>
    @foreach($items as $item)
        <div class="custom-control custom-checkbox">
            {{ Form::checkbox($name, $item->$id, null, [
                'id' => 'checkbox-' . $name . '-' . $item->$id,
                'class' => 'custom-control-input',
                'v-model' => !empty($attributes['v-model']) ? $attributes['v-model'] : 'form.' . $name
            ]) }}

            <label class="custom-control-label" for="checkbox-{{ $name . '-' . $item->$id}}">
                {{ $item->$value }}
            </label>
        </div>
    @endforeach

    <div class="invalid-feedback d-block"
         v-if="{{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.has(' . $name . ')' }}"
         v-html="{{ isset($attributes['v-error-message']) ? $attributes['v-error-message'] : 'form.errors.get(' . $name . ')' }}">
    </div>
</div>

@stack($name . '_input_end')
