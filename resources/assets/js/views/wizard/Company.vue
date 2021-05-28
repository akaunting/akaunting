<template>
  <div>
    <h1 class="text-white">
      {{ translations.company.title }}
    </h1>
    <div class="card">
      <div class="card-header wizard-header p-3">
        <el-steps :active="active" finish-status="success" align-center>
          <el-step :title="translations.company.title"></el-step>
          <el-step :title="translations.currencies.title"></el-step>
          <el-step :title="translations.taxes.title"></el-step>
          <el-step :title="translations.finish.title"></el-step>
        </el-steps>
      </div>
      <form ref="form" class="w-100">
        <div class="card-body">
          <div class="row mb-4">
            <div class="col-12 mb-4">
              <base-input
                :label="translations.company.api_key"
                name="api_key"
                data-name="api_key"
                :placeholder="translations.company.api_key"
                prepend-icon="fas fa-key"
                v-model="company.api_key"
              />
              <p class="mb-0 mt--3">
                <small>
                  <div><a href="https://akaunting.com/dashboard" target="_blank">Click here</a>
                   to get your API key.
                  </div>
                </small>
              </p>
            </div>
            <div class="col-6 mb-4">
              <base-input
                type="text"
                :label="translations.company.tax_number"
                name="tax_number"
                data-name="tax_number"
                :placeholder="translations.company.tax_number"
                prepend-icon="fas fa-percent"
                v-model="company.tax_number"
              />
            </div>
            <div class="col-6 mb-4">
              <akaunting-date
                :title="translations.company.financial_start"
                data-name="financial_start"
                :placeholder="translations.company.financial_start"
                prepend-icon="fas fa-calendar"
                :date-config="{
                  dateFormat: 'd-m',
                  allowInput: true,
                  altInput: true,
                  altFormat: 'j F',
                }"
                v-model="real_date"
              ></akaunting-date>
            </div>
            <div class="col-12 mb-4">
              <base-input :label="translations.company.address">
                <textarea
                  class="form-control"
                  name="address"
                  data-name="address"
                  rows="3"
                  :placeholder="translations.company.address"
                  v-model="company.address"
                ></textarea>
              </base-input>
            </div>
            <div class="col-3">
              <label class="form-control-label">{{ translations.company.logo }}</label>
                <akaunting-dropzone-file-upload
                  ref="dropzoneWizard"
                  preview-classes="single"
                  :attachments="logo"
                  :v-model="logo"
                >
                </akaunting-dropzone-file-upload>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="row">
            <div class="col-md-12 text-right">
              <base-button
               id="button"
                type="success"
                native-type="submit"
                @click="onEditSave()"
                >{{ translations.company.save }}</base-button
              >
              <base-button type="white" native-type="submit" @click="next()">{{
                translations.company.skip
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
    company: {
      type: [Object, Array],
    },
    translations: {
      type: [Object, Array],
    },
    url: {
      type: String,
      default: 'text'
    }
  },
  data() {
    return {
      active: 0,
      logo: [],
      real_date: "",
    };
  },
  mounted() {
    let company_data = this.company;
      this.dataWatch(company_data);
  },
  watch: {
    company: function (company) {
      this.dataWatch(company);
    },
  },
  methods: {
    dataWatch(company) {
      if(Object.keys(company).length) {
         let logo_arr = [
            {
              id: company.logo.id,
              name: company.logo.filename + "." + company.logo.extension,
              path: company.logo.path,
              type: company.logo.mime_type,
              size: company.logo.size,
              downloadPath: false,
            },
      ];
      this.logo.push(logo_arr);
      this.real_date = company.financial_start;
      }
    },

    next() {
      if (this.active++ > 2);
      this.$router.push("/wizard/currencies");
    },

    onEditSave() {
      this.onEditCompany();
      this.$router.push("/wizard/currencies");
    },
  },
};
</script>