@extends('layouts.install')

@section('header', trans('install.steps.database'))

@section('content')

    {{ Form::selectGroup('driver', trans('install.database.driver'), 'plug', ['mysql' => 'MySQL', 'pgsql' => 'PostgreSQL'], old('driver', 'mysql'), ['required' => 'required'],  'col-md-12') }}

    {{ Form::textGroup('hostname', trans('install.database.hostname'), 'server', ['required' => 'required'], old('hostname', 'localhost'), 'col-md-12') }}

    {{ Form::numberGroup('port', trans('install.database.port'), 'link', ['placeholder' => 'Leave empty for default port'], old('port', 3306), 'col-md-12') }}

    {{ Form::textGroup('username', trans('install.database.username'), 'user', ['required' => 'required'], old('username'), 'col-md-12') }}

    {{ Form::passwordGroup('password', trans('install.database.password'), 'key', [], old('password'), 'col-md-12') }}

    {{ Form::textGroup('database', trans('install.database.name'), 'database', ['required' => 'required'], old('database'), 'col-md-12') }}

    {{ Form::textGroup('prefix', trans('install.database.prefix'), 'align-left', [], old('prefix'), 'col-md-12') }}
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#next-button').attr('disabled', true);

            $('#hostname, #username, #database').keyup(function() {
                inputCheck();
            });
        });

        function inputCheck() {
            hostname = $('#hostname').val();
            username = $('#username').val();
            database = $('#database').val();

            if (hostname != '' && username != '' && database != '') {
                $('#next-button').attr('disabled', false);
            } else {
                $('#next-button').attr('disabled', true);
            }
        }
    </script>
@endpush
