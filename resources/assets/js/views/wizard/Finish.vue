<template>
    <div class="relative bg-body z-10 rounded-lg shadow-2xl p-5 sm:p-10 full-height-mobile overflow-hidden">
        <WizardSteps :active_state="active"></WizardSteps>

        <div class="flex flex-col justify-between -mt-5 sm:mt-0" style="height:523px;">
            <div v-if="pageLoad" class="absolute left-0 right-0 top-0 bottom-0 w-full h-full bg-white rounded-lg flex items-center justify-center z-50">
                <span class="material-icons form-spin animate-spin text-9xl">data_usage</span>
            </div>

            <div class="flex flex-col lg:flex-row mt-4">
                <div class="w-full lg:w-1/2 ltr:pr-10 rtl:pl-10 mt-3">
                    <div class="grid sm:grid-cols-6 overflow-y-scroll lg:overflow-hidden scroll">
                        <h1 class="sm:col-span-6 text-black-300 mb-2 text-lg font-light">
                            {{ translations.finish.recommended_apps }}
                        </h1>

                        <div v-for="(item, index) in modules" :key="index" class="sm:col-span-6 mb-3 bg-default rounded-md">
                            <a :href="route_url + '/apps/' + item.slug" class="flex items-center space-x-4 bg-purple-50 rounded-md p-2 sliding-app">
                                <div class="w-1/4">
                                <img v-for="(file, indis) in item.files" :key="indis" v-if="file.media_type == 'image' && file.pivot.zone == 'thumbnail'"
                                    :src="file.path_string"
                                    :alt="item.name"
                                    class="rounded-lg object-cover ml-1 mt-0.5"
                                    />
                                </div>

                                <div class="w-3/4">
                                    <h4 class="font-medium text-sm pb-1">
                                        {{ item.name }}
                                    </h4>

                                    <div class="text-black-300 text-sm mt-1 line-clamp-2 h-10 ltr:pr-0 rtl:pr-2" v-html="item.description"></div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="lg:hidden">
                        <base-button class="btn flex items-center justify-center text-base disabled:opacity-50 relative mt-5 mx-auto bg-green hover:bg-gray-100 text-white rounded-md py-3 px-5 font-medium" @click="finish()">
                            {{ translations.finish.create_first_invoice }}
                        </base-button>
                    </div>
                </div>

                <div class="absolute w-1/2 ltr:right-0 rtl:left-0 ltr:pl-10 rtl:pr-10 mt-3 hidden lg:flex lg:flex-col">
                    <div class="flex">
                        <img :src="image_src" class="absolute top-12 ltr:right-2 rtl:left-2 w-10/12 p-3 -mt-12" alt="Akaunting" />
                    </div>

                    <div class="flex">
                        <base-button
                            class="relative rtl:right-20 flex items-center justify-center text-base rounded-lg m-auto mt-96 hover:underline py-1.5 -right-20 font-semibold disabled:bg-gray-100 "
                            :disabled="anchor_loading"
                            @click="finish()"
                            style="top: 5.9rem"
                        >
                            <i v-if="anchor_loading" class="animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-2.5 m-auto before:absolute before:w-2 before:h-2 before:rounded-full before:animate-submit_second before:delay-[0.14s] after:absolute after:w-2 after:h-2 after:rounded-full after:animate-submit_second before:-left-3.5 after:-right-3.5 after:delay-[0.42s]"></i> 

                            <span :class="[{'opacity-0': anchor_loading}]">
                                {{ translations.company.skip }}
                            </span>
                        </base-button>

                        <base-button
                            class="relative ltr:-right-2 rtl:-left-2 flex items-center justify-center text-base rounded-lg m-auto mt-96 bg-default hover:bg-default-hover text-white py-1.5 px-7 font-medium"
                            :disabled="anchor_loading"
                            @click="finish()"
                            style="top: 5.9rem;"
                        >
                            <i v-if="anchor_loading" class="animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-2.5 m-auto before:absolute before:w-2 before:h-2 before:rounded-full before:animate-submit_second before:delay-[0.14s] after:absolute after:w-2 after:h-2 after:rounded-full after:animate-submit_second before:-left-3.5 after:-right-3.5 after:delay-[0.42s]"></i> 

                            <span :class="[{'opacity-0': anchor_loading}]">
                                {{ translations.finish.create_first_invoice }}
                            </span>
                        </base-button>
                    </div>
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
            image_src: app_url + "/public/img/wizard-rocket.gif",
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
                verticalAlign: 'bottom',
                horizontalAlign: 'left',
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
    .sliding-app:hover {
        animation: slidingAnimation 600ms ease-out forwards;
    }   

    @keyframes slidingAnimation {
        0% { transform: translateX(0); }
        40% { transform: translateX(36px); }
        60% { transform: translateX(24px); }
        80% { transform: translateX(30px); }
        100% { transform: translateX(24px); }
    }

    @media only screen and (max-width: 991px) {
        [modal-container] {
            height: 100% !important;
        }

        .scroll{
            height:450px;
        }
    }
</style>
