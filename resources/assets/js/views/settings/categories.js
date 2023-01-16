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
        Global,
    ],

    data: function () {
        return {
            form: new Form('category'),
            bulk_action: new BulkAction('categories'),
            categoriesBasedTypes: null,
            selected_type: true
        }
    },

    methods: {
        updateParentCategories(event) {
            if (event === '') {
                return;
            }

            if (typeof JSON.parse(this.form.categories)[event] === 'undefined') {
                this.categoriesBasedTypes = [];

                return;
            }

            if (this.form.parent_category_id) {
                this.form.parent_category_id = null;

                return;
            }

            this.selected_type = false;

            this.categoriesBasedTypes = JSON.parse(this.form.categories)[event];
        }
    }
});
