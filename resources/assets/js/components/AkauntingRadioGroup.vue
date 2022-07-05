<template>
    <div :class="col">
        <label :for="name" class="text-black text-sm font-medium">{{ text }}</label>

        <div class="tab-pane tab-example-result fade show active" role="tabpanel" aria-labelledby="-component-tab">
            <div class="btn-group btn-group-toggle radio-yes-no" data-toggle="buttons" v-on:click="onClick">
                <label class="btn disabled:bg-green-100"
                       :class="[{'active': value === 1}]">
                    <input type="radio"
                           :name="name"
                           value="1"
                           :value="real_value = 1"
                           v-on="listeners"
                           :id="name + '-1'"
                           > {{ enable }}
                </label>
                <label class="btn btn-danger"
                       :class="[{'active': value === 0}]">
                    <input type="radio"
                           :name="name"
                           value="0"
                           :value="real_value = 0"
                           v-on="listeners"
                           :id="name + '-0'"> {{ disable }}
                </label>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'akaunting-radio-group',
    props: {
        name: '',
        text: '',
        value: '',
        enable: '',
        disable: '',
        col: ''
    },
    data() {
        return {
            focused: false,
            real_value: this.value
        };
    },
    computed: {
        listeners() {
            return {
                ...this.$listeners,
                change: this.onChange,
                click: this.onClick,
                input: this.updateValue,
                focus: this.onFocus,
                blur: this.onBlur
            };
        }
    },
    methods: {
        updateValue(evt) {
            let val = evt.target.value;
            this.$emit("input", val);
        },
        onChange(evt) {
            let val = evt.target.control.value;
            this.value= val;

            this.$emit("change", val);
        },
        onClick(evt) {
            let val = evt.target.control.value;
            this.real_value = val;

            this.$emit("change", val);
        },
        onFocus(evt) {
            this.focused = true;
            this.$emit("focus", evt);
        },
        onBlur(evt) {
            this.focused = false;
            this.$emit("blur", evt);
        }
    }
}
</script>
