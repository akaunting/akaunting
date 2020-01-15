@stack('steps_start')
    <div class="card-header wizard-header p-3">
        <el-steps :active="active" finish-status="success" align-center>
            <el-step title="Company"></el-step>
            <el-step title="Currency"></el-step>
            <el-step title="Taxes"></el-step>
            <el-step title="Finish"></el-step>
        </el-steps>
    </div>
@stack('steps_end')
