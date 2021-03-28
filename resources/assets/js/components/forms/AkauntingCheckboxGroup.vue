<template>
    <div class="form-group" :class="(attributes.required) ? col + ' required' : col">
        <div class="input-checkbox">
            <label :for="name" class="form-control-label">{{ text }}</label>
        </div>
        <div class="row">
            <div class="col-md-4" v-for="(key, item, index) in items">
                <div class="input-checkbox">
                    <input type="checkbox" :name="name" :value="key[id]"> <small>{{ key[value] }}</small>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'akaunting-checkbox-group',
    props: {
        name: '',
        text: '',
        items: [],
        id: '',
        value: '',
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
