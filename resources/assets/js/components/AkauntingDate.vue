<template>
    <base-input :label="title"
        :name="name"
        :class="[
            {'readonly': readonly},
            {'disabled': disabled},
            formClasses
        ]"
        :footer-error="formError"
        :prependIcon="icon"
        :readonly="readonly"
        :disabled="disabled"
        >
        <flat-picker slot-scope="{focus, blur}"
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
        }
    },

    data() {
        return {
            real_model: this.model,
        }
    },
    
    created() {
        if (this.locale !== 'en') {
            const lang = require(`flatpickr/dist/l10n/${this.locale}.js`).default[this.locale];

            this.dateConfig.locale = lang;
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
        }
    }
}
</script>
