@extends('layouts.admin')

@section('title', trans_choice('general.notifications', 2))

@section('content')
    <div class="card">
        <div class="card-header"></div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            @include('partials.admin.pagination', ['items' => $notifications])
        </div>
    </div>
@endsection
