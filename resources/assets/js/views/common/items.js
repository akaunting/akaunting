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
            sale_information : false,
            purchase_information : false
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
                    const removeLastChar  = newVal.length - 1;
                    const inputShown = newVal.slice(0, removeLastChar);
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
                    const removeLastChar  = newVal.length - 1;
                    const inputShown = newVal.slice(0, removeLastChar);
                    this.form.purchase_price = inputShown;
                }
            }
        },
    },

    mounted() {
        if (this.form.sale_price != '' && this.form.purchase_price == '') {
            this.form.sale_information = true;
            this.form.purchase_information = false;
            this.purchase_information = true;
        } else if (this.form.sale_price == '' && this.form.purchase_price != '') {
            this.form.sale_information = false;
            this.form.purchase_information = true;
            this.sale_information = true;
        } else {
            this.form.sale_information = true;
            this.form.purchase_information = true;
        }
    },

    methods:{
        onInformation(event, type) {
            if (event.target.checked) {
                if (type == 'sale') {
                    this.sale_information = false;
                    this.form.sale_price = '';
                    this.form.purchase_information = true;
                } else {
                    this.purchase_information = false;
                    this.form.purchase_price = '';
                }
            } else {
                if (type == 'sale') {
                    if (! this.form.purchase_information) {
                        this.purchase_information = false;
                        this.form.purchase_information = true;
                    }

                    this.sale_information = true;
                    this.form.sale_price = '';
                } else {
                    if (! this.form.sale_information) {
                        this.sale_information = false;
                        this.form.sale_information = true;
                    }

                    this.purchase_information = true;
                    this.form.purchase_price = '';
                }
            }
        },
    }
});
