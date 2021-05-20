<template>
  <router-view
    :translations="translations"
    :currencies="currencies"
    :taxes="taxes"
    :modules="modules.data"
    :currency_codes="currency_codes"
    :companies="companies"
  ></router-view>
</template>

<script>
export default {
  name: 'Wizard',

  created() {
    let self = this;

    window
      .axios({
        method: "GET",
        url: url + "/wizard/data",
      })
      .then((response) => {
        let data = response.data.data;

        for (let item in data) {
          self[item] = data[item];
        }

        Object.keys(data.currency_codes).map((key) => {
          return data.currency_codes[key];
        });
      });
  },

  data() {
    return {
      currencies: [],
      currency_codes: [],
      taxes: [],
      modules: {},
      companies: {},
      translations: {
        companies: {},
        currencies: {},
        taxes: {},
        finish: {},
      },
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

@media screen and (max-width: 991px) {
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
