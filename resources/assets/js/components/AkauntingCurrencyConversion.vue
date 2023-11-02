<template>
    <div class="flex items-center justify-end text-xs mt-3">
        <span>{{ texts[0] }}</span>
        <money v-bind="{
                decimal: this.currencySymbol.decimal_mark,
                thousands: this.currencySymbol.thousands_separator,
                prefix: (this.currencySymbol.symbol_first) ? this.currencySymbol.symbol : '',
                suffix: (!this.currencySymbol.symbol_first) ? this.currencySymbol.symbol : '',
                precision: parseInt(this.currencySymbol.precision),
                masked: true
            }"
            :value="price"
            disabled size="5"
            masked
            class="disabled-money text-right mr-1 js-conversion-input text-xs px-1"
        ></money>

        <span class="mr-2">
            {{ texts[1] }}
        </span>

        <input name="currency_rate"
            v-model="rate"
            @input="onChange"
            class="text-sm px-3 py-2.5 mt-1 rounded-lg border border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple w-16 h-10 text-right js-conversion-input"
        />
    </div>
</template>

<script>
    import {Money} from 'v-money';

    export default {
        name: 'akaunting-currency-conversion',

        components: {
            Money
        },

        props: {
            currencyConversionText: {
                type: String,
                default: 'Currency conversion'
            },

            price: {
                type: String,
                default: 'sale'
            },

            currecyCode: {
                type: String,
                default: 'USD'
            },

            currencyRate: {
                default: 1.000,
            },

            currencySymbol: {
            default: {}
            }
        },

        data() {
            return {
                conversion: '',
                rate: this.currencyRate,
                texts: [],
            };
        },

        created() {
            let conversion = this.currencyConversionText.split(':price');

            if (conversion[0]) {
                this.texts.push(conversion[0]);
            }
            
            if (conversion[1]) {
                this.texts.push(conversion[1].replace(':currency_code', company_currency_code).replace(':currency_rate', ''));
            }
        },

        methods: {
            onChange() {
                this.$emit('change', this.rate);

                this.currencySymbol.rate = this.rate;
            }
        },

        watch: {
            currencyConversionText: function (text) {
                this.conversion = text.replace(':price', this.price).replace(':currency_code', this.currecyCode);
            },

            price: function (price) {
                this.conversion = this.currencyConversionText.replace(':price', price).replace(':currency_code', this.currecyCode).replace();
            },

            currecyCode: function (currecyCode) {
                this.conversion = this.currencyConversionText.replace(':price', this.price).replace(':currency_code', this.currecyCode).replace();
            },

            currencyRate: function (currencyRate) {
                this.rate = currencyRate;

                this.conversion = this.currencyConversionText.replace(':price', this.price).replace(':currency_code', this.currecyCode).replace();
            },

            currencySymbol: function (currencySymbol) {
                this.conversion = this.currencyConversionText.replace(':price', this.price).replace(':currency_code', this.currecyCode).replace();
            },
        },
    };
</script>
