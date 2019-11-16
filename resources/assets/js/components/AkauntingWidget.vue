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
    .form-group .el-select {
        width: 100%
    }

    .el-input__prefix
    {
        left: 20px;
        z-index: 999;
        top: 2px;
    }

    .el-select .el-input .el-input__inner {
        font-size: .875rem;
        width: 100%;
        height: calc(1.5em + 1.25rem + 2px);
        -webkit-transition: all .15s ease-in-out;
        transition: all .15s ease-in-out
    }

    @media (prefers-reduced-motion:reduce) {
        .el-select .el-input .el-input__inner {
            -webkit-transition: none;
            transition: none
        }
    }

    .el-select .el-input .el-input__inner:focus {
        border-color: #324cdd!important;
        border: 1px solid #2a44db
    }

    .el-select .el-input .el-input__inner::-webkit-input-placeholder {
        color: #adb5bd;
        opacity: 1
    }

    .el-select .el-input .el-input__inner::-moz-placeholder {
        color: #adb5bd;
        opacity: 1
    }

    .el-select .el-input .el-input__inner::-ms-input-placeholder {
        color: #adb5bd;
        opacity: 1
    }

    .el-select .el-input .el-input__inner::placeholder {
        color: #adb5bd;
        opacity: 1
    }

    .el-select .el-input .el-input__inner:disabled {
        background-color: #e9ecef;
        opacity: 1
    }

    .el-select .el-input.is-focus .el-input__inner {
        border-color: #324cdd!important;
        border: 1px solid #2a44db
    }

    .el-select-dropdown.el-popper .el-select-dropdown__item.selected,.el-select-dropdown.el-popper.is-multiple .el-select-dropdown__item.selected {
        color: #5e72e4
    }

    .el-select .el-select__tags {
        padding-left: 10px
    }

    .el-select .el-select__tags .el-tag {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        padding: .625rem .625rem .5rem;
        height: 25px;
        margin: .125rem;
        border-radius: .25rem;
        background: #172b4d;
        color: #fff;
        line-height: 1.5;
        cursor: pointer;
        -webkit-box-shadow: 0 1px 2px rgba(68,68,68,.25);
        box-shadow: 0 1px 2px rgba(68,68,68,.25);
        -webkit-transition: all .15s ease;
        transition: all .15s ease
    }

    @media (prefers-reduced-motion:reduce) {
        .el-select .el-select__tags .el-tag {
            -webkit-transition: none;
            transition: none
        }
    }

    .el-select .el-select__tags .el-tag .el-tag__close.el-icon-close {
        background-color: transparent;
        color: #fff;
        font-size: 12px
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
