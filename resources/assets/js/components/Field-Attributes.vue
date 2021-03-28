<template>
    <div>
        <component v-for="(field, index) in schema"
                   :key="index"
                   :is="field.fieldType"
                   v-bind="field">

        </component>
    </div>
</template>

<script>
    import { SlideYUpTransition } from "vue2-transitions";

    export default {
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
