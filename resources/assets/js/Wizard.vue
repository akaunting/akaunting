<template>
    <router-view
      :translations="translations"
      :currencies="currencies"
      :modules="modules.data"
      :currency_codes="currency_codes"
      :company="company"
      :countries="countries"
      :pageLoad="page_loaded"
    ></router-view>
</template>

<script>
    export default {
        name: "Wizard",

        created() {
            this.translations = wizard_translations;
            this.company = wizard_company;
            this.countries = wizard_countries;
            this.currencies = wizard_currencies;
            this.currency_codes = wizard_currency_codes;
            this.modules = wizard_modules;

            Object.keys(this.currency_codes).map((key) => {
                return this.currency_codes[key];
            });

            this.page_loaded = false;
        },

        data() {
            return {
                translations: {
                    company: {},
                    currencies: {},
                    finish: {},
                },
                company: {},
                countries: {},
                currencies: [],
                currency_codes: [],
                modules: {},
                page_loaded: true
            };
        },
    };
</script>
