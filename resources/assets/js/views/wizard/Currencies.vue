<template>
  <div>
    <h1 class="text-white">
      {{ translations.currencies.title }}
    </h1>

    <div class="card">
      <div class="card-header wizard-header p-3">
        <el-steps :active="active" finish-status="success" align-center>
          <el-step :title="translations.companies.title"></el-step>
          <el-step :title="translations.currencies.title"></el-step>
          <el-step :title="translations.taxes.title"></el-step>
          <el-step :title="translations.finish.title"></el-step>
        </el-steps>
      </div>

      <div class="card-body">
        <div class="d-flex justify-content-end mb-3">
          <base-button
            type="success"
            native-type="button"
            class="btn-sm"
            @click="addItem()"
            >{{ translations.currencies.add_new }}</base-button>
        </div>

        <div class="row flex-column">
          <form ref="form">
            <table class="table table-flush table-hover" id="tbl-currencies">
              <thead class="thead-light">
                <tr class="row table-head-line">
                  <th class="col-xs-4 col-sm-4 col-md-3">
                    {{ translations.currencies.name }}
                  </th>
                  <th class="col-md-3 d-none d-md-block">
                    {{ translations.currencies.code }}
                  </th>
                  <th class="col-md-2 d-none d-md-block">
                    {{ translations.currencies.rate }}
                  </th>
                  <th class="col-xs-4 col-sm-4 col-md-2">
                    {{ translations.currencies.enabled }}
                  </th>
                  <th class="col-xs-4 col-sm-4 col-md-2 text-center">
                    {{ translations.currencies.actions }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(item, index) in currencies"
                  :key="index"
                  class="row align-items-center border-top-1"
                >
                  <td class="col-xs-4 col-sm-4 col-md-3">
                    <a href="javascript:void(0);"> {{ item.name }} </a>
                  </td>
                  <td class="col-md-3 d-none d-md-block">{{ item.code }}</td>
                  <td class="col-md-2 d-none d-md-block">{{ item.rate }}</td>
                  <td class="col-xs-4 col-sm-4 col-md-2">
                    <label class="custom-toggle d-inline-block" name="staus-1">
                      <input
                        type="checkbox"
                        :checked="item.enabled"
                        @input="inputHandle(item)"
                      />
                      <span
                        class="custom-toggle-slider rounded-circle status-green"
                        :data-label-on="translations.currencies.yes"
                        :data-label-off="translations.currencies.no"
                      >
                      </span>
                    </label>
                  </td>
                  <td class="col-xs-4 col-sm-4 col-md-2 text-center">
                    <div class="dropdown">
                      <a
                        class="btn btn-neutral btn-sm text-light items-align-center py-2"
                        href="#"
                        role="button"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                      >
                        <i class="fa fa-ellipsis-h text-muted"></i>
                      </a>

                      <div
                        class="dropdown-menu dropdown-menu-right dropdown-menu-arrow"
                      >
                        <button
                          type="button"
                          class="dropdown-item"
                          @click="handeClickEdit(item, index)"
                        >
                          {{ translations.currencies.edit }}
                        </button>
                        <div class="dropdown-divider"></div>
                        <button
                          type="button"
                          class="dropdown-item"
                          @click="handleClickDelete(item)"
                        >
                          {{ translations.currencies.delete }}
                        </button>
                      </div>
                    </div>
                  </td>
                  <td class="w-100 p-0 current-tab" v-if="currentTab == index">
                    <div class="row pt-3 pb-3">
                      <div
                        class="form-container col-12 d-flex justify-content-between align-items-start"
                      >
                        <base-input
                          :label="translations.currencies.name"
                          name="name"
                          data-name="name"
                          :placeholder="translations.currencies.name"
                          prepend-icon="fas fa-font"
                          form-classes="col-md-3"
                          v-model="model.name"
                        />
                        <base-input
                          :label="translations.currencies.code"
                          form-classes="col-md-3"
                        >
                          <el-select
                            name="code"
                            v-model="model.select"
                            required="required"
                            @change="onChangeCode(model.select)"
                            filterable
                          >
                            <template slot="prefix">
                              <span
                                class="el-input__suffix-inner el-select-icon"
                              >
                                <i
                                  :class="'select-icon-position el-input__icon fa fa-code'"
                                ></i>
                              </span>
                            </template>
                            <el-option
                              v-for="option in currency_codes"
                              :key="option"
                              :label="option"
                              :value="option"
                            >
                            </el-option> </el-select
                        ></base-input>
                        <base-input
                          :label="translations.currencies.rate"
                          name="rate"
                          data-name="rate"
                          :placeholder="translations.currencies.rate"
                          prepend-icon="fas fa-percentage"
                          form-classes="col-md-3"
                          required="required"
                          v-model="model.rate"
                        />
                        <div class="mt-4 col-md-3 current-tab-btn">
                          <base-button
                            type="success"
                            native-type="button"
                            @click="onEditSave(item)"
                          >
                            {{ translations.currencies.save }}</base-button
                          >
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>

            <div class="mt-2" v-if="newDatas">
              <div class="row p-3">
                <div
                  class="form-container col-12 d-flex justify-content-between align-items-start"
                >
                  <base-input
                    :label="translations.currencies.name"
                    name="name"
                    data-name="name"
                    :placeholder="translations.currencies.name"
                    prepend-icon="fas fa-font"
                    v-model="model.name"
                  />
                  <base-input :label="translations.currencies.code">
                    <el-select
                      name="code"
                      v-model="model.select"
                      required="required"
                      @change="onChangeCode(model.select)"
                      filterable
                    >
                      <template slot="prefix">
                        <span class="el-input__suffix-inner el-select-icon">
                          <i
                            :class="'select-icon-position el-input__icon fa fa-code'"
                          ></i>
                        </span>
                      </template>
                      <el-option
                        v-for="option in currency_codes"
                        :key="option"
                        :label="option"
                        :value="option"
                      >
                      </el-option> </el-select
                  ></base-input>
                  <base-input
                    :label="translations.currencies.rate"
                    name="rate"
                    data-name="rate"
                    :placeholder="translations.currencies.rate"
                    prepend-icon="fas fa-percentage"
                    v-model="model.rate"
                    required="required"
                  />
                  <div>
                    <div class="d-flex">
                      <akaunting-radio-group
                        name="enabled"
                        :text="translations.currencies.enabled"
                        :enable="translations.currencies.yes"
                        :disable="translations.currencies.no"
                        :value="model.enabled"
                      >
                      </akaunting-radio-group>
                    </div>
                  </div>
                  <div class="mt-4">
                    <base-button
                      type="success"
                      native-type="button"
                      @click="onSubmitForm()"
                      >{{ translations.currencies.save }}</base-button
                    >
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>

        <notifications></notifications>
        <form id="form-dynamic-component" method="POST" action="#"></form>
        <component
          v-bind:is="component"
          @deleted="deleteCurrency($event)"
        ></component>
      </div>

      <div class="card-footer">
        <div class="row">
          <div class="col-md-12 d-flex justify-content-between">
            <base-button type="white" native-type="submit" @click="prev()">{{
              translations.currencies.previous
            }}</base-button>
            <base-button type="white" native-type="submit" @click="next()">{{
              translations.currencies.next
            }}</base-button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Step, Steps, Select, Option } from "element-ui";
import AkauntingRadioGroup from "./../../components/forms/AkauntingRadioGroup";
import Form from "./../../plugins/form";
import BulkAction from "./../../plugins/bulk-action";
import MixinsGlobal from "./../../mixins/global";
import MixinsSpaGlobal from "./../../mixins/spa-global";

export default {
  name: "Currencies",

  mixins: [MixinsGlobal, MixinsSpaGlobal],

  components: {
    [Step.name]: Step,
    [Steps.name]: Steps,
    [Select.name]: Select,
    [Option.name]: Option,
    AkauntingRadioGroup,
  },

  props: {
    currencies: {
      type: [Object, Array],
    },
    currency_codes: {
      type: [Object, Array],
    },
    translations: {
      type: [Object, Array],
    },
  },

  data() {
    return {
      active: 1,
      bulk_action: new BulkAction(url + "/settings/currencies"),
    };
  },

  methods: {
    inputHandle(item) {
      this.onStatus(item.id, event);
    },

    handleClickDelete(item) {
      this.confirmDelete(
        `${
          new URL(url).protocol +
          "//" +
          location.host +
          location.pathname +
          "/" +
          item.id
        }`,
        this.translations.currencies.title,
        `Confirm Delete <strong>${item.name}</strong> ${this.translations.currencies.title}?`,
        this.translations.currencies.cancel,
        this.translations.currencies.delete
      );
    },

    next() {
      if (this.active++ > 2);
      this.$router.push("/wizard/taxes");
    },

    prev() {
      if (this.active-- > 2);
      this.$router.push("/wizard/companies");
    },

    onChangeCode(code) {
      let self = this;

      const formData = new FormData(this.$refs["form"]);
      const data = {
        rate: "",
        precision: "",
        symbol: "",
        symbol_first: "",
        decimal_mark: "",
        thousands_separator: "",
      };
      
      for (let [key, val] of formData.entries()) {
        Object.assign(data, {
          [key]: val,
        });
      }

      window
        .axios({
          method: "GET",
          url: url + "/settings/currencies/config",
          params: {
            code: code,
          },
        })
        .then((response) => {
          data.rate = response.data.rate;
          data.precision = response.data.precision;
          data.symbol = response.data.symbol;
          data.symbol_first = response.data.symbol_first;
          data.decimal_mark = response.data.decimal_mark;
          data.thousands_separator = response.data.thousands_separator;
          self.model.rate = response.data.rate;
        });
    },

    onEditSave(item) {
      this.onEditEvent("PATCH", url + "/wizard/currencies/" + item.id, '', this.currencies, item.id);
    },

    onSubmitForm() {
      this.onSubmitEvent("POST", url + "/wizard/currencies", '', this.currencies);
    },

    deleteCurrency(event) {
      this.currencies.forEach(function (currency, index) {
        if (currency.id == event.currency_id) {
          this.currencies.splice(index, 1);
          return;
        }
      }, this);

      this.component = "";

      let type = event.success ? 'success' : 'error';
      let timeout = 1000;

      if (event.important) {
        timeout = 0;
      }

      this.$notify({
        message: event.message,
        timeout: timeout,
        icon: "fas fa-bell",
        type,
      });
    },
  },
};
</script>
