@extends('layouts.install')

@section('header', trans('install.steps.language'))

@section('content')
    <div class="form-group">
        <div class="col-md-12">
            <select name="lang" id="lang" size="17" class="form-control">
                @foreach (language()->allowed() as $code => $name)
                <option value="{{ $code }}" @if ($code == 'en-GB') {{ 'selected="selected"' }} @endif>{{ $name }}</option>
                @endforeach
            </select>
        </div>
    </div>
@endsection
