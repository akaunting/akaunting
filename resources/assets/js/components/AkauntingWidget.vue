<template>
    <akaunting-modal
            :title="title"
            :show="display"
            @cancel="onCancel"
            v-if="display">
        <template #modal-body>
            <div class="modal-body text-left">
                <div class="row">
                    <div class="col-md-12">
                        <base-input
                                v-model="form.name"
                                :label="text.name"
                                prepend-icon="fas fa-font"
                                :placeholder="placeholder.name"
                                inputGroupClasses="input-group-merge">
                        </base-input>
                    </div>

                    <div class="col-md-12">
                        <base-input
                                :label="text.type">
                            <span class="el-input__prefix">
                                <span class="el-input__suffix-inner el-select-icon">
                                    <i class="select-icon-position el-input__icon fa fa-bars"></i>
                                </span>
                            </span>
                            <el-select
                                    class="select-primary"
                                    v-model="form.widget_id" filterable
                                    :placeholder="placeholder.type">
                                <el-option v-for="option in types"
                                           class="select-primary"
                                           :key="option.name"
                                           :label="option.name"
                                           :value="option.id">
                                </el-option>
                            </el-select>
                        </base-input>
                    </div>

                    <div class="col-md-12">
                        <base-input
                                :label="text.width">
                            <span class="el-input__prefix">
                                <span class="el-input__suffix-inner el-select-icon">
                                    <i class="select-icon-position el-input__icon fas fa-ruler-horizontal"></i>
                                </span>
                            </span>
                            <el-select
                                    class="select-primary"
                                    v-model="form.width" filterable
                                    :placeholder="placeholder.width">
                                <el-option v-for="option in widthOptions"
                                           class="select-primary"
                                           :key="option.label"
                                           :label="option.label"
                                           :value="option.value">
                                </el-option>
                            </el-select>
                        </base-input>
                    </div>

                    <div class="col-md-12">
                        <base-input
                                v-model="form.sort"
                                :label="text.sort"
                                prepend-icon="fas fa-sort"
                                :placeholder="placeholder.sort"
                                inputGroupClasses="input-group-merge"></base-input>
                    </div>
                </div>
            </div>
        </template>

        <template #card-footer>
            <div class="row">
                <div class="col-md-12">
                    <div class="float-right">
                        <button type="button" class="btn btn-icon btn-outline-secondary" @click="onCancel">
                            <span class="btn-inner--icon"><i class="fas fa-times"></i></span>
                            <span class="btn-inner--text">{{ text.cancel }}</span>
                        </button>

                        <button :disabled="form.loading" type="button" class="btn btn-icon btn-success button-submit" @click="onSave">
                            <div v-if="form.loading" class="aka-loader-frame"><div class="aka-loader"></div></div>
                            <span v-if="!form.loading" class="btn-inner--icon"><i class="fas fa-save"></i></span>
                            <span v-if="!form.loading" class="btn-inner--text">{{ text.save }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </akaunting-modal>
</template>

<style>
    .el-input__prefix
    {
        left: 20px;
        z-index: 999;
        top: 2px;
    }
</style>

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
        show: Boolean,
        action: {
            type: String,
            default: 'create',
            description: "Modal header title"
        },
        title: {
            type: String,
            default: '',
            description: "Modal header title"
        },
        text: {},
        placeholder: {},
        name: {
            type: String,
            default: ''
        },
        width: {
            type: String,
            default: ''
        },
        type: {
            type: String,
            default: 'create',
            description: "Modal header title"
        },
        sort: {
            type: String,
            default: 'create',
            description: "Modal header title"
        },
        types: {},
        dashboard_id: {
            type: Number,
            default: 0,
            description: "Modal header title"
        },
        widget_id: {
            type: Number,
            default: 0,
            description: "Modal header title"
        },
    },

    data() {
        return {
            widthOptions: [
                {
                    label: '25%',
                    value: 'col-md-3'
                },
                {
                    label: '33%',
                    value: 'col-md-4'
                },
                {
                    label: '50%',
                    value: 'col-md-6'
                },
                {
                    label: '100%',
                    value: 'col-md-12'
                }
            ],
            form: {
                loading: false,
                name: this.name,
                width: this.width,
                widget_id: this.type,
                sort: this.sort,
                dashboard_id: this.dashboard_id
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

                self.form.response = response.data;
            })
            .catch(function (error) {
                this.errors.record(error.response.data.errors);

                self.form.loading = false;
            });
        },

        onCancel() {
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
                documentClasses.add("modal-open");
            } else {
                documentClasses.remove("modal-open");
            }
        }
    }
}
</script>
