/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./../../bootstrap');

import Vue from 'vue';

import Global from './../../mixins/global';

import Form from './../../plugins/form';
import BulkAction from './../../plugins/bulk-action';
import HtmlEditor from './../../components/Inputs/HtmlEditor';

const app = new Vue({
    el: '#app',

    mixins: [
        Global
    ],

    components: {
        HtmlEditor
    },

    data: function () {
        return {
            form: new Form('setting'),
            bulk_action: new BulkAction('settings')
        }
    }
});
