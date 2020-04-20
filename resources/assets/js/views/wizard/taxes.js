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
            form: new Form('tax'),
            active: 2,
            bulk_action: new BulkAction(url + '/settings/taxes'),
            show: false,
            tax: {
                name: '',
                code: '',
                type: 'normal',
                enabled: 1
            },
            submit_function: ''
        }
    },

    methods: {
        onAddTax() {
            this.submit_function = 'onStoreTax';
            this.form.method = 'post';
            this.form.action = url + '/wizard/taxes';

            this.form.name = '';
            this.form.rate = '';
            this.form.type = 'normal';
            this.form.enabled = 1;

            this.show = true;
        },

        onEditTax(tax_id) {
            this.submit_function = 'onUpdateTax';
            this.form.method = 'patch';
            this.form.action = url + '/wizard/taxes/' + tax_id;

            taxes.forEach(tax => {
                if (tax.id == tax_id) {
                    this.form.name = tax.name;
                    this.form.rate = tax.rate;
                    this.form.type = tax.type;
                    this.form.enabled = tax.enabled;
                }
            });

            this.show = true;
        },

        onSubmit() {
            this.form.oldSubmit();
        },
    }
});
