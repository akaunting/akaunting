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
    el: '#app',

    mixins: [
        Global
    ],

    data: function () {
        return {
            form: new Form('transfer'),
            bulk_action: new BulkAction('transfers'),
            show_rate: false,
        }
    },

    methods: {
        async onChangeFromAccount(from_account_id) {
            if (!from_account_id) {
                return;
            }

            let from_promise = Promise.resolve(window.axios.get(url + '/banking/accounts/currency', {
                params: {
                    account_id: from_account_id
                }
            }));

            from_promise.then(response => {
                this.currency = response.data;

                this.form.currency_code = response.data.currency_code;
                this.form.currency_rate = response.data.currency_rate;
                this.form.from_currency_code = response.data.currency_code;
                this.form.from_account_rate = response.data.currency_rate;
            })
            .catch(error => {
            })
            .finally(() => { 
                this.show_rate = false;
                if (this.form.to_currency_code && this.form.from_currency_code != this.form.to_currency_code) {
                    this.show_rate = true;
                }
            });
        },

        async onChangeToAccount(to_account_id) {
            if (!to_account_id) {
                return;
            }

            let to_promise = Promise.resolve(window.axios.get(url + '/banking/accounts/currency', {
                params: {
                    account_id: to_account_id
                }
            }));

            to_promise.then(response => {
                this.form.to_currency_code = response.data.currency_code;
                this.form.to_account_rate = response.data.currency_rate;
            })
            .catch(error => {
            })
            .finally(() => { 
                this.show_rate = false;
                if (this.form.from_currency_code && this.form.from_currency_code != this.form.to_currency_code) {
                    this.show_rate = true;
                }
            });
        },
    }
});
