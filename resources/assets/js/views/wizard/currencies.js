/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../../bootstrap');

import Vue from 'vue';

import DashboardPlugin from './../../plugins/dashboard-plugin';

import Global from '../../mixins/global';

import Form from '../../plugins/form';
import BulkAction from './../../plugins/bulk-action';

import {Step, Steps} from 'element-ui';

// plugin setup
Vue.use(DashboardPlugin, Step, Steps);

const app = new Vue({
    el: '#app',

    mixins: [
        Global
    ],

    components: {
        [Step.name]: Step,
        [Steps.name]: Steps,
    },

    data: function () {
        return {
            form: new Form('currency'),
            active: 1,
            bulk_action: new BulkAction(url + '/settings/currencies'),
            show: false,
            currency: {
                name: '',
                code: '',
                rate: '1',
                enabled: 1
            },
            submit_function: '',
        }
    },

    methods: {
        onAddCurrency() {
            this.submit_function = 'onStoreCurrency';
            this.form.method = 'post';
            this.form.action = url + '/wizard/currencies';

            this.form.name = '';
            this.form.code = '';
            this.form.rate = '';
            this.form.enabled = 1;
            this.form.precision = '';
            this.form.symbol = '';
            this.form.symbol_first = '';
            this.form.decimal_mark = '';
            this.form.thousands_separator = '';

            this.show = true;
        },

        onEditCurrency(currency_id) {
            this.submit_function = 'onUpdateCurrency';
            this.form.method = 'patch';
            this.form.action = url + '/wizard/currencies/' + currency_id;

            currencies.forEach(currency => {
                if (currency.id == currency_id) {
                    this.form.name = currency.name;
                    this.form.code = currency.code;
                    this.form.rate = currency.rate;
                    this.form.enabled = currency.enabled;
                    this.form.precision = currency.precision;
                    this.form.symbol = currency.symbol;
                    this.form.symbol_first = currency.symbol_first;
                    this.form.decimal_mark = currency.decimal_mark;
                    this.form.thousands_separator = currency.thousands_separator;
                }
            });

            this.show = true;
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
        },

        onSubmit() {
            this.form.oldSubmit();
        },
    }
});
