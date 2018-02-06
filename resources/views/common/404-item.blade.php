@extends('layouts.modules')

@section('title', __('general.dashboard'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-8 no-padding-left">
                <div class="content-header no-padding-left">
                    <h3>{{ __('general.Item not found') }}</h3>
                    <p>{{ __('general.Sorry this item has been deleted or you followed bad URL.') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

