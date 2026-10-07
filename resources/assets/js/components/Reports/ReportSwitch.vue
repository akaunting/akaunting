<template>
    <fieldset class="min-w-0" :aria-label="ariaLabel || null">
        <legend v-if="! ariaLabel" class="mb-1 text-sm font-medium text-black">
            {{ label }}<template v-if="changed"><span class="inline-block w-1.5 h-1.5 ms-1.5 align-middle rounded-full bg-purple" aria-hidden="true"></span><span class="sr-only">{{ ' ' + changed }}</span></template>
        </legend>

        <div class="flex gap-1 h-9 p-1 rounded-lg bg-gray-200">
            <label v-for="option in options" :key="option[0]" class="flex-1 min-w-0 cursor-pointer">
                <!-- Native radios: the arrow keys move between the options and change only the staged value -->
                <input
                    type="radio"
                    class="sr-only peer"
                    :name="name"
                    :value="option[0]"
                    :checked="option[0] === value"
                    @change="$emit('input', option[0])"
                >

                <!-- The purple outline marks the choice: white on gray alone is about 1.25:1, below SC 1.4.11's 3:1 -->
                <span class="block px-2 rounded-md text-sm leading-7 text-center text-black truncate peer-checked:bg-white peer-checked:font-medium peer-checked:text-purple peer-checked:shadow-sm peer-checked:ring-1 peer-checked:ring-purple peer-focus-visible:ring-2 peer-focus-visible:ring-purple">
                    {{ option[1] }}
                </span>
            </label>
        </div>
    </fieldset>
</template>

<script>
/**
 * A segmented control built from native radios, for settings with a few values (Basis, Direction) and for a
 * filter's Include/Exclude.
 */
export default {
    name: 'report-switch',

    props: {
        label: {
            type: String,
            default: '',
        },

        // Names the group instead of a visible legend
        ariaLabel: {
            type: String,
            default: '',
        },

        name: {
            type: String,
            required: true,
        },

        // [value, label] pairs
        options: {
            type: Array,
            default: () => [],
        },

        value: {
            type: String,
            default: '',
        },

        // The hidden "changed, applied value …" text while the staged value differs from the applied one
        changed: {
            type: String,
            default: '',
        },
    },
}
</script>
