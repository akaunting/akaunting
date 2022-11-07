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
            form: new Form('user'),
            bulk_action: new BulkAction('users'),
            show_password: false,
            landing_pages: null,
        }
    },

    mounted() {
       this.form.password = '';
    },

    methods: {
        onChangePassword(event) {
            if (this.show_password == false) {
                event.target.closest('.grid-rows-3').classList.replace('grid-rows-3', 'grid-rows-4');
                event.target.closest('.grid-rows-4').nextElementSibling.classList.replace('grid-rows-3', 'grid-rows-4');

                this.show_password = true;
            } else {
                event.target.closest('.grid-rows-4').classList.replace('grid-rows-4', 'grid-rows-3');
                event.target.closest('.grid-rows-3').nextElementSibling.classList.replace('grid-rows-4', 'grid-rows-3');

                this.show_password = false;
            }
        },

        onChangeRole(role_id) {
            if (! role_id) {
                return;
            }

            let role_promise = Promise.resolve(window.axios.get(url + '/auth/users/landingpages', {
                params: {
                    role_id: role_id
                }
            }));

            role_promise.then(response => {
                if (response.data.success) {
                    this.landing_pages = response.data.data;
                }
            })
            .catch(error => {
            });
        },
    }
});
