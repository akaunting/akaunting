<template>
    <div>
        <nav aria-label="Progress">
        	<ol role="list" class="flex mb-10">
                <li class="w-1/3">
                	<span class="pr-6 flex flex-col">
                        <span
                           	class="w-full h-1 bg-gray-300 rounded-xl text-transparent"
                          	:class="[{ 'bg-purple': active_state >= 0 }]"
                        >
                            Text
                        </span>

                        <span
                            class="text-sm font-normal mt-2"
                            :class="[{ 'font-bold': active_state >= 0 }]"
                        >
                            {{ translations.company.title }}
                        </span>
                	</span>
              	</li>

                <li class="w-1/3">
                    <span class="px-3 flex flex-col">
                        <span
                            class="w-full h-1 bg-gray-300 rounded-xl text-transparent"
                            :class="[{ 'bg-purple': active_state >= 1 }]"
                        >
                            Text
                        </span>

                        <span
                            class="text-sm font-normal mt-2"
                            :class="[{ 'font-bold': active_state >= 1 }]"
                        >
                            {{ translations.currencies.title }}
                        </span>
                    </span>
                </li>

                <li class="w-1/3">
                    <span class="ltr:pl-6 rtl:pl-3 flex flex-col">
                        <span
                            :class="[{'bg-purple': active_state == 2}]"
                            class="w-full h-1 bg-gray-300 rounded-xl text-transparent"
                        >
                            Text
                        </span>

                        <span
                            class="text-sm font-normal mt-2"
                            :class="[{ 'font-bold': active_state == 2 }]"
                        >
                            {{ translations.finish.title }}
                        </span>
                    </span>
                </li>
            </ol>
        </nav>
    </div>
</template>

<script>
    export default {
        name: "Steps",

        components: {},

        props: {
            active_state: {
                type: [Boolean, String, Number],
            }
        },

        created() {
            this.translations = wizard_translations;
        },

        data() {
            return {
                translations: {
                    company: {},
                    currencies: {},
                    finish: {},
                },
            };
        },
    };
</script>
