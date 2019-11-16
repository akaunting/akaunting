<template>
    <base-input :label="title"
            :name="name"
            :class="formClasses"
            :error="formError"
            :prependIcon="icon">
        <flat-picker slot-scope="{focus, blur}"
                     @on-open="focus"
                     @on-close="blur"
                     :config="config"
                     class="form-control datepicker"
                     v-model="real_model"
                     @input="change">
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
        formClasses: null,
        formError: null,
        name: null,
        value: null,
        model: null,
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
