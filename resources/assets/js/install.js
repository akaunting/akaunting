require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import DashboardPlugin from './plugins/dashboard-plugin';

import Install from './Install.vue';

import Form from './plugins/form';

// plugin setup
Vue.use(DashboardPlugin);
Vue.use(VueRouter);

import Requirements from './views/install/Requirements';
import Language from './views/install/Language';
import Database from './views/install/Database';
import Settings from './views/install/Settings';

import Swiper, { Navigation, Pagination, Autoplay } from 'swiper';
Swiper.use([Navigation, Pagination, Autoplay]);

var global_path = new URL(url).protocol + '//' + window.location.host;
var base_path = url.replace(global_path, '');

const router = new VueRouter({
    mode: 'history',
    base: base_path,
    routes: [
        {
            path:  '/',
            name: 'home',
            component: Requirements
        },
        {
            path:  '/install/requirements',
            name: 'requirements',
            component: Requirements
        },
        {
            path:  '/install/language',
            name: 'language',
            component: Language
        },
        {
            path:  '/install/database',
            name: 'database',
            component: Database
        },
        {
            path:  '/install/settings',
            name: 'settings',
            component: Settings
        }
    ],
    linkActiveClass: 'active',
    scrollBehavior: (to, from ,savedPosition) => {
        if (savedPosition) {
            return savedPosition;
        }

        if (to.hash) {
            return { selector: to.hash };
        }

        return { x: 0, y: 0 };
    }
});

/* eslint-disable no-new */
new Vue({
    el    : '#app',
    render: h => h(Install),
    router,
    mounted() {
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
    }
});
