@extends('layouts.admin')

@section('title', $dashboard->name)

@section('dashboard_action')
    @permission(['create-common-widgets', 'read-common-dashboards'])
        <span class="dashboard-action">
            <div class="dropdown">
                <a class="btn btn-sm items-align-center py-2 mt--1" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-v"></i>
                </a>

                <div class="dropdown-menu dropdown-menu-sm-right dropdown-menu-xs-right dropdown-menu-arrow">
                    @permission('create-common-widgets')
                        {!! Form::button(trans('general.title.add', ['type' => trans_choice('general.widgets', 1)]), [
                            'type'    => 'button',
                            'class'   => 'dropdown-item',
                            'title'   => trans('general.title.add', ['type' => trans_choice('general.widgets', 1)]),
                            '@click'  => 'onCreateWidget()',
                        ]) !!}
                    @endpermission
                    @permission('update-common-dashboards')
                        <div class="dropdown-divider"></div>
                        @permission('create-common-dashboards')
                            <a class="dropdown-item" href="{{ route('dashboards.create') }}">{{ trans('general.title.create', ['type' => trans_choice('general.dashboards', 1)]) }}</a>
                        @endpermission
                        <a class="dropdown-item" href="{{ route('dashboards.index') }}">{{ trans('general.title.manage', ['type' => trans_choice('general.dashboards', 2)]) }}</a>
                    @endpermission
                </div>
            </div>
        </span>
    @endpermission

    @php
        $text = json_encode([
            'name' => trans('general.name'),
            'type' => trans_choice('general.types', 1),
            'width' => trans('general.width'),
            'sort' => trans('general.sort'),
            'enabled' => trans('general.enabled'),
            'yes' => trans('general.yes'),
            'no' => trans('general.no'),
            'save' => trans('general.save'),
            'cancel' => trans('general.cancel')
        ]);

        $placeholder = json_encode([
            'name' => trans('general.form.enter', ['field' => trans('general.name')]),
            'type' => trans('general.form.select.field', ['field' => trans_choice('general.types', 1)]),
            'width' => trans('general.form.select.field', ['field' => trans('general.width')]),
            'sort' => trans('general.form.enter', ['field' => trans('general.sprt')])
        ]);
    @endphp

    <akaunting-widget
        v-if="widget_modal"
        :title="'{{ trans_choice('general.widgets', 1) }}'"
        :show="widget_modal"
        :widget_id="widget.id"
        :name="widget.name"
        :width="widget.width"
        :action="widget.action"
        :type="widget.class"
        :types="widgets"
        :sort="widget.sort"
        :dashboard_id="{{ $dashboard->id }}"
        :text="{{ $text }}"
        :placeholder="{{ $placeholder }}"
        @cancel="onCancel">
    </akaunting-widget>
@endsection

@section('new_button')
    <!--Dashboard General Filter-->
    <el-date-picker
        v-model="filter_date"
        type="daterange"
        align="right"
        unlink-panels
        :format="'yyyy-MM-dd'"
        value-format="yyyy-MM-dd"
        @change="onChangeFilterDate"
        range-separator=">>"
        start-placeholder="{{ trans('general.start_date')}}"
        end-placeholder="{{ trans('general.end_date')}}"
        :picker-options="{
            shortcuts: [
                {
                    text: '{{ trans("reports.this_year") }}',
                    onClick(picker) {
                        const end = new Date('{{ $financial_start }}');
                        const start = new Date('{{ $financial_start }}');

                        end.setFullYear(start.getFullYear() + 1);
                        end.setTime(end.getTime() - 3600 * 1000 * 24 * 1);

                        picker.$emit('pick', [start, end]);
                    }
                },
                {
                    text: '{{ trans("reports.previous_year") }}',
                    onClick(picker) {
                        const end = new Date('{{ $financial_start }}');
                        const start = new Date('{{ $financial_start }}');

                        start.setTime(start.getTime() - 3600 * 1000 * 24 * 365);

                        end.setFullYear(start.getFullYear() + 1);
                        end.setTime(end.getTime() - 3600 * 1000 * 24 * 1);

                        picker.$emit('pick', [start, end]);
                    }
                },
                {
                    text: '{{ trans("reports.this_quarter") }}',
                    onClick(picker) {
                        const now = new Date();
                        const quarter = Math.floor((now.getMonth() / 3));
                        const start = new Date(now.getFullYear(), quarter * 3, 1);
                        const end = new Date(start.getFullYear(), start.getMonth() + 3, 0);

                        picker.$emit('pick', [start, end]);
                    }
                },
                {
                    text: '{{ trans("reports.previous_quarter") }}',
                    onClick(picker) {
                        const now = new Date();
                        const quarter = Math.floor((now.getMonth() / 3));
                        const start = new Date(now.getFullYear(), quarter * 3, 1);
                        const end = new Date(start.getFullYear(), start.getMonth() + 3, 0);

                        start.setMonth(start.getMonth() - 3);
                        end.setMonth(end.getMonth() - 3);

                        picker.$emit('pick', [start, end]);
                    }
                },
                {
                    text: '{{ trans("reports.last_12_months") }}',
                    onClick(picker) {
                        const end = new Date();
                        const start = new Date();

                        start.setTime(start.getTime() - 3600 * 1000 * 24 * 365);

                        picker.$emit('pick', [start, end]);
                    }
                }
            ]
        }">
    </el-date-picker>
@endsection

@section('content')
    <div class="row">
        @foreach($widgets as $widget)
            @widget($widget)
        @endforeach
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/common/dashboards.js?v=' . version('short')) }}"></script>
@endpush
