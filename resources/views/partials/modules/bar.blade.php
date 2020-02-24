<div class="row justify-content-center">
    <div class="col-xs-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-xs-12 col-sm-9">
                        <akaunting-select
                            class="form-control-sm d-inline-block w-auto"
                            :placeholder="'{{ trans('general.form.select.field', ['field' => trans_choice('general.categories', 1)]) }}'"
                            :name="'category'"
                            :icon="'folder'"
                            :options="{{ json_encode($categories) }}"
                            :value="'{{ request('category') }}'"
                            @change="onChangeCategory($event)"
                        ></akaunting-select>
                        {{ Form::hidden('category_page', url("apps/categories"), ['id' => 'category_page']) }}

                        <a href="{{ route('apps.paid') }}" class="btn btn-sm btn-white header-button-top">{{ trans('modules.top_paid') }}</a>
                        <a href="{{ route('apps.new') }}" class="btn btn-sm btn-white header-button-top">{{ trans('modules.new') }}</a>
                        <a href="{{ route('apps.free') }}" class="btn btn-sm btn-white header-button-top">{{ trans('modules.top_free') }}</a>
                    </div>
                    <div class="col-xs-12 col-sm-3 text-right">
                        {!! Form::open(['route' => 'apps.search', 'role' => 'form', 'method' => 'GET', 'class' => 'm-0']) !!}
                            <input name="keyword" value="{{ isset($keyword) ? $keyword : '' }}" type="text" class="form-control form-control-sm d-inline-block w-auto" placeholder="Search Apps">
                            <span class="glyphicon glyphicon-search form-control-feedback"></span>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
