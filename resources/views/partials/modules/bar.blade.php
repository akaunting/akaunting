<div class="col-md-12 no-padding-left">
    <div class="box box-success">
        <div class="box-header with-border">

            <div class="pull-left">
                {!! Form::select('category', $categories, request('category'), ['class' => 'form-control input-sm', 'style' => 'display:inline;width:inherit;']) !!}
                <a href="{{ url('modules/paid') }}" class="btn btn-sm btn-default btn-flat margin" style="margin-left: 20px;">Top Paid</a>
                <a href="{{ url('modules/new') }}" class="btn btn-sm btn-default btn-flat margin">New</a>
                <a href="{{ url('modules/free') }}" class="btn btn-sm btn-default btn-flat margin">Top Free</a>
            </div>

            <div class="pull-right">
                <div class="has-feedback">
                    {!! Form::open(['url' => 'modules/search', 'role' => 'form']) !!}
                    <input type="text" class="form-control input-sm" style="margin-top: 10px;" placeholder="Search Apps">
                    <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    {!! Form::close() !!}
                </div>
            </div>

        </div>
    </div>
</div>