/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./../../bootstrap');

import Vue from 'vue';

import DashboardPlugin from './../../plugins/dashboard-plugin';
import BulkAction from './../../plugins/bulk-action';

import Global from './../../mixins/global';
import Form from './../../plugins/form';
import CardForm from './../../components/CreditCard/CardForm';
import Swiper, { Navigation, Pagination } from 'swiper';

Swiper.use([Navigation, Pagination]);

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

    data: function() {
        return {
            form:  new Form('portal'),
            bulk_action: new BulkAction('portal'),

            // for profile
            show_password: false,

            // for payments
            connect: {
                show: false,
                currency: {},
                documents: [],
            },

            // for invoices
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
            },
        }
    },

    mounted() {
        if (typeof this.form.password !== 'undefined') {
            this.form.password = '';
        }

        new Swiper(".swiper-links", {
            loop: false,
            slidesPerView: 3,
            pagination: {
                el: ".swiper-pagination",
                clickable: true
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });
    },

    methods: {
        // for profile
        onChangePassword(event) {
            if (this.show_password == false) {
                event.target.closest('.grid-rows-4').classList.replace('grid-rows-4', 'grid-rows-5');
                event.target.closest('.grid-rows-5').nextElementSibling.classList.replace('grid-rows-4', 'grid-rows-5');

                this.show_password = true;
            } else {
                event.target.closest('.grid-rows-5').classList.replace('grid-rows-5', 'grid-rows-4');
                event.target.closest('.grid-rows-4').nextElementSibling.classList.replace('grid-rows-5', 'grid-rows-4');

                this.show_password = false;
            }
        },

        // for payments
        onConnect(transaction, currency, documents) {
            this.connect.show = true;

            this.connect.transaction = transaction;

            this.connect.currency = {
                decimal_mark: currency.decimal_mark,
                precision: currency.precision,
                symbol: currency.symbol,
                symbol_first: currency.symbol_first,
                thousands_separator: currency.thousands_separator,
            };

            this.connect.documents = documents;
        },

        // for invoices
        onChangePaymentMethod(payment_method) {
            if (! payment_method) {
                return;
            }

            let method = payment_method.split('.');

            let path = url + '/portal/' + method[0] + '/invoices/' + this.form.document_id;

            this.method_show_html = Vue.component('payment-method-confirm', function (resolve, reject) {
                resolve({
                    template:'<div id="loading" class="description text-center"><i class="submit-spin absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto"></i></div>'
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

                            mixins: [
                                Global
                            ],

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
            if (! payment_method) {
                return;
            }

            this.form.payment_action = payment_method;

            let method = payment_method.split('.');

            let payment_action = payment_action_path[method[0]];

            this.method_show_html = Vue.component('payment-method-confirm', function (resolve, reject) {
                resolve({
                    template:'<div id="loading" class="description text-center"><i class="submit-spin absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto"></i></div>'
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

                            mixins: [
                                Global
                            ],

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
    },
});
