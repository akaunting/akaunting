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
            form: new Form('transfer'),
            bulk_action: new BulkAction('transfers'),
            show_rate: false,
            edit: {
                status: false,
                form_account: false,
                to_account: false,
            },

            transfer_form: new Form('template'),
            template: {
                modal: false,
                title: '',
                message: '',
                html: '',
                errors: new Error()
            },
        }
    },

    methods: {
        async onChangeFromAccount(from_account_id) {
            if (!from_account_id) {
                return;
            }

            if (this.edit.status && this.edit.form_account < 2) {
                this.edit.form_account++;
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

            if (this.edit.status && this.edit.to_account < 2) {
                this.edit.to_account++;
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

        onTemplate() {
            this.template.modal = true;

            this.transfer_form = new Form('template');

            this.transfer_form.template = this.transfer_form._template;
        },

        addTemplate() {
            if (this.transfer_form.template != 1) {

                this.transfer_form.submit();

                this.template.errors = this.transfer_form.errors;
            }

            this.form.loading = true;

            this.$emit("confirm");
        },

        closeTemplate() {
            this.template = {
                modal: false,
                title: '',
                message: '',
                errors: this.transfer_form.errors
            };
        },
    },

    created() {
        if (typeof transfer_edit !== 'undefined' && transfer_edit) {
            this.show_rate = true;

            this.edit.status = true;
            this.edit.form_account = 1;
            this.edit.to_account = 1;
        }
    },
});
