<template>
    <div class="xl:absolute w-12 xl:ltr:right-0 xl:rtl:left-0 xl:-top-12">
        <label class="text-black text-sm font-medium mb-1 xl:hidden">{{ label }}</label>

        <div class="relative full flex items-center cursor-pointer">
            <input type="radio" :name="name" v-show="selected == '0'" @click="enabled = 1" value="1" id="enabled-1" v-model="selected" class="w-full h-full absolute right-0 z-20 opacity-0 cursor-pointer">
            <input type="radio" :name="name" v-show="selected == '1'" @click="enabled = 0" value="0" id="enabled-0" v-model="selected" class="w-full h-full absolute left-0 z-20 opacity-0 cursor-pointer">

            <div class="absolute left-1 top-1 bg-white w-5 h-5 rounded-full transition transform" :class="selected == '1' ? 'translate-x-full' : 'translate-x-0'"></div>
            <div class="block w-full h-7 rounded-full transition transition-color" :class="selected == '1' ? 'bg-green' : 'bg-green-200'"></div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'akaunting-switch',

    props: {
        name: null,

        value: {
            type: [String, Number, Array, Object, Boolean],
            default: '1',
            description: "Selectbox selected value"
        },

        label: {
            type: String,
            description: "Selectbox selected label"
        },

        model: {
            type: [String, Number, Array, Object, Boolean],
            default: '',
            description: "Selectbox selected model"
        },

        inputData: {
            type: [String, Number, Array, Object, Boolean],
        },
    },

    data() {
        return {
            enabled: 1,
            selected: this.value,
        }
    },

    methods: {
        change() {
            this.$emit('interface', this.selected);

            this.$emit('change', this.selected);
        }
    },

    mounted() {
        this.$emit('interface', this.selected);

        setTimeout(function() {
            this.change();
        }.bind(this), 800);
    },

    watch: {
        value: function(val) {
            this.selected = val;
        },

        selected: function(val) {
            this.change();
        },
    }
}
</script>