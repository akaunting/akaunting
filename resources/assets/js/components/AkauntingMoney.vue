<template>
    <div v-if="! rowInput"
        :class="[{'has-error': error}, {'required': required}, {'readonly': readonly}, {'disabled': disabled}, col]">

        <label v-if="title" :for="name" class="text-black text-sm font-medium">
            {{ title }}
            <span v-if="!notRequired" class="text-red ltr:ml-1 rtl:mr-1">*</span>
        </label>

        <div class="relative" :class="group_class">
            <!-- <div class="input-group-prepend absolute left-2 bottom-3 text-light-gray">
                <span class="input-group-text">
                    <span class="material-icons w-4 h-5 text-sm">{{ icon }}</span>
                </span>
            </div> -->

            <money :name="name" @input="input" :placeholder="placeholder" v-bind="money" :value="model" :disabled="disabled" :masked="masked" class="input-money" :class="moneyClass"></money>
        </div>

        <div class="text-red text-sm mt-1 block" v-if="error" v-html="error"></div>
    </div>

    <div v-else
        :class="[{'has-error': error}, {'required': required}, {'readonly': readonly}, {'disabled': disabled}, col]">

        <label v-if="title" :for="name" class="text-black text-sm font-medium">
            {{ title }}
        </label>

        <div v-if="icon" :class="group_class">
            <div v-if="icon" class="input-group-prepend">
                <span class="input-group-text">
                    <i :class="'fa fa-' + icon"></i>
                </span>
            </div>

            <money :name="name" @input="input" :placeholder="placeholder" v-bind="money" :value="model" :disabled="disabled" :masked="masked" class="input-money" :class="moneyClass"></money>
        </div>

        <money v-else :name="name" @input="input" :placeholder="placeholder" v-bind="money" :value="model" :disabled="disabled" :masked="masked" class="input-money" :class="moneyClass"></money>

        <div class="text-red text-sm mt-1 block" v-if="error" v-html="error"></div>
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
        moneyClass: {
            type: String,
            default: null,
            description: "Selectbox disabled status"
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
        isDynamic: {
            type: Boolean,
            default: true,
            description: "Currency is dynamic"
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
        rowInput: {
            type: [Boolean, Number],
            default: false,
            description: "Money result value"
        },
        notRequired: {
            type: Boolean,
            default: false
        }
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
        //this.model = this.value;

        if (this.isDynamic) {
            if (this.dynamicCurrency.code != this.currency.code) {
                if (! this.dynamicCurrency.decimal) {
                    this.money = {
                        decimal: this.dynamicCurrency.decimal_mark,
                        thousands: this.dynamicCurrency.thousands_separator,
                        prefix: (this.dynamicCurrency.symbol_first) ? this.dynamicCurrency.symbol : '',
                        suffix: (! this.dynamicCurrency.symbol_first) ? this.dynamicCurrency.symbol : '',
                        precision: parseInt(this.dynamicCurrency.precision),
                        masked: this.masked
                    };
                } else {
                    this.money = this.dynamicCurrency;
                }
            }
        }

        this.$emit('interface', this.model);
    },

    methods: {
        change() {
            //this.$emit('change', this.model);
            //this.$emit('interface', this.model);

            this.$emit('input', this.model);
        },

        input(event) {
            this.model = event;

            this.$emit('input', event);

            //this.$emit('change', this.model);
            //this.$emit('interface', this.model);
        }
    },

    watch: {
        dynamicCurrency: function (currency) {
            if (! currency) {
                return;
            }

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
