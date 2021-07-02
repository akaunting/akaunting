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
            form: new Form('item'),
            bulk_action: new BulkAction('items'),
            regex_condition: [
                '..',
                '.,',
                ',.',
                ',,'
            ],
        }
    },

    watch: {
        'form.sale_price': function (newVal, oldVal) {
            if (newVal != '' && newVal.search('^(?=.*?[0-9])[0-9.,]+$') !== 0) {
                this.form.sale_price = oldVal;
                return;
            }
 
            for (let item of this.regex_condition) {
                if (this.form.sale_price.includes(item)) {
                    const removeLastChar  = newVal.length - 1 
                    const inputShown = newVal.slice(0, removeLastChar)
                    this.form.sale_price = inputShown;
                }
            }
        },

        'form.purchase_price': function (newVal, oldVal) {
            if (newVal != '' && newVal.search('^(?=.*?[0-9])[0-9.,]+$') !== 0) {
                this.form.purchase_price = oldVal;
                return;
            }

            for (let item of this.regex_condition) {
                if (this.form.purchase_price.includes(item)) {
                    const removeLastChar  = newVal.length - 1 
                    const inputShown = newVal.slice(0, removeLastChar)
                    this.form.purchase_price = inputShown;
                }
            }
        },
     },
});
