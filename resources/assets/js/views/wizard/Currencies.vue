<template>
    <div class="relative bg-body z-10 rounded-lg shadow-2xl p-5 sm:p-10 full-height-mobile" style="height:675px;">
        <WizardSteps :active_state="active"></WizardSteps>

        <div class="flex flex-col justify-between -mt-5 sm:mt-0 overflow-y-auto" style="height: calc(100% - 53px)">
            <div v-if="pageLoad" class="absolute left-0 right-0 top-0 bottom-0 w-full h-full bg-white rounded-lg flex items-center justify-center z-50">
                <span class="material-icons form-spin animate-spin text-9xl">data_usage</span>
            </div>

            <div class="overflow-x-visible menu-scroll mt-1">
                <form ref="form" class="py-2 align-middle inline-block min-w-full">
                    <table v-if="currencies.length" id="tbl-currencies" class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="flex items-center px-1">
                                <th class="w-4/12 ltr:pr-6 rtl:pl-6 py-3 ltr:text-left rtl:text-right text-sm font-bold text-black tracking-wider">
                                    {{ translations.currencies.name }}
                                </th>

                                <th class="w-4/12 ltr:pr-6 rtl:pl-6 py-3 text-center text-sm font-bold text-black tracking-wider">
                                    {{ translations.currencies.code }}
                                </th>

                                <th class="w-4/12 ltr:pr-6 rtl:pl-6 py-3 ltr:text-right rtl:text-left text-sm font-bold text-black tracking-wider">
                                    {{ translations.currencies.rate }}
                                </th>
                            </tr>
                        </thead>

                        <tbody data-table-body>
                            <tr v-for="(item, index) in currencies" :key="index" data-table-list class="relative flex items-center border-b hover:bg-gray-100 px-1 flex-wrap group/actions">
                                <td :class="current_tab == index ? 'hidden' : ''" class="w-4/12 ltr:pr-6 rtl:pl-6 py-4 ltr:text-left rtl:text-right whitespace-nowrap text-sm font-medium text-black">
                                    {{ item.name }}

                                    <span v-if="item.default" class="cursor-pointer">
                                        <span class="relative auto" data-tooltip-target="wizard-currency-default" data-tooltip-placement="right">
                                            <span class="material-icons-round text-purple text-sm ltr:ml-2 rtl:mr-2">lock</span>
                                        </span>

                                        <div id="wizard-currency-default" role="tooltip"
                                            class="inline-block absolute z-20 py-1 px-2 text-sm font-medium rounded-lg bg-white text-gray-900 w-auto border border-gray-200 shadow-sm whitespace-normal opacity-0 invisible"
                                        >
                                            {{ translations.currencies.default }}
                                            <div 
                                                class="absolute w-2 h-2 before:absolute before:w-2 before:h-2 before:bg-white before:border-gray-200 before:transform before:rotate-45 before:border -left-1 before:border-t-0 before:border-r-0 border-gray-200"
                                                data-popper-arrow
                                            >
                                            </div>
                                        </div>
                                    </span>
                                </td>

                                <td :class="current_tab == index ? 'hidden' : ''" class="w-4/12 ltr:pr-6 rtl:pl-6 py-4 text-center whitespace-nowrap text-sm font-medium text-black">
                                    {{ item.code }} 
                                </td>

                                <td :class="current_tab == index ? 'hidden' : ''" class="w-4/12 relative ltr:pr-6 rtl:pl-6 py-4 ltr:text-right rtl:text-left whitespace-nowrap text-sm font-medium text-black">
                                    {{ item.rate }}

                                    <div class="absolute ltr:right-12 rtl:left-12 -top-4 hidden items-center group-hover/actions:flex">
                                        <button type="button" class="relative bg-white hover:bg-gray-100 border py-0.5 px-1 cursor-pointer group/tooltip index-actions" @click="onEditItem(item, index)">
                                            <span class="material-icons-outlined text-purple text-lg">edit</span>

                                           <div class="inline-block absolute invisible z-20 py-1 px-2 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 shadow-sm opacity-0 whitespace-nowrap -top-10 -left-2 group-hover/tooltip:opacity-100 group-hover/tooltip:visible" data-tooltip-placement="top">
                                                <span>{{ translations.currencies.edit }}</span>
                                                <div class="absolute w-2 h-2 -bottom-1 before:content-[' '] before:absolute before:w-2 before:h-2 before:bg-white before:border-gray-200 before:transform before:rotate-45 before:border before:border-t-0 before:border-l-0" data-popper-arrow></div>
                                            </div>
                                        </button>

                                        <button type="button" class="relative bg-white hover:bg-gray-100 border py-0.5 px-1 cursor-pointer group/tooltip index-actions" @click="onClickDelete(item)">
                                            <span class="material-icons-outlined text-purple text-lg">delete</span>

                                            <div class="inline-block absolute invisible z-20 py-1 px-2 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 shadow-sm opacity-0 whitespace-nowrap -top-10 -left-2 group-hover/tooltip:opacity-100 group-hover/tooltip:visible" data-tooltip-placement="top">
                                                <span>{{ translations.currencies.delete }}</span>
                                                <div class="absolute w-2 h-2 -bottom-1 before:content-[' '] before:absolute before:w-2 before:h-2 before:bg-white before:border-gray-200 before:transform before:rotate-45 before:border before:border-t-0 before:border-l-0" data-popper-arrow></div>
                                            </div>
                                        </button>
                                    </div>
                                </td>

                                <td class="w-full p-0 current-tab" v-if="current_tab == index">
                                    <div class="grid sm:grid-cols-12 gap-x-8 gap-y-6 py-3">
                                        <base-input :label="translations.currencies.name" name="name" data-name="name"
                                            form-classes="sm:col-span-4"
                                            v-model="model.name"
                                            :error="onFailErrorGet('name')"
                                        />

                                        <base-input :label="translations.currencies.code" class="sm:col-span-3" :error="onFailErrorGet('code')">
                                            <el-select name="code" v-model="model.select" @change="onChangeCodeItem(model.select)" filterable>
                                                <el-option
                                                v-for="option in currency_codes"
                                                :key="option"
                                                :label="option"
                                                :value="option"
                                                >
                                                </el-option>
                                            </el-select>
                                        </base-input>

                                        <base-input :label="translations.currencies.rate" name="rate" data-name="rate" :placeholder="translations.currencies.rate"
                                            form-classes="sm:col-span-3"
                                            v-model="model.rate"
                                            :error="onFailErrorGet('rate')"
                                            :disabled="model.default_currency == 1"
                                        />

                                        <base-input :label="translations.currencies.default" class="sm:col-span-2" :error="onFailErrorGet('default_currency')">
                                            <div class="flex items-center mt-1">
                                                <label class="relative w-10 rounded-tl-lg rounded-bl-lg py-2 px-1 text-sm text-center transition-all cursor-pointer" @click="onChangeDefault(1)" v-bind:class="[model.default_currency == 1 ? ['bg-green-500','text-white'] : 'bg-black-100']">
                                                    Yes
                                                    <input type="radio" name="default_currency" id="default-1" class="absolute left-0 opacity-0">
                                                </label>

                                                <label class="relative w-10 rounded-tr-lg rounded-br-lg py-2 px-1 text-sm text-center transition-all cursor-pointer" @click="onChangeDefault(0)" v-bind:class="[model.default_currency == 0 ? ['bg-red-500','text-white'] : 'bg-black-100']">
                                                    No
                                                    <input type="radio" name="default_currency" id="default-0" class="absolute left-0 opacity-0">
                                                </label>
                                            </div>

                                            <input type="hidden" name="default_currency" value="0" v-model="model.default_currency" />
                                        </base-input>

                                        <div class="flex justify-end items-center sm:col-span-12">
                                            <base-button class="flex items-center justify-center px-6 py-1.5 text-base rounded-lg bg-transparent hover:bg-gray-100 ltr:mr-2 rtl:ml-2" @click="onCancelItem()">
                                                {{ translations.currencies.cancel }}
                                            </base-button>

                                            <button
                                                type="submit"
                                                class="relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100"
                                                :disabled="button_loading"
                                                @click="onEditForm(item, $event)"
                                            >
                                                <i
                                                    class="animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto before:absolute before:w-2 before:h-2 before:rounded-full before:animate-submit before:delay-[0.14s]after:absolute after:w-2 after:h-2 after:rounded-full after:animate-submit before:-left-3.5 after:-right-3.5 after:delay-[0.42s]"
                                                    v-if="button_loading"
                                                >
                                                </i>

                                                <span :class="[{'opacity-0': button_loading}]">
                                                    {{ translations.currencies.save }}
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="flex flex-col items-center">
                        <div v-if="new_datas" class="grid sm:grid-cols-12 gap-x-8 gap-y-6 px-1 py-3.5 w-full border-b hover:bg-gray-100">
                            <base-input :label="translations.currencies.name" 
                                name="name" 
                                data-name="name" 
                                :placeholder="translations.currencies.name"
                                class="sm:col-span-4"
                                v-model="model.name"
                                :error="onFailErrorGet('name')"
                            />

                            <base-input :label="translations.currencies.code" class="sm:col-span-3" :error="onFailErrorGet('code')">
                                <el-select name="code" v-model="model.select" @change="onChangeCodeItem(model.select)"filterable>
                                    <el-option
                                        v-for="option in currency_codes"
                                        :key="option"
                                        :label="option"
                                        :value="option"
                                    >
                                    </el-option>
                                </el-select>
                            </base-input>

                            <base-input :label="translations.currencies.rate"
                                name="rate"
                                data-name="rate"
                                :placeholder="translations.currencies.rate"
                                class="sm:col-span-3"
                                v-model="model.rate"
                                :disabled="model.default_currency == 1"
                                :error="onFailErrorGet('rate')"
                            />

                            <base-input :label="translations.currencies.default" class="sm:col-span-2" :error="onFailErrorGet('default_currency')">
                                <div class="flex items-center mt-1">
                                    <label class="relative w-10 rounded-tl-lg rounded-bl-lg py-2 px-1 text-sm text-center transition-all cursor-pointer" @click="onChangeDefault(1)" v-bind:class="[model.default_currency == 1 ? ['bg-green-500','text-white'] : 'bg-black-100']">
                                        Yes
                                        <input type="radio" name="default_currency" id="default-1" class="absolute left-0 opacity-0">
                                    </label>

                                    <label class="relative w-10 rounded-tr-lg rounded-br-lg py-2 px-1 text-sm text-center transition-all cursor-pointer" @click="onChangeDefault(0)" v-bind:class="[model.default_currency == 0 ? ['bg-red-500','text-white'] : 'bg-black-100']">
                                        No
                                        <input type="radio" name="default_currency" id="default-0" class="absolute left-0 opacity-0">
                                    </label>
                                </div>

                                <input type="hidden" name="default_currency" value="0" v-model="model.default_currency" />
                            </base-input>

                            <div class="flex items-center justify-end sm:col-span-12">
                                <base-button class=" flex items-center justify-center px-6 py-1.5 text-base rounded-lg bg-transparent hover:bg-gray-100 ltr:mr-2 rtl:ml-2" @click="new_datas = false">
                                    {{ translations.currencies.cancel }}
                                </base-button>

                                <button type="submit" :disabled="button_loading" class="relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100" @click="onSubmitForm($event)">
                                    <i v-if="button_loading" class="animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto before:absolute before:w-2 before:h-2 before:rounded-full before:animate-submit before:delay-[0.14s] after:absolute after:w-2 after:h-2 after:rounded-full after:animate-submit before:-left-3.5 after:-right-3.5 after:delay-[0.42s]"></i> 
                                    <span :class="[{'opacity-0': button_loading}]">
                                        {{ translations.currencies.save }}
                                    </span>
                                </button>
                            </div>
                        </div>

                        <div v-if="! currencies.length && ! new_datas" class="flex flex-col items-center gap-y-2">
                            <span class="text-dark">
                                {{ translations.currencies.no_currency }}
                            </span>

                            <span class="text-gray-700">
                                {{ translations.currencies.create_currency }}
                            </span>
                        </div>

                        <button v-if="! currencies.length && ! new_datas" type="button" class="relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100 mt-3" @click="onAddItem()">
                            {{ translations.currencies.new_currency }}
                        </button>

                        <div v-if="currencies.length && ! new_datas" class="w-full border-b hover:bg-gray-100" style="height:53px;">
                            <button type="button" class="w-full h-full flex items-center justify-center text-purple font-medium disabled:bg-gray-200" @click="onAddItem()">
                                <span class="material-icons-outlined text-base font-bold ltr:mr-1 rtl:ml-1 pointer-events-none">add</span>
                                <span class="bg-no-repeat bg-0-2 bg-0-full hover:bg-full-2 bg-gradient-to-b from-transparent to-purple transition-backgroundSize">{{ translations.currencies.new_currency }}</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="flex items-center justify-center mt-5 gap-x-10">
                <base-button class="w-1/2  flex items-center justify-center px-6 py-1.5 text-base rounded-lg bg-transparent hover:bg-gray-100" @click="prev()">
                    {{ translations.currencies.previous }}
                </base-button>

                <base-button class="w-1/2 relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100" @click="next()">
                    {{ translations.currencies.next }}
                </base-button>
            </div>
        </div>

        <form id="form-dynamic-component" method="POST" action="#"></form>

        <component v-bind:is="component" @deleted="onDeleteCurrency($event)"></component>

        <notifications></notifications>
    </div>
</template>

<script>
    import { Select, Option } from "element-ui";
    import AkauntingRadioGroup from "./../../components/AkauntingRadioGroup";
    import BulkAction from "./../../plugins/bulk-action";
    import MixinsGlobal from "./../../mixins/global";
    import WizardAction from "./../../mixins/wizardAction";
    import WizardSteps from "./Steps.vue";

    export default {
        name: "Currencies",

        mixins: [
            MixinsGlobal,
            WizardAction
        ],

        components: {
            [Select.name]: Select,
            [Option.name]: Option,
            AkauntingRadioGroup,
            WizardSteps
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

            pageLoad: {
                type: [Boolean, String]
            }
        },

        data() {
            return {
                active: 1,
                empty: false,
            };
        },

        created() {
            this.empty = ! this.currencies.length;
        },

        methods: {
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
                    this.translations.currencies.title,
                    `Confirm Delete <strong>${item.name}</strong> ${this.translations.currencies.title}?`,
                    this.translations.currencies.cancel,
                    this.translations.currencies.delete
                );
            },

            onDeleteCurrency(event) {
                this.empty = this.currencies.length;

                if (event.success) {
                    this.onEjetItem(event, this.currencies, event.currency_id);

                    this.empty = ! this.currencies.length;
                } else {
                    this.component = "";
                    event.important = true;
                    document.body.classList.remove("overflow-hidden");

                    this.onDeleteItemMessage(event);
                }
            },

            onChangeDefault(value) {
                this.model.rate = 1;
                this.model.default_currency = value;
            },

            onChangeCodeItem(code) {
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

                window.axios({
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

                    this.model.rate = response.data.rate;
                }, this);
            },

            onEditForm(item, event) {
                event.preventDefault();

                this.onSubmitEvent(
                    "PATCH",
                    url + "/wizard/currencies/" + item.id,
                    "",
                    this.currencies,
                    item.id
                );
            },

            onSubmitForm(event) {
                event.preventDefault();

                this.onSubmitEvent(
                    "POST",
                    url + "/wizard/currencies",
                    "",
                    this.currencies
                );
            },

            prev() {
                if (this.active-- > 2);
                //history.back()

                this.$router.push("/wizard/companies");
            },

            next() {
                if (this.active++ > 2);

                this.$router.push("/wizard/finish");
            },
        },
    };
</script>
