/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./../../../../../resources/assets/js/bootstrap');

import Vue from 'vue';

import Global from './../../../../../resources/assets/js/mixins/global';

import Form from './../../../../../resources/assets/js/plugins/form';

const app = new Vue({
    el: '#app',

    mixins: [
        Global
    ],

    data() {
        return {
            form: new Form('offline-payments'),
            confirm: {
                code: '',
                title: '',
                message: '',
                button_cancel: '',
                button_delete: '',
                show: false
            },
        }
    },

    methods:{
        onEdit(event) {
            var code = event.target.dataset.code;

            this.form.loading = true;

            axios.post('offline-payments/get', {
                code: code
            })
            .then(response => {
                this.form.name = response.data.data.name;
                this.form.code = response.data.data.code;
                this.form.customer = response.data.data.customer;
                this.form.order = response.data.data.order;
                this.form.description = response.data.data.description;
                this.form.update = response.data.data.update;
                this.form.loading = false;
            })
            .catch(error => {
                this.form.loading = false;
            });
        },

        // Actions > Delete
        confirmDelete(code, title, message, button_cancel, button_delete) {
            this.confirm.code = code;
            this.confirm.title = title;
            this.confirm.message = message;
            this.confirm.button_cancel = button_cancel;
            this.confirm.button_delete = button_delete;
            this.confirm.show = true;
        },

        cancelDelete() {
            this.confirm.code = '';
            this.confirm.title = '';
            this.confirm.message = '';
            this.confirm.show = false;
        },

        onDelete() {
            axios({
                method: 'DELETE',
                url: 'offline-payments/delete',
                data: {
                    code: this.confirm.code
                }
            })
            .then(response => {
                if (response.data.success) {
                    if (response.data.redirect) {
                        window.location.href = response.data.redirect;
                    }

                    document.getElementById('method-' + this.confirm.code).remove();

                    this.confirm.code = '';
                    this.confirm.title = '';
                    this.confirm.message = '';

                    this.confirm.show = false;
                }
            })
            .catch(error => {
                this.success = false;
            });
        }
    }
});
