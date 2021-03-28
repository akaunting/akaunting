
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./../../bootstrap');

import Vue from 'vue';

import DashboardPlugin from './../../plugins/dashboard-plugin';

import Global from './../../mixins/global';

import {Tooltip} from 'element-ui';

// plugin setup
Vue.use(DashboardPlugin, Tooltip);

const app = new Vue({
    el: '#app',

    components: {
        [Tooltip.name]: Tooltip,
    },

    mixins: [
        Global
    ],
});
