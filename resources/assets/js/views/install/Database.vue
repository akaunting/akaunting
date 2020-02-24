<template>
    <div class="card">
        <div class="card-header wizard-header p-3">
            <el-steps :active="active" finish-status="success" align-center>
                <el-step title="Language"></el-step>
                <el-step title="Database"></el-step>
                <el-step title="Admin"></el-step>
            </el-steps>
        </div>

        <div class="card-body">
            <div role="alert" class="alert alert-danger d-none" :class="(form.response.error) ? 'show' : ''" v-if="form.response.error" v-html="form.response.message"></div>

            <div class="row">
                <div class="col-md-12 form-group required" :class="[{'has-error': form.errors.get('hostname')}]">
                    <label for="hostname" class="form-control-label">Hostname</label>

                    <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-server"></i>
                            </span>
                        </div>
                        <input class="form-control" data-name="hostname" data-value="localhost" @keydown="form.errors.clear('hostname')" v-model="form.hostname" required="required" name="hostname" type="text" value="localhost" id="hostname">
                    </div>

                    <div class="invalid-feedback d-block" v-if="form.errors.has('hostname')" v-html="form.errors.get('hostname')"></div>
                </div>

                <div class="col-md-12 form-group required" :class="[{'has-error': form.errors.get('username')}]">
                    <label for="username" class="form-control-label">Username</label>

                    <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-user"></i>
                            </span>
                        </div>
                        <input class="form-control" data-name="username" @keydown="form.errors.clear('username')" v-model="form.username" required="required" name="username" type="text" id="username">
                    </div>

                    <div class="invalid-feedback d-block" v-if="form.errors.has('username')" v-html="form.errors.get('username')"></div>
                </div>

                <div class="col-md-12 form-group" :class="[{'has-error': form.errors.get('password')}]">
                    <label for="password" class="form-control-label">Password</label>

                    <div class="input-group input-group-merge ">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-key"></i>
                            </span>
                        </div>
                        <input class="form-control" data-name="password" v-model="form.password" name="password" type="password" value="" id="password">
                    </div>

                    <div class="invalid-feedback d-block" v-if="form.errors.has('password')" v-html="form.errors.get('password')"></div>
                </div>

                <div class="col-md-12 form-group mb--2 required" :class="[{'has-error': form.errors.get('database')}]">
                    <label for="database" class="form-control-label">Database</label>

                    <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-database"></i>
                            </span>
                        </div>
                        <input class="form-control" data-name="database" @keydown="form.errors.clear('database')" v-model="form.database" required="required" name="database" type="text" id="database">
                    </div>

                    <div class="invalid-feedback d-block" v-if="form.errors.has('database')" v-html="form.errors.get('database')"></div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="row save-buttons">
                <div class="col-md-12">
                    <button type="submit" @click="onSubmit" :disabled="form.loading" id="next-button" class="btn btn-icon btn-success button-submit header-button-top">
                        <div v-if="form.loading" class="aka-loader-frame">
                            <div class="aka-loader"></div>
                        </div>
                         <span v-if="!form.loading" class="btn-inner--text">
                            Next &nbsp;
                        </span>
                        <span v-if="!form.loading" class="btn-inner--icon">
                            <i class="fas fa-arrow-right"></i>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
    import Form from './../../plugins/form';
    import {Step, Steps} from 'element-ui';

    export default {
        name: 'database',

        components: {
            [Step.name]: Step,
            [Steps.name]: Steps
        },

        data() {
            return {
                form: new Form('form-install'),
                active: 1
            }
        },

        methods: {
            // Form Submit
            onSubmit() {
                this.form.submit();
            },

            next() {
                if (this.active++ > 2);
            }
        }
    }
</script>
