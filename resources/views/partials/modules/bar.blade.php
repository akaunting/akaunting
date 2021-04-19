<div class="row justify-content-center apps-store-bar">
    <div class="col-xs-12 col-sm-12">
        <div class="card">
            <div class="card-body p-2">
                <div class="row align-items-center">
                    <div class="col-xs-12 col-sm-2 pl-0 pr-0">
                        <akaunting-select
                            class="form-control-sm d-inline-block w-100"
                            placeholder="{{ trans('general.form.select.field', ['field' => trans_choice('general.categories', 1)]) }}"
                            name="category"
                            :icon="''"
                            :options="{{ json_encode($categories) }}"
                            :value="'{{ request('category') }}'"
                            @change="onChangeCategory($event)"
                        ></akaunting-select>
                        {{ Form::hidden('category_page', url(company_id() . "/apps/categories"), ['id' => 'category_page']) }}
                    </div>

                    <div class="vr d-none d-sm-block"></div>

                    <div class="col-xs-12 col-sm-6">
                        {!! Form::open(['route' => 'apps.search', 'role' => 'form', 'method' => 'GET', 'class' => 'm-0']) !!}
                            <div class="searh-field tags-input__wrapper">
                                <input name="keyword" value="{{ isset($keyword) ? $keyword : '' }}" type="text" class="form-control form-control-sm d-inline-block w-100" placeholder="{{ trans('general.search_placeholder') }}" autocomplete="off">
                            </div>
                        {!! Form::close() !!}
                    </div>

                    <div class="col-xs-12 col-sm-4 text-center">
                        <a href="{{ route('apps.paid') }}" class="btn btn-sm btn-white">{{ trans('modules.top_paid') }}</a>
                        <a href="{{ route('apps.new') }}" class="btn btn-sm btn-white">{{ trans('modules.new') }}</a>
                        <a href="{{ route('apps.free') }}" class="btn btn-sm btn-white">{{ trans('modules.top_free') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
