@extends('layouts.install')

@section('header', trans('install.steps.requirements'))

@push('scripts_start')
    <script type="text/javascript">
        var flash_requirements = {!! ($requirements) ? json_encode($requirements) : '[]' !!};
    </script>
@endpush
