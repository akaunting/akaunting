<template>
    <div>
        <InstallSteps :active_state="active"></InstallSteps>

        <form>
            <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5 mt-0">
                <div class="sm:col-span-6 required">
                    <label for="company_name" class="text-black text-sm font-medium">Company Name</label>
                    <span class="text-red ltr:ml-1 rtl:mr-1">*</span>

                    <div class="input-group input-group-merge">
                        <input :class="[{'border-red': form.errors.get('company_name')}, {'border-light-gray': !form.errors.get('company_name')}]" class="w-full text-sm px-3 py-2.5 mt-1 rounded-lg border text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple" data-name="company_name" @keydown="form.errors.clear('company_name')" v-model="form.company_name" required="required" name="company_name" type="text" id="company_name" />
                    </div>
                </div>

                <div class="sm:col-span-6 required">
                    <label for="company_email" class="text-black text-sm font-medium">Company Email</label>
                    <span class="text-red ltr:ml-1 rtl:mr-1">*</span>

                    <div class="input-group input-group-merge">
                        <input :class="[{'border-red': form.errors.get('company_email')}, {'border-light-gray': !form.errors.get('company_email')}]" class="w-full text-sm px-3 py-2.5 mt-1 rounded-lg border text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple" data-name="company_email" @keydown="form.errors.clear('company_email')" v-model="form.company_email" required="required" name="company_email" type="text" id="company_email" />
                    </div>
                </div>

                <div class="sm:col-span-6 required">
                    <label for="user_email" class="text-black text-sm font-medium">Admin Email</label>
                    <span class="text-red ltr:ml-1 rtl:mr-1">*</span>

                    <div class="input-group input-group-merge">
                        <input :class="[{'border-red': form.errors.get('user_email')}, {'border-light-gray': !form.errors.get('user_email')}]" class="w-full text-sm px-3 py-2.5 mt-1 rounded-lg border text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple" data-name="user_email" @keydown="form.errors.clear('user_email')" v-model="form.user_email" required="required" name="user_email" type="text" id="user_email" />
                    </div>
                </div>

                <div class="sm:col-span-6 required">
                    <label for="user_password" class="text-black text-sm font-medium">Admin Password</label>
                    <span class="text-red ltr:ml-1 rtl:mr-1">*</span>

                    <div class="input-group input-group-merge">
                        <input :class="[{'border-red': form.errors.get('user_password')}, {'border-light-gray': !form.errors.get('user_password')}]" class="w-full text-sm px-3 py-2.5 mt-1 rounded-lg borde text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple" data-name="user_password" @keydown="form.errors.clear('user_password')" v-model="form.user_password" required="required" name="user_password" type="password" value="" id="user_password" />
                    </div>
                </div>

                <div class="sm:col-span-6 flex items-center justify-end mt-3.5">
                    <button type="submit" @click="onSubmit($event)" :disabled="form.loading" id="next-button" class="relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100">
                        <i v-if="form.loading" class="animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto before:absolute before:w-2 before:h-2 before:rounded-full before:animate-submit before:delay-[0.14s] after:absolute after:w-2 after:h-2 after:rounded-full after:animate-submit before:-left-3.5 after:-right-3.5 after:delay-[0.42s]"></i>
                        <span :class="[{'opacity-0': form.loading}]">
                            Next
                        </span>
                    </button>
                </div>
            </div>
        </form>
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
