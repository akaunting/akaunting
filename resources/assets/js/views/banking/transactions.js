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
            form: new Form('transaction'),
            bulk_action: new BulkAction('transactions'),
        }
    },

    methods: {
        async onEmail(route) {
            let email = {
                modal: false,
                route: route,
                title: '',
                html: '',
                buttons:{}
            };

            let email_promise = Promise.resolve(window.axios.get(email.route));

            email_promise.then(response => {
                email.modal = true;
                email.title = response.data.data.title;
                email.html = response.data.html;
                email.buttons = response.data.data.buttons;

                this.component = Vue.component('add-new-component', (resolve, reject) => {
                    resolve({
                        template: '<div id="dynamic-email-component"><akaunting-modal-add-new modal-dialog-class="max-w-screen-md" :show="email.modal" @submit="onSubmit" @cancel="onCancel" :buttons="email.buttons" :title="email.title" :is_component=true :message="email.html"></akaunting-modal-add-new></div>',

                        mixins: [
                            Global
                        ],

                        data: function () {
                            return {
                                form:{},
                                email: email,
                            }
                        },

                        methods: {
                            onSubmit(event) {
                                this.$emit('submit', event);

                                event.submit();
                            },

                            onCancel() {
                                this.email.modal = false;
                                this.email.html = null;

                                let documentClasses = document.body.classList;

                                documentClasses.remove('overflow-y-hidden', 'overflow-overlay', '-ml-4');
                            },
                        }
                    })
                });
            })
            .catch(error => {
            })
            .finally(function () {
                // always executed
            });
        },
    },
});
