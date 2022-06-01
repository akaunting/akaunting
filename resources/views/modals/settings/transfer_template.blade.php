<div class="py-1 px-5 bg-body">
    <x-form id="template" method="PATCH" route="modals.transfer-templates.update">
        <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
            <div class="sm:col-span-2 bg-gray-100 rounded-lg cursor-pointer text-center py-2 px-2">
                <div @click="transfer_form.template='default'">
                    <img src="{{ asset('public/img/transfer_templates/default.png') }}" class="h-72 m-auto" alt="Default"/>
                    </br>
                    <label style="font-size: initial;">
                        <input type="radio" name="template" value="default" v-model="transfer_form.template">
                        {{ trans('settings.invoice.default') }}
                    </label>
                </div>
            </div>

            <div class="sm:col-span-2 bg-gray-100 rounded-lg cursor-pointer text-center py-2 px-2">
                <div @click="transfer_form.template='second'">
                    <img src="{{ asset('public/img/transfer_templates/second.png') }}" class="h-72 m-auto" alt="Second"/>
                    </br>
                    <label style="font-size: initial;">
                        <input type="radio" name="template" value="second" v-model="transfer_form.template">
                        {{ trans('settings.transfer.second') }}
                    </label>
                </div>
            </div>

            <div class="sm:col-span-2 bg-gray-100 rounded-lg cursor-pointer text-center py-2 px-2">
                <div @click="transfer_form.template='third'">
                    <img src="{{ asset('public/img/transfer_templates/third.png') }}" class="h-72 m-auto" alt="Third"/>
                    </br>
                    <label style="font-size: initial;">
                        <input type="radio" name="template" value="third" v-model="transfer_form.template">
                        {{ trans('settings.transfer.third') }}
                    </label>
                </div>
            </div>
        </div>

        <x-form.input.hidden name="transfer_id" :value="$transfer->id" />
        <x-form.input.hidden name="_template" :value="setting('transfer.template')" />
        <x-form.input.hidden name="_prefix" value="transfer" />
    </x-form>
</div>
