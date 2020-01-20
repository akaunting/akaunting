<template>

    <base-input :label="title"
        :name="name"
        :class="formClasses"
        :error="formError">

        <el-select  @input="change" filterable :placeholder="placeholder">

            <template slot="prefix">
                <span class="el-input__suffix-inner el-select-icon">
                    <i :class="'select-icon-position el-input__icon fa fa-' + icon"></i>
                </span>
            </template>

            <el-option v-if="!group" v-for="(label, value) in selectOptions"
                :key="value"
                :label="label"
                :value="value">
            </el-option>

            <el-option-group
                v-if="group"
                v-for="(options, name) in selectOptions"
                :key="name"
                :label="name">
                <el-option
                    v-for="(label, value) in options"
                    :key="value"
                    :label="label"
                    :value="value">
                </el-option>
            </el-option-group>

        </el-select>

    </base-input>

</template>

<script>
import { Select, Option, OptionGroup } from 'element-ui';

export default {
    components: {
        [Select.name]: Select,
        [Option.name]: Option,
        [OptionGroup.name]: OptionGroup,
    },

    props: {
        title: {
            type: String,
            default: '',
            description: "Modal header title"
        },
        placeholder: {
            type: String,
            default: '',
            description: "Modal header title"
        },
        formClasses: null,
        formError: null,
        name: null,
        value: null,
        options: null,
        model: null,
        icon: {
            type: String,
            description: "Prepend icon (left)"
        },

        group: false,
        multiple:false,
        disabled:false,
        collapse: false
    },

    data() {
        return {
            isOpen: false,
            selectOptions: this.options,
            real_model: this.model,
        }
    },

    methods: {
        toggleDropDown() {
            this.isOpen = !this.isOpen;
            this.$emit("change", this.isOpen);
        },
        closeDropDown() {
            this.isOpen = false;
            this.$emit("change", this.isOpen);
        }
    },

    directives: {
        'click-outside': {
            bind: function(el, binding, vNode) {
                // Provided expression must evaluate to a function.
                if (typeof binding.value !== 'function') {
                    const compName = vNode.context.name
                    let warn = `[Vue-click-outside:] provided expression '${binding.expression}' is not a function, but has to be`
                    if (compName) { warn += `Found in component '${compName}'` }

                    console.warn(warn)
                }
                // Define Handler and cache it on the element
                const bubble = binding.modifiers.bubble
                const handler = (e) => {
                    if (bubble || (!el.contains(e.target) && el !== e.target)) {
                        binding.value(e)
                    }
                }
                el.__vueClickOutside__ = handler

                // add Event Listeners
                document.addEventListener('click', handler)
            },

            unbind: function(el, binding) {
                // Remove Event Listeners
                document.removeEventListener('click', el.__vueClickOutside__)
                el.__vueClickOutside__ = null

            }
        }
    }
}
</script>
