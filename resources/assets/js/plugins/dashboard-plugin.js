// Polyfills for js features used in the Dashboard but not supported in some browsers (mainly IE)
import './../polyfills';
// Notifications plugin. Used on Notifications page
import Notifications from './../components/NotificationPlugin';
// Validation plugin used to validate forms
import VeeValidate from 'vee-validate';
// A plugin file where you could register global components used across the app
import GlobalComponents from './globalComponents';
// A plugin file where you could register global directives
import GlobalDirectives from './globalDirectives';

// element ui language configuration
import lang from 'element-ui/lib/locale/lang/en';
import locale from 'element-ui/lib/locale';

locale.use(lang);

// asset imports

export default {
    install(Vue) {
        Vue.use(GlobalComponents);
        Vue.use(GlobalDirectives);
        Vue.use(Notifications);
        Vue.use(VeeValidate, {
            fieldsBagName: 'veeFields',
            classes      : true,
            validity     : true,
            classNames   : {
                valid  : 'is-valid',
                invalid: 'is-invalid'
            }
        });
    }
};
