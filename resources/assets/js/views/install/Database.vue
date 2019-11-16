<template>
    <div>
        <div class="card-body">
            <div role="alert" class="alert alert-danger d-none" :class="(form.response.error) ? 'show' : ''" v-if="form.response.error" v-html="form.response.message"></div>

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
                    <button type="button" class="btn btn-default btn-lg wizard-steps rounded-circle">
                        <span class="btn-inner--icon wizard-steps-inner">2</span>
                    </button>
                    <p class="mt-2 after-step-text">Database</p>
                </div>


                <div class="col-md-4 text-center">
                    <button type="button" class="btn btn-secondary btn-lg wizard-steps rounded-circle steps">
                        <span class="btn-inner--icon wizard-steps-inner wizard-steps-color">3</span>
                    </button>
                    <p class="mt-2 text-muted step-text">Admin</p>
                </div>

                <div class="form-group col-md-12 required" :class="[{'has-error': form.errors.get('hostname')}]">
                    <label for="hostname" class="form-control-label">Hostname</label>

                    <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-server"></i>
                            </span>
                        </div>
                        <input class="form-control" data-name="hostname" data-value="localhost" @keydown="form.errors.clear('hostname')" v-model="form.hostname" required="required" name="hostname" type="text" value="localhost" id="hostname">
                    </div>

                    <div class="invalid-feedback" style="display: block;" v-if="form.errors.has('hostname')" v-html="form.errors.get('hostname')"></div>
                </div>

                <div class="form-group col-md-12 required" :class="[{'has-error': form.errors.get('username')}]">
                    <label for="username" class="form-control-label">Username</label>

                    <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-user"></i>
                            </span>
                        </div>
                        <input class="form-control" data-name="username" @keydown="form.errors.clear('username')" v-model="form.username" required="required" name="username" type="text" id="username">
                    </div>

                    <div class="invalid-feedback" style="display: block;" v-if="form.errors.has('username')" v-html="form.errors.get('username')"></div>
                </div>

                <div class="form-group col-md-12" :class="[{'has-error': form.errors.get('password')}]">
                    <label for="password" class="form-control-label">Password</label>

                    <div class="input-group input-group-merge ">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-key"></i>
                            </span>
                        </div>
                        <input class="form-control" data-name="password" v-model="form.password" name="password" type="password" value="" id="password">
                    </div>

                    <div class="invalid-feedback" style="display: block;" v-if="form.errors.has('password')" v-html="form.errors.get('password')"></div>
                </div>

                <div class="form-group col-md-12 mb--2 required" :class="[{'has-error': form.errors.get('database')}]">
                    <label for="database" class="form-control-label">Database</label>

                    <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-database"></i>
                            </span>
                        </div>
                        <input class="form-control" data-name="database" @keydown="form.errors.clear('database')" v-model="form.database" required="required" name="database" type="text" id="database">
                    </div>

                    <div class="invalid-feedback" style="display: block;" v-if="form.errors.has('database')" v-html="form.errors.get('database')"></div>
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
        name: 'database',
        data() {
            return {
                form: new Form('form-install')
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
