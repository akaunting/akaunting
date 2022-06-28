<template>
    <div>
        <InstallSteps :active_state="active"></InstallSteps>

        <div class="card-body">
            <el-alert v-for="requirement in requirements" :key="requirement" :title="requirement" :closable="false" type="error" effect="dark"> </el-alert>
        </div>

        <div class="sm:col-span-6 flex items-center justify-end mt-3.5">
            <button type="button" @click="onRefresh" :disabled="button_loading" class="relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100">
                <i v-if="button_loading" class="animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto before:absolute before:w-2 before:h-2 before:rounded-full before:animate-submit before:delay-[0.14s] after:absolute after:w-2 after:h-2 after:rounded-full after:animate-submit before:-left-3.5 after:-right-3.5 after:delay-[0.42s]"></i>
                <span :class="[{'opacity-0': button_loading}]">
                    Refresh
                </span>
            </button>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
    import { Step, Steps, Alert } from "element-ui";
    import InstallSteps from "./Steps.vue";

    export default {
        name: "requirements",

        components: {
            [Step.name]: Step,
            [Steps.name]: Steps,
            [Alert.name]: Alert,
            InstallSteps
        },

        data() {
            return {
                requirements: flash_requirements,
                button_loading: false,
                active:null
            };
        },

        methods: {
            onRefresh() {
                this.button_loading = true;
                window.location.reload();
            },
        },
    };
</script>
