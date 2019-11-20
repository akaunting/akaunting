<div class="row justify-content-center">
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-9">
                        {!! Form::select('category', $categories, request('category'), ['class' => 'form-control form-control-sm table-header-search mt-0']) !!}

                        <a href="{{ route('apps.paid') }}" class="btn btn-sm btn-white card-buttons filter-button ml-2">{{ trans('modules.top_paid') }}</a>
                        <a href="{{ route('apps.new') }}" class="btn btn-sm btn-white card-buttons filter-button">{{ trans('modules.new') }}</a>
                        <a href="{{ route('apps.free') }}" class="btn btn-sm btn-white card-buttons filter-button">{{ trans('modules.top_free') }}</a>
                    </div>
                    <div class="col-3 text-right">
                        {!! Form::open(['route' => ['apps.search'], 'role' => 'form', 'method' => 'GET', 'class' => 'm-0']) !!}
                            <input name="keyword" value="{{ isset($keyword) ? $keyword : '' }}" type="text" class="form-control form-control-sm table-header-search" placeholder="Search Apps">
                            <span class="glyphicon glyphicon-search form-control-feedback"></span>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
