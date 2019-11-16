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

const app = new Vue({
    el: '#app',
    mixins: [
        Global
    ],
    data: function () {
        return {
            form: new Form('report'),
            bulk_action: new BulkAction('reports')
        }
    },

    methods: {
        onChangeClass(class_name) {
            axios.get(url + '/common/reports/groups', {
                params: {
                    class: class_name
                }
              })
            .then(response => {
                let options = response.data.data;

                this.$children.forEach(select => {
                    if (select.name == 'group') {
                        select.selectOptions = options;
                    }
                });
            })
            .catch(error => {
            });
        }
    }
});
