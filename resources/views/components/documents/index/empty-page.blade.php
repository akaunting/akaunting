
<div class="card">
    <div class="row align-items-center">
        <div class="col-xs-12 col-sm-6 text-center p-5">
            <img class="blank-image" src="{{ asset($imageEmptyPage) }}" alt="@yield('title')"/>
        </div>

        <div class="col-xs-12 col-sm-6 text-center p-5">
            <p class="text-justify description">
                {!! trans($textEmptyPage) !!} {!! trans('general.empty.documentation', ['url' => $urlDocsPath]) !!}
            </p>

            @if ($checkPermissionCreate)
                @can($permissionCreate)
                    <a href="{{ route($createRoute) }}" class="btn btn-success float-right mt-4">
                        <span class="btn-inner--text">{{ trans('general.title.create', ['type' => trans_choice($textPage, 1)]) }}</span>
                    </a>
                @endcan
            @else
                <a href="{{ route($createRoute) }}" class="btn btn-success float-right mt-4">
                    <span class="btn-inner--text">{{ trans('general.title.create', ['type' => trans_choice($textPage, 1)]) }}</span>
                </a>
            @endif
        </div>
    </div>
</div>
