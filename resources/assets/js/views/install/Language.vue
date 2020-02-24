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
                <div class="col-md-12">
                    <div class="form-group mb-0">
                        <select v-model="form.lang" name="lang" id="lang" size="13" class="col-xl-12 form-control-label">
                            <option v-for="(name, code) in languages" :value="code">
                                {{ name }}
                            </option>
                        </select>
                    </div>
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

    var base_path = url.replace(window.location.origin, '');

    export default {
        name: 'language',

        components: {
            [Step.name]: Step,
            [Steps.name]: Steps
        },

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
                languages: [],
                active: 0
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
