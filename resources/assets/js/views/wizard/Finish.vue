<template>
    <div class="relative bg-body z-10 rounded-lg shadow-2xl py-10 ltr:pl-10 rtl:pr-10 overflow-hidden">
        <div class="pr-10">
            <WizardSteps :active_state="active"></WizardSteps>
        </div>

        <div class="flex flex-col justify-between" style="height:565px;">
            <div v-if="pageLoad" class="absolute left-0 right-0 top-0 bottom-0 w-full h-full bg-white rounded-lg flex items-center justify-center z-50">
                <span class="material-icons form-spin text-lg animate-spin text-9xl">data_usage</span>
            </div>

            <div class="flex mt-6">
                <div class="w-full lg:w-1/2 ltr:pr-10 rtl:pl-10 mt-3">
                    <div class="grid sm:grid-cols-6">
                        <h1 class="sm:col-span-6 text-black-300 mb-2">
                            {{ translations.finish.recommended_apps }}
                        </h1>
                        <div v-for="(item, index) in modules" :key="index" class="sm:col-span-6 mb-6">
                            <a :href="route_url + '/apps/' + item.slug" class="flex items-center">
                                <div class="w-1/4">
                                    <img v-for="(file, indis) in item.files" :key="indis" v-if="file.media_type == 'image' && file.pivot.zone == 'thumbnail'"
                                    :src="file.path_string"
                                    :alt="item.name"
                                    class="rounded-lg object-cover"
                                    />
                                </div>
                                <div class="w-3/4 lg:ltr:pl-8 lg:rtl:pr-8">
                                    <span class="font-medium">{{ item.name }}</span>
                                    <div class="text-black-300 text-sm my-2 line-clamp-3"
                                     v-html="item.description"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="relative w-1/2 right-0 ltr:pl-10 rtl:pr-10 mt-3 hidden: lg:flex lg:flex-col">
                    <div class="bg-purple rounded-tl-lg rounded-bl-lg p-6">
                        <div class="w-48 text-white rtl:float-left rtl:text-left text-2xl font-semibold leading-9">
                            {{ translations.finish.apps_managing }}
                        </div>
                        <div style="width:372px; height:372px;"></div>
                        <img :src="image_src" class="absolute top-0 right-2 top-12" alt="" />
                    </div>
                    <base-button class="flex items-center justify-center text-base rounded-lg disabled:opacity-50 relative m-auto bottom-40 bg-white hover:bg-gray-100 text-purple rounded-md py-3 px-5 font-semibold btn-default" @click="finish()">
                        {{ translations.finish.go_to_dashboard }}
                    </base-button>
                </div>
                    
            </div>
        </div>
    </div>
</template>

<script>
import WizardSteps from "./Steps.vue";

export default {
    name: "Finish",

    components: {
        WizardSteps
    },

    props: {
        modules: {
            type: [Object, Array],
        },

        translations: {
            type: [Object, Array],
        },

        pageLoad: {
          type: [Boolean, String]
        }
    },

    data() {
        return {
            active: 3,
            route_url: url,
            image_src: app_url + "/public/img/wizard-modules.png",
        };
    },

    created() {
        window.axios({
            method: "PATCH",
            url: url + "/wizard/finish",
        })
        .then((response) => {
        })
        .catch((error) => {
            this.$notify({
                message: this.translations.finish.error_message,
                timeout: 1000,
                icon: "",
                type: 0
            });

            this.prev();
        });
    },

    methods: {
        prev() {
            if (this.active-- > 2);

            this.$router.push("/wizard/taxes");
        },

        finish() {
            window.location.href = url;
        },
    },
};
</script>