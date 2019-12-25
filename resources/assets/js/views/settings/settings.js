/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./../../bootstrap');

import Vue from 'vue';

import Global from './../../mixins/global';

import Form from './../../plugins/form';
import BulkAction from './../../plugins/bulk-action';
import HtmlEditor from './../../components/Inputs/HtmlEditor';

const app = new Vue({
    el: '#app',

    mixins: [
        Global
    ],

    components: {
        HtmlEditor
    },

    data: function () {
        return {
            form: new Form('setting'),
            bulk_action: new BulkAction('settings'),
            email:{
                sendmailPath:true,
                smtpHost:true,
                smtpPort:true,
                smtpUsername:true,
                smtpPassword:true,
                smtpEncryption:true,
            },
            invoice_form: new Form('template'),
            template: {
                modal: false,
                title: '',
                message: '',
                html: '',
                errors: new Error()
            }
        }
    },

    mounted(){
        this.onChangeProtocol(this.form.protocol);
    },

    methods:{
        onChangeProtocol(protocol){
            switch(protocol){
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

            this.invoice_form.template = this.invoice_form._template;
        },

        addTemplate() {
            if (this.invoice_form.template != 1) {
                this.invoice_form.submit();

                this.template.errors = this.invoice_form.errors;
            }
        },

        closeTemplate() {
            this.template = {
                modal: false,
                title: '',
                message: '',
                errors: this.invoice_form.errors
            };
        },
    }
});
