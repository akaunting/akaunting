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
import Swiper, { Navigation, Pagination } from 'swiper';
Swiper.use([Navigation, Pagination]);

// plugin setup
Vue.use(DashboardPlugin);

const app = new Vue({
    el: '#main-body',

    mixins: [
        Global
    ],

    data: function () {
        return {
            form: new Form('contact'),
            bulk_action: new BulkAction('contacts'),
            can_login : false,
            contact_persons: false,
        }
    },

    mounted() {
        if (! this.form.create_user) {
            this.form.create_user = false;
        }

        this.form.contact_persons = [];

        if (this.form.method) {
            this.onAddPerson();
        }

        if (typeof contact_persons !== 'undefined' && contact_persons) {
            let persons = [];

            contact_persons.forEach(function(item) {
                persons.push({
                    name: item.name,
                    email: item.email,
                    phone: item.phone
                });
            });

            this.form.contact_persons = persons;
        }
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

                        if (response.data.error) {
                            this.$notify({
                                verticalAlign: 'bottom',
                                horizontalAlign: 'left',
                                message: response.data.message,
                                timeout: 0,
                                icon: 'fas fa-bell',
                                type: 'warning',
                            });

                            return false;
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
        },

        onAddPerson() {
            var row = [];

            let keys = Object.keys(this.form.item_backup[0]);

            keys.forEach(function(item) {
                row[item] = '';
            });

            this.form.contact_persons.push(Object.assign({}, row));

            // Force update to re-render the component
            this.$forceUpdate();
        },

        onDeletePerson(index) {
            this.form.contact_persons.splice(index, 1);

            // Force update to re-render the component
            this.$forceUpdate();
        },

        forceUpdate() {
            // Force update to re-render the component
            this.$forceUpdate();
        },
    }
});
