<template>
    <div class="form-group" :class="(attributes.required) ? col + ' required' : col">
        <label :for="name" class="form-control-label">{{ text }}</label>
        <div class="input-group input-group-merge">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fa" :class="'fa-' + icon"></i>
                </span>
            </div>
            <select :name="name" :id="name" class="form-control" v-bind="attributes">
                <option value="" disabled>{{ attributes.placeholder }}</option>
                <option v-for="(key, value, index) in values" :value="value" :selected="selected == value">{{ key }}</option>
            </select>
        </div>
    </div>
</template>

<script>
export default {
    name: 'akaunting-select-group',
    props: {
        name: '',
        text: '',
        icon: '',
        values: [],
        selected: '',
        attributes: [],
        col: ''
    },
    data () {
        return {
        }
    },
    created() {
    }
}
</script>
