@stack('save_buttons_start')
    @php
        if (\Str::contains($cancel, ['.'])) {
            $url = route($cancel);
        } else {
            $url = url($cancel);
        }
    @endphp

    <div class="{{ $col }}">
        <a href="{{ $url }}" class="btn btn-icon btn-outline-secondary header-button-top">
            <span class="btn-inner--icon"><i class="fas fa-times"></i></span>
            <span class="btn-inner--text">{{ trans('general.cancel') }}</span>
        </a>

        {!! Form::button(
        '<div v-if="form.loading" class="aka-loader-frame"><div class="aka-loader"></div></div> <span v-if="!form.loading" class="btn-inner--icon"><i class="fas fa-save"></i></span>' . '<span v-if="!form.loading" class="btn-inner--text">' . trans('general.save') . '</span>',
        [':disabled' => 'form.loading', 'type' => 'submit', 'class' => 'btn btn-icon btn-success button-submit header-button-top', 'data-loading-text' => trans('general.loading')]) !!}
    </div>

@stack('save_buttons_end')
