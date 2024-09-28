<template>
    <div
        @click="tryClose"
        data-notify="container"
        :class="[
            'alert alert-notify',
            'fixed w-full sm:w-500 flex items-center justify-between',
            {
                'rtl:right-0 ltr:left-0' : horizontalAlign == 'left',
                'sm:rtl:right-4 sm:ltr:left-4' : horizontalAlign == 'left',
            },
            {
                'ltr:right-0 rtl:left-0' : horizontalAlign == 'right',
                'sm:ltr:right-4 sm:rtl:left-4' : horizontalAlign == 'right',
            },
            'p-4',
            'text-black font-bold',
            'rounded-lg',
            { 
                'alert-with-icon': icon
            },
            verticalAlign,
            horizontalAlign,
            alertType
        ]"
        role="alert"
        :style="customPosition"
        style="z-index: 100;"
        data-notify-position="top-center"
    >
        <div class="flex items-center ltr:pr-3 rtl:pl-3">
            <template v-if="icon || $slots.icon">
                <slot name="icon">
                    <span class="alert-icon flex items-center ltr:mr-2 rtl:ml-2" data-notify="icon">
                        <span class="material-icons text-2xl">{{ icon }}</span>
                    </span>
                </slot>
            </template>

            <span class="alert-text">
                <span v-if="title" class="title">
                    <b>{{ title }}<br/></b>
                </span>

                <span v-if="message" v-html="message"></span>

                <content-render v-if="!message && component" :component="component"></content-render>
            </span>
        </div>

        <slot name="dismiss-icon">
            <button type="button"
                class="close text-2xl"
                data-dismiss="alert"
                aria-label="Close"
                @click="close"
            >
                <span aria-hidden="true">×</span>
            </button>
        </slot>
    </div>
</template>

<script>
    export default {
        name: 'notification',

        components: {
            contentRender: {
                props: ['component'],
                render: h => h(this.component)
            }
        },

        props: {
            message: String,

            title: {
                type: String,
                description: 'Notification title'
            },

            icon: {
                type: String,
                description: 'Notification icon'
            },

            verticalAlign: {
                type: String,
                default: 'top',
                validator: value => {
                    let acceptedValues = ['top', 'bottom'];

                    return acceptedValues.indexOf(value) !== -1;
                },
                description: 'Vertical alignment of notification (top|bottom)'
            },

            horizontalAlign: {
                type: String,
                default: 'right',
                validator: value => {
                    let acceptedValues = ['left', 'center', 'right'];

                    return acceptedValues.indexOf(value) !== -1;
                },
                description: 'Horizontal alignment of notification (left|center|right)'
            },

            type: {
                type: String,
                default: 'info',
                validator: value => {
                    let acceptedValues = [
                        'default',
                        'info',
                        'primary',
                        'danger',
                        'warning',
                        'success'
                    ];

                    return acceptedValues.indexOf(value) !== -1;
                },
                description: 'Notification type of notification (gray-300|blue-300|gray-300|red-300|orange-300|green-300)'
            },

            timeout: {
                type: Number,
                default: 5000,
                validator: value => {
                    return value >= 0;
                },
                description: 'Notification timeout (closes after X milliseconds). Default is 5000 (5s)'
            },

            timestamp: {
                type: Date,
                default: () => new Date(),
                description: 'Notification timestamp (used internally to handle notification removal correctly)'
            },

            component: {
                type: [Object, Function],
                description: 'Custom content component. Cane be a `.vue` component or render function'
            },

            showClose: {
                type: Boolean,
                default: true,
                description: 'Whether to show close button'
            },

            closeOnClick: {
                type: Boolean,
                default: true,
                description: 'Whether to close notification when clicking it\' body'
            },

            clickHandler: {
                type: Function,
                description: 'Custom notification click handler'
            }
        },

        data() {
            return {
                elmHeight: 0,

                typeByClass: {
                    'default': 'black-100',
                    'info':    'blue-100',
                    'primary': 'black-100',
                    'danger':  'red-100',
                    'warning': 'orange-100',
                    'success': 'green-100',
                },

                textByClass: {
                    'default': 'black-600',
                    'info':    'blue-600',
                    'primary': 'black-600',
                    'danger':  'red-600',
                    'warning': 'orange-600',
                    'success': 'green-600',
                }
            };
        },

        computed: {
            hasIcon() {
                return this.icon && this.icon.length > 0;
            },

            alertType() {
                return `bg-${this.typeByClass[this.type]} text-${this.textByClass[this.type]}`;
            },

            customPosition() {
                let initialMargin = 20;
                let alertHeight = this.elmHeight + 10;

                let sameAlertsCount = this.$notifications.state.filter(alert => {
                    return (
                        alert.horizontalAlign === this.horizontalAlign &&
                        alert.verticalAlign === this.verticalAlign &&
                        alert.timestamp <= this.timestamp
                    );
                }).length;

                if (this.$notifications.settings.overlap) {
                    sameAlertsCount = 1;
                }

                let pixels = (sameAlertsCount - 1) * alertHeight + initialMargin;

                if (sameAlertsCount > 1) {
                    pixels = 30 + this.$parent.children[sameAlertsCount - 2].elm.offsetHeight;
                }

                let styles = {};

                styles.zIndex = 100;

                if (this.verticalAlign === 'top') {
                    styles.top = `${pixels}px`;
                } else {
                    styles.bottom = `${pixels}px`;
                }

                return styles;
            }
        },

        methods: {
            close() {
                this.$emit('close', this.timestamp);
            },

            tryClose(evt) {
                if (this.clickHandler) {
                    this.clickHandler(evt, this);
                }

                if (this.closeOnClick) {
                    this.close();
                }
            }
        },

        mounted() {
            this.elmHeight = this.$el.clientHeight;

            if (this.timeout) {
                setTimeout(this.close, this.timeout);
            }
        },
    };
</script>
