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
import BulkAction from './../../plugins/bulk-action';

// plugin setup
Vue.use(DashboardPlugin);

const app = new Vue({
    el: '#main-body',

    mixins: [
        Global
    ],

    data: function () {
        return {
            form: new Form('transaction'),
            bulk_action: new BulkAction('transactions'),
            connect: {
                show: false,
                currency: {},
                documents: [],
            },
        }
    },

    methods: {
        onConnect(route) {
            let dial_promise = Promise.resolve(window.axios.get(route));

            dial_promise.then(response => {
                this.connect.show = true;

                this.connect.transaction = JSON.parse(response.data.transaction);

                let currency = JSON.parse(response.data.currency);

                this.connect.currency = {
                    decimal_mark: currency.decimal_mark,
                    precision: currency.precision,
                    symbol: currency.symbol,
                    symbol_first: currency.symbol_first,
                    thousands_separator: currency.thousands_separator,
                };
    
                this.connect.documents = JSON.parse(response.data.documents);
            })
            .catch(error => {
            })
            .finally(function () {
                // always executed
            });
        },

        async onEmail(route) {
            let email = {
                modal: false,
                route: route,
                title: '',
                html: '',
                buttons:{}
            };

            let email_promise = Promise.resolve(window.axios.get(email.route));

            email_promise.then(response => {
                email.modal = true;
                email.title = response.data.data.title;
                email.html = response.data.html;
                email.buttons = response.data.data.buttons;

                this.component = Vue.component('add-new-component', (resolve, reject) => {
                    resolve({
                        template: '<div id="dynamic-email-component"><akaunting-modal-add-new modal-dialog-class="max-w-screen-lg" :show="email.modal" @submit="onSubmit" @cancel="onCancel" :buttons="email.buttons" :title="email.title" :is_component=true :message="email.html"></akaunting-modal-add-new></div>',

                        mixins: [
                            Global
                        ],

                        data: function () {
                            return {
                                form:{},
                                email: email,
                            }
                        },

                        methods: {
                            onSubmit(event) {
                                this.$emit('submit', event);

                                event.submit();
                            },

                            onCancel() {
                                this.email.modal = false;
                                this.email.html = null;

                                let documentClasses = document.body.classList;

                                documentClasses.remove("modal-open");
                            },
                        }
                    })
                });
            })
            .catch(error => {
            })
            .finally(function () {
                // always executed
            });
        },
    },
});
