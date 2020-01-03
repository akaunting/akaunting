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

// plugin setup
Vue.use(DashboardPlugin);

const app = new Vue({
    el: '#app',

    mixins: [
        Global
    ],

    data: function () {
        return {
            form: new Form('form-app')
        }
    },

    methods: {
        onChangeCategory(category) {
            let path =  document.getElementById('category_page').value;

            if (category) {
                path += '/' + encodeURIComponent(category);
            } else {
                path = app_home;
            }

            location = path;
        }
    }
});
