
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./../../bootstrap');

import Vue from 'vue';

import Global from './../../mixins/global';

import Form from './../../plugins/form';

const app = new Vue({
    el: '#app',

    mixins: [
        Global
    ],

    data: function () {
        return {
            form:  new Form('invoice-payment'),
            redirectForm: new Form('redirect-form'),
            method_show_html: ''
        }
    },

    methods:{
        onChangePaymentMethod(event) {
            let method = event.split('.');

            let path = url + '/portal/invoices/' + this.form.invoice_id + '/' + method[0];

            //this.method_show_html = '<div id="loading" class="text-center"><i class="fa fa-spinner fa-spin fa-5x checkout-spin"></i></div>';

            axios.get(path)
            .then(response => {
                this.method_show_html = '';

                if (response.data.redirect) {
                    location = response.data.redirect;
                }

                if (response.data.html) {
                    this.method_show_html = Vue.component('payment-method-confirm', function (resolve, reject) {
                          resolve({
                            template: response.data.html,

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

        onChangePaymentMethodSigned(event) {
            let method = event.split('.');

            this.form.payment_action = event;

            let payment_action = payment_action_path[event];

            axios.get(payment_action)
            .then(response => {
                this.method_show_html = '';

                if (response.data.redirect) {
                    location = response.data.redirect;
                }

                if (response.data.html) {
                    this.method_show_html = Vue.component('payment-method-confirm', function (resolve, reject) {
                          resolve({
                            template: response.data.html,

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
        }
    }
});
