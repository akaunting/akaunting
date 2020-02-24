<template>
    <div class="card">
        <div class="card-header wizard-header p-3">
            <el-steps finish-status="success" align-center>
                <el-step title="Language"></el-step>
                <el-step title="Database"></el-step>
                <el-step title="Admin"></el-step>
            </el-steps>
        </div>

        <div class="card-body">
            <el-alert
                v-for="requirement in requirements"
                :key="requirement"
                :title="requirement"
                :closable="false"
                type="error"
                effect="dark">
            </el-alert>
        </div>

        <div class="card-footer">
            <div class="row save-buttons">
                <div class="col-md-12">
                    <button type="button" @click="onRefresh" class="btn btn-icon btn-success button-submit header-button-top">
                        <span class="btn-inner--text">
                            Refresh &nbsp;
                        </span>
                        <span class="btn-inner--icon">
                            <i class="fas fa-sync"></i>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
    import {Step, Steps, Alert} from 'element-ui';

    export default {
        name: 'requirements',

        components: {
            [Step.name]: Step,
            [Steps.name]: Steps,
            [Alert.name]: Alert,
        },

        data() {
            return {
                requirements: flash_requirements,
            }
        },

        methods: {
            onRefresh() {
                window.location.reload();
            }
        }
    }
</script>
