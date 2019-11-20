@extends('layouts.admin')

@section('title', trans_choice('general.updates', 2))

@section('new_button')
    <span class="new-button">
        <a href="{{ route('updates.check') }}" class="btn btn-warning btn-sm">
            <span class="fa fa-history"></span> &nbsp;{{ trans('updates.check') }}
        </a>
    </span>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="title-filter hidden-xs">{{ $name }}</span>
        </div>

        <div class="card-body">
            <p>
                <el-progress :text-inside="true" :stroke-width="24" :percentage="update.total" :status="update.status"></el-progress>

                <div id="progress-text" v-html="update.html"></div>

                {{ Form::hidden('page', 'update', ['id' => 'page']) }}
                {{ Form::hidden('name', $name, ['id' => 'name']) }}
                {{ Form::hidden('version', $version, ['id' => 'version']) }}
                {{ Form::hidden('alias', $alias, ['id' => 'alias']) }}
                {{ Form::hidden('installed', $installed, ['id' => 'installed']) }}
            </p>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/install/update.js?v=' . version('short')) }}"></script>
@endpush
