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
                            prepend-icon="fas fa-tag"
                            :placeholder="text.name"
                            inputGroupClasses="input-group-merge"
                        ></base-input>
                    </div>

                    <!--
                    <akaunting-radio-group
                        :name="'enabled'"
                        :text="text.enabled"
                        :value="form.enabled"
                        @change="onEnabled"
                        :enable="text.yes"
                        :disable="text.no"
                        :col="'col-md-12'"
                    ></akaunting-radio-group>
                    -->
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

<script>
import AkauntingModal from "./AkauntingModal";
import AkauntingRadioGroup from './forms/AkauntingRadioGroup';
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'
import Form from './../plugins/form';
import BulkAction from './../plugins/bulk-action';
import NProgressAxios from './../plugins/nprogress-axios';

export default {
    name: 'akaunting-dashobard',

    components: {
        AkauntingModal,
        AkauntingRadioGroup
    },

    props: {
        show: Boolean,
        title: {
            type: String,
            default: '',
            description: "Modal header title"
        },
        text: {},
        name: {
            type: String,
            default: ''
        },
        enabled: Number,
        type: {
            type: String,
            default: 'create',
            description: "Modal header title"
        },
        dashboard_id: {
            type: Number,
            default: 0,
            description: "Modal header title"
        },
    },

    data() {
        return {
            form: {
                loading: false,
                name: this.name,
                enabled: this.enabled
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

            var path =  url + '/common/dashboards';

            if ((self.type != 'create')) {
                path = url + '/common/dashboards/' + self.dashboard_id;
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

<style>
    .loader10 {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        position: relative;
        animation: loader10 0.9s ease alternate infinite;
        animation-delay: 0.36s;
        top: 50%;
        margin: -42px auto 0;
    }

    .loader10::after, .loader10::before {
        content: '';
        position: absolute;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        animation: loader10 0.9s ease alternate infinite;
    }

    .loader10::before {
        left: -40px;
        animation-delay: 0.18s;
    }

    .loader10::after {
        right: -40px;
        animation-delay: 0.54s;
    }

    @keyframes loader10 {
        0% {
            box-shadow: 0 28px 0 -28px #0052ec;
        }

        100% {
            box-shadow: 0 28px 0 #0052ec;
        }
    }

</style>
