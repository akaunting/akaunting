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
            form: new Form('account'),
            bulk_action: new BulkAction('accounts')
        }
    },

    methods:{
        onChangeCurrency(currency_code) {
            axios.get(url + '/settings/currencies/currency', {
                params: {
                  code: currency_code
                }
            })
            .then(response => {
                this.money.decimal = response.data.decimal_mark;
                this.money.thousands = response.data.thousands_separator;
                this.money.prefix = (response.data.symbol_first) ? response.data.symbol : '';
                this.money.suffix = !(response.data.symbol_first) ? response.data.symbol : '';
                this.money.precision = response.data.precision;
            })
            .catch(error => {
            });
        }
    }
});
