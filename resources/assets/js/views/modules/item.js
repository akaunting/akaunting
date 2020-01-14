/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../../bootstrap');

import Vue from 'vue';

import DashboardPlugin from './../../plugins/dashboard-plugin';

import Global from '../../mixins/global';

import {Progress} from 'element-ui';

import AkauntingCarousel from './../../components/AkauntingCarousel';

// plugin setup
Vue.use(DashboardPlugin, Progress);

const app = new Vue({
    el: '#app',

    mixins: [
        Global
    ],

    components: {
        [Progress.name]: Progress,
        AkauntingCarousel
    },

    mounted() {
        this.onGetReviews('', 1);
    },

    data: function () {
        return {
            reviews: '',
            faq: false,
            installation: {
                show: false,
                steps: [],
                steps_total: 0,
                total: 0,
                path: '',
                version: '',
                status: 'success',
                html: ''
            },
        }
    },

    methods: {
        onChangeCategory(category) {
            let path =  document.getElementById('category_page').value;

            if (category) {
                path += '/' + encodeURIComponent(category);
            } else {
                path = app_home;
            }

            location = path;
        },

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
        },

        onInstall(path, name, version) {
            this.installation.show = true;
            this.installation.total = 0;
            this.installation.path = path;
            this.installation.version = version;

            axios.post(url + '/apps/steps', {
                name: name,
                version: version
            })
            .then(response => {
                if (response.data.error) {
                    this.installation.status = 'exception';
                    this.installation.html = '<div class="text-danger">' + response.data.message + '</div>';
                }

                // Set steps
                if (response.data.data) {
                    this.installation.steps = response.data.data;
                    this.installation.steps_total = this.installation.steps.length;

                    this.next();
                }
            })
            .catch(error => {
            });
        },

        next() {
            let data = this.installation.steps.shift();

            if (data) {
                this.installation.total = (100 - ((this.installation.steps.length / this.installation.steps_total) * 100)).toFixed(0);

                this.installation.html = '<span class="text-default"><i class="fa fa-spinner fa-spin update-spin"></i> ' + data['text'] + '</span> </br>';

                axios.post(data.url, {
                    version: this.installation.version,
                    path: this.installation.path,
                })
                .then(response => {
                    if (response.data.error) {
                        this.installation.status = 'exception';
                        this.installation.html = '<div class="text-danger"><i class="fa fa-times update-error"></i> ' + response.data.message + '</div>';
                    }

                    if (response.data.success) {
                        this.installation.status = 'success';
                    }

                    if (response.data.data.path) {
                        this.installation.path = response.data.data.path;
                    }

                    if (!response.data.error && !response.data.redirect) {
                        let self = this;

                        setTimeout(function() {
                            self.next();
                        }, 800);
                    }

                    if (response.data.redirect) {
                        window.location = response.data.redirect;
                    }
                })
                .catch(error => {
                });
            }
        }
    }
});
