<template>
    <div class="col-md-12">
        <a href="http://localhost/Ak-Dev/Beta/v2.0.0/common/items" class="btn btn-icon btn-outline-secondary">
            <span class="btn-inner--icon"><i class="fas fa-times"></i></span>
            <span class="btn-inner--text">Cancel</span>
        </a>

        <loading :active.sync="isLoading"
                 :can-cancel="false"
                 :on-cancel="onCancel"
                 :is-full-page="fullPage"
        ></loading>

        <button type="button" v-on:click="doLoading" class="btn btn-icon btn-success button-submit">
            <span class="btn-inner--icon"><i class="fas fa-save"></i></span>
            <span class="btn-inner--text"> Save</span>
        </button>
    </div>
</template>

<script>
    import Vue from 'vue';
    // Import component
    import Loading from 'vue-loading-overlay';
    // Import stylesheet
    import 'vue-loading-overlay/dist/vue-loading.css';

    export default {
        name: 'akaunting-save-buttons',
        components: {
            Loading
        },
        props: {
            formSubmit: Function,
            loading: false
        },
        data () {
            return {
                isLoading: loading,
                fullPage: true
            }
        },
        methods: {
            doLoading() {
                this.isLoading = true;

                this.formSubmit();
            },
            onCancel() {
                console.log('User cancelled the loader.')
            }
        }
    }
</script>
