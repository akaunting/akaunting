<template>
    <base-input :label="title" :name="name"
        :readonly="readonly"
        :disabled="disabled"
        :not-required="notRequired"
        :class="[
            {'readonly': readonly},
            {'disabled': disabled},
            formClasses
        ]"
        :error="formError">
        <div class="flex justify-between relative mt-1">
            <input type="text" @change="change" :name="name" :id="name" v-model="color" @keyup="addColor" class="w-full text-sm px-3 py-2.5 mt-1 rounded-lg border border-light-gray text-black placeholder-light-gray bg-white disabled:bg-gray-200 focus:outline-none focus:ring-transparent focus:border-purple">

            <div class="absolute w-7 h-7 flex rounded-full my-auto bottom-2 ltr:right-2 rtl:left-2 cursor-pointer"
                ref="dropdownMenu"
                @click="openPalette"
                :class="`bg-${color}`"
                :style="this.color.includes('#') ? `backgroundColor: ${color}` : `backgroundColor: #${color}`"
            ></div>

            <transition name="fade">
                <div v-show="isOpen" class="w-full border border-gray-300 origin-top-right absolute left-0 top-full mt-2 rounded-md shadow-lg z-10">
                    <div class="rounded-md bg-white shadow-xs p-2">
                        <div class="flex">
                            <div class="w-full flex flex-wrap justify-between">
                                <div v-for="color in colors" :key="color">
                                    <div v-for="variant in variants"
                                        :key="variant"
                                        :colorId="`${color}-${variant}`"
                                        class="rounded-full m-1 color cursor-pointer"
                                        :class="[`bg-${color}-${variant}`, small ? 'w-6 h-6 lg:w-4 lg:h-4' : 'w-8 h-8 xl:w-6 xl:h-6 2xl:w-8 2xl:h-8']"
                                        @click="setColor($event)"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </base-input>
</template>

<script>
export default {
    name: 'akaunting-color',

    props: {
        title: {
            type: String,
            default: '',
            description: "Selectbox label text"
        },

        placeholder: {
            type: String,
            default: '',
            description: "Selectbox input placeholder text"
        },

        formClasses: {
            type: Array,
            default: null,
            description: "Selectbox input class name"
        },

        formError: {
            type: String,
            default: null,
            description: "Selectbox input error message"
        },

        icon: {
            type: String,
            description: "Prepend icon (left)"
        },

        name: {
            type: String,
            default: null,
            description: "Selectbox attribute name"
        },

        value: {
            type: [String, Number, Array, Object],
            default: '',
            description: "Selectbox selected value"
        },

        model: {
            type: [String, Number, Array, Object],
            default: '',
            description: "Selectbox selected model"
        },

        readonly: {
            type: Boolean,
            default: false,
            description: "Selectbox disabled status"
        },

        disabled: {
            type: Boolean,
            default: false,
            description: "Selectbox disabled status"
        },

        notRequired: {
            type: Boolean,
            default: false
        },

        small: {
            type: [Boolean, String],
            default: false,
        },
    },

    data() {
        return {
            isOpen: false,
            color: 'green-500',
            colors: [
                'gray',
                'red',
                'yellow',
                'green',
                'blue',
                'indigo',
                'purple',
                'pink',
            ],

            variants: [
                50,
                100,
                200,
                300,
                400,
                500,
                600,
                700,
                800,
                900,
            ],
        }
    },

    created () {
        document.addEventListener('click', this.closeIfClickedOutside);
    },

    mounted() {
        // Check Here..
        if (this.value) {
            this.color = this.value;
        }

        this.$emit('interface', this.color);

        setTimeout(function() {
            this.change();
        }.bind(this), 800);
    },

    methods: {
        change() {
            this.$emit('interface', this.color);

            this.$emit('change', this.color);
        },

        openPalette() {
            this.isOpen = ! this.isOpen;
        },

        setColor(event) {
            this.isOpen = false;
            this.color = event.target.getAttribute('colorid');
            this.$refs.dropdownMenu.style = '';
        },

        addColor() {
            let code = this.color;
        },

        closeIfClickedOutside(event) {
            let el = this.$refs.dropdownMenu;
            let target = event.target;

            if (typeof el != "undefined") {
                if (el !== target && ! el.contains(target)) {
                    this.isOpen = false;
                }
            }
        },
    },

    watch: {
        color: function (value) {
            this.change();
        },

        value: function (value) {
            this.color = value;
        },

        model: function (value) {
            this.color = value;
        },
    }
}
</script>

<style scoped>
    .fade-enter-active, .fade-leave-active {
        transition: opacity .2s
    }

    .fade-enter, .fade-leave-to
    /* .fade-leave-active in <2.1.8 */
    {
        opacity: 0
    }
</style>
