@stack('save_buttons_start')

    <div class="{{ $col }}">
        <a href="{{ url($cancel) }}" class="btn btn-icon btn-outline-secondary">
            <span class="btn-inner--icon"><i class="fas fa-times"></i></span>
            <span class="btn-inner--text">{{ trans('general.cancel') }}</span>
        </a>

        {!! Form::button(
        '<div v-if="form.loading" class="aka-loader-frame"><div class="aka-loader"></div></div> <span v-if="!form.loading" class="btn-inner--icon"><i class="fas fa-save"></i></span>' . '<span v-if="!form.loading" class="btn-inner--text"> ' . trans('general.save') . '</span>',
        [':disabled' => 'form.loading', 'type' => 'submit', 'class' => 'btn btn-icon btn-success button-submit', 'data-loading-text' => trans('general.loading')]) !!}
    </div>

@stack('save_buttons_end')
