@stack('save_buttons_start')
    @php
        if (\Str::contains($cancel, ['.']) || $cancel == 'dashboard') {
            $url = route($cancel);
        } else {
            $url = url($cancel);
        }
    @endphp

    <div class="{{ $col }}">
        <a href="{{ $url }}" class="btn btn-outline-secondary">{{ trans('general.cancel') }}</a>

        {!! Form::button(
        '<span v-if="form.loading" class="btn-inner--icon"><i class="aka-loader"></i></span> <span :class="[{\'ml-0\': form.loading}]" class="btn-inner--text">' . trans('general.save') . '</span>',
        [':disabled' => 'form.loading', 'type' => 'submit', 'class' => 'btn btn-icon btn-success']) !!}
    </div>

@stack('save_buttons_end')
