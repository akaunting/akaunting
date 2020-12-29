<template>
    <div>
        {{ conversion }}
    </div>
</template>

<script>
import Vue from 'vue';

export default {
    name: 'akaunting-currency-conversion',

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
    },

    data() {
        return {
            conversion: '',
        };
    },

    created() {
        this.conversion = this.currencyConversionText.replace(':price', this.price).replace(':currency_code', this.currecyCode).replace(':currency_rate', this.currencyRate);
    },
    
    watch: {
        currencyConversionText: function (text) {
            this.conversion = text.replace(':price', this.price).replace(':currency_code', this.currecyCode).replace('', this.currencyRate);
        },

        price: function (price) {
            this.conversion = this.currencyConversionText.replace(':price', price).replace(':currency_code', this.currecyCode).replace(':currency_rate', this.currencyRate);
        },

        currecyCode: function (currecyCode) {
            this.conversion = this.currencyConversionText.replace(':price', this.price).replace(':currency_code', currecyCode).replace(':currency_rate', this.currencyRate);
        },

        currencyRate: function (currencyRate) {
            this.conversion = this.currencyConversionText.replace(':price', this.price).replace(':currency_code', this.currecyCode).replace(':currency_rate', currencyRate);
        },
    },
};
</script>
