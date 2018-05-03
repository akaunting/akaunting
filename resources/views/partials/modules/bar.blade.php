<div class="col-md-12 no-padding-left">
    <div class="box box-success">
        <div class="box-header with-border">

            <div class="pull-left">
                {!! Form::select('category', $categories, request('category'), ['class' => 'form-control input-sm', 'style' => 'display:inline;width:inherit;']) !!}
                <a href="{{ url('apps/paid') }}" class="btn btn-sm btn-default btn-flat margin" style="margin-left: 20px;">{{ trans('modules.top_paid') }}</a>
                <a href="{{ url('apps/new') }}" class="btn btn-sm btn-default btn-flat margin">{{ trans('modules.new') }}</a>
                <a href="{{ url('apps/free') }}" class="btn btn-sm btn-default btn-flat margin">{{ trans('modules.top_free') }}</a>
            </div>

            <div class="pull-right">
                <div class="has-feedback">
                    {!! Form::open(['url' => 'apps/search', 'role' => 'form', 'method' => 'GET']) !!}
                    <input name="keyword" value="{{ isset($keyword) ? $keyword : '' }}" type="text" class="form-control input-sm" style="margin-top: 10px;" placeholder="Search Apps">
                    <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    {!! Form::close() !!}
                </div>
            </div>

        </div>
    </div>
</div>