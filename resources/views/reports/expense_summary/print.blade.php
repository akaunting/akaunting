@extends('layouts.print')

@section('title', trans('reports.summary.expense'))

@section('content')
    @include('reports.expense_summary.body')
@endsection