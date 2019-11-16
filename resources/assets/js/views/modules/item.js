/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../../bootstrap');

import Vue from 'vue';

import Global from '../../mixins/global';

const app = new Vue({
    el: '#app',

    mixins: [
        Global
    ],

    mounted() {
        this.onGetReviews('', 1);
    },

    data: function () {
        return {
            reviews: '',
            faq: false
        }
    },

    methods: {
        onGetReviews (path, page) {
            axios.post(url + '/apps/' + app_slug  + '/reviews', {
                patth: path,
                page: page
            })
            .then(response => {
                this.reviews = response.data.html;
            })
            .catch(error => {
            });
        },

        onShowFaq() {
            this.faq = true;
        }
    }
});
