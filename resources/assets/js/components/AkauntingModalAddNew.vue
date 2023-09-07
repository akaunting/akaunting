<template>
    <SlideYUpTransition :duration="animationDuration">
        <div class="modal w-full h-full fixed top-0 left-0 right-0 z-50 overflow-y-auto overflow-hidden modal-add-new fade justify-center"
            @click.self="closeModal"
            :class="[modalPositionTop ? 'items-start' : 'items-center', {'show flex flex-wrap modal-background': show}, {'hidden': !show}]"
            v-show="show"
            tabindex="-1"
            role="dialog"
            data-modal-handle
            :aria-hidden="!show">
            <div class="w-full my-10 m-auto flex flex-col px-2 sm:px-0" :class="modalDialogClass ? modalDialogClass : 'max-w-md'">
                <slot name="modal-content">
                    <div class="bg-body rounded-lg modal-content">
                        <div class="p-5">
                            <div class="flex items-center justify-between border-b pb-5">
                                <slot name="card-header">
                                    <h4 class="text-base font-medium" v-html="title"></h4>

                                    <button type="button" class="text-lg" @click="onCancel" aria-hidden="true">
                                        <span class="rounded-md border-b-2 px-2 py-1 text-sm bg-gray-100">esc</span>
                                    </button>
                                </slot>
                            </div>
                        </div>

                        <slot name="modal-body">
                            <div class="py-1 px-5" v-if="!is_component" v-html="message"></div>
                            <div class="py-1 px-5" v-else>
                                <form id="form-create" method="POST" action="#"/>

                                <component v-bind:is="component"></component>
                            </div>
                        </slot>

                        <div class="p-5 border-gray-300">
                            <slot name="card-footer">
                                <div class="flex items-center justify-end space-x-2 rtl:space-x-reverse">
                                    <button type="button" class="px-6 py-1.5 hover:bg-gray-200 rounded-lg" :class="buttons.cancel.class" @click="onCancel">
                                        {{ buttons.cancel.text }}
                                    </button>

                                    <a v-if="buttons.payment" :href="buttons.payment.url" class="px-6 py-1.5 text-xs bg-transparent hover:bg-gray-200 font-medium rounded-lg leading-6" :class="buttons.payment.class">
                                        {{ buttons.payment.text }}
                                    </a>

                                    <button :disabled="form.loading" type="button" class="relative px-6 py-1.5 bg-green hover:bg-green-700 text-white rounded-lg" :class="buttons.confirm.class" @click="onSubmit">
                                        <i v-if="form.loading" class="animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto before:absolute before:w-2 before:h-2 before:rounded-full before:animate-submit before:delay-[0.14s] after:absolute after:w-2 after:h-2 after:rounded-full after:animate-submit before:-left-3.5 after:-right-3.5 after:delay-[0.42s]"></i>
                                        <span :class="[{'opacity-0': form.loading}]">{{ buttons.confirm.text }}</span>
                                    </button>
                                </div>
                            </slot>
                        </div>
                    </div>
                </slot>
            </div>
        </div>
    </SlideYUpTransition>
</template>

<script>
import Vue from 'vue';

import { SlideYUpTransition } from "vue2-transitions";
import AkauntingModal from './AkauntingModal';
import AkauntingMoney from './AkauntingMoney';
import AkauntingRadioGroup from './AkauntingRadioGroup';
import AkauntingSelect from './AkauntingSelect';
import AkauntingSelectRemote from './AkauntingSelectRemote';
import AkauntingDate from './AkauntingDate';
import AkauntingRecurring from './AkauntingRecurring';

import Form from './../plugins/form';
import { Alert, ColorPicker } from 'element-ui';
import Global from './../mixins/global';

export default {
    name: 'akaunting-modal-add-new',

    components: {
        SlideYUpTransition,
        AkauntingRadioGroup,
        AkauntingSelect,
        AkauntingModal,
        AkauntingMoney,
        AkauntingDate,
        AkauntingRecurring,
        [ColorPicker.name]: ColorPicker,
    },

    props: {
        show: Boolean,
        is_component: Boolean,
        title: {
            type: String,
            default: '',
            description: "Modal header title"
        },
        message: {
            type: String,
            default: '',
            description: "Modal body message"
        },
        buttons: {
            type: Object,
            default: function () {
                return {
                    cancel: {
                        text: 'Cancel',
                        class: 'btn-outline-secondary',
                    },
                    confirm: {
                        text: 'Save',
                        class: 'disabled:bg-green-100',
                    }
                };
            },
            description: "Modal footer button"
        },
        animationDuration: {
            type: Number,
            default: 800,
            description: "Modal transition duration"
        },
        modalDialogClass: {
            type: String,
            default: '',
            description: "Modal Body size Class"
        },
        modalPositionTop: {
            type: Boolean,
            default: false,
            description: "Modal Body position Class"
        },
    },

    data() {
        return {
            form: new Form('form-create'),

            display: this.show,
            component:'',
            money: {
                decimal: '.',
                thousands: ',',
                prefix: '$ ',
                suffix: '',
                precision: 2,
                masked: false /* doesn't work with directive */
            },
        };
    },

    created: function () {
        let documentClasses = document.body.classList;

        documentClasses.add('overflow-y-hidden', 'overflow-overlay');

        if (this.modalDialogClass) {
            let modal_size = this.modalDialogClass.replace('modal-', 'max-w-screen-');

            this.modalDialogClass = modal_size;
        }
    },

    mounted() {
        let form_prefix = this._uid;

        if (this.is_component) {
            this.component = Vue.component('add-new-component', (resolve, reject) => {
                resolve({
                    template : '<div id="modal-add-new-form-' + form_prefix + '">' + this.message + '</div>',

                    mixins: [
                        Global
                    ],

                    components: {
                        AkauntingRadioGroup,
                        AkauntingSelect,
                        AkauntingSelectRemote,
                        AkauntingModal,
                        AkauntingMoney,
                        AkauntingDate,
                        AkauntingRecurring,
                        [ColorPicker.name]: ColorPicker,
                    },

                    created: function() {
                        this.form = new Form('form-create');

                        // for override global currency variable..
                        this.currency = {
                            decimal: '.',
                            thousands: ',',
                            prefix: '$ ',
                            suffix: '',
                            precision: 2,
                            masked: false /* doesn't work with directive */
                        };

                        // Parent vue instance methods merge with child vue instance methods
                        if (this.$root.$options.methods) {
                            let parent_methods = this.$root.$options.methods;

                            for (let method_key in parent_methods) {
                                if (this.$options.methods[method_key] === undefined) {
                                    this[method_key] = parent_methods[method_key];
                                }
                            }
                        }

                        // Parent vue instance data merge with child vue instance data
                        if (this.$root._data) {
                            let parent_data = this.$root._data;

                            for (let data_key in parent_data) {
                                if (this[data_key] === undefined) {
                                    this[data_key] = parent_data[data_key];
                                }
                            }
                        }
                    },

                    mounted() {
                        let form_id = document.getElementById('modal-add-new-form-' + form_prefix).children[0].id;

                        this.form = new Form(form_id);
                    },

                    data: function () {
                        return {
                            form: {},
                            currency: {
                                decimal: '.',
                                thousands: ',',
                                prefix: '$ ',
                                suffix: '',
                                precision: 2,
                                masked: false /* doesn't work with directive */
                            },
                            selected_card: null,
                        }
                    },

                    methods: {
                        onChangeRate() {
                            this.form.rate = this.form.rate.replace(',', '.');
                        },

                        onChangeCode(code) {
                            window.axios.get(url + '/settings/currencies/config', {
                                params: {
                                    code: code
                                }
                            })
                            .then(response => {
                                this.form.rate = response.data.rate;
                                this.form.precision = response.data.precision;
                                this.form.symbol = response.data.symbol;
                                this.form.symbol_first = response.data.symbol_first;
                                this.form.decimal_mark = response.data.decimal_mark;
                                this.form.thousands_separator = response.data.thousands_separator;
                            })
                            .catch(error => {
                            });
                        },

                        // Change bank account get money and currency rate
                        async onChangePaymentAccount(account_id) {
                            let payment_account = Promise.resolve(window.axios.get(url + '/banking/accounts/currency', {
                                params: {
                                    account_id: account_id
                                }
                            }));

                            payment_account.then(response => {
                                this.form.currency = response.data.currency_name;
                                this.form.currency_code = response.data.currency_code;
                                this.form.currency_rate = response.data.currency_rate;

                                this.currency.decimal = response.data.decimal_mark;
                                this.currency.thousands = response.data.thousands_separator;
                                this.currency.prefix = (response.data.symbol_first) ? response.data.symbol : '';
                                this.currency.suffix = (! response.data.symbol_first) ? response.data.symbol : '';
                                this.currency.precision = parseInt(response.data.precision);
                            })
                            .catch(error => {
                            });
                        },
                    },

                    watch: {
                        'selected_card': function (newVal, oldVal) {
                            this.form.card_id = newVal;
                        },
                    },
                })
            });
        }

        window.addEventListener('keyup',(e) => {
            if (e.key === 'Escape') {
                this.onCancel();
            }
        });
    },

    methods: {
        closeModal() {
            this.$emit("update:show", false);
            this.$emit("close");
        },

        onSubmit() {
            this.form = this.$children[0].$children[0].form;
            this.form.loading = true;

            this.$emit("submit", this.form);
        },

        onCancel() {
            let documentClasses = document.body.classList;

            documentClasses.remove('overflow-y-hidden', 'overflow-overlay');

            this.$emit("cancel");
        }
    },

    watch: {
        show(val) {
            let documentClasses = document.body.classList;

            if (val) {
                documentClasses.add('overflow-y-hidden', 'overflow-overlay');
            } else {
                documentClasses.remove('overflow-y-hidden', 'overflow-overlay');
            }
        }
    }
}
</script>
