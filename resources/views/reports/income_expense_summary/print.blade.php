@extends('layouts.print')

@section('title', trans('reports.summary.income_expense'))

@section('content')
    @include('reports.income_expense_summary.body')
@endsection