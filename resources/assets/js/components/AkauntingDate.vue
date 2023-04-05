<template>
    <base-input :label="title"
        :name="name"
        :class="[
            {'readonly': readonly},
            {'disabled': disabled},
            {'hidden-year': hiddenYear},
            {'data-value-min': dataValueMin},
            formClasses
        ]"
        :footer-error="formError"
        :appendIcon="icon"
        :readonly="readonly"
        :disabled="disabled"
        :not-required="notRequired"
        @focus="focus"
        >
        <flat-picker slot-scope="{focus, blur}"
            :name="dataName"
            @on-open="focus"
            @on-close="blur"
            :config="dateConfig"
            class="datepicker w-full text-sm px-3 py-2.5 mt-1 rounded-lg border border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple"
            v-model="real_model"
            :placeholder="placeholder"
            @input="change"
            :readonly="readonly"
            :disabled="disabled">
        </flat-picker>
    </base-input>
</template>

<script>
import flatPicker from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";

export default {
    name: 'akaunting-date',

    components: {
        flatPicker
    },

    props: {
        title: {
            type: String,
            default: '',
            description: "Modal header title"
        },

        dataName: {
            type: String,
            default: '',
            description: "Modal header title"
        },

        placeholder: {
            type: String,
            default: '',
            description: "Modal header title"
        },

        readonly: {
            type: Boolean,
            default: false,
            description: "Input readonly status"
        },

        notRequired: {
            type: Boolean,
            default: false
        },

        period: {
            type: [Number, String],
            default: "0",
            description: "Payment period"
        },

        disabled: {
            type: Boolean,
            default: false,
            description: "Input disabled status"
        },

        formClasses: null,

        formError: null,

        name: null,

        value: {
            default: null,
            description: "Input value defalut"
        },

        model: {
            default: null,
            description: "Input model defalut"
        },

        dateConfig: {
            type: Object,
            default: function () {
                return {
                    allowInput: true,
                    altFormat: "d M Y",
                    altInput: true,
                    dateFormat: "Y-m-d",
                    wrap: true,
                };
            },
            description: "FlatPckr date configuration"
        },

        icon: {
            type: String,
            description: "Prepend icon (left)"
        },

        locale: {
            type: String,
            default: 'en',
        },

        hiddenYear: {
            type: [Boolean, String]
        },

        dataValueMin: {
            type: [Boolean, String, Date]
        }
    },

    data() {
        return {
            real_model: '',
        }
    },

    created() {
        if (this.locale !== 'en') {
            try {
                const lang = require(`flatpickr/dist/l10n/${this.locale}.js`).default[this.locale];

                this.dateConfig.locale = lang;
            }
            catch (e) {
            }
        }
    },

    mounted() {
        this.real_model = this.value;

        if (this.model) {
            this.real_model = this.model;
        }

        this.$emit('interface', this.real_model);
    },

    methods: {
        change() {
            this.$emit('interface', this.real_model);

            this.$emit('change', this.real_model);
        },

        focus() {
            let date_wrapper_html = document.querySelectorAll('.numInputWrapper');

            if (this.hiddenYear) {
                date_wrapper_html.forEach((wrapper) => {
                    wrapper.classList.add('hidden-year-flatpickr');
                });
            } else {
                date_wrapper_html.forEach((wrapper) => {
                    wrapper.classList.remove('hidden-year-flatpickr');
                });
            }
        },

        addDays(dateInput) {
            if (!this.period) {
                return;
            }

            let dateString = new Date(dateInput);
            let aMillisec = 86400000;
            let dateInMillisecs = dateString.getTime();
            let settingPaymentTermInMs = parseInt(this.period) * aMillisec;
            let prospectedDueDate = new Date(dateInMillisecs + settingPaymentTermInMs);

            return prospectedDueDate;
        },
    },

    watch: {
        value: function(val) {
            this.real_model = val;
        },

        dateConfig: function() {
           if (!this.dateConfig.minDate) {
               return;
           }

            if (this.real_model < this.dateConfig.minDate) {
                this.real_model = this.addDays(this.dateConfig.minDate);
            }
        },
    }
}
</script>

<style>
    .hidden-year-flatpickr {
        display: none !important;
    }
</style>
