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
            <div class="row">
                <div class="col-md-12 form-group required" :class="[{'has-error': form.errors.get('company_name')}]">
                    <label for="company_name" class="form-control-label">Company Name</label>

                    <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-building"></i>
                            </span>
                        </div>
                        <input class="form-control" data-name="company_name" @keydown="form.errors.clear('company_name')" v-model="form.company_name" required="required" name="company_name" type="text" id="company_name">
                    </div>

                    <div class="invalid-feedback d-block" v-if="form.errors.has('company_name')" v-html="form.errors.get('company_name')"></div>
                </div>

                <div class="col-md-12 form-group required" :class="[{'has-error': form.errors.get('company_email')}]">
                    <label for="company_email" class="form-control-label">Company Email</label>

                    <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-envelope"></i>
                            </span>
                        </div>
                        <input class="form-control" data-name="company_email" @keydown="form.errors.clear('company_email')" v-model="form.company_email" required="required" name="company_email" type="text" id="company_email">
                    </div>

                    <div class="invalid-feedback d-block" v-if="form.errors.has('company_email')" v-html="form.errors.get('company_email')"></div>
                </div>

                <div class=" col-md-12 form-group required" :class="[{'has-error': form.errors.get('user_email')}]">
                    <label for="user_email" class="form-control-label">Admin Email</label>

                    <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-envelope"></i>
                            </span>
                        </div>
                        <input class="form-control" data-name="user_email" @keydown="form.errors.clear('user_email')" v-model="form.user_email" required="required" name="user_email" type="text" id="user_email">
                    </div>

                    <div class="invalid-feedback d-block" v-if="form.errors.has('user_email')" v-html="form.errors.get('user_email')"></div>
                </div>

                <div class="col-md-12 form-group mb--2 required" :class="[{'has-error': form.errors.get('user_password')}]">
                    <label for="user_password" class="form-control-label">Admin Password</label>

                    <div class="input-group input-group-merge ">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-key"></i>
                            </span>
                        </div>
                        <input class="form-control" data-name="user_password" @keydown="form.errors.clear('user_password')" v-model="form.user_password" required="required" name="user_password" type="password" value="" id="user_password">
                    </div>

                    <div class="invalid-feedback d-block" v-if="form.errors.has('user_password')" v-html="form.errors.get('user_password')"></div>
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
        name: 'settings',

        components: {
            [Step.name]: Step,
            [Steps.name]: Steps
        },

        data() {
            return {
                form: new Form('form-install'),
                languages: [],
                active: 2
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
