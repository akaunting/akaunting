<div class="modal-body">
    {!! Form::open([
            'route' => 'modals.invoice-template.update',
            'method' => 'PATCH',
            'id' => 'template',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'invoice_form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button mb-0',
            'novalidate' => true
    ]) !!}
        <div class="row">
            <div class="col-md-4 text-center px-0">
                <div class="bg-print border-radius-5 print-edge choose" @click="invoice_form.template='default'">
                    <img src="{{ asset('public/img/print_templates/default.png') }}" class="mb-1 mt-3" height="200" alt="Default"/>
                    <label>
                        <input type="radio" name="template" value="default" v-model="invoice_form.template">
                        {{ trans('settings.invoice.default') }}
                    </label>
                </div>
            </div>

            <div class="col-md-4 text-center px-0">
                <div class="bg-print border-radius-5 print-edge choose" @click="invoice_form.template='classic'">
                    <img src="{{ asset('public/img/print_templates/classic.png') }}" class="mb-1 mt-3" height="200" alt="Classic"/>
                    <label>
                        <input type="radio" name="template" value="classic" v-model="invoice_form.template">
                        {{ trans('settings.invoice.classic') }}
                    </label>
                </div>
            </div>

            <div class="col-md-4 text-center px-0">
                <div class="bg-print border-radius-5 print-edge choose" @click="invoice_form.template='modern'">
                    <img src="{{ asset('public/img/print_templates/modern.png') }}" class="mb-1 mt-3" height="200" alt="Modern"/>
                    <label>
                        <input type="radio" name="template" value="modern" v-model="invoice_form.template">
                        {{ trans('settings.invoice.modern') }}
                    </label>
                </div>
            </div>
        </div>

        {!! Form::hidden('_template', $setting['template']) !!}
        {!! Form::hidden('_prefix', 'invoice') !!}
    {!! Form::close() !!}
</div>
