<template>
    <div>
        <InstallSteps :active_state="active"></InstallSteps>

        <div class="mb-0">
            <form>
                <select v-model="form.lang" name="lang" id="lang" size="13" class="w-full text-black text-sm font-medium">
                    <option v-for="(name, code) in languages" :value="code">
                        {{ name }}
                    </option>
                </select>

                <div class="sm:col-span-6 flex items-center justify-end mt-3.5">
                    <button type="submit" @click="onSubmit($event)" :disabled="form.loading" id="next-button" class="relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100">
                        <i v-if="form.loading" class="animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto before:absolute before:w-2 before:h-2 before:rounded-full before:animate-submit before:delay-[0.14s] after:absolute after:w-2 after:h-2 after:rounded-full after:animate-submit before:-left-3.5 after:-right-3.5 after:delay-[0.42s]"></i>
                        <span :class="[{'opacity-0': form.loading}]">
                            Next
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
    import Form from "./../../plugins/form";
    import { Step, Steps } from "element-ui";
    import InstallSteps from "./Steps.vue";

    var base_path = url.replace(window.location.origin, "");

    export default {
        name: "language",

        components: {
            [Step.name]: Step,
            [Steps.name]: Steps,
            InstallSteps,
        },

        mounted() {
            axios
                .get(base_path + "/install/language/getLanguages")
                .then((response) => {
                    this.languages = response.data.languages;
                    this.form.lang = "en-GB";
                })
                .catch((error) => {});
        },
        data() {
            return {
                form: new Form("form-install"),
                languages: [],
                active: 0,
            };
        },
        methods: {
            // Form Submit
            onSubmit(event) {
                event.preventDefault();
                this.form.submit();
            },

            next() {
                if (this.active++ > 2);
            },
        },
    };
</script>

<style scoped>
 select {
     background-image: none;
 }
</style>
