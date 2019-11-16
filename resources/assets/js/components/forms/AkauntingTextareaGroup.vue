<template>
    <div class="form-group" :class="(attributes.required) ? col + ' required' : col">
        <label :for="name" class="form-control-label">{{ text }}</label>
        <textarea :name="name" :id="name" class="form-control" cols="50" v-bind="attributes">{{ value }}</textarea>
    </div>
</template>

<script>
export default {
    name: 'akaunting-textarea-group',
    props: {
        name: '',
        text: '',
        icon: '',
        value: '',
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
