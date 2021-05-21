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
        if (typeof this.form.permissions !== 'undefined' && !this.form.permissions.length) {
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
            if (this.permissions.all.length) {
                this.permissions.all.forEach(function (value) {
                    this.setFormPermission(value);
                }, this);
            }
        },

        permissionUnselectAll() {
            this.form.permissions = [];
        },

        select(type) {
            let values = this.permissions[type].permissions;

            if (values.length) {
                values.forEach(function (value) {
                    this.setFormPermission(value);
                }, this);
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
        },

        setFormPermission(permission) {
            if (this.form.permissions.includes(permission)) {
                return;
            }

            if ((this.permissions.read_admin_panel == permission) || (this.permissions.read_client_portal == permission)) {
                if (this.permissions.read_admin_panel == permission && this.form.permissions.includes(this.permissions.read_client_portal)) {
                    return;
                } else if(this.permissions.read_client_portal == permission && this.form.permissions.includes(this.permissions.read_admin_panel)) {
                    return;
                }

                this.form.permissions.push(permission);

                return;
            }

            this.form.permissions.push(permission);
        },
    }
});
