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

import {ColorPicker} from 'element-ui';

// plugin setup
Vue.use(DashboardPlugin, ColorPicker);

const app = new Vue({
    el: '#app',

    mixins: [
        Global
    ],

    components: {
        [ColorPicker.name]: ColorPicker,
    },

    mounted() {
        this.color = this.form.color;
    },

    data: function () {
        return {
            form: new Form('category'),
            bulk_action: new BulkAction('categories'),
            color: '#55588b',
            predefineColors: [
                '#3c3f72',
                '#55588b',
                '#e5e5e5',
                '#328aef',
                '#efad32',
                '#ef3232',
                '#efef32'
            ]
        }
    },

    methods: {
        onChangeColor() {
            this.form.color = this.color;
        },

        onChangeColorInput() {
            this.color = this.form.color;
        }
    }
});
