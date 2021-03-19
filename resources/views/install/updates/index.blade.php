@extends('layouts.admin')

@section('title', trans_choice('general.updates', 2))

@section('new_button')
    <a href="{{ route('updates.check') }}" class="btn btn-white btn-sm">{{ trans('updates.check') }}</a>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="table-text text-primary">Akaunting</span>
        </div>

        <div class="card-body">
            <div class="row">
                @if (empty($core))
                    <div class="col-md-12">
                        {{ trans('updates.latest_core') }}
                    </div>
                @else
                    <div class="col-sm-2 col-md-6 long-texts">
                        {{ trans('updates.new_core') }}
                    </div>

                    <div class="col-sm-10 col-md-6 text-right">
                        <a href="{{ route('updates.run', ['alias' => 'core', 'version' => $core]) }}" class="btn btn-info btn-sm long-texts header-button-bottom">
                            {{ trans('updates.update', ['version' => $core]) }}
                        </a>

                        <button type="button" @click="onChangelog" class="btn btn-white btn-sm header-button-bottom">
                            {{ trans('updates.changelog') }}
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header border-bottom-0">
            {{ trans_choice('general.modules', 2) }}
        </div>

        <div class="table-responsive">
            <table class="table table-flush table-hover" id="tbl-translations">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-xs-4 col-sm-4 col-md-4">{{ trans('general.name') }}</th>
                        <th class="col-sm-3 col-md-3 d-none d-sm-block">{{ trans('updates.installed_version') }}</th>
                        <th class="col-xs-4 col-sm-3 col-md-3">{{ trans('updates.latest_version') }}</th>
                        <th class="col-xs-4 col-sm-2 col-md-2 text-center">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @if ($modules)
                        @foreach($modules as $module)
                            <tr class="row align-items-center border-top-1">
                                <td class="col-xs-4 col-sm-4 col-md-4">{{ $module->name }}</td>
                                <td class="col-sm-3 col-md-3 d-none d-sm-block">{{ $module->installed }}</td>
                                <td class="col-xs-4 col-sm-3 col-md-3">{{ $module->latest }}</td>
                                <td class="col-xs-4 col-sm-2 col-md-2 text-center">
                                    <a href="{{ route('updates.run', ['alias' => $module->alias, 'version' => $module->latest]) }}" class="btn btn-warning btn-sm">
                                       {{ trans_choice('general.updates', 1) }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="row">
                            <td class="col-12">
                                <div class="text-sm text-muted" id="datatable-basic_info" role="status" aria-live="polite">
                                    <small>{{ trans('general.no_records') }}</small>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <akaunting-modal v-if="changelog.show"
        modal-dialog-class="modal-lg"
        :show="changelog.show"
        :title="'{{ trans('updates.changelog') }}'"
        @cancel="changelog.show = false"
        :message="changelog.html">
        <template #card-footer>
            <span></span>
        </template>
    </akaunting-modal>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/install/update.js?v=' . version('short')) }}"></script>
@endpush
