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
            form: new Form('account'),
            bulk_action: new BulkAction('accounts'),
            connect: {
                show: false,
                currency: {},
                documents: [],
            },
        }
    },

    methods: {
        onType(event) {
            return;
            let type = event.target.value;

            switch(type) {
                case 'credit_card':
                    this.onCreditCard();
                    break;
                case 'bank':
                default:
                    this.onBank();
                    break;
            }
        },

        onCreditCard() {

        },

        onBank() {

        },

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
    }
});
