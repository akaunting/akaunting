<template>
    <SlideYUpTransition :duration="animationDuration">
    <div class="modal modal-add-new fade"
         @click.self="closeModal"
         :class="[{'show d-block': show}, {'d-none': !show}]"
         v-show="show"
         tabindex="-1"
         role="dialog"
         :aria-hidden="!show">
        <div class="modal-dialog" :class="modalDialogClass">
            <slot name="modal-content">
            <div class="modal-content">
                <div class="card-header pb-2">
                    <slot name="card-header">
                        <h4 class="float-left"> {{ title }} </h4>
                        <button type="button" class="close" @click="onCancel" aria-hidden="true">&times;</button>
                    </slot>
                </div>
                <slot name="modal-body">
                    <div class="modal-body pb-0" v-if="!is_component" v-html="message">
                    </div>
                    <div class="modal-body pb-0" v-else>
                        <form id="form-create" method="POST" action="#"/>
                        <component v-bind:is="component"></component>
                    </div>
                </slot>
                <div class="card-footer border-top-0 pt-0">
                    <slot name="card-footer">
                        <div class="float-right">
                            <button type="button" class="btn btn-outline-secondary" :class="buttons.cancel.class" @click="onCancel">
                                {{ buttons.cancel.text }}
                            </button>

                            <button :disabled="form.loading" type="button" class="btn button-submit" :class="buttons.confirm.class" @click="onSubmit">
                                <div class="aka-loader"></div><span>{{ buttons.confirm.text }}</span>
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
import AkauntingRadioGroup from './forms/AkauntingRadioGroup';
import AkauntingSelect from './AkauntingSelect';
import AkauntingDate from './AkauntingDate';
import AkauntingRecurring from './AkauntingRecurring';

import Form from './../plugins/form';
import { Alert, ColorPicker } from 'element-ui';

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
        modalDialogClass: '',
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
                        class: 'btn-success',
                    }
                };
            },
            description: "Modal footer button"
        },

        animationDuration: {
            type: Number,
            default: 800,
            description: "Modal transition duration"
        }
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

    mounted() {
        if (this.is_component) {
            this.component = Vue.component('add-new-component', (resolve, reject) => {
                resolve({
                    template : '<div id="modal-add-new-form">' + this.message + '</div>',

                    components: {
                        AkauntingRadioGroup,
                        AkauntingSelect,
                        AkauntingModal,
                        AkauntingMoney,
                        AkauntingDate,
                        AkauntingRecurring,
                        [ColorPicker.name]: ColorPicker,
                    },

                    created: function() {
                        this.form = new Form('form-create');
                    },

                    mounted() {
                        let form_id = document.getElementById('modal-add-new-form').children[0].id;

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
                            color: '#55588b',
                            predefineColors: [
                                '#3c3f72',
                                '#55588b',
                                '#e5e5e5',
                                '#328aef',
                                '#efad32',
                                '#ef3232',
                                '#efef32'
                            ]
                        }
                    },

                    methods: {
                        onChangeColor() {
                            this.form.color = this.color;
                        },

                        onChangeColorInput() {
                            this.color = this.form.color;
                        },

                        onChangeRate() {
                            this.form.rate = this.form.rate.replace(',', '.');
                        },

                        onChangeCode(code) {
                            axios.get(url + '/settings/currencies/config', {
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
                        }
                    }
                })
            });
        }
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
            this.$emit("cancel");
        }
    },

    watch: {
        show(val) {
            let documentClasses = document.body.classList;

            if (val) {
                documentClasses.add("modal-open");
            } else {
                documentClasses.remove("modal-open");
            }
        }
    }
}
</script>

<style>
    .modal.show {
        background-color: rgba(0, 0, 0, 0.3);
    }
</style>
