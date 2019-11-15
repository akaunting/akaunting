@extends('layouts.admin')

@section('title', $dashboard->name)

@section('dashboard_action')
    <span class="dashboard-action">
        <div class="dropdown">
            <a class="btn btn-sm items-align-center py-2 mt--1" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-ellipsis-v"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-left dropdown-menu-arrow">
                {!! Form::button(trans('general.title.add', ['type' => trans_choice('general.widgets', 1)]), array(
                    'type'    => 'button',
                    'class'   => 'dropdown-item',
                    'title'   => trans('general.title.add', ['type' => trans_choice('general.widgets', 1)]),
                    '@click'  => 'onCreateWidget()'
                )) !!}

                {!! Form::button(trans('general.title.edit', ['type' => trans_choice('general.dashboard', 1)]), array(
                    'type'    => 'button',
                    'class'   => 'dropdown-item',
                    'title'   => trans('general.title.edit', ['type' => trans_choice('general.dashboard', 1)]),
                    '@click'  => 'onEditDashboard(' . $dashboard->id . ')'
                )) !!}

                @if ($dashboards->count() > 1)
                {!! Form::deleteLink($dashboard, 'common/dashboards') !!}
                @endif

                <div class="dropdown-divider"></div>
                {!! Form::button(trans('general.title.add', ['type' => trans_choice('general.dashboard', 1)]), array(
                    'type'    => 'button',
                    'class'   => 'dropdown-item',
                    'title'   => trans('general.title.add', ['type' => trans_choice('general.dashboard', 1)]),
                    '@click'  => 'onCreateDashboard()'
                )) !!}
            </div>
        </div>
    </span>

    @php
        $text = json_encode([
            'name' => trans('general.name'),
            'type' => 'Type',
            'width' => 'Width',
            'sort' => 'Sort',
            'enabled' => trans('general.enabled'),
            'yes' => trans('general.yes'),
            'no' => trans('general.no'),
            'save' => trans('general.save'),
            'cancel' => trans('general.cancel')
        ]);

        $placeholder = json_encode([
            'name' => trans('general.form.enter', ['field' => trans('general.name')]),
            'type' => trans('general.form.enter', ['field' => 'Type']),
            'width' => trans('general.form.enter', ['field' => 'Width']),
            'sort' => trans('general.form.enter', ['field' => 'Sort'])
        ]);
     @endphp

    <akaunting-dashboard
        v-if="dashboard_modal"
        :title="'{{ trans('general.dashboard') }}'"
        :show="dashboard_modal"
        :name="dashboard.name"
        :enabled="dashboard.enabled"
        :type="dashboard.type"
        :dashboard_id="dashboard.dashboard_id"
        :text="{{ $text }}"
        @cancel="onCancel">
    </akaunting-dashboard>

    <akaunting-widget
        v-if="widget_modal"
        :title="'{{ trans_choice('general.widgets', 1) }}'"
        :show="widget_modal"
        :name="widget.name"
        :width="widget.width"
        :action="widget.action"
        :type="widget.type"
        :sort="widget.sort"
        :types="widgets"
        :widget_id="widget.widget_id"
        :dashboard_id="{{ $dashboard->id }}"
        :text="{{ $text }}"
        :placeholder="{{ $placeholder }}"
        @cancel="onCancel">
    </akaunting-widget>
@endsection

@section('new_button')
    <!--Dashboard General Filter-->
@endsection

@section('content')
    <div class="row">
        @foreach($widgets as $dashboard_widget)
            @widget($dashboard_widget->widget->alias, $dashboard_widget->settings)
        @endforeach
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/common/dashboard.js?v=' . version('short')) }}"></script>
@endpush
