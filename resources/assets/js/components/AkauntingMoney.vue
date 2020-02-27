<template>
    <div class="form-group"
        :class="[{'has-error': error}, {'required': required}, {'readonly': readonly}, {'disabled': disabled}, col]">
        <label v-if="title" :for="name" class="form-control-label">{{ title }}</label>

        <div class="input-group input-group-merge" :class="group_class">
            <div v-if="icon" class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fa fa-" :class="icon"></i>
                </span>
            </div>

            <money :name="name" @input="input" :placeholder="placeholder" v-bind="money" :value="value" :disabled="disabled" :masked="masked" class="form-control"></money>
        </div>

        <div class="invalid-feedback d-block" v-if="error" v-html="error"></div>
    </div>
</template>

<script>
import {Money} from 'v-money';

export default {
    name: "akaunting-money",

    components: {
        Money
    },

    props: {
        title: {
            type: String,
            default: '',
            description: "Selectbox label text"
        },
        placeholder: {
            type: String,
            default: '',
            description: "Selectbox input placeholder text"
        },
        name: {
            type: String,
            default: null,
            description: "Selectbox attribute name"
        },
        value: 0,
        icon: {
            type: String,
            description: "Prepend icon (left)"
        },
        group_class: {
            type: String,
            default: null,
            description: "Selectbox disabled status"
        },
        col: {
            type: String,
            default: null,
            description: "Selectbox disabled status"
        },
        error: {
            type: String,
            default: null,
            description: "Selectbox disabled status"
        },
        required: {
            type: Boolean,
            default: false,
            description: "Selectbox disabled status"
        },
        readonly: {
            type: Boolean,
            default: false,
            description: "Selectbox disabled status"
        },
        disabled: {
            type: [Boolean, Number],
            default: false,
            description: "Selectbox disabled status"
        },
        dynamicCurrency: {
            type: Object,
            default: function () {
                return {
                    decimal_mark: '.',
                    thousands_separator: ',',
                    symbol_first: 1,
                    symbol: '$',
                    precision: 2,
                };
            },
            description: "Dynamic currency"
        },
        currency: {
            type: Object,
            default: function () {
                return {
                    decimal_mark: '.',
                    thousands_separator: ',',
                    symbol_first: 1,
                    symbol: '$',
                    precision: 2,
                };
            },
            description: "Default currency"
        },
        masked: {
            type: Boolean,
            default: false,
            description: "Money result value"
        },
    },

    data() {
        return {
            model: this.value,
            money: {
                decimal: this.currency.decimal_mark,
                thousands: this.currency.thousands_separator,
                prefix: (this.currency.symbol_first) ? this.currency.symbol : '',
                suffix: (!this.currency.symbol_first) ? this.currency.symbol : '',
                precision: parseInt(this.currency.precision),
                masked: this.masked
            }
        }
    },

    mounted() {
        this.$emit('interface', this.model);
    },

    methods: {
        change() {
            //this.$emit('change', this.model);
            //this.$emit('interface', this.model);
        },
        input(event) {
            this.model = event;

            //this.$emit('change', this.model);
            //this.$emit('interface', this.model);
        }
    },

    watch: {
        dynamicCurrency: function (currency) {
            this.money = {
                decimal: currency.decimal_mark,
                thousands: currency.thousands_separator,
                prefix: (currency.symbol_first) ? currency.symbol : '',
                suffix: (!currency.symbol_first) ? currency.symbol : '',
                precision: parseInt(currency.precision),
                masked: this.masked
            };
        },
        value: function (value) {
            this.model = value;
        },
        model: function (model) {
            this.$emit('change', this.model);
            this.$emit('interface', this.model);
        }
    },
}
</script>

<style scoped>
    .text-right.input-price .v-money {
        text-align: right;
    }
</style>
