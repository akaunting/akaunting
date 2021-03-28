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
            form: new Form('reset')
        }
    },

    mounted() {
        this.checkNotify();
    },

    methods: {
        // Check Default set notify > store / update action
        checkNotify: function () {
            if (!flash_notification) {
                return false;
            }

            flash_notification.forEach(notify => {
                let type = notify.level;

                this.$notify({
                    message: notify.message,
                    timeout: 5000,
                    icon: 'fas fa-bell',
                    type
                });
            });
        },

        // Form Submit
        onSubmit() {
            this.form.submit();
        },
    }
});
