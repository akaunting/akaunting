<template>
    <div class="relative w-full h-full flex">
        <slot name="search">
            <input
                v-if="! show_date"
                type="text"
                class="w-full h-12 lg:h-auto bg-transparent text-black text-sm border-0 pb-0 focus:outline-none focus:ring-transparent focus:border-purple-100"
                :class="!show_icon ? 'ltr:pr-4 rtl:pl-4' : 'ltr:pr-10 rtl:pl-10'"
                :placeholder="dynamicPlaceholder"
                :ref="'input-search-field-' + id"
                v-model="search"
                @focus="onInputFocus"
                @input="onInput"
                @blur="onBlur"
                @keyup.enter="onInputConfirm"
            />
        </slot>

        <slot name="date">
            <flat-picker
                v-if="show_date"
                @on-open="onInputFocus"
                @blur="onBlur"
                :config="dateConfig"
                class="w-full h-12 lg:h-auto bg-transparent text-black text-sm border-0 pb-0 focus:outline-none focus:ring-transparent focus:border-purple-100 datepicker"
                :class="!show_icon ? 'ltr:pr-4 rtl:pl-4' : 'ltr:pr-10 rtl:pl-10'"
                :placeholder="dynamicPlaceholder"
                :ref="'input-search-date-field-' + id"
                value=""
                @focus="onInputFocus"
                @on-close="onInputDateSelected"
                @keyup.enter="onInputConfirm"
            >
            </flat-picker>
        </slot>

        <button
            v-if="show_icon"
            @focus="onInputFocus"
            v-show="show_button"
            @click="onInputConfirm"
            class="absolute ltr:right-0.5 rtl:left-0.5 z-50 mt-3 text-sm text-gray-700 font-medium px-2.5 py-1 h-7 rounded-lg"
            :class="search.length == 0 ? 'opacity-60 cursor-default' : 'cursor-pointer hover:bg-gray-100'"
            :disabled="search.length == 0"
        >
            <div class="flex">
                <span>search</span>
                <span class="material-icons-outlined text-sm ltr:scale-x-100 rtl:-scale-x-100 ltr:ml-1 rtl:mr-1 mt-0.5">
                    keyboard_return
                </span>
            </div>
        </button>

        <button type="button" class="absolute ltr:right-0 rtl:left-0 top-4 lg:top-2 clear" v-if="show_close_icon" @click="onSearchAndFilterClear">
            <span class="material-icons text-sm">close</span>
        </button>

        <filters
            :id="id"
            :filters="filters"
            :search="search"

            :search-text="searchText"
        ></filters>
    </div>
</template>

<script>
    import {getQueryVariable} from './../../plugins/functions';
    import Filters from "./Filters";
    import flatPicker from "vue-flatpickr-component";
    import "flatpickr/dist/flatpickr.css";

    export default {
        name: 'filter-main',

        components: {
            flatPicker,
            Filters,
        },

        props: {
            id: {
                type: Number,
                default: 0,
                description: '',
            },
            
            placeholder: {
                type: String,
                default: 'Search or filter results...',
                description: 'Input placeholder'
            },

            filters: {
                type: Array|Object,
                default: [],
                description: 'Get Filter List'
            },

            searchText: {
                type: String,
                default: 'Search for this text',
                description: 'Input placeholder'
            },
        },

        data() {
            return {
                search: '', // search cloumn model

                show_date: false,
                show_button: false,
                show_close_icon: false,
                show_icon: true,
                defaultPlaceholder: this.placeholder,
                dynamicPlaceholder: this.placeholder,
            };
        },

        methods: {
            onInputFocus() {
                this.show_button = true;

                if (!this.filter_list.length) {
                    return;
                }

                if (this.filter_last_step != 'values'
                    || (this.filter_last_step == 'values' && this.selected_options[this.filter_index].type != 'date')
                ) {
                    this.visible[this.filter_last_step] = true;

                    this.$nextTick(() => {
                        this.$refs['input-search-field-' + this._uid].focus();
                    });
                } else {
                    this.show_date = true;

                    this.$nextTick(() => {
                        this.$refs['input-search-date-field-' + this._uid].fp.open();
                    });
                }

                this.input_focus = true;
            },

            onBlur() {
                this.input_focus = false;
            },

            onInput(evt) {
                this.search = evt.target.value;
                this.show_button = true;

                let option_url = this.selected_options.length > 0 && this.selected_options[this.filter_index] !== undefined ? this.selected_options[this.filter_index].url : '';

                if (this.search) {
                    if (option_url.indexOf('?') === -1) {
                        option_url += '?search="' + this.search + '" limit:10';
                    } else {
                        if (option_url.indexOf('search=') === -1) {
                            option_url += '&search="' + this.search + '" limit:10';
                        } else {
                            option_url += ' "' + this.search + '" limit:10';
                        }
                    }
                }

                if (option_url) {
                    if (option_url.indexOf('limit') === -1) {
                        option_url += ' limit:10';
                    }

                    window.axios.get(option_url)
                    .then(response => {
                        this.values = [];

                        let data = response.data.data;

                        data.forEach(function (item) {
                            this.values.push({
                                key: item.id,
                                value: item.name
                            });
                        }, this);

                        this.option_values[value] = this.values;
                    })
                    .catch(error => {

                    });
                }

                this.$emit('input', evt.target.value);
            },

            onInputConfirm() {
                let path = window.location.href.replace(window.location.search, '');
                let list = getQueryVariable('list_records');
                let args = '';
                let sign = '?';
                let redirect = true;

                if (list) {
                    args = '?list_records=' + list;
                    sign = '&';
                }

                if (this.search) {
                    args += sign + 'search="' + this.search + '" ';
                    sign = '&';
                }

                let now = new Date();
                now.setTime(now.getTime() + 1 * 3600 * 1000);
                let expires = now.toUTCString();

                let search_string = {};
                search_string[path] = {};

                this.filtered.forEach(function (filter, index) {
                    if (list) {
                        args += sign + 'search=';
                    }

                    if (! args) {
                        args += sign + 'search=';
                    }

                    if (! this.selected_operator.length || ! this.selected_values.length) {
                        redirect = false;
                        return;
                    }

                    if (this.selected_operator[index].key == '!=') {
                        args += 'not ';
                    }

                    if (this.selected_operator[index].key == '><') {
                        let dates = this.selected_values[index].key.split('-to-');

                        args += this.selected_options[index].key + '>=' + dates[0] + ' ';
                        args += this.selected_options[index].key + '<=' + dates[1] + ' ';
                    } else if (this.selected_operator[index].key == '||') {
                        args += this.selected_options[index].key + ':';

                        let multiple_values = '';

                        this.selected_values[index].forEach(function (value, index) {
                            if (index == 0) {
                                multiple_values += value.key;
                            } else {
                                multiple_values += ',' + value.key;
                            }
                        }, this);

                        args +=  multiple_values + ' ';
                    }
                    else {
                        args += this.selected_options[index].key + ':' + this.selected_values[index].key + ' ';
                    }

                    search_string[path][this.selected_options[index].key] = {
                        'key': Array.isArray(this.selected_values[index]) ? this.selected_values[index] : this.selected_values[index].key,
                        'value': Array.isArray(this.selected_values[index]) ? this.selected_values[index] : this.selected_values[index].value,
                        'operator': this.selected_operator[index].key,
                    };
                }, this);

                Cookies.set('search-string', search_string, expires);

                if (redirect) {
                    window.location = path + args;
                }
            },
        },

        created() {

        },

        mounted() {

        },

        computed: {

        },

        watch: {

        },
    };
</script>

<style>
    .searh-field .form-control.datepicker.flatpickr-input {
        padding: inherit !important;
    }
</style>
