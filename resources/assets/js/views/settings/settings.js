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
            form: new Form('setting'),
            bulk_action: new BulkAction('settings'),
            email: {
                sendmailPath:true,
                smtpHost:true,
                smtpPort:true,
                smtpUsername:true,
                smtpPassword:true,
                smtpEncryption:true,
            },
            tags: null,
            template_title: '',

            invoice_form: new Form('template'),
            template: {
                modal: false,
                title: '',
                message: '',
                html: '',
                errors: new Error()
            },
            item_name_input: false,
            price_name_input: false,
            quantity_name_input: false
        }
    },

    methods:{
        onChangeProtocol(protocol) {
            switch(protocol) {
                case "smtp":
                    this.email.sendmailPath = true;
                    this.email.smtpHost = false;
                    this.email.smtpPort = false;
                    this.email.smtpUsername = false;
                    this.email.smtpPassword = false;
                    this.email.smtpEncryption = false;
                    break;

                case "sendmail":
                    this.email.sendmailPath = false;
                    this.email.smtpHost = true;
                    this.email.smtpPort = true;
                    this.email.smtpUsername = true;
                    this.email.smtpPassword = true;
                    this.email.smtpEncryption = true;
                    break;

                default:
                    this.email.sendmailPath = true;
                    this.email.smtpHost = true;
                    this.email.smtpPort = true;
                    this.email.smtpUsername = true;
                    this.email.smtpPassword = true;
                    this.email.smtpEncryption = true;
                    break;
            }
        },

        onTemplate() {
            this.template.modal = true;

            this.invoice_form = new Form('template');

            let skips = [
                '_method', '_prefix', '_token', 'action', 'errors', 'loading', 'method', 'response'
            ];

            for (const [key, value] of Object.entries(this.form)) {
                if (!skips.includes(key)) {
                    this.invoice_form[key] = value;
                }
            }

            this.invoice_form.template = this.invoice_form._template;
        },

        addTemplate() {
            if (this.invoice_form.template != 1) {

                this.invoice_form.submit();

                this.template.errors = this.invoice_form.errors;
            }

            this.form.loading = true;

            this.$emit("confirm");
        },

        closeTemplate() {
            this.template = {
                modal: false,
                title: '',
                message: '',
                errors: this.invoice_form.errors
            };
        },

        onEditEmailTemplate(template_id) {
            axios.get(url + '/settings/email-templates/get', {
                params: {
                    id: template_id
                }
            })
            .then(response => {
                this.template_title = response.data.data.title;
                this.form.subject = response.data.data.subject;
                this.form.body = response.data.data.body;
                this.form.id = response.data.data.id;
                this.tags = response.data.data.tags;
            });
        },

        settingsInvoice() {
            if (this.form.item_name == 'custom') {
                this.item_name_input = true;
            } else {
                this.item_name_input = false;
            }

            if (this.form.price_name == 'custom') {
                this.price_name_input = true;
            } else {
                this.price_name_input = false;
            }

            if (this.form.quantity_name == 'custom') {
                this.quantity_name_input = true;
            } else {
                this.quantity_name_input = false;
            }
        }
    }
});
