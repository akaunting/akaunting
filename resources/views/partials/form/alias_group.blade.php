@stack($name . '_input_start')

<div
    class="form-group visible-field {{ $col }} {{ isset($attributes['required']) ? 'required' : '' }}"
    :class="[{'has-error': {{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.get(' . $name . ')' }} }]">
    {!! Form::label($name, $text, ['class' => 'form-control-label'])!!}

    <div class="input-group input-group-merge {{ $group_class }}">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <i class="fa fa-{{ $icon }}"></i>
            </span>
        </div>

        <vue-suglify :slugify="form.{{ $original }}" :slug.sync="form.{{ $name }}" :disabled="true">
            {!! Form::text($name, $value, array_merge([
                'class' => 'form-control',
                "slot-scope" => "{inputBidding}",
                "v-bind" => "inputBidding"
            ], $attributes)) !!}
        </vue-suglify>
    </div>

    <div class="invalid-feedback d-block"
         v-if="{{ isset($attributes['v-error']) ? $attributes['v-error'] : 'form.errors.has(' . $name . ')' }}"
         v-html="{{ isset($attributes['v-error-message']) ? $attributes['v-error-message'] : 'form.errors.get(' . $name . ')' }}">
    </div>
</div>

@stack($name . '_input_end')
