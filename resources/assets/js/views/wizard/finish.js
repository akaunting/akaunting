/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./../../bootstrap');

import Vue from 'vue';

import DashboardPlugin from './../../plugins/dashboard-plugin';

import Global from './../../mixins/global';

import {Step, Steps} from 'element-ui';

// plugin setup
Vue.use(DashboardPlugin, Step, Steps);

const app = new Vue({
    el: '#app',

    mixins: [
        Global
    ],

    components: {
        [Step.name]: Step,
        [Steps.name]: Steps,
    },

    data: function () {
        return {
            active: 3,
        }
    },

    methods: {

    }
});
