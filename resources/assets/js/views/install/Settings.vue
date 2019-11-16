<template>
    <div>
        <div class="card-body">
            <div class="row">
                <hr class="install-line">
                <div class="col-md-4 text-center">
                    <router-link to='./language'>
                        <button type="button" class="btn btn-secondary btn-lg wizard-steps wizard-steps-color-active rounded-circle">
                            <span class="btn-inner--icon wizard-steps-inner"><i class="fa fa-check"></i></span>
                        </button>
                        <p class="mt-2 text-muted step-text">Language</p>
                    </router-link>
                </div>

                <div class="col-md-4 text-center">
                    <router-link to='./database'>
                        <button type="button" class="btn btn-secondary btn-lg wizard-steps wizard-steps-color-active rounded-circle">
                            <span class="btn-inner--icon wizard-steps-inner"><i class="fa fa-check"></i></span>
                        </button>
                        <p class="mt-2 text-muted step-text">Database</p>
                    </router-link>
                </div>


                <div class="col-md-4 text-center">
                    <button type="button" class="btn btn-default btn-lg wizard-steps rounded-circle">
                        <span class="btn-inner--icon wizard-steps-inner">3</span>
                    </button>
                    <p class="mt-2 after-step-text">Admin</p>
                </div>

                <div class="form-group col-md-12 required" :class="[{'has-error': form.errors.get('company_name')}]">
                    <label for="company_name" class="form-control-label">Company Name</label>

                    <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-building"></i>
                            </span>
                        </div>
                        <input class="form-control" data-name="company_name" @keydown="form.errors.clear('company_name')" v-model="form.company_name" required="required" name="company_name" type="text" id="company_name">
                    </div>

                    <div class="invalid-feedback" style="display: block;" v-if="form.errors.has('company_name')" v-html="form.errors.get('company_name')"></div>
                </div>

                <div class="form-group col-md-12 required" :class="[{'has-error': form.errors.get('company_email')}]">
                    <label for="company_email" class="form-control-label">Company Email</label>

                    <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-envelope"></i>
                            </span>
                        </div>
                        <input class="form-control" data-name="company_email" @keydown="form.errors.clear('company_email')" v-model="form.company_email" required="required" name="company_email" type="text" id="company_email">
                    </div>

                    <div class="invalid-feedback" style="display: block;" v-if="form.errors.has('company_email')" v-html="form.errors.get('company_email')"></div>
                </div>

                <div class="form-group col-md-12 required" :class="[{'has-error': form.errors.get('user_email')}]">
                    <label for="user_email" class="form-control-label">Admin Email</label>

                    <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-envelope"></i>
                            </span>
                        </div>
                        <input class="form-control" data-name="user_email" @keydown="form.errors.clear('user_email')" v-model="form.user_email" required="required" name="user_email" type="text" id="user_email">
                    </div>

                    <div class="invalid-feedback" style="display: block;" v-if="form.errors.has('user_email')" v-html="form.errors.get('user_email')"></div>
                </div>

                <div class="form-group col-md-12 mb--2 required" :class="[{'has-error': form.errors.get('user_password')}]">
                    <label for="user_password" class="form-control-label">Admin Password</label>

                    <div class="input-group input-group-merge ">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-key"></i>
                            </span>
                        </div>
                        <input class="form-control" data-name="user_password" @keydown="form.errors.clear('user_password')" v-model="form.user_password" required="required" name="user_password" type="password" value="" id="user_password">
                    </div>

                    <div class="invalid-feedback" style="display: block;" v-if="form.errors.has('user_password')" v-html="form.errors.get('user_password')"></div>
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

    export default {
        name: 'settings',
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
