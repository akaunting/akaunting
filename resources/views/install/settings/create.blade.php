@extends('layouts.install')

@section('header', trans('install.steps.settings'))

@section('content')
    {{ Form::textGroup('company_name', trans('install.settings.company_name'), 'id-card-o', ['required' => 'required'], old('company_name'), 'col-md-12') }}

    {{ Form::textGroup('company_email', trans('install.settings.company_email'), 'envelope', ['required' => 'required'], old('company_email'), 'col-md-12') }}

    {{ Form::textGroup('user_email', trans('install.settings.admin_email'), 'envelope', ['required' => 'required'], old('user_email'), 'col-md-12') }}

    {{ Form::passwordGroup('user_password', trans('install.settings.admin_password'), 'key', ['required' => 'required'], old('user_password'), 'col-md-12') }}
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#next-button').attr('disabled', true);

            $('#company_name, #company_email, #user_email, #user_password').keyup(function() {
                inputCheck();
            });
        });

        function inputCheck() {
            company_name = $('#company_name').val();
            company_email = $('#company_email').val();
            user_email = $('#user_email').val();
            user_password = $('#user_password').val();

            if (company_name != '' && company_email != '' && user_email != '' && user_password != '') {
                $('#next-button').attr('disabled', false);
            } else {
                $('#next-button').attr('disabled', true);
            }
        }
    </script>
@endpush
