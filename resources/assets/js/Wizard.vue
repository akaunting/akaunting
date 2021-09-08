<template>
    <router-view
      :translations="translations"
      :currencies="currencies"
      :taxes="taxes"
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
            this.taxes = wizard_taxes;
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
                  taxes: {},
                  finish: {},
                },
                company: {},
                countries: {},
                currencies: [],
                currency_codes: [],
                taxes: [],
                modules: {},
                page_loaded: true
            };
        },
    };
</script>

<style>
  .document-loading {
    width: 1140px;
    display: flex;
    align-items: center;
    justify-content: center;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 9999;
  }

  .document-loading div {
    margin-top: unset;
    margin-left: unset;
  }

  .current-tab {
    background-color: #f6f9fc;
  }

  .current-tab-btn {
    text-align: right;
    padding: 0 40px;
  }

  .form-container {
    flex-flow: row wrap;
  }

  .form-container .invalid-feedback {
    position: absolute;
    bottom: -18px;
  }

  .form-container .has-error {
    position: relative;
    margin-bottom: unset !important;
  }

  .form-container .has-error .form-control {
    border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;
    border-right: 1px solid;
  }

  .el-step__icon {
    -webkit-transition: unset;
    transition: unset;
  }

  @media screen and (max-width: 991px) {
    .form-container .has-error {
      position: relative;
      margin-bottom: 1.5rem !important;
    }

    .current-tab-btn {
      padding: 0 15px;
    }

    .form-container {
      flex-direction: column;
    }

    .form-container .form-group {
      width: 100%;
    }
  }
</style>
