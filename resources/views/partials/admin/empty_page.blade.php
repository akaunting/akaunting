<div class="card">
    <div class="row align-items-center">
        <div class="col-xs-12 col-sm-6 text-center p-5">
            <img class="blank-image" src="{{ asset('public/img/empty_pages/' . $page . '.png') }}" alt="@yield('title')"/>
        </div>

        <div class="col-xs-12 col-sm-6 text-center p-5">
            <p class="text-justify description">{!! trans('general.empty.' . $page) !!} {!! trans('general.empty.documentation', ['url' => 'https://akaunting.com/docs/user-manual/' . $docs_path]) !!}</p>
            <a href="{{ route($page . '.create') }}" class="btn btn-success header-button-top float-right mt-4">
                <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                <span class="btn-inner--text">{{ trans('general.title.create', ['type' => trans_choice('general.' . $page, 1)]) }}</span>
            </a>
        </div>
    </div>
</div>
