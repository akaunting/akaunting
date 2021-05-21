<template>
  <div>
    <h1 class="text-white">{{ translations.companies.title }}</h1>
    <div class="card">
      <div class="card-header wizard-header p-3">
        <el-steps :active="active" finish-status="success" align-center>
          <el-step :title="translations.companies.title"></el-step>
          <el-step :title="translations.currencies.title"></el-step>
          <el-step :title="translations.taxes.title"></el-step>
          <el-step :title="translations.finish.title"></el-step>
        </el-steps>
      </div>
      <form
        ref="form"
        class="w-100"
      >
        <div class="card-body">
          <div class="row mb-4">
            <div class="col-12 mb-4">
              <base-input
                :label="translations.companies.api_key"
                name="api_key"
                data-name="api_key"
                :placeholder="translations.companies.api_key"
                prepend-icon="fas fa-key"
                v-model="model.apiKey"
              />
              <p class="mb-0 mt--3">
                <small>
                  <div v-html="translations.companies.get_api_key"></div>
                </small>
              </p>
            </div>
            <div class="col-6 mb-4">
              <base-input
                type="text"
                :label="translations.companies.tax_number"
                name="tax_number"
                data-name="tax_number"
                :placeholder="translations.companies.tax_number"
                prepend-icon="fas fa-percent"
                v-model="companies.tax_number"
              />
            </div>
            <div class="col-6 mb-4">
              <akaunting-date
                :title="translations.companies.financial_start"
                :placeholder="translations.companies.financial_start"
                prepend-icon="fas fa-calendar"
                :date-config="{
                  dateFormat: 'd-m',
                  allowInput: true,
                  altInput: true,
                  altFormat: 'j F',
                }"
                v-model="model.date"
              ></akaunting-date>
            </div>
            <div class="col-12 mb-4">
              <base-input :label="translations.companies.address">
                <textarea
                  class="form-control"
                  name="address"
                  data-name="address"
                  rows="3"
                  :placeholder="translations.companies.address"
                  v-model="companies.address"
                ></textarea>
              </base-input>
            </div>
            <div class="col-6">
              <base-input :label="translations.companies.logo">
                <akaunting-dropzone-file-upload
                  preview-classes="single"
                >
                </akaunting-dropzone-file-upload>
              </base-input>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="row">
            <div class="col-md-12 text-right">
              <base-button type="success" native-type="button" @click="onEditSave()">{{
                translations.companies.save
              }}</base-button>
              <base-button type="white" native-type="submit" @click="next()">{{
                translations.companies.skip
              }}</base-button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Step, Steps } from "element-ui";
import AkauntingDropzoneFileUpload from "./../../components/AkauntingDropzoneFileUpload";
import AkauntingDate from "./../../components/AkauntingDate";
import MixinsSpaGlobal from "./../../mixins/spa-global";

export default {
  name: "Company",
  mixins: [MixinsSpaGlobal],
  components: {
    [Step.name]: Step,
    [Steps.name]: Steps,
    AkauntingDropzoneFileUpload,
    AkauntingDate,
  },
  props: {
    companies: {
      type: [Object, Array],
    },
    translations: {
      type: [Object, Array],
    },
  },
  data() {
    return {
      active: 0,
      model: {
        apiKey: "503df039-d0bc-4f74-aba8-a6f1d38c645b",
        taxNumber: "",
        address: "",
        date: "01.01",
      },
    };
  },
  methods: {
    next() {
      if (this.active++ > 2);
      this.$router.push("/wizard/currencies");
    },
    onEditSave() {
      this.onEditEvent("PATCH", url + "/wizard/companies", '', '', '');
      this.$router.push("/wizard/currencies");
    }
  },
};
</script>

<style scoped>
form {
  flex-flow: row wrap;
}
</style>
