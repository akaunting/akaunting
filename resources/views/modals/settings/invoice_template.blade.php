<div class="modal-body pb-0">
    {!! Form::open([
            'route' => 'modals.invoice-templates.update',
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
            <div class="col-md-4 text-center">
                <div class="bg-print border-radius-default print-edge choose" @click="invoice_form.template='default'">
                    <img src="{{ asset('public/img/invoice_templates/default.png') }}" class="mb-1 mt-3" height="200" alt="Default"/>
                    <label>
                        <input type="radio" name="template" value="default" v-model="invoice_form.template">
                        {{ trans('settings.invoice.default') }}
                    </label>
                </div>
            </div>

            <div class="col-md-4 text-center px-2">
                <div class="bg-print border-radius-default print-edge choose" @click="invoice_form.template='classic'">
                    <img src="{{ asset('public/img/invoice_templates/classic.png') }}" class="mb-1 mt-3" height="200" alt="Classic"/>
                    <label>
                        <input type="radio" name="template" value="classic" v-model="invoice_form.template">
                        {{ trans('settings.invoice.classic') }}
                    </label>
                </div>
            </div>

            <div class="col-md-4 text-center px-0">
                <div class="bg-print border-radius-default print-edge choose" @click="invoice_form.template='modern'">
                    <img src="{{ asset('public/img/invoice_templates/modern.png') }}" class="mb-1 mt-3" height="200" alt="Modern"/>
                    <label>
                        <input type="radio" name="template" value="modern" v-model="invoice_form.template">
                        {{ trans('settings.invoice.modern') }}
                    </label>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            @stack('color_input_start')
                <div class="form-group col-md-12 {{ $errors->has('color') ? 'has-error' : ''}}">
                    {!! Form::label('color', trans('general.color'), ['class' => 'form-control-label']) !!}
                    <div class="input-group input-group-merge" id="invoice-color-picker">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <el-color-picker popper-class="template-color-picker" v-model="invoice_form.color" size="mini" :predefine="predefineColors" @change="onChangeColor"></el-color-picker>
                            </span>
                        </div>
                        {!! Form::text('color', setting('invoice.color'), ['v-model' => 'invoice_form.color', '@input' => 'onChangeColorInput', 'id' => 'color', 'class' => 'form-control color-hex', 'required' => 'required']) !!}
                    </div>
                    {!! $errors->first('color', '<p class="help-block">:message</p>') !!}
                </div>
            @stack('color_input_end')
        </div>

        {!! Form::hidden('_template', setting('invoice.template')) !!}
        {!! Form::hidden('_prefix', 'invoice') !!}
    {!! Form::close() !!}
</div>
