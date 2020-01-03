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

    mounted() {
        if (!this.form.permissions.length) {
            this.form.permissions = [];
        }
    },

    data: function () {
        return {
            form: new Form('role'),
            bulk_action: new BulkAction('roles'),
            permissions: {
                'all': $('input:checkbox').serializeAll().permissions,
                'read': $('#tab-read input:checkbox').serializeAll(),
                'create': $('#tab-create input:checkbox').serializeAll(),
                'update': $('#tab-update input:checkbox').serializeAll(),
                'delete': $('#tab-delete input:checkbox').serializeAll(),
                'read_admin_panel': $('#read-admin-panel').val(),
                'read_client_portal': $('#read-client-portal').val(),
            }
        }
    },

    methods:{
        permissionSelectAll() {
            var is_admin = false;
            var is_portal = false;

            if (this.permissions.all.length) {
                for (var i = 0; i < this.permissions.all.length; i++) {
                    var value = this.permissions.all[i];

                    if ((is_admin && value == this.permissions.read_client_portal) ||
                        (is_portal && value == this.permissions.read_admin_panel)) {
                    } else {
                        this.form.permissions.push(value);
                    }

                    if (value == this.permissions.read_admin_panel) {
                        is_admin = true;
                    } else if (value == this.permissions.read_client_portal) {
                        is_portal = true;
                    }
                }
            }
        },

        permissionUnselectAll() {
            this.form.permissions = [];
        },

        select(type) {
            var is_admin = false;
            var is_portal = false;

            var values = this.permissions[type].permissions;

            if (values.length) {
                for (var i = 0; i < values.length; i++) {
                    var value = values[i];

                    if ((is_admin && value == this.permissions.read_client_portal) ||
                        (is_portal && value == this.permissions.read_admin_panel)) {
                    } else {
                        this.form.permissions.push(value);
                    }

                    if (value == this.permissions.read_admin_panel) {
                        is_admin = true;
                    } else if (value == this.permissions.read_client_portal) {
                        is_portal = true;
                    }
                }
            }
        },

        unselect(type) {
            var values = this.permissions[type].permissions;

            if (values.length) {
                for (var i = 0; i < values.length; i++) {
                    var index = this.form.permissions.indexOf(values[i]);

                    if (index > -1) {
                        this.form.permissions.splice(index, 1);
                    }
                }
            }
        }
    }
});
