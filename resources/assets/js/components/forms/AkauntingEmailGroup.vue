<template>
    <div class="form-group" :class="(attributes.required) ? col + ' required' : col">
        <label :for="name" class="form-control-label">{{ text }}</label>
        <div class="input-group input-group-merge">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fa" :class="'fa-' + icon"></i>
                </span>
            </div>
            <input type="email" :name="name" :value="value" :id="name" class="form-control" v-bind="attributes">
        </div>
    </div>
</template>

<script>
export default {
    name: 'akaunting-email-group',
    props: {
        name: '',
        text: '',
        icon: '',
        attributes: [],
        value: '',
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
