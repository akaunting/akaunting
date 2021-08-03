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

});
