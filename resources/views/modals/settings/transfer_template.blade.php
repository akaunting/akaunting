<div class="modal-body pb-0">
    {!! Form::open([
            'route' => 'modals.transfer-templates.update',
            'method' => 'PATCH',
            'id' => 'template',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'transfer_form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button mb-0',
            'novalidate' => true
    ]) !!}
        <div class="row mb-4">
            <div class="col-md-4 text-center">
                <div class="bg-print border-radius-default print-edge choose" @click="transfer_form.template='default'">
                    <img src="{{ asset('public/img/transfer_templates/default.png') }}" class="mb-1 mt-3" height="200" alt="Default"/>
                    </br>
                    <label style="font-size: initial;">
                        <input type="radio" name="template" value="default" v-model="transfer_form.template">
                        {{ trans('settings.invoice.default') }}
                    </label>
                </div>
            </div>

            <div class="col-md-4 text-center px-2">
                <div class="bg-print border-radius-default print-edge choose" @click="transfer_form.template='second'">
                    <img src="{{ asset('public/img/transfer_templates/second.png') }}" class="mb-1 mt-3" height="200" alt="Second"/>
                    </br>
                    <label style="font-size: initial;">
                        <input type="radio" name="template" value="second" v-model="transfer_form.template">
                        {{ trans('settings.transfer.second') }}
                    </label>
                </div>
            </div>

            <div class="col-md-4 text-center px-0">
                <div class="bg-print border-radius-default print-edge choose" @click="transfer_form.template='third'">
                    <img src="{{ asset('public/img/transfer_templates/third.png') }}" class="mb-1 mt-3" height="200" alt="Third"/>
                    </br>
                    <label style="font-size: initial;">
                        <input type="radio" name="template" value="third" v-model="transfer_form.template">
                        {{ trans('settings.transfer.third') }}
                    </label>
                </div>
            </div>
        </div>

        {!! Form::hidden('transfer_id', $transfer->id) !!}
        {!! Form::hidden('_template', setting('transfer.template')) !!}
        {!! Form::hidden('_prefix', 'transfer') !!}
    {!! Form::close() !!}
</div>
