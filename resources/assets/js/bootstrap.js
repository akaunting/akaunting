
window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.Laravel.csrfToken;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Content-Type'] = 'multipart/form-data';


// #start NProgress integration
// Added nprogress to use nprogress bar
import NProgress from "nprogress";

window.axios.interceptors.request.use(function (config) {
    // Do something before request is sent
    NProgress.start();

    return config;
}, function (error) {
    // Do something with request error
    console.log(error);

    return Promise.reject(error);
});

// Add a response interceptor
window.axios.interceptors.response.use(function (response) {
    // Do something with response data
    NProgress.done();

    return response;
}, function (error) {
    NProgress.done();
    // Do something with response error
    console.log(error);

    return Promise.reject(error);
});
// #end NProgress integration

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key'
// });

