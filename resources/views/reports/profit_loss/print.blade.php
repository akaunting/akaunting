@extends('layouts.print')

@section('title', trans('reports.profit_loss'))

@section('content')
    @include('reports.profit_loss.body')
@endsection