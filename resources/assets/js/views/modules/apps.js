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
import { Progress } from 'element-ui';
import AkauntingSlider from './../../components/AkauntingSlider.vue';

// plugin setup
Vue.use(DashboardPlugin, Progress);

const app = new Vue({
    el: '#app',

    mixins: [
        Global
    ],

    components: {
        [Progress.name]: Progress,
        AkauntingSlider
    },

    created() {
        document.addEventListener('click', this.closeIfClickedOutside);
    },

    mounted() {
        if (typeof app_slug !== 'undefined') {
            this.onReleases(1);
            this.onReviews(1);
        }
    },

    data: function () {
        return {
            keyword: '',
            form: new Form('form-app'),
            page: 2,
            current_page: 1,

            releases: {
                status: false,
                html: '',
                pagination: {
                    current_page: 1,
                    last_page: 1
                }
            },

            reviews: {
                status: false,
                html: '',
                pagination: {
                    current_page: 1,
                    last_page: 1
                }
            },

            page: {
                reviews: 2,
                releases: 2,
            },

            reviewPage: 2,

            faq: false,

            installation: {
                show: false,
                steps: [],
                steps_total: 0,
                total: 0,
                path: '',
                alias: '',
                version: '',
                status: 'success',
                html: ''
            },

            addToCartLoading: false,
            loadMoreLoading: false,
            live_search: {
                data: [],
                modal: false,
                not_found: false
            },
            route_url: url
        }
    },

    methods: {
        addToCart(alias, subscription_type) {
            this.addToCartLoading = true;

            let add_to_cart_promise = Promise.resolve(axios.get(url + '/apps/' + alias + '/' + subscription_type +'/add'));

            add_to_cart_promise.then(response => {
                if (response.data.success) {
                    this.$notify({
                        verticalAlign: 'bottom',
                        horizontalAlign: 'left',
                        message: response.data.message,
                        timeout: 0,
                        icon: "shopping_cart_checkout",
                        type: 'success'
                    });
                }

                this.addToCartLoading = false;
            })
            .catch(error => {
                this.addToCartLoading = false;
            });
        },

        async onloadMore() {
            this.loadMoreLoading = true;
            this.current_page++;

            let path = document.getElementById('see_more_path').value;
            let alias = '';
            let keyword = '';

            if (document.getElementById('see_more_alias')) {
                alias = document.getElementById('see_more_alias').value;
            }

            if (document.getElementById('see_more_keyword')) {
                keyword = document.getElementById('see_more_keyword').value;
            }

            if (this.keyword !== '') {
                keyword = this.keyword;
            }

            document.getElementById("button-pre-load").setAttribute("disabled", true);

            let more_promise = Promise.resolve(window.axios.post(path, {
                page: this.current_page,
                alias: alias,
                keyword: keyword,
            }));

            more_promise.then(response => {
                if (response.data.success) {
                    document.querySelector("[data-apps-content]").innerHTML += response.data.html;
                }

                if (response.data.last_page == this.current_page) {
                    document.getElementById("button-pre-load").remove();
                }

                this.loadMoreLoading = false;
            })
            .catch(error => {
                this.loadMoreLoading = false;
            });
        },

        async onReleases(page) {
            let releases_promise = Promise.resolve(window.axios.post(url + '/apps/' + app_slug  + '/releases', {
                page: page
            }));

            releases_promise.then(response => {
                if (response.data.success) {
                    this.releases.status= true;
                    this.releases.html = response.data.html;

                    this.releases.pagination.current_page = page;
                    this.releases.pagination.last_page = response.data.data.last_page;
                }
            })
            .catch(error => {
            });
        },

        async onReviews(page) {
            let reviews_promise = Promise.resolve(window.axios.post(url + '/apps/' + app_slug  + '/reviews', {
                page: page
            }));

            reviews_promise.then(response => {
                if (response.data.success) {
                    this.reviews.status= true;
                    this.reviews.html = response.data.html;

                    this.reviews.pagination.current_page = page;
                    this.reviews.pagination.last_page = response.data.data.last_page;
                }
            })
            .catch(error => {
            });
        },

        onShowFaq() {
            this.faq = true;
        },

        async onInstall(path, alias, name, version) {
            this.installation.alias = alias;
            this.installation.show = true;
            this.installation.total = 0;
            this.installation.path = path;
            this.installation.version = version;

            let steps_promise = Promise.resolve(axios.post(url + '/apps/steps', {
                name: name,
                alias: alias,
                version: version
            }));

            steps_promise.then(response => {
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

        async next() {
            let data = this.installation.steps.shift();

            if (data) {
                this.installation.total = parseInt((100 - ((this.installation.steps.length / this.installation.steps_total) * 100)).toFixed(0));

                this.installation.html = '<span class="text-default"><i class="submit-spin absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto"></i> ' + data['text'] + '</span> </br>';

                let step_promise = Promise.resolve(axios.post(data.url, {
                    alias: this.installation.alias,
                    version: this.installation.version,
                    path: this.installation.path,
                }));

                step_promise.then(response => {
                    if (response.data.error) {
                        this.installation.status = 'exception';
                        this.installation.html = '<div class="text-danger"><i class="submit-spin absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto"></i> ' + response.data.message + '</div>';
                    }

                    if (response.data.success) {
                        this.installation.status = 'success';
                    }

                    if (response.data.data.path) {
                        this.installation.path = response.data.data.path;
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
        },

        async onModuleLoadMore(type) {
            this.loadMoreLoading = true;

            let module_promise = Promise.resolve(window.axios.post(url + '/apps/' + app_slug  + '/' + type, {
                page: this.page[type]
            }));

            module_promise.then(response => {
                if (response.data.success) {
                    this.page[type]++;

                    document.querySelector('.js-'+ type + '-content').innerHTML += response.data.html;
                    
                    this.loadMoreLoading = false;
                }
            })
            .catch(error => {
                this.loadMoreLoading = false;
            });
        },

        closeIfClickedOutside(event) {
            let el = this.$refs.liveSearchModal;
            let target = event.target;

            if (el !== target && target.contains(el)) {
                this.live_search.modal = false;
            }
        },

        onLiveSearch(event) {
            let target_length = event.target.value.length;

            if (target_length > 2) {
                window.axios.get(url + '/apps/search?keyword=' + event.target.value)
                .then(response => {
                    this.live_search.data = response.data.data.data;
                    this.live_search.modal = true;
                    this.live_search.not_found = false;
                })
                .catch(error => {
                    this.live_search.not_found = true;
                    this.live_search.data = [];
                    console.log(error);
                })
            } else if (target_length == 0) {
                this.live_search.modal = false;
            }
        }
    }
});
