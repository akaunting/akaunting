<template>
    <SlideYUpTransition :duration="animationDuration">
        <div class="modal fade w-full h-full fixed top-0 left-0 right-0 z-50 overflow-y-auto overflow-hidden modal-add-new fade justify-center"
            @click.self="closeModal"
            :class="[modalPositionTop ? 'items-start' : 'items-center', {'show flex flex-wrap modal-background': show}, {'hidden': !show}]"
            v-show="show"
            tabindex="-1"
            role="dialog"
            data-modal-handle
            :aria-hidden="!show">
            <div class="w-full my-10 m-auto flex flex-col px-2 sm:px-0" :class="modalDialogClass ? modalDialogClass : 'max-w-md'">
                <slot name="modal-content">
                    <div class="bg-body rounded-lg modal-content">
                        <div class="p-5">
                            <div class="flex items-center justify-between border-b pb-5">
                                <slot name="card-header">
                                    <h4 class="text-base font-medium">
                                        {{ title }}
                                    </h4>

                                    <button type="button" class="text-lg" @click="onCancel" aria-hidden="true">
                                        <span class="rounded-md border-b-2 px-2 py-1 text-sm bg-gray-100">esc</span>
                                    </button>
                                </slot>
                            </div>
                        </div>

                        <slot name="modal-body">
                            <div class="py-1 px-5" v-html="message"></div>
                        </slot>

                        <div class="p-5 border-gray-300">
                            <slot name="card-footer">
                                <div class="flex items-center justify-end space-x-2 rtl:space-x-reverse">
                                    <button type="button" class="px-6 py-1.5 hover:bg-gray-200 rounded-lg" @click="onCancel">
                                        {{ button_cancel }}
                                    </button>

                                    <button :disabled="form.loading" type="button" class="relative flex items-center justify-center bg-red hover:bg-red-700  px-6 py-1.5 text-base rounded-lg disabled:opacity-50 text-white" @click="onConfirm">
                                        <i v-if="form.loading" class="animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto before:absolute before:w-2 before:h-2 before:rounded-full before:animate-submit before:delay-[0.14s] after:absolute after:w-2 after:h-2 after:rounded-full after:animate-submit before:-left-3.5 after:-right-3.5 after:delay-[0.42s]"></i>
                                        <span :class="[{'opacity-0': form.loading}]">{{ button_delete }}</span>
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
import AkauntingRadioGroup from './AkauntingRadioGroup';
import AkauntingSelect from './AkauntingSelect';
import AkauntingDate from './AkauntingDate';
import AkauntingRecurring from './AkauntingRecurring';
import AkauntingMoney from './AkauntingMoney';

export default {
    name: 'akaunting-modal',

    components: {
        SlideYUpTransition,
        AkauntingRadioGroup,
        AkauntingSelect,
        AkauntingDate,
        AkauntingRecurring,
        AkauntingMoney,
    },

    props: {
        show: {
            type: Boolean,
        },
        modalDialogClass: {
            type: String,
            default: '',
            description: "Modal Body size Class"
        },
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
        },
        modalPositionTop: {
            type: Boolean,
            default: false,
            description: "Modal Body position Class"
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

    created: function () {
        if (this.show) {
            let documentClasses = document.body.classList;

            documentClasses.add('overflow-y-hidden', 'overflow-overlay');
        }

        if (this.modalDialogClass) {
            let modal_size = this.modalDialogClass.replace('modal-', 'max-w-screen-');

            this.modalDialogClass = modal_size;
        }
    },

    mounted() {
        window.addEventListener('keyup',(e) => {
            if (e.key === 'Escape') { 
                this.onCancel();
            }
        });
    },

    methods: {
        closeModal() {
            this.$emit("update:show", false);
            this.$emit("close");
        },

        onConfirm() {
            this.form.loading = true;

            this.$emit("confirm");
        },

        onCancel() {
            let documentClasses = document.body.classList;

            documentClasses.remove('overflow-y-hidden', 'overflow-overlay');

            this.$emit("cancel");
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
        }
    }
}
</script>