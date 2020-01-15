@stack('steps_start')
    <div class="card-header wizard-header p-3">
        <el-steps :active="active" finish-status="success" align-center>
            <el-step href="{{ url('wizard/companies') }}" title="Company"></el-step>
            <el-step href="{{ url('wizard/currencies') }}" title="Currency"></el-step>
            <el-step href="{{ url('wizard/taxes') }}" title="Taxes"></el-step>
            <el-step href="{{ url('wizard/finish') }}" title="Finish"></el-step>
        </el-steps>
    </div>
@stack('steps_end')
