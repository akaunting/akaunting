require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import DashboardPlugin from './plugins/dashboard-plugin';

Vue.use(DashboardPlugin);
Vue.use(VueRouter);

import Wizard from './Wizard.vue';
import Company from './views/wizard/Company.vue';
import Currencies from './views/wizard/Currencies.vue';
import Taxes from './views/wizard/Taxes.vue';
import Finish from './views/wizard/Finish.vue';

var global_path = new URL(url).protocol + '//' + window.location.host;
var base_path = url.replace(global_path, '');

const router = new VueRouter({
    mode: 'history',
    base: base_path,
    routes: [
        {
            path: '/wizard',
            name: 'Wizard',
            component: Company
        }, 
        {
            path: '/wizard/companies',
            name: 'Company',
            component: Company
        }, 
        {
            path: '/wizard/currencies',
            name: 'Currencies',
            component: Currencies
        },
        {
            path: '/wizard/taxes',
            name: 'Taxes',
            component: Taxes
        },
        {
            path: '/wizard/finish',
            name: 'Finish',
            component: Finish
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

new Vue({
    el : '#app',
    router,
    render: h => h(Wizard),
});
