@extends('layouts.print')

@section('title', trans('reports.summary.income'))

@section('content')
    @include('reports.income_summary.body')
@endsection