<template>
    <base-input :label="title"
        :name="name"
        :class="[
            {'readonly': readonly},
            {'disabled': disabled},
            formClasses
        ]"
        :error="formError"
        :prependIcon="icon"
        :readonly="readonly"
        :disabled="disabled"
        >
        <flat-picker slot-scope="{focus, blur}"
            @on-open="focus"
            @on-close="blur"
            :config="config"
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
        config: null,
        icon: {
            type: String,
            description: "Prepend icon (left)"
        }
    },

    data() {
        return {
            real_model: this.model
        }
    },

    mounted() {
        this.real_model = this.value;

        this.$emit('interface', this.real_model);
    },

    methods: {
        change() {
            this.$emit('interface', this.real_model);
        }
    }
}
</script>
