<template>
    <div class="relative bg-body z-10 rounded-lg shadow-2xl p-5 ltr:pr-0 rtl:pl-0 sm:py-10 sm:ltr:pl-10 sm:rtl:pr-10 overflow-hidden">
        <WizardSteps :active_state="active"></WizardSteps>

        <div class="flex flex-col justify-between -mt-5 sm:mt-0" style="height:565px;">
            <div v-if="pageLoad" class="absolute left-0 right-0 top-0 bottom-0 w-full h-full bg-white rounded-lg flex items-center justify-center z-50">
                <span class="material-icons form-spin text-lg animate-spin text-9xl">data_usage</span>
            </div>

            <div class="flex flex-col lg:flex-row mt-6">
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

                                <div class="w-3/4 ltr:pl-8 rtl:pr-8">
                                    <span class="font-medium">
                                        {{ item.name }}
                                    </span>

                                    <div class="text-black-300 text-sm my-2 line-clamp-2 h-10" v-html="item.description"></div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="lg:hidden">
                        <base-button class="btn flex items-center justify-center text-base disabled:opacity-50 relative mt-5 mx-auto bg-green hover:bg-gray-100 text-white rounded-md py-3 px-5 font-semibold" @click="finish()">
                            {{ translations.finish.create_first_invoice }}
                        </base-button>
                    </div>
                </div>

                <div class="relative w-1/2 right-0 ltr:pl-10 rtl:pr-10 mt-3 hidden lg:flex lg:flex-col">
                    <div class="flex flex-col ltr:items-start rtl:items-end bg-purple ltr:rounded-tl-lg ltr:rounded-bl-lg rtl:rounded-tr-lg rtl:rounded-br-lg p-6">
                        <div class="w-48 text-white text-left text-2xl font-semibold leading-9">
                            {{ translations.finish.apps_managing }}
                        </div>

                        <div style="width:372px; height:372px;"></div>

                        <img :src="image_src" class="absolute top-3 right-2" alt="" />
                    </div>

                    <base-button
                        class="relative flex items-center justify-center text-base rounded-lg m-auto bottom-48 bg-white hover:bg-gray-100 text-purple py-3 px-5 font-semibold disabled:bg-gray-100 "
                        :disabled="anchor_loading"
                        @click="finish()"
                    >
                        <i v-if="anchor_loading" class="animate-submit_second delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-2.5 m-auto before:absolute before:w-2 before:h-2 before:rounded-full before:animate-submit_second before:delay-[0.14s] after:absolute after:w-2 after:h-2 after:rounded-full after:animate-submit_second before:-left-3.5 after:-right-3.5 after:delay-[0.42s]"></i> 
                        
                        <span :class="[{'opacity-0': anchor_loading}]">
                            {{ translations.finish.create_first_invoice }}
                        </span>
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
            anchor_loading: false
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

            this.$router.push("/wizard/currencies");
        },

        finish() {
            window.location.href = url + "/sales/invoices/create";
            this.anchor_loading = true;
        },
    },
};
</script>

<style scoped>
    @media only screen and (max-width: 991px) {
        [modal-container] {
            height: 100% !important;
        }
    }
</style>
