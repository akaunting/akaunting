@extends('layouts.install')

@section('header', trans('install.steps.language'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="form-group mb-0">
                <select name="lang" id="lang" size="14" class="col-xl-12 form-control-label">
                    @foreach (language()->allowed() as $code => $name)
                        <option value="{{ $code }}" @if ($code == 'en-GB') {{ 'selected="selected"' }} @endif>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
@endsection
