/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../../bootstrap');

import Vue from 'vue';

import DashboardPlugin from './../../plugins/dashboard-plugin';

import Global from './../../mixins/global';

import {Progress} from 'element-ui';

Vue.use(Progress);

// plugin setup
Vue.use(DashboardPlugin);

const app = new Vue({
    el: '#app',

    mixins: [
        Global
    ],

    components: {
        [Progress.name]: Progress,
    },

    data: function () {
        return {
            changelog: {
                show:false,
                html: null
            },
            update: {
                steps: [],
                steps_total: 0,
                total: 0,
                path: '',
                status: 'success',
                html: ''
            },
            page: 'check',
            name: null,
            version: null
        }
    },

    mounted() {
        if (document.getElementById('page') != null && document.getElementById('page').value == 'update') {
            this.steps();
        }
    },

    methods: {
        async onChangelog() {
            let changelog_promise = Promise.resolve(axios.get(url + '/install/updates/changelog'));

            changelog_promise.then(response => {
                this.changelog.show = true;
                this.changelog.html = response.data;
            })
            .catch(e => {
                this.errors.push(e)
            })
            .finally(function () {
                // always executed
            });
        },

        async steps() {
            let name = document.getElementById('name').value;
            let version = document.getElementById('version').value;

            let steps_promise = Promise.resolve(axios.post(url + '/install/updates/steps', {
                name: name,
                version: version
            }));

            steps_promise.then(response => {
                if (response.data.error) {
                    this.update.status = 'exception';
                    this.update.html = '<div class="text-danger">' + response.data.message + '</div>';
                }

                // Set steps
                if (response.data.data) {
                    this.update.steps = response.data.data;
                    this.update.steps_total = this.update.steps.length;

                    this.next();
                }
            })
            .catch(error => {
            });
        },

        async next() {
            let data = this.update.steps.shift();

            let name = document.getElementById('name').value;
            let alias = document.getElementById('alias').value;
            let version = document.getElementById('version').value;
            let installed = document.getElementById('installed').value;

            if (data) {
                this.update.total = parseInt((100 - ((this.update.steps.length / this.update.steps_total) * 100)).toFixed(0));

                this.update.html = '<span class="text-default"><i class=""></i> ' + data['text'] + '</span> </br>';

                let step_promise = Promise.resolve(axios.post(data.url, {
                    name: name,
                    alias: alias,
                    version: version,
                    installed: installed,
                    path: this.update.path,
                }));

                step_promise.then(response => {
                    if (response.data.error) {
                        this.update.status = 'exception';
                        this.update.html = '<div class="text-danger"><i class="submit-spin absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto"></i> ' + response.data.message + '</div>';
                    }

                    if (response.data.success) {
                        this.update.status = 'success';
                    }

                    if (response.data.data.path) {
                        this.update.path = response.data.data.path;
                    }

                    if (!response.data.error && !response.data.redirect) {
                        setTimeout(function() {
                            this.next();
                        }.bind(this), 800);
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
