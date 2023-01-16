<template>
    <akaunting-modal
        :title="title"
        :show="display"
        @cancel="onCancel"
        v-if="display">
        <template #modal-body>
            <div class="py-1 px-5 bg-body">
                <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
                    <div class="sm:col-span-3">
                        <base-input
                            v-model="form.name"
                            :label="text.name"
                            :placeholder="placeholder.name"
                            :error="form.errors.name[0]"
                            @input="form.errors.name[0] = ''"
                            inputGroupClasses="input-group-merge">
                        </base-input>
                    </div>

                    <div class="sm:col-span-3">
                        <base-input
                            :error="form.errors.class[0]"
                            :label="text.type">
                            
                            <el-select
                                @change="form.errors.class[0] = ''"
                                v-model="form.class" filterable
                                :placeholder="placeholder.type">
                                <el-option v-for="(name, value) in types"
                                :key="name"
                                :label="name"
                                :value="value">
                                </el-option>
                            </el-select>
                        </base-input>
                    </div>

                    <div class="sm:col-span-3">
                        <base-input :label="text.width" not-required>
                            <el-select
                                v-model="form.width" filterable
                                :placeholder="placeholder.width">
                                <el-option v-for="option in widthOptions"
                                :key="option.label"
                                :label="option.label"
                                :value="option.value">
                                </el-option>
                            </el-select>
                        </base-input>
                    </div>

                    <div class="sm:col-span-3">
                        <base-input
                            not-required
                            v-model="form.sort"
                            :label="text.sort"
                            :placeholder="placeholder.sort"
                            :error="form.errors.sort[0]"
                            @input="form.errors.sort[0] = ''"
                            inputGroupClasses="input-group-merge"></base-input>
                    </div>
                </div>
            </div>
        </template>

        <template #card-footer>
            <div class="flex items-center justify-end">
                <button type="button" class="flex items-center justify-center px-6 py-1.5 text-base rounded-lg mr-2 bg-transparent hover:bg-gray-300 disabled:bg-gray-200" @click="onCancel">
                    {{ text.cancel }}
                </button>

                <button :disabled="form.loading" type="button" class="relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100" @click="onSave">
                    <i v-if="form.loading" class="animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto before:absolute before:w-2 before:h-2 before:rounded-full before:animate-submit before:delay-[0.14s] after:absolute after:w-2 after:h-2 after:rounded-full after:animate-submit before:-left-3.5 after:-right-3.5 after:delay-[0.42s]"></i>
                    <span :class="[{'opacity-0': form.loading}]">{{ text.save }}</span>
                </button>
            </div>
        </template>
    </akaunting-modal>
</template>

<script>
import axios from 'axios';
import { Select, Option } from 'element-ui';
import AkauntingModal from "./AkauntingModal";
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'
import Form from './../plugins/form';
import NProgressAxios from './../plugins/nprogress-axios';

export default {
    name: 'akaunting-widget',

    components: {
        AkauntingModal,
        [Select.name]: Select,
        [Option.name]: Option,
    },

    props: {
        show: {
            type: Boolean,
            default: false,
            description: "Modal Status"
        },
        action: {
            type: String,
            default: 'create',
            description: "Widget modal action create/edit"
        },
        title: {
            type: String,
            default: '',
            description: "Modal header title"
        },
        text: {
            type: Object,
            default: {},
            description: "Widget form texts"
        },
        placeholder: {
            type: Object,
            default: {},
            description: "Widget form placeholder"
        },
        name: {
            type: String,
            default: '',
            description: "Widget Name Field"
        },
        width: {
            type: String,
            default: '',
            description: "Widget Width Field"
        },
        type: {
            type: String,
            default: '',
            description: "Widget Class Field"
        },
        types: {
            type: Object,
            default: {},
            description: "Widget Get Classes"
        },
        sort: {
            type: Number,
            default: 0,
            description: "Widget Sort Field"
        },
        dashboard_id: {
            type: Number,
            default: 0,
            description: "Widget Dashboard Id"
        },
        widget_id: {
            type: [Number, String],
            default: 0,
            description: "Edit Widget ID"
        },
    },

    data() {
        return {
            widthOptions: [
                {
                    label: '25%',
                    value: 'w-full lg:w-1/4 lg:px-6'
                },
                {
                    label: '33%',
                    value: 'w-full lg:w-1/3 lg:px-6'
                },
                {
                    label: '50%',
                    value: 'w-full lg:w-2/4 lg:px-12'
                },
                {
                    label: '100%',
                    value: 'w-full lg:px-12'
                }
            ],
            form: {
                loading: false,
                class: this.type,
                name: this.name,
                width: this.width,
                sort: this.sort,
                dashboard_id: this.dashboard_id,
                errors: {
                    name: [],
                    class: [],
                    sort: [],
                }
            },
            display: this.show
        };
    },

    methods: {
        closeModal() {
            this.$emit("update:show", false);
            this.$emit("close");
        },

        onSave() {
            this.form.loading = true;

            let data = Object.assign({}, this.form);
            delete data.loading;

            var self = this;

            var path =  url + '/common/widgets';

            if ((self.action != 'create')) {
                path = url + '/common/widgets/' + self.widget_id;
                data['_method'] = 'PATCH';
            }

            axios.post(path, data)
                .then(function (response) {
                    self.form.loading = false;

                    if (response.data.redirect) {
                        self.form.loading = true;

                        window.location.href = response.data.redirect;
                    }

                    if (response.errors) {
                        self.form.errors.name = (error.response.data.errors.name) ? error.response.data.errors.name : [];
                        self.form.errors.class = (error.response.data.errors.class) ? error.response.data.errors.class : [];
                        self.form.errors.sort = (error.response.data.errors.sort) ? error.response.data.errors.sort : [];

                        self.form.loading = false;
                    }

                    self.form.response = response.data;
                })
                .catch(function (error) {
                    self.form.errors.name = (error.response.data.errors.name) ? error.response.data.errors.name : [];
                    self.form.errors.class = (error.response.data.errors.class) ? error.response.data.errors.class : [];
                    self.form.errors.sort = (error.response.data.errors.sort) ? error.response.data.errors.sort : [];

                    self.form.loading = false;
                });
        },

        onCancel() {
            let documentClasses = document.body.classList;	

            documentClasses.remove('overflow-y-hidden', 'overflow-overlay');

            this.display = false;
            this.form.name = '';
            this.form.enabled = 1;

            this.$emit("cancel");
        },

        onEnabled(value) {
            this.form.enabled = value;
        }
    },

    watch: {
        show(val) {
            let documentClasses = document.body.classList;

            if (val) {
                documentClasses.add('overflow-y-hidden', 'overflow-overlay');
            } else {
                documentClasses.remove('overflow-y-hidden', 'overflow-overlay');
            }
        },

        'form.sort': function (val) {
            this.form.sort = Number(val);
        }
    }
}
</script>
