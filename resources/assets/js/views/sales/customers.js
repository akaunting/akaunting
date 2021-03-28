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
            form: new Form('customer'),
            bulk_action: new BulkAction('customers'),
            can_login : false
        }
    },

    mounted() {
        this.form.create_user = false;
    },

    methods:{
        onCanLogin(event) {
            if (event.target.checked) {
                if (this.form.email) {
                    axios.get(url + '/auth/users/autocomplete', {
                        params: {
                            column: 'email',
                            value : this.form.email
                        }
                    })
                    .then(response => {
                        if (response.data.errors) {
                            if (response.data.data) {
                                this.form.errors.set('email', {
                                    0: response.data.data
                                });

                                return false;
                            }

                            this.can_login = true;
                            this.form.create_user = true;
                            return true;
                        }

                        if (response.data.success) {
                            this.form.errors.set('email', {
                                0: can_login_errors.email
                            });

                            this.can_login = false;
                            this.form.create_user = false;
                            return false;
                        }
                    })
                    .catch(error => {
                    });
                } else {
                    this.form.errors.set('email', {
                        0: can_login_errors.valid
                    });

                    this.can_login = false;
                    this.form.create_user = false;
                    return false;
                }

                return false;
            } else {
                this.form.errors.clear('email');

                this.can_login = false;
                this.form.create_user = false;
                return false;
            }
        }
    }
});
