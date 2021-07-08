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
        :prependIcon="icon"
        :readonly="readonly"
        :disabled="disabled"
        @focus="focus"
        >
        <flat-picker slot-scope="{focus, blur}"
            :name="dataName"
            @on-open="focus"
            @on-close="blur"
            :config="dateConfig"
            class="form-control datepicker"
            v-model="real_model"
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
            const lang = require(`flatpickr/dist/l10n/${this.locale}.js`).default[this.locale];

            this.dateConfig.locale = lang;
        }

        this.real_model = this.value;
    },

    mounted() {
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
            if(this.hiddenYear) {
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
            if(!default_payment_terms) return;

            const dateString = new Date(dateInput);
            const aMillisec = 86400000;
            const dateInMillisecs = dateString.getTime();
            const settingPaymentTermInMs = parseInt(default_payment_terms) * aMillisec;
            const prospectedDueDate = new Date(dateInMillisecs + settingPaymentTermInMs);

            return prospectedDueDate;
        },
    },

    watch: {
        value: function(val) {
            this.real_model = val;
        },

        dateConfig: function() {
            if(!default_payment_terms || this.real_model < this.dateConfig.minDate) {
                 this.real_model = this.dateConfig.minDate;
            }

            if(this.dateConfig.minDate && this.real_model > this.dateConfig.minDate ) {
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
