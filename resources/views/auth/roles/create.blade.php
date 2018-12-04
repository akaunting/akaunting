@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('general.roles', 1)]))

@section('content')
    <!-- Default box -->
    <div class="box box-success">
        {!! Form::open(['url' => 'auth/roles', 'role' => 'form', 'class' => 'form-loading-button']) !!}

        <div class="box-body">
            {{ Form::textGroup('display_name', trans('general.name'), 'id-card-o') }}

            {{ Form::textGroup('name', trans('general.code'), 'code') }}

            {{ Form::textareaGroup('description', trans('general.description')) }}

            <div id="role-permissions" class="col-md-12">
                <label for="permissions" class="control-label">{{trans_choice('general.permissions', 2)}}</label>

                <br>

                <span class="permission-select-button">{{trans('general.select_all')}}</span> |
                <span class="permission-unselect-button">{{trans('general.unselect_all')}}</span>
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        @foreach($names as $name)
                        <li @php echo ($name == 'read') ? 'class="active"' : ''; @endphp><a href="#tab-{{ $name }}" data-toggle="tab" aria-expanded="false">{{ ucwords($name) }}</a></li>
                        @endforeach
                    </ul>

                    <div class="tab-content">
                        @foreach($permissions as $code => $code_permissions)
                            <div class="tab-pane in @php echo ($code == 'read') ? 'active' : ''; @endphp" id="tab-{{ $code }}">
                                <div class="permission-button-group">
                                    <span class="permission-select-button">{{trans('general.select_all')}}</span>|
                                    <span class="permission-unselect-button">{{trans('general.unselect_all')}}</span>
                                </div>

                                @stack('permissions_input_start')

                                <div class="form-group col-md-12 {{ $errors->has('permissions') ? 'has-error' : '' }}">
                                    <label class="input-checkbox"></label>
                                    <br>
                                    @foreach($code_permissions as $item)
                                        <div class="col-md-3">
                                            <label class="input-checkbox">{{ Form::checkbox('permissions' . '[]', $item->id) }} &nbsp;{{ $item->display_name }}</label>
                                        </div>
                                    @endforeach
                                    {!! $errors->first('permissions', '<p class="help-block">:message</p>') !!}
                                </div>

                                @stack('permissions_input_end')
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            {{ Form::saveButtons('auth/roles') }}
        </div>
        <!-- /.box-footer -->

        {!! Form::close() !!}
    </div>
@endsection

@push('js')
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/iCheck/icheck.min.js') }}"></script>
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/plugins/iCheck/square/green.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $('input[type=checkbox]').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
                increaseArea: '20%' // optional
            });

            $('.permission-select-button').on('click', function (event) {
                $(this).parent().parent().find('input[type=checkbox]').iCheck('check');
            });

            $('.permission-unselect-button').on('click', function (event) {
                $(this).parent().parent().find('input[type=checkbox]').iCheck('uncheck');
            });
        });
    </script>
@endpush

