
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./../../bootstrap');

import Vue from 'vue';

import DashboardPlugin from './../../plugins/dashboard-plugin';

import Global from './../../mixins/global';
import {getQueryVariable} from './../../plugins/functions';
import {DatePicker, Tooltip} from 'element-ui';

Vue.use(DatePicker, Tooltip);

// plugin setup
Vue.use(DashboardPlugin, DatePicker, Tooltip);

const app = new Vue({
    el: '#main-body',

    components: {
        [DatePicker.name]: DatePicker,
        [Tooltip.name]: Tooltip,
    },

    mixins: [
        Global
    ],

    data: function () {
        return {
            filter_date: [],
        };
    },

    mounted() {
        let start_date = getQueryVariable('start_date');

        if (start_date) {
            let end_date = getQueryVariable('end_date');

            this.filter_date.push(start_date);
            this.filter_date.push(end_date);
        }
    },

    methods: {
        // Global filter change date column
        onChangeFilterDate() {
            if (this.filter_date) {
                window.location.href = url + '/portal?start_date=' + this.filter_date[0] + '&end_date=' + this.filter_date[1];
            } else {
                window.location.href = url + '/portal';
            }
        },
    }
});
