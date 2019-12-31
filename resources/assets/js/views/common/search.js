/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./../../bootstrap');

import Vue from 'vue';

import axios from 'axios';

import NProgress from 'nprogress';
import 'nprogress/nprogress.css';
import NProgressAxios from './../../plugins/nprogress-axios';

import clickOutside from './../../directives/click-ouside.js';

Vue.directive('click-outside', clickOutside);

const search = new Vue({
    el: '#global-search',

    data: function () {
        return {
            show: false,
            count:0,
            keyword: '',
            items: {}
        }
    },

    methods:{
        onChange() {
            this.show = false;

            if (this.keyword.length) {
                axios.get(url + '/common/search', {
                    params: {
                        keyword: this.keyword
                    }
                  })
                .then(response => {
                    this.items = response.data;
                    this.count = Object.keys(this.items).length;

                    if (this.count) {
                        this.show = true;
                    }
                })
                .catch(error => {
                });
            }
        },

        closeResult() {
            this.show = false;
            this.count = 0;
            this.items = {};
        }
    }
});
