@extends('layouts.admin')

@section('title', $dashboard->name)

@section('dashboard_action')
    @canany(['create-common-widgets', 'read-common-dashboards'])
        <span class="dashboard-action">
            <div class="dropdown">
                <a class="btn btn-sm items-align-center py-2 mt--1" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-v"></i>
                </a>

                <div class="dropdown-menu dropdown-menu-sm-right dropdown-menu-xs-right dropdown-menu-arrow">
                    @can('create-common-widgets')
                        {!! Form::button(trans('general.title.add', ['type' => trans_choice('general.widgets', 1)]), [
                            'type'    => 'button',
                            'class'   => 'dropdown-item',
                            'title'   => trans('general.title.add', ['type' => trans_choice('general.widgets', 1)]),
                            '@click'  => 'onCreateWidget()',
                        ]) !!}
                    @endcan
                    @can('update-common-dashboards')
                        <div class="dropdown-divider"></div>
                        @can('create-common-dashboards')
                            <a class="dropdown-item" href="{{ route('dashboards.create') }}">{{ trans('general.title.create', ['type' => trans_choice('general.dashboards', 1)]) }}</a>
                        @endcan
                        <a class="dropdown-item" href="{{ route('dashboards.index') }}">{{ trans('general.title.manage', ['type' => trans_choice('general.dashboards', 2)]) }}</a>
                    @endcan
                </div>
            </div>
        </span>
    @endcanany

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
        start-placeholder="{{ $date_picker_shortcuts[trans("reports.this_year")]["start"] }}"
        end-placeholder="{{ $date_picker_shortcuts[trans("reports.this_year")]["end"] }}"
        :picker-options="{
            shortcuts: [
                {
                    text: '{{ trans("reports.this_year") }}',
                    onClick(picker) {
                        const start = new Date('{{ $date_picker_shortcuts[trans("reports.this_year")]["start"] }}');
                        const end = new Date('{{ $date_picker_shortcuts[trans("reports.this_year")]["end"] }}');

                        picker.$emit('pick', [start, end]);
                    }
                },
                {
                    text: '{{ trans("reports.previous_year") }}',
                    onClick(picker) {
                        const start = new Date('{{ $date_picker_shortcuts[trans("reports.previous_year")]["start"] }}');
                        const end = new Date('{{ $date_picker_shortcuts[trans("reports.previous_year")]["end"] }}');

                        picker.$emit('pick', [start, end]);
                    }
                },
                {
                    text: '{{ trans("reports.this_quarter") }}',
                    onClick(picker) {
                        const start = new Date('{{ $date_picker_shortcuts[trans("reports.this_quarter")]["start"] }}');
                        const end = new Date('{{ $date_picker_shortcuts[trans("reports.this_quarter")]["end"] }}');

                        picker.$emit('pick', [start, end]);
                    }
                },
                {
                    text: '{{ trans("reports.previous_quarter") }}',
                    onClick(picker) {
                        const start = new Date('{{ $date_picker_shortcuts[trans("reports.previous_quarter")]["start"] }}');
                        const end = new Date('{{ $date_picker_shortcuts[trans("reports.previous_quarter")]["end"] }}');

                        picker.$emit('pick', [start, end]);
                    }
                },
                {
                    text: '{{ trans("reports.last_12_months") }}',
                    onClick(picker) {
                        const start = new Date('{{ $date_picker_shortcuts[trans("reports.last_12_months")]["start"] }}');
                        const end = new Date('{{ $date_picker_shortcuts[trans("reports.last_12_months")]["end"] }}');

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
