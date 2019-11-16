<template>
    <component class="dropdown col-md-11 pl-0"
               :is="tag"
               :class="[{show: isOpen}, {'dropdown': direction === 'down'}, {'dropup': direction ==='up'}]"
               aria-haspopup="true"
               :aria-expanded="isOpen"
               @click="toggleDropDown"
               v-click-outside="closeDropDown">
        <input
               autocomplete="off"
               placeholder="Type to search.."
               name="search"
               type="text"
               class="form-control form-control-sm table-header-search"
               style="width: 100%;">
        <div class="dropdown-menu"
             ref="menu"
             :class="[{'dropdown-menu-right': position === 'right'}, {show: isOpen}, menuClasses]"
        >
            <a class="dropdown-item d-none" href="#about">About</a>
        </div>
    </component>
</template>

<script>
export default {
    props: {
        direction: {
            type: String,
            default: "down"
        },
        title: {
            type: String,
            description: "Dropdown title"
        },
        icon: {
            type: String,
            description: "Icon for dropdown title"
        },
        position: {
            type: String,
            description: "Position of dropdown menu (e.g right|left)"
        },
        menuClasses: {
            type: [String, Object],
            description: "Dropdown menu classes"
        },
        hideArrow: {
            type: Boolean,
            description: "Whether dropdown arrow should be hidden"
        },
        appendToBody: {
            type: Boolean,
            default: true,
            description: "Whether dropdown should be appended to document body"
        },
        tag: {
            type: String,
            default: "div",
            description: "Dropdown html tag (e.g div, li etc)"
        }
    },

    data() {
        return {
            isOpen: false
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
