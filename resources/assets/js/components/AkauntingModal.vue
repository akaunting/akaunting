<template>
    <SlideYUpTransition :duration="animationDuration">
    <div class="modal fade"
         @click.self="closeModal"
         :class="[{'show d-block': show}, {'d-none': !show}]"
         v-show="show"
         tabindex="-1"
         role="dialog"
         :aria-hidden="!show">
        <div class="modal-dialog">
            <slot name="modal-content">
            <div class="modal-content">
                <div class="card-header pb-2">
                    <slot name="card-header">
                        <h4 class="float-left"> {{ title }} </h4>
                        <button type="button" class="close" @click="onCancel" aria-hidden="true">&times;</button>
                    </slot>
                </div>
                <slot name="modal-body">
                    <div class="modal-body" v-html="message">
                    </div>
                </slot>
                <div class="card-footer border-top-0">
                    <slot name="card-footer">
                        <div class="float-right">
                            <button type="button" class="btn btn-icon btn-outline-secondary" @click="onCancel">
                                <span class="btn-inner--icon"><i class="fas fa-times"></i></span>
                                <span class="btn-inner--text">{{ button_cancel }}</span>
                            </button>

                            <button :disabled="form.loading" type="button" class="btn btn-icon btn-danger button-submit" @click="onConfirm">
                                <div v-if="form.loading" class="aka-loader-frame"><div class="aka-loader"></div></div>
                                <span v-if="!form.loading" class="btn-inner--icon"><i class="fas fa-save"></i></span>
                                <span v-if="!form.loading" class="btn-inner--text">{{ button_delete }}</span>
                            </button>
                        </div>
                    </slot>
                </div>
            </div>
            </slot>
        </div>
    </div>
    </SlideYUpTransition>
</template>

<script>
import { SlideYUpTransition } from "vue2-transitions";
import AkauntingRadioGroup from './forms/AkauntingRadioGroup';
import AkauntingSelect from './AkauntingSelect';
import AkauntingDate from './AkauntingDate';
import AkauntingRecurring from './AkauntingRecurring';

export default {
    name: 'akaunting-modal',

    componentName: 'akaunting-modal',

    components: {
        SlideYUpTransition,
        AkauntingRadioGroup,
        AkauntingSelect,
        AkauntingDate,
        AkauntingRecurring
    },

    props: {
        show: Boolean,
        title: {
            type: String,
            default: '',
            description: "Modal header title"
        },
        message: {
            type: String,
            default: '',
            description: "Modal body message"
        },
        button_cancel: {
            type: String,
            default: '',
            description: "Modal footer cancel button text"
        },
        button_delete: {
            type: String,
            default: '',
            description: "Modal footer delete button text"
        },
        animationDuration: {
            type: Number,
            default: 800,
            description: "Modal transition duration"
        }
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

        onConfirm() {
            this.$emit("confirm");
        },

        onCancel() {
            this.$emit("cancel");
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
    .modal.show {
        background-color: rgba(0, 0, 0, 0.3);
    }

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
