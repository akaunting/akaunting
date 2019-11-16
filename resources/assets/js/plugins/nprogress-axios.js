import axios from "axios";
import NProgress from "nprogress";

axios.interceptors.request.use(function (config) {
    // Do something before request is sent
    NProgress.start();

    return config;
}, function (error) {
    // Do something with request error
    console.log(error);

    return Promise.reject(error);
});

// Add a response interceptor
axios.interceptors.response.use(function (response) {
    // Do something with response data
    NProgress.done();

    return response;
}, function (error) {
    NProgress.done();
    // Do something with response error
    console.log(error);

    return Promise.reject(error);
});
