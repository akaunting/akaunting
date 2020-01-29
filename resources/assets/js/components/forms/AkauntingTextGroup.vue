<template>
    <div class="form-group" :class="(attributes.required) ? col + ' required' : col">
        <label :for="name" class="form-control-label">{{ text }}</label>
        <div class="input-group input-group-merge">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fa" :class="'fa-' + icon"></i>
                </span>
            </div>
            <input type="text" :name="name" :value="value" :id="name" class="form-control" v-bind="attributes" v-on:input="onChange" v-model:input="forms.data[name]">
            <div class="text-danger invalid-feedback d-block" v-text="" v-if="errors[name]">
                {{ errors[name][0] }}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'akaunting-text-group',
    props: {
        name: '',
        text: '',
        icon: '',
        attributes: [],
        value: '',
        col: ''
    },
    data: {
    },
    created() {
    }
}
</script>
