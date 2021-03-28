
    <div class="accordion">
        <div class="card border-1 box-shadow-none">
            <div class="card-header background-none collapsed" id="accordion-footer-header" data-toggle="collapse" data-target="#accordion-footer-body" aria-expanded="false" aria-controls="accordion-footer-body">
                <h4 class="mb-0">{{ trans('general.footer') }}</h4>
            </div>

            <div id="accordion-footer-body" class="collapse hide" aria-labelledby="accordion-footer-header">
                {{ Form::textareaGroup('footer', '', '', $footerSetting, ['rows' => '3'], 'embed-acoordion-textarea') }}
            </div>
        </div>
    </div>
