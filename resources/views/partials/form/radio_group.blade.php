@stack($name . '_input_start')
<div
    class="form-group {{ $col }} {{ isset($attributes['required']) ? 'required' : '' }}"
    :class="[{'has-error': {{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.get("' . $name . '")' }} }]">
    {!! Form::label($name, $text, ['class' => 'form-control-label']) !!}

    <div class="tab-pane tab-example-result fade show active" role="tabpanel" aria-labelledby="-component-tab">
        <div class="btn-group btn-group-toggle radio-yes-no" data-toggle="buttons">
            <label class="btn btn-success" @click="form.{{ $name }}=1"  v-bind:class="{ active: form.{{ $name }} }" >
                {{ trans('general.yes') }}
                <input type="radio" name="{{ $name }}-1" id="{{ $name }}-1" v-model="{{ !empty($attributes['v-model']) ? $attributes['v-model'] : 'form.' . $name }}">
            </label>

            <label class="btn btn-danger" @click="form.{{ $name }}=0" v-bind:class="{ active: !form.{{ $name }} }" >
                {{ trans('general.no') }}
                <input type="radio" name="{{ $name }}-0" id="{{ $name }}-0" v-model="{{ !empty($attributes['v-model']) ? $attributes['v-model'] : 'form.' . $name }}">
            </label>
            
            <input type="radio" value="{{ $value ? true : false }}" name="{{ $name }}" class="d-none">
        </div>
    </div>

    <div class="invalid-feedback d-block"
         v-if="{{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.has("' . $name . '")' }}"
         v-html="{{ isset($attributes['v-error-message']) ? $attributes['v-error-message'] : 'form.errors.get("' . $name . '")' }}">
    </div>
</div>

@stack($name . '_input_end')
