@extends('layouts.print')

@section('title', trans('reports.summary.tax'))

@section('content')
    <div class="box-header">
        <h2>{{ trans('reports.summary.tax') }}</h2>
        <div class="text-muted">
            {{ setting('general.company_name') }}
            <br/>
            {{ Date::parse(request('year') . '-1-1')->format($date_format) }} - {{ Date::parse(request('year') . '-12-31')->format($date_format) }}
        </div>
    </div>
    @include('reports.tax_summary.body')
@endsection