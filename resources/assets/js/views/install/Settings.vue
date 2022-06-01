<template>
    <div>
        <InstallSteps :active_state="active"></InstallSteps>

        <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5 mt-0">
            <div class="sm:col-span-6 required" :class="[{'has-error': form.errors.get('company_name')}]">
                <label for="company_name" class="form-control-label">Company Name</label>

                <div class="input-group input-group-merge">
                    <input class="form-element" data-name="company_name" @keydown="form.errors.clear('company_name')" v-model="form.company_name" required="required" name="company_name" type="text" id="company_name" />
                </div>

                <div class="text-red text-sm mt-1 block" v-if="form.errors.has('company_name')" v-html="form.errors.get('company_name')"></div>
            </div>

            <div class="sm:col-span-6 required" :class="[{'has-error': form.errors.get('company_email')}]">
                <label for="company_email" class="form-control-label">Company Email</label>

                <div class="input-group input-group-merge">
                    <input class="form-element" data-name="company_email" @keydown="form.errors.clear('company_email')" v-model="form.company_email" required="required" name="company_email" type="text" id="company_email" />
                </div>

                <div class="text-red text-sm mt-1 block" v-if="form.errors.has('company_email')" v-html="form.errors.get('company_email')"></div>
            </div>

            <div class="sm:col-span-6 required" :class="[{'has-error': form.errors.get('user_email')}]">
                <label for="user_email" class="form-control-label">Admin Email</label>

                <div class="input-group input-group-merge">
                    <input class="form-element" data-name="user_email" @keydown="form.errors.clear('user_email')" v-model="form.user_email" required="required" name="user_email" type="text" id="user_email" />
                </div>

                <div class="text-red text-sm mt-1 block" v-if="form.errors.has('user_email')" v-html="form.errors.get('user_email')"></div>
            </div>

            <div class="sm:col-span-6 required" :class="[{'has-error': form.errors.get('user_password')}]">
                <label for="user_password" class="form-control-label">Admin Password</label>

                <div class="input-group input-group-merge">
                    <input class="form-element" data-name="user_password" @keydown="form.errors.clear('user_password')" v-model="form.user_password" required="required" name="user_password" type="password" value="" id="user_password" />
                </div>

                <div class="text-red text-sm mt-1 block" v-if="form.errors.has('user_password')" v-html="form.errors.get('user_password')"></div>
            </div>
        </div>

        <div class="relative__footer">
            <div class="sm:col-span-6 flex items-center justify-end mt-3.5">
                <button type="submit" @click="onSubmit" :disabled="form.loading" id="next-button" class="relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100">
                    <i v-if="form.loading" class="animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto before:absolute before:w-2 before:h-2 before:rounded-full before:animate-submit before:delay-[0.14s] after:absolute after:w-2 after:h-2 after:rounded-full after:animate-submit before:-left-3.5 after:-right-3.5 after:delay-[0.42s]"></i>
                    <span :class="[{'opacity-0': form.loading}]">
                        Next
                    </span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
    import Form from "./../../plugins/form";
    import { Step, Steps } from "element-ui";
    import InstallSteps from "./Steps.vue";

    export default {
        name: "settings",

        components: {
            [Step.name]: Step,
            [Steps.name]: Steps,
            InstallSteps,
        },

        data() {
            return {
                form: new Form("form-install"),
                languages: [],
                active: 2,
            };
        },
        methods: {
            // Form Submit
            onSubmit() {
                this.form.submit();
            },

            next() {
                if (this.active++ > 2);
            },
        },
    };
</script>
