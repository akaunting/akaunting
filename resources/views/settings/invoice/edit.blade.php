<x-layouts.admin>
    <x-slot name="title">
        {{ trans_choice('general.invoices', 1) }}
    </x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="setting" method="PATCH" route="settings.invoice.update">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.general') }}" description="{{ trans('settings.invoice.form_description.general') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.text name="number_prefix" label="{{ trans('settings.invoice.prefix') }}"  value="{{ setting('invoice.number_prefix') }}" not-required />

                        <x-form.group.number_digit label="{{ trans('settings.invoice.digit') }}" :clearable="'false'" not-required />

                        <x-form.group.number name="number_next" label="{{ trans('settings.invoice.next') }}" value="{{ setting('invoice.number_next') }}" not-required />

                        <x-form.group.payment-term label="{{ trans('settings.invoice.payment_terms') }}" :clearable="'false'" :selected="setting('invoice.payment_terms')" not-required />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title=" {{ trans_choice('general.templates', 1) }}" description="{{ trans('settings.invoice.form_description.template') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <div class="sm:col-span-2 rounded-lg cursor-pointer text-center py-2 px-2">
                            <label class="cursor-pointer">
                                <div @click="form.template='default'" class="flex flex-col items-center">
                                    <img src="{{ asset('public/img/invoice_templates/default.png') }}" class="h-60 my-3" alt="Default" />

                                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                        <input type="radio" name="template" value="default" v-model="form._template">
                                        <span> {{ trans('settings.invoice.default') }} </span>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <div class="sm:col-span-2 rounded-lg cursor-pointer text-center py-2 px-2">
                            <label class="cursor-pointer">
                                <div @click="form.template='classic'" class="flex flex-col items-center">
                                    <img src="{{ asset('public/img/invoice_templates/classic.png') }}" class="h-60 my-3" alt="Classic" />

                                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                        <input type="radio" name="template" value="classic" v-model="form._template">
                                        <span> {{ trans('settings.invoice.classic') }} </span>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <div class="sm:col-span-2 rounded-lg cursor-pointer text-center py-2 px-2">
                            <label class="cursor-pointer">
                                <div @click="form.template='modern'" class="flex flex-col items-center">
                                    <img src="{{ asset('public/img/invoice_templates/modern.png') }}" class="h-60 my-3" alt="Modern" />

                                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                        <input type="radio" name="template" value="modern" v-model="form._template">
                                        <span> {{ trans('settings.invoice.modern') }} </span>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <x-form.group.color name="color" label="{{ trans('general.color') }}" :value="setting('invoice.color')" />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans_choice('general.defaults', 2) }}" description="{{ trans('settings.invoice.form_description.default') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.text name="title" label="{{ trans('settings.invoice.title') }}" value="{{ setting('invoice.title') }}" not-required />

                        <x-form.group.text name="subheading" label="{{ trans('settings.invoice.subheading') }}" value="{{ setting('invoice.subheading') }}" not-required />

                        <x-form.group.text name="logo_size_width" label="{{ trans('settings.invoice.logo_size_width') }}" value="{{ setting('invoice.logo_size_width') }}" not-required trailing="PX" />

                        <x-form.group.text name="logo_size_height" label="{{ trans('settings.invoice.logo_size_height') }}" value="{{ setting('invoice.logo_size_height') }}" not-required trailing="PX" />

                        <x-form.group.textarea name="notes" label="{{ trans_choice('general.notes', 2) }}" :value="setting('invoice.notes')" form-group-class="sm:col-span-3" not-required />

                        <x-form.group.textarea name="footer" label="{{ trans('general.footer') }}" :value="setting('invoice.footer')" form-group-class="sm:col-span-3" not-required />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans_choice('settings.invoice.column', 2) }}" description="{{ trans('settings.invoice.form_description.column') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <div class="grid col-span-6 gap-y-6 grid-rows-3">
                            <x-form.group.invoice-text
                                name="item_name"
                                label="{{ trans('settings.invoice.item_name') }}"
                                :options="$item_names"
                                :clearable="'false'"
                                :selected="setting('invoice.item_name')"
                                change="settingsInvoice"
                                input-name="item_name_input"
                                :input-value="setting('invoice.item_name_input')"
                                form-group-class="sm:col-span-6"
                            />

                            <x-form.group.invoice-text
                                name="price_name"
                                label="{{ trans('settings.invoice.price_name') }}"
                                :options="$price_names"
                                :clearable="'false'"
                                :selected="setting('invoice.price_name')"
                                change="settingsInvoice"
                                input-name="price_name_input"
                                :input-value="setting('invoice.price_name_input')"
                                form-group-class="col-span-3 sm:col-span-6"
                            />

                            <x-form.group.invoice-text
                                name="quantity_name"
                                label="{{ trans('settings.invoice.quantity_name') }}"
                                :options="$quantity_names"
                                :clearable="'false'"
                                :selected="setting('invoice.quantity_name')"
                                change="settingsInvoice"
                                input-name="quantity_name_input"
                                :input-value="setting('invoice.quantity_name_input')"
                                form-group-class="sm:col-span-6"
                            />
                        </div>

                        <div class="flex gap-10">
                            <x-form.group.toggle name="hide_item_description" label="{{ trans('settings.invoice.hide.item_description') }}" :value="setting('invoice.hide_item_description')" change="settingsInvoice()" not-required form-group-class="sm:col-span-6" />

                            <x-form.group.toggle name="hide_amount" label="{{ trans('settings.invoice.hide.amount') }}" :value="setting('invoice.hide_amount')" not-required form-group-class="sm:col-span-6" />
                        </div>
                    </x-slot>
                </x-form.section>

                @can('update-settings-invoice')
                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons :cancel="url()->previous()" />
                    </x-slot>
                </x-form.section>
                @endcan

                <x-form.input.hidden name="_template" :value="setting('invoice.template')" />
                <x-form.input.hidden name="_prefix" value="invoice" />
                <x-form.input.hidden name="message_name_or_description_required" :value="trans('invoices.messages.name_or_description_required', ['name' => trans('general.name'), 'description' => trans('general.description')])" />
            </x-form>
        </x-form.container>
    </x-slot>

    <x-script folder="settings" file="settings" />
</x-layouts.admin>
