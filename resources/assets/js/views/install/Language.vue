<template>
    <div>
        <div class="card-body">
            <div class="row">
                <hr class="install-line">
                <div class="col-md-4 text-center">
                    <button type="button" class="btn btn-default btn-lg wizard-steps rounded-circle">
                        <span class="btn-inner--icon wizard-steps-inner">1</span>
                    </button>
                    <p class="mt-2 after-step-text">Language</p>
                </div>

                <div class="col-md-4 text-center">
                    <button type="button" class="btn btn-secondary btn-lg wizard-steps rounded-circle steps">
                        <span class="btn-inner--icon wizard-steps-inner wizard-steps-color">2</span>
                    </button>
                    <p class="mt-2 text-muted step-text">Database</p>
                </div>

                <div class="col-md-4 text-center">
                    <button type="button" class="btn btn-secondary btn-lg wizard-steps rounded-circle steps">
                        <span class="btn-inner--icon wizard-steps-inner wizard-steps-color">3</span>
                    </button>
                    <p class="mt-2 text-muted step-text">Admin</p>
                </div>


                <div class="col-md-12">
                    <div class="form-group mb-0">
                        <select v-model="form.lang" name="lang" id="lang" size="13" class="col-xl-12 form-control-label">
                            <option
                                v-for="(name, code) in languages"
                                v-bind:value="code">
                                {{ name }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="float-right">
                <button v-on:click="onSubmit" :disabled="form.loading" type="submit" id="next-button" class="btn btn-success" data-loading-text="Loading...">
                    <div class="aka-loader"></div>
                    <span>Next &nbsp;
                        <i  class="fa fa-arrow-right"></i>
                    </span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
    import Form from './../../plugins/form';

    var base_path = url.replace(window.location.origin, '');

    export default {
        name: 'language',
        mounted() {
            axios.get(base_path + '/install/language/getLanguages')
            .then(response => {
                this.languages = response.data.languages;
                this.form.lang = 'en-GB';
            })
            .catch(error => {
            });
        },
        data() {
            return {
                form: new Form('form-install'),
                languages: []
            }
        },
        methods: {
            // Form Submit
            onSubmit() {
                this.form.submit();
            },
        }
    }
</script>
