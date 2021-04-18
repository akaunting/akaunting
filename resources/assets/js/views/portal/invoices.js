
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./../../bootstrap');

import Vue from 'vue';

import DashboardPlugin from './../../plugins/dashboard-plugin';

import Global from './../../mixins/global';

import Form from './../../plugins/form';
import CardForm from './../../components/CreditCard/CardForm';

// plugin setup
Vue.use(DashboardPlugin);

const app = new Vue({
    el: '#app',

    mixins: [
        Global
    ],

    components: {
        CardForm
    },

    data: function () {
        return {
            form:  new Form('invoice-payment'),
            redirectForm: new Form('redirect-form'),
            method_show_html: '',
            formData: {
                cardName: '',
                cardNumber: '',
                cardMonth: '',
                cardYear: '',
                cardCvv: '',
                storeCard: false,
                card_id: 0,
            }
        }
    },

    methods:{
        onChangePaymentMethod(payment_method) {
            if (!payment_method) {
                return;
            }

            let method = payment_method.split('.');

            let path = url + '/portal/' + method[0] + '/invoices/' + this.form.document_id;

            this.method_show_html = Vue.component('payment-method-confirm', function (resolve, reject) {
                resolve({
                    template:'<div id="loading" class="description text-center"><i class="fa fa-spinner fa-spin fa-5x checkout-spin"></i></div>'
                })
            });

            axios.get(path, {
                params: {
                    payment_method: payment_method
                }
            })
            .then(response => {
                this.method_show_html = '';

                if (response.data.redirect) {
                    location = response.data.redirect;
                }

                if (response.data.html) {
                    this.method_show_html = Vue.component('payment-method-confirm', function (resolve, reject) {
                          resolve({
                            template: '<div>' + response.data.html + '</div>',

                            components: {
                                CardForm
                            },

                            created: function() {
                                this.form = new Form('redirect-form');
                            },

                            data: function () {
                                return {
                                    form: {},
                                    formData: {
                                        cardName: '',
                                        cardNumber: '',
                                        cardMonth: '',
                                        cardYear: '',
                                        cardCvv: '',
                                        storeCard: false,
                                        card_id: 0,
                                    }
                                }
                            },

                            methods: {
                                onRedirectConfirm() {
                                    let redirect_form = new Form('redirect-form');

                                    this.$emit('interface', redirect_form);
                                }
                            }
                          })
                    });
                }
            })
            .catch(error => {
                this.method_show_html = error.message;
            });
        },

        onRedirectConfirm() {
            this.redirectForm = new Form('redirect-form');

            axios.post(this.redirectForm.action, this.redirectForm.data())
            .then(response => {
                if (response.data.redirect) {
                    location = response.data.redirect;
                }

                if (response.data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                this.method_show_html = error.message;
            });
        },

        onChangePaymentMethodSigned(payment_method) {
            if (!payment_method) {
                return;
            }

            this.form.payment_action = payment_method;

            let method = payment_method.split('.');

            let payment_action = payment_action_path[method[0]];

            this.method_show_html = Vue.component('payment-method-confirm', function (resolve, reject) {
                resolve({
                    template:'<div id="loading" class="description text-center"><i class="fa fa-spinner fa-spin fa-5x checkout-spin"></i></div>'
                })
            });

            axios.get(payment_action, {
                params: {
                    payment_method: payment_method
                }
            })
            .then(response => {
                this.method_show_html = '';

                if (response.data.redirect) {
                    location = response.data.redirect;
                }

                if (response.data.html) {
                    this.method_show_html = Vue.component('payment-method-confirm', function (resolve, reject) {
                          resolve({
                            template: '<div>' + response.data.html + '</div>',

                            components: {
                                CardForm
                            },

                            created: function() {
                                this.form = new Form('redirect-form');
                            },

                            data: function () {
                                return {
                                    form: {},
                                    formData: {
                                        cardName: '',
                                        cardNumber: '',
                                        cardMonth: '',
                                        cardYear: '',
                                        cardCvv: '',
                                        storeCard: false,
                                        card_id: 0,
                                    }
                                }
                            },

                            methods: {
                                onRedirectConfirm() {
                                    let redirect_form = new Form('redirect-form');

                                    this.$emit('interface', redirect_form);
                                }
                            }
                          })
                    });
                }
            })
            .catch(error => {
                this.method_show_html = error.message;
            });
        },
    }
});
