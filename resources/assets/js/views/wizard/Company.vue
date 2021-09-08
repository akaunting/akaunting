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

            <form ref="form" class="w-100 mb-0">
                <div class="card-body">
                    <div class="document-loading" v-if="pageLoad">
                        <div>
                            <i class="fas fa-spinner fa-pulse fa-7x"></i>
                        </div>
                    </div>

                    <div class="row mb-0">
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
                                    <div>
                                        <a href="https://akaunting.com/dashboard" target="_blank">Click here</a>
                                        to get your API key.
                                    </div>
                                </small>
                            </p>
                        </div>

                        <div class="col-6">
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

                        <div class="col-6">
                            <akaunting-date
                            :title="translations.company.financial_start"
                                data-name="financial_start"
                                :placeholder="translations.company.financial_start"
                                icon="fas fa-calendar"
                                :date-config="{
                                  dateFormat: 'd-m',
                                  allowInput: false,
                                  altInput: true,
                                  altFormat: 'j F'
                                }"
                                v-model="real_date"
                            ></akaunting-date>
                        </div>

                        <div class="col-12">
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

                        <div class="col-6">
                            <base-input :label="translations.company.country">
                                <el-select name="country" v-model="company.country" filterable>
                                    <template slot="prefix">
                                        <span class="el-input__suffix-inner el-select-icon">
                                            <i :class="'select-icon-position el-input__icon fas fa-globe-americas'"></i>
                                        </span>
                                    </template>
                                    <el-option
                                        v-for="(country, index) in sortedCountries"
                                        :key="index"
                                        :label="country.value"
                                        :value="country.key"
                                    >
                                    </el-option>
                                </el-select>
                            </base-input>
                        </div>

                        <div class="col-6 mb-0">
                            <label class="form-control-label">{{  translations.company.logo }}</label>
                            <akaunting-dropzone-file-upload
                                ref="dropzoneWizard"
                                class="form-file"
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
                                native-type="button"
                                @click="onEditSave()"
                                >{{ translations.company.save }}</base-button>

                            <base-button type="white" native-type="submit" @click="next()">{{ translations.company.skip }}</base-button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
  </div>
</template>

<script>
import { Step, Steps, Select, Option } from "element-ui";
import AkauntingDropzoneFileUpload from "./../../components/AkauntingDropzoneFileUpload";
import AkauntingDate from "./../../components/AkauntingDate";
import WizardAction from "./../../mixins/wizardAction";

export default {
    name: "Company",

    mixins: [WizardAction],

    components: {
        [Step.name]: Step,
        [Steps.name]: Steps,
        [Select.name]: Select,
        [Option.name]: Option,
        AkauntingDropzoneFileUpload,
        AkauntingDate,
    },

    props: {
        company: {
            type: [Object, Array],
        },

        countries: {
            type: [Object, Array],
        },

        translations: {
            type: [Object, Array],
        },

        url: {
            type: String,
            default: "text",
        },

        pageLoad: {
          type: [Boolean, String]
        },

        locale: {
            type: String,
        },

        dateConfig: {
            type: Object,
            default: function () {
                return {

                };
            },
            description: "FlatPckr date configuration"
        },
    },

    data() {
        return {
            active: 0,
            logo: [],
            real_date: "",
            lang_data: '',
            sorted_countries: [],
        };
    },

    created() {
        if (document.documentElement.lang) {
            let lang_split = document.documentElement.lang.split("-");

            if (lang_split[0] !== 'en') {
                const lang = require(`flatpickr/dist/l10n/${lang_split[0]}.js`).default[lang_split[0]];

                this.dateConfig.locale = lang;
            }
        }

        this.setSortedCountries();
    },

    computed: {
        sortedCountries() {
            this.sorted_countries.sort(this.sortBy('value'));

            return this.sorted_countries;
        },
    },

    mounted() {
        let company_data = this.company;

        this.onDataWatch(company_data);
    },

    methods: {
        sortBy(option) {
            return (firstEl, secondEl) => {
                let first_element = firstEl[option].toUpperCase(); // ignore upper and lowercase
                let second_element = secondEl[option].toUpperCase(); // ignore upper and lowercase

                if (first_element < second_element) {
                    return -1;
                }

                if (first_element > second_element) {
                    return 1;
                }

                // names must be equal
                return 0;
            }
        },

        setSortedCountries() {
            // Reset sorted_countries
            this.sorted_countries = [];

            let created_options = this.countries;

            // Option set sort_option data
            if (!Array.isArray(created_options)) {
                for (const [key, value] of Object.entries(created_options)) {
                    this.sorted_countries.push({
                        key: key.toString(),
                        value: value
                    });
                }
            } else {
                created_options.forEach(function (option, index) {
                    if (typeof(option) == 'string') {
                        this.sorted_countries.push({
                            index: index,
                            key: index.toString(),
                            value: option
                        });
                    } else {
                        this.sorted_countries.push({
                            index: index,
                            key: option.id.toString(),
                            value: (option.title) ? option.title : (option.display_name) ? option.display_name : option.name
                        });
                    }
                }, this);
            }
        },

        onDataWatch(company) {
            if (Object.keys(company).length) {
                if (company.logo) {
                    let logo_arr = [{
                        id: company.logo.id,
                        name: company.logo.filename + "." + company.logo.extension,
                        path: company.logo.path,
                        type: company.logo.mime_type,
                        size: company.logo.size,
                        downloadPath: false,
                    }];

                    this.logo.push(logo_arr);
                }

                this.real_date = company.financial_start;
            }
        },

        onEditSave() {
            FormData.prototype.appendRecursive = function (data, wrapper = null) {
                for (var name in data) {
                    if (name == "previewElement" || name == "previewTemplate") {
                        continue;
                    }

                    if (wrapper) {
                        if (
                        (typeof data[name] == "object" || Array.isArray(data[name])) &&
                        data[name] instanceof File != true &&
                        data[name] instanceof Blob != true
                        ) {
                            this.appendRecursive(data[name], wrapper + "[" + name + "]");
                        } else {
                            this.append(wrapper + "[" + name + "]", data[name]);
                        }
                    } else {
                        if (
                        (typeof data[name] == "object" || Array.isArray(data[name])) &&
                        data[name] instanceof File != true &&
                        data[name] instanceof Blob != true
                        ) {
                            this.appendRecursive(data[name], name);
                        } else {
                            this.append(name, data[name]);
                        }
                    }
                }
            };

            const formData = new FormData(this.$refs["form"]);

            let data_name = {};

            for (let [key, val] of formData.entries()) {
                Object.assign(data_name, {
                    [key]: val,
                });
            }

            let logo = '';

            if (this.$refs.dropzoneWizard.files[1]) {
                logo = this.$refs.dropzoneWizard.files[1];
            } else if (this.$refs.dropzoneWizard.files[0]) {
                logo = this.$refs.dropzoneWizard.files[0];
            }

            Object.assign(data_name, {
                ["logo"]: logo,
                ["_prefix"]: "company",
                ["_token"]: window.Laravel.csrfToken,
                ["_method"]: "POST",
            });

            formData.appendRecursive(data_name);

            window.axios({
                method: "POST",
                url: url + "/wizard/companies",
                data: formData,
                headers: {
                    "X-CSRF-TOKEN": window.Laravel.csrfToken,
                    "X-Requested-With": "XMLHttpRequest",
                    "Content-Type": "multipart/form-data",
                },
            })
            .then((response) => {
                this.onSuccessMessage(response);

                this.$router.push("/wizard/currencies");
            }, this)
            .catch((error) => {
            }, this);
        },

        next() {
            if (this.active++ > 2);

            this.$router.push("/wizard/currencies");
        },
    },

    watch: {
        company: function (company) {
            this.onDataWatch(company);
        },
    },
};
</script>
