<template>
  <div>
    <h1 class="text-white">{{ translations.taxes.title }}</h1>
    <div class="card">
      <div class="card-header wizard-header p-3">
        <el-steps :active="active" finish-status="success" align-center>
          <el-step :title="translations.company.title"></el-step>
          <el-step :title="translations.currencies.title"></el-step>
          <el-step :title="translations.taxes.title"></el-step>
          <el-step :title="translations.finish.title"></el-step>
        </el-steps>
      </div>
      <div class="card-body">
        <div class="document-loading" v-if="pageLoad">
          <div>
            <i class="fas fa-spinner fa-pulse fa-7x"></i>
          </div>
        </div>
        <div class="d-flex justify-content-end mb-3">
          <base-button
            type="success"
            native-type="button"
            class="btn-sm"
            @click="onAddItem()"
            >{{ translations.taxes.add_new }}</base-button
          >
        </div>
        <div class="row flex-column">
          <form ref="form">
            <table class="table table-flush table-hover" id="tbl-taxes">
              <thead class="thead-light">
                <tr class="row table-head-line">
                  <th class="col-xs-4 col-sm-4 col-md-3">
                    {{ translations.taxes.name }}
                  </th>
                  <th class="col-md-3 d-none d-md-block">
                    {{ translations.taxes.rate }}
                  </th>
                  <th class="col-xs-4 col-sm-4 col-md-3">
                    {{ translations.taxes.enabled }}
                  </th>
                  <th class="col-xs-4 col-sm-4 col-md-3 text-center">
                    {{ translations.taxes.actions }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(item, index) in taxes"
                  :key="index"
                  class="row align-items-center border-top-1"
                >
                  <td class="col-xs-4 col-sm-4 col-md-3 tax-name">
                    <a href="javascript:void(0);"> {{ item.name }} </a>
                  </td>
                  <td class="col-md-3 d-none d-md-block">{{ item.rate }}</td>
                  <td class="col-xs-4 col-sm-4 col-md-3">
                    <label class="custom-toggle d-inline-block" name="staus-1">
                      <input
                        type="checkbox"
                        :checked="item.enabled"
                        @input="onSwitchUpdate(item)"
                      />
                      <span
                        class="custom-toggle-slider rounded-circle status-green"
                        :data-label-on="translations.taxes.yes"
                        :data-label-off="translations.taxes.no"
                      >
                      </span>
                    </label>
                  </td>
                  <td class="col-xs-4 col-sm-4 col-md-3 text-center">
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
                          @click="onEditItem(item, index)"
                        >
                          {{ translations.taxes.edit }}
                        </button>
                        <div class="dropdown-divider"></div>
                        <button
                          type="button"
                          class="dropdown-item"
                          @click="onClickDelete(item)"
                        >
                          {{ translations.taxes.delete }}
                        </button>
                      </div>
                    </div>
                  </td>
                  <td class="w-100 p-0 current-tab" v-if="current_tab == index">
                    <div class="row pt-3 pb-3">
                      <div
                        class="form-container col-12 d-flex justify-content-between align-items-start"
                      >
                        <base-input
                          :label="translations.taxes.name"
                          name="name"
                          data-name="name"
                          :placeholder="translations.taxes.name"
                          prepend-icon="fas fa-font"
                          form-classes="col-md-4"
                          class="required"
                          v-model="model.name"
                          :error="onFailErrorGet('name')"
                        />
                        <base-input
                          :label="translations.taxes.rate"
                          name="rate"
                          data-name="rate"
                          :placeholder="translations.taxes.rate"
                          prepend-icon="fas fa-percentage"
                          form-classes="col-md-4"
                          class="required"
                          v-model="model.rate"
                          :error="onFailErrorGet('rate')"
                        />
                        <div class="mt-4 col-md-4 current-tab-btn">
                          <base-button
                            type="white"
                            native-type="button"
                            @click="onCancelItem()"
                          >
                            {{ translations.taxes.cancel }}</base-button
                          >
                          <base-button
                            type="success"
                            native-type="button"
                            @click="onEditForm(item)"
                            >{{ translations.taxes.save }}</base-button
                          >
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
                <tr v-if="new_datas">
                  <td class="p-0">
                    <div class="row pt-3 pb-3">
                      <div
                        class="form-container col-12 d-flex justify-content-between align-items-start"
                      >
                        <base-input
                          :label="translations.taxes.name"
                          name="name"
                          data-name="name"
                          :placeholder="translations.taxes.name"
                          prepend-icon="fas fa-font"
                          class="required"
                          v-model="model.name"
                          :error="onFailErrorGet('name')"
                        />
                        <base-input
                          :label="translations.taxes.rate"
                          name="rate"
                          data-name="rate"
                          :placeholder="translations.taxes.rate"
                          prepend-icon="fas fa-percentage"
                          class="required"
                          v-model="model.rate"
                          :error="onFailErrorGet('rate')"
                        />
                        <div>
                          <div class="d-flex">
                            <akaunting-radio-group
                              name="enabled"
                              :text="translations.taxes.enabled"
                              :enable="translations.taxes.yes"
                              :disable="translations.taxes.no"
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
                            >{{ translations.taxes.save }}</base-button
                          >
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </form>
        </div>
        <notifications></notifications>
        <form id="form-dynamic-component" method="POST" action="#"></form>
        <component
          v-bind:is="component"
          @deleted="onDeleteCurrency($event)"
        ></component>
      </div>
      <div class="card-footer">
        <div class="row">
          <div class="col-md-12 d-flex justify-content-between">
            <base-button type="white" native-type="submit" @click="prev()">{{
              translations.taxes.previous
            }}</base-button>
            <base-button type="white" native-type="submit" @click="next()">{{
              translations.taxes.next
            }}</base-button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Step, Steps } from "element-ui";
import AkauntingRadioGroup from "./../../components/forms/AkauntingRadioGroup";
import BulkAction from "./../../plugins/bulk-action";
import MixinsGlobal from "./../../mixins/global";
import WizardAction from "./../../mixins/wizardAction";

export default {
    name: "Taxes",

    mixins: [MixinsGlobal, WizardAction],

    components: {
        [Step.name]: Step,
        [Steps.name]: Steps,
        AkauntingRadioGroup,
    },

    props: {
        taxes: {
            type: [Object, Array],
        },

        translations: {
            type: [Object, Array],
        },

        pageLoad: {
          type: [Boolean, String]
        }
    },

    data() {
        return {
            active: 2,
            bulk_action: new BulkAction(url + "/settings/taxes"),
        };
    },

    methods: {
        onSwitchUpdate(item) {
            this.onStatus(item.id, event);

            this.onStatusControl(this.taxes, item.id, event);
        },

        onClickDelete(item) {
            this.confirmDelete(
                `${
                  new URL(url).protocol +
                  "//" +
                  location.host +
                  location.pathname +
                  "/" +
                  item.id
                }`,
                this.translations.taxes.title,
                `${
                  this.translations.currencies.title +
                  "&nbsp;" +
                  this.translations.currencies.delete
                } <strong>${item.name}</strong>?`,
                this.translations.taxes.cancel,
                this.translations.taxes.delete
            );
        },

        onDeleteCurrency(event) {
            this.onEjetItem(event, this.taxes, event.tax_id);
        },

        onEditForm(item) {
            this.onSubmitEvent(
                "PATCH",
                url + "/wizard/taxes/" + item.id,
                "type",
                this.taxes,
                item.id
            );
        },

        onSubmitForm() {
            this.onSubmitEvent("POST", url + "/wizard/taxes", "type", this.taxes);
        },

        prev() {
            if (this.active-- > 2);
            this.$router.push("/wizard/currencies");
        },

        next() {
            if (this.active++ > 2);
            this.$router.push("/wizard/finish");
        },
    },
};
</script>

<style scoped>
.current-tab-btn {
  padding: 0 80px;
}
</style>
