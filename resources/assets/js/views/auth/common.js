/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./../../bootstrap');

import Vue from 'vue';

import Form from './../../plugins/form';
import Notifications from './../../components/NotificationPlugin';
import Swiper, { Navigation, Pagination, Autoplay } from 'swiper';

Vue.use(Notifications);

const login = new Vue({
    el: '#app',

    data: function () {
        return {
            form: new Form('auth'),

            // Activation polling — only active on the thankyou page.
            // isPolling is initialised eagerly so the spinner is visible
            // immediately (no flicker after Vue mounts).
            isPolling: typeof activationPollUrl !== 'undefined',
            pollingMessage: 'Waiting for email verification\u2026',
        }
    },

    mounted() {
        Swiper.use([Navigation, Pagination, Autoplay]);

        new Swiper(".swiper-container", {
            loop: true,
            speed: 1000,
            allowTouchMove: true,
            autoplay: {
                delay: 3000,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
        });

        this.checkNotify();

        // Start polling only when the thankyou page sets this global.
        if (this.isPolling) {
            this.startActivationPolling();
        }
    },

    methods: {
        onSubmit() {
            this.form.submit();
        },

        // Check Default set notify > store / update action
        checkNotify: function () {
            if (! flash_notification) {
                return false;
            }

            flash_notification.forEach(notify => {
                let type = notify.level;

                this.$notify({
                    verticalAlign: 'bottom',
                    horizontalAlign: 'left',
                    message: notify.message,
                    timeout: 5000,
                    icon: '',
                    type
                });
            });
        },

        /**
         * Poll the activation-status endpoint every 3 s.
         *
         * When the server reports the user is activated (invitation consumed),
         * it also logs the user into this session (Session A — the in-app
         * browser that started the OAuth flow) and returns the redirect target.
         * We then navigate to that URL so the OAuth authorization can continue.
         *
         * Security: the poll URL contains a session-bound token generated in
         * Register::thankyou(). Requests from any other session will receive
         * 403 and be silently ignored.
         */
        startActivationPolling: function () {
            var self      = this;
            var pollTimer = null;
            var stopped   = false;

            function poll() {
                if (stopped) return;

                window.axios.get(activationPollUrl, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    withCredentials: true,
                })
                .then(function (res) {
                    // 403 = token mismatch (wrong session) — ignore silently.
                    if (res.status === 403) { return null; }

                    return res.data;
                })
                .then(function (data) {
                    if (!data || !data.activated) { return; }

                    stopped = true;
    
                    clearTimeout(pollTimer);

                    self.pollingMessage = 'Email verified! Redirecting\u2026';

                    // Small delay so the user can read the message.
                    setTimeout(function () {
                        window.location.href = data.redirect;
                    }, 800);
                })
                .catch(function () {
                    // Network error — just retry on the next tick.
                })
                .finally(function () {
                    if (!stopped) {
                        pollTimer = setTimeout(poll, 3000);
                    }
                });
            }

            // First poll after a short initial delay.
            pollTimer = setTimeout(poll, 3000);
        },
    }
});
