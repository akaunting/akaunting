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
import BulkAction from './../../plugins/bulk-action';
import {getQueryVariable} from './../../plugins/functions';

import AkauntingWidget from './../../components/AkauntingWidget';

import {DatePicker, Tooltip} from 'element-ui';

Vue.use(DatePicker, Tooltip);

// plugin setup
Vue.use(DashboardPlugin);

const dashboard = new Vue({
    el: '#main-body',

    components: {
        [DatePicker.name]: DatePicker,
        [Tooltip.name]: Tooltip,
        AkauntingWidget
    },

    mixins: [
        Global
    ],

    data: function () {
        return {
            widget_modal: false,
            widgets: {},
            widget: {
                id: 0,
                name: '',
                class: '',
                width: '',
                action: 'create',
                sort: 0,
            },
            filter_date: [],
            form: new Form('dashboard'),
            bulk_action: new BulkAction('dashboards')
        };
    },

    mounted() {
        let start_date = getQueryVariable('start_date');

        if (start_date) {
            let end_date = getQueryVariable('end_date');

            this.filter_date.push(start_date);
            this.filter_date.push(end_date);
        }

        this.getWidgets();

        // dashboard slider starting
        const slider = document.getElementById('dashboard-slider');
        const scrollLeft = document.getElementById('dashboard-left');
        const scrollRight = document.getElementById('dashboard-right');

        scrollLeft.addEventListener('click', () => scrollToItem('left'));
        scrollRight.addEventListener('click', () => scrollToItem('right'));

        const isRtl = document.documentElement.dir === 'rtl';

        function scrollToItem(direction) {
            if (direction == 'right') {
                scrollLeft.classList.add('text-purple');
                scrollLeft.classList.remove('text-purple-200');
                scrollLeft.removeAttribute('disabled');
            }

            if (direction == 'left') {
                scrollRight.classList.add('text-purple');
                scrollRight.classList.remove('text-purple-200');

                scrollRight.removeAttribute('disabled');
            }

            const visibleItems = Array.from(slider.children);
            const sliderRect = slider.getBoundingClientRect();

            const currentIndex = visibleItems.findIndex(item => {
                const itemRect = item.getBoundingClientRect();

                return itemRect.left >= sliderRect.left && itemRect.right <= sliderRect.right;
            });

            // In RTL, items flow right-to-left so physical direction is reversed
            const effectiveDirection = isRtl ? (direction === 'right' ? 'left' : 'right') : direction;
            const nextIndex = effectiveDirection === 'right' ? currentIndex + 1 : currentIndex - 1;

            if (nextIndex == 0) {
                const startButton = isRtl ? scrollRight : scrollLeft;
                startButton.classList.add('text-purple-200');
                startButton.classList.remove('text-purple');

                startButton.setAttribute('disabled', 'disabled');
            }

            if (nextIndex >= 0 && nextIndex < visibleItems.length) {
                const nextItem = visibleItems[nextIndex];
                const scrollAmount = nextItem.getBoundingClientRect().left - sliderRect.left;

                slider.scrollBy({
                    left: isRtl ? -scrollAmount : scrollAmount,
                    behavior: 'smooth'
                });
            }

            const tolerance = 5; // Pixel tolerance
            const atEnd = isRtl
                ? Math.abs(slider.scrollLeft) + slider.clientWidth >= slider.scrollWidth - tolerance
                : slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - tolerance;

            if (atEnd) {
                const endButton = isRtl ? scrollLeft : scrollRight;
                endButton.classList.add('text-purple-200');
                endButton.classList.remove('text-purple');

                endButton.setAttribute('disabled', 'disabled');
            }
        }

        function updateSlider() {
            const sliderWidth = slider.clientWidth;
            const windowWidth = window.innerWidth;

            if (sliderWidth <= 850) {
                slider.parentElement.classList.remove('w-9/12', 'w-8/12');

                scrollLeft.classList.add('hidden');
                scrollRight.classList.add('hidden');
            } else {
                if (windowWidth < 1396) {
                    slider.parentElement.classList.remove('w-9/12');
                    slider.parentElement.classList.add('w-8/12');
                } else {
                    slider.parentElement.classList.remove('w-8/12');
                    slider.parentElement.classList.add('w-9/12');
                }

                scrollLeft.classList.remove('hidden');
                scrollRight.classList.remove('hidden');
            }
        }

        updateSlider();

        window.addEventListener('resize', updateSlider);
        // dashboard slider ending
    },

    methods:{
        // Get All Widgets
        getWidgets() {
            var self = this;

            axios.get(url + '/common/widgets')
            .then(function (response) {
                self.widgets = response.data;
            })
            .catch(function (error) {
            });
        },

        // Add new widget on dashboard
        onCreateWidget() {
            this.widget_modal = true;
        },

        // Edit Dashboard selected widget setting.
        onEditWidget(widget_id) {
            var self = this;

            axios.get(url + '/common/widgets/' + widget_id + '/edit')
            .then(function (response) {
                self.widget.id = widget_id;
                self.widget.name = response.data.name;
                self.widget.class = response.data.class;
                self.widget.width = (response.data.settings.raw_width) ? response.data.settings.raw_width : response.data.settings.width;
                self.widget.action = 'edit';
                self.widget.sort = response.data.sort;

                self.widget_modal = true;
            })
            .catch(function (error) {
                self.widget_modal = false;
            });
        },

        onCancel() {
            this.widget_modal = false;

            this.widget.id = 0;
            this.widget.name = '';
            this.widget.class = '';
            this.widget.width = '';
            this.widget.action = 'create';
            this.widget.sort = 0;
        },

        // Global filter change date column
        onChangeFilterDate() {
            if (this.filter_date) {
                window.location.href = url + '?start_date=' + this.filter_date[0] + '&end_date=' + this.filter_date[1];
            } else {
                window.location.href = url;
            }
        },
    }
});
