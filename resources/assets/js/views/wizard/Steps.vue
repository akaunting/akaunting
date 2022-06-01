<template>
  <div>
      <nav aria-label="Progress">
          <ol role="list" class="flex mb-10">
              <li class="w-1/4">
                <span class="pr-6 flex flex-col">
                  <span
                  :class="[{'bg-purple': active_state > 0}, {'bg-purple': active_state == 0}]"
                   class="w-full h-1 bg-gray-300 rounded-xl text-transparent">Text</span>
                  <span :class="[{'font-bold': active_state == 0}, {'font-bold': active_state > 0}]" class="text-sm font-normal mt-2">{{ translations.company.title }}</span>
                </span>
              </li>

              <li class="w-1/4">
                  <span class="px-3 flex flex-col">
                    <span
                     :class="[{'bg-purple': active_state > 1}, {'bg-purple': active_state == 1}]"
                     class="w-full h-1 bg-gray-300 rounded-xl text-transparent">Text</span>
                  <span :class="[{'font-bold': active_state == 1}, {'font-bold': active_state > 1}]" class="text-sm font-normal mt-2">{{ translations.currencies.title }}</span>
                </span>
              </li>

              <li class="w-1/4">
                <span class="px-3 flex flex-col">
                  <span 
                  :class="[{'bg-purple': active_state > 2}, {'bg-purple': active_state == 2}]"
                  class="w-full h-1 bg-gray-300 rounded-xl text-transparent">Text</span>
                  <span :class="[{'font-bold': active_state == 2}, {'font-bold': active_state > 2}]" class="text-sm font-normal mt-2">{{ translations.taxes.title }}</span>
              </span>
              </li>

              <li class="w-1/4">
                <span class="pl-6 flex flex-col">
                  <span
                  :class="[{'bg-purple': active_state == 3}]"
                   class="w-full h-1 bg-gray-300 rounded-xl text-transparent">Text</span>
                  <span :class="[{'font-bold': active_state == 3}]" class="text-sm font-normal mt-2">{{ translations.finish.title }}</span>
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
                  taxes: {},
                  finish: {},
              },
          };
      },
  };
</script>