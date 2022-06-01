import BaseInput from './../components/Inputs/BaseInput';
import Card from './../components/Cards/Card.vue';
import Modal from './../components/Modal.vue';
import StatsCard from './../components/Cards/StatsCard.vue';
import BaseButton from './../components/BaseButton.vue';
import Badge from './../components/Badge.vue';
import BaseAlert from './../components/BaseAlert';
import { Input, Tooltip, Popover } from 'element-ui';
/**
 * You can register global components here and use them as a plugin in your main Vue instance
 */

const GlobalComponents = {
    install(Vue) {
        Vue.component(Badge.name, Badge);
        Vue.component(BaseAlert.name, BaseAlert);
        Vue.component(BaseButton.name, BaseButton);
        Vue.component(Badge.name, Badge);
        Vue.component(BaseInput.name, BaseInput);
        Vue.component(Card.name, Card);
        Vue.component(Modal.name, Modal);
        Vue.component(StatsCard.name, StatsCard);
        Vue.component(Input.name, Input);
        Vue.use(Tooltip);
        Vue.use(Popover);
    }
};

export default GlobalComponents;
