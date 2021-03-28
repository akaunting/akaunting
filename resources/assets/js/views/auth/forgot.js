/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./../../bootstrap');

import Vue from 'vue';

import Form from './../../plugins/form';

const app = new Vue({
    el: '#app',

    data: function () {
        return {
            form: new Form('forgot')
        }
    },

    methods: {
        onSubmit() {
            this.form.submit();
        },
    }
});
