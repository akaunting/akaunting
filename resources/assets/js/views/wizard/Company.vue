<template>
    <div class="relative bg-body z-10 rounded-lg shadow-2xl p-5 sm:p-10 full-height-mobile" style="height:675px;">
        <WizardSteps :active_state="active"></WizardSteps>

        <div class="flex flex-col justify-between -mt-5 sm:mt-0" style="height: calc(100% - 53px)">
            <div v-if="pageLoad" class="absolute inset-0 w-full h-full bg-white rounded-lg flex items-center justify-center z-50">
                <span class="material-icons form-spin animate-spin text-9xl">data_usage</span>
            </div>

            <div class="overflow-x-visible overflow-y-auto menu-scroll mt-1">
                <form ref="form" class="py-2 align-middle inline-block min-w-full">
                    <div class="relative">
                        <div v-if="pageLoad" class="absolute left-0 right-0 top-0 bottom-0 w-full h-full bg-white rounded-lg flex items-center justify-center z-50">
                            <span class="material-icons form-spin animate-spin text-9xl">data_usage</span>
                        </div>

                        <div class="flex flex-col justify-between -mt-5 sm:mt-0">
                            <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5 menu-scroll gap-10">
                                <div class="sm:col-span-6">
                                    <base-input
                                        not-required
                                        :label="translations.company.api_key"
                                        name="api_key"
                                        data-name="api_key"
                                        :placeholder="translations.company.api_key"
                                        v-model="company.api_key"
                                        :error="onFailErrorGet('api_key')"
                                    />

                                    <div class="mt-2">
                                        <small>
                                            <a href="https://akaunting.com/dashboard" class="text-green" target="_blank">Click here</a>
                                                to get your API key.
                                        </small>
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <base-input
                                        not-required
                                        type="text"
                                        :label="translations.company.tax_number"
                                        name="tax_number"
                                        data-name="tax_number"
                                        :placeholder="translations.company.tax_number"
                                        v-model="company.tax_number"
                                        :error="onFailErrorGet('tax_number')"
                                    />
                                </div>

                                <div class="sm:col-span-3">
                                    <akaunting-date
                                        not-required 
                                        :title="translations.company.financial_start"
                                        data-name="financial_start"
                                        :placeholder="translations.company.financial_start"
                                        icon="calendar_today"
                                        :date-config="{
                                            dateFormat: 'd-m',
                                            allowInput: false,
                                            altInput: true,
                                            altFormat: 'j F'
                                        }"
                                        v-model="company.financial_start"
                                        :form-error="onFailErrorGet('financial_start')"
                                    ></akaunting-date>
                                </div>

                                <div class="sm:col-span-3 grid gap-y-4">
                                    <div class="sm:col-span-3">
                                        <base-input not-required :label="translations.company.address" :error="onFailErrorGet('address')">
                                            <textarea class="w-full text-sm px-3 py-2.5 mt-1 rounded-lg border border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple" name="address" data-name="address" rows="3" :placeholder="translations.company.address" v-model="company.address"></textarea>
                                        </base-input>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <base-input not-required :label="translations.company.country" :error="onFailErrorGet('country')">
                                            <el-select v-model="company.country" filterable>
                                                <el-option
                                                    v-for="(country, index) in sortedCountries"
                                                    :key="index"
                                                    :label="country.value"
                                                    :value="country.key"
                                                >
                                                </el-option>
                                            </el-select>
                                        </base-input>

                                        <input name="country" type="hidden" class="d-none" v-model="company.country">
                                    </div>
                                </div>

                                <div class="sm:col-span-3">
                                    <label class="text-black text-sm font-medium">
                                        {{  translations.company.logo }}
                                    </label>

                                    <akaunting-dropzone-file-upload ref="dropzoneWizard" class="form-file dropzone-column" style="width: 10.8rem; height: 10.8rem;" preview-classes="single" :attachments="logo" :v-model="logo">
                                    </akaunting-dropzone-file-upload>

                                    <div v-if="onFailErrorGet('logo')" class="text-red text-sm mt-1 block" v-html="onFailErrorGet('logo')"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="flex items-center justify-center mt-5 gap-x-10">
                <base-button class="w-1/2  flex items-center justify-center px-6 py-1.5 text-base rounded-lg bg-transparent hover:bg-gray-100" @click="next()">
                    {{ translations.company.skip }}
                </base-button>

                <button
                    type="submit"
                    id="button"
                    :disabled="button_loading_company"
                    class="w-1/2 relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100"
                    @click="onEditSave($event)"
                >
                    <i v-if="button_loading_company" class="animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto before:absolute before:w-2 before:h-2 before:rounded-full before:animate-submit before:delay-[0.14s] after:absolute after:w-2 after:h-2 after:rounded-full after:animate-submit before:-left-3.5 after:-right-3.5 after:delay-[0.42s]"></i> 
                    <span :class="[{'opacity-0': button_loading_company}]">
                        {{ translations.company.save }}
                    </span>
                </button>
            </div>
        </div>

        <form id="form-dynamic-component" method="POST" action="#"></form>

        <component v-bind:is="component" @deleted="onDeleteCurrency($event)"></component>

        <notifications></notifications>
    </div>
</template>

<script>
    import { Select, Option } from "element-ui";
    import AkauntingDropzoneFileUpload from "./../../components/AkauntingDropzoneFileUpload";
    import AkauntingDate from "./../../components/AkauntingDate";
    import WizardAction from "./../../mixins/wizardAction";
    import WizardSteps from "./Steps.vue";

    export default {
        name: "Company",

        mixins: [
            WizardAction
        ],

        components: {
            [Select.name]: Select,
            [Option.name]: Option,
            AkauntingDropzoneFileUpload,
            AkauntingDate,
            WizardSteps
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
                button_loading_company: false
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
                }
            },

            onEditSave(event) {
                event.preventDefault();

                this.button_loading_company = true;
                
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

                this.company.financial_start = data_name.financial_start;

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
                    this.button_loading_company = false;
                }, this)
                .catch((error) => {
                    this.onFailError(error);
                    this.button_loading_company = false;
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
