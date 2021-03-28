<template>
    <div class="form-group" :class="col">
        <label :for="name" class="form-control-label">{{ text }}</label>
        <div class="input-group input-group-merge">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fa" :class="'fa-' + icon"></i>
                </span>
            </div>
            <input type="text" :name="input_name" :value="input_value" :id="input_name" class="form-control" v-bind="attributes">
        </div>
    </div>
</template>

<script>
export default {
    name: 'akaunting-invoice-text-group',
    props: {
        name: '',
        input_name: '',
        input_value: '',
        text: '',
        icon: '',
        attributes: [],
        values: [],
        selected: '',
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
