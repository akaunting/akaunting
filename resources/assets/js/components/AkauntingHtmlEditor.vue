<template>
    <div>
        <vue-editor :id="editorId" v-model="content" :editorToolbar="customToolbar" :disabled="disabled"></vue-editor>
    </div>
</template>

<script>
import { VueEditor } from "vue2-editor";

export default {
    name: 'akaunting-html-editor',

    components: {
        VueEditor
    },

    props: {
        name: {
            type: String,
            default: '',
            description: 'The name of the field',
        },
        value: {
            type: String,
            default: ''
        },
        model: {
            type: [String, Number, Array, Object],
            default: '',
            description: "Selectbox selected model"
        },
        disabled: {
            type: Boolean,
            default: false,
            description: "Selectbox disabled status"
        },
    },

    data () {
        return {
            content: null,
            editorId: null,
            customToolbar: [
                ["bold", "italic", "underline"],
                [{ list: "ordered" }, { list: "bullet" }],
                ["link"]
            ],
        }
    },

    methods: {
        randomString() {
            let text = "";
            let possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

            for (let i = 0; i < 5; i++) {
                text += possible.charAt(Math.floor(Math.random() * possible.length));
            }

            return text;
        },
    },

    async mounted () {
        this.editorId = this.randomString();

        this.content = this.value;
    },

    watch: {
        value (newVal) {
            if (newVal !== this.content) {
                this.content = newVal;
            }
        },

        model (newVal) {
            if (newVal !== this.content) {
                this.content = newVal;
            }
        },

        content (newVal) {
            // #337y1z3 This issue reason <p> tag broken email template
            newVal = newVal.replace(/(<p[^>]+?>|<p>|<\/p>)/img, "");

            this.$emit('input', newVal);

            document.querySelectorAll('.ql-tooltip').forEach((tooltip) => {
                tooltip.querySelector('input').focus();
            });
        },
    },
 }
</script>
