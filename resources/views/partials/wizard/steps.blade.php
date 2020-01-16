@stack('steps_start')
    <div class="card-header wizard-header p-3">
        <el-steps :active="active" finish-status="success" align-center>
            <el-step title="{{ trans_choice('general.companies', 1) }}"></el-step>
            <el-step title="{{ trans_choice('general.currencies', 2) }}"></el-step>
            <el-step title="{{ trans_choice('general.taxes', 2) }}"></el-step>
            <el-step title="{{ trans('general.finish') }}"></el-step>
        </el-steps>
    </div>
@stack('steps_end')
