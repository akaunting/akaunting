@extends('layouts.modules')

@section('title', trans_choice('general.modules', 2))

@section('new_button')
    <a href="{{ route('apps.api-key.create') }}" class="btn btn-white btn-sm">{{ trans('modules.api_key') }}</a>
    <a href="{{ route('apps.my.index') }}" class="btn btn-white btn-sm">{{ trans('modules.my_apps') }}</a>
@endsection

@section('content')
    @include('partials.modules.bar')

    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-xs-6 col-sm-6">
                    <div class="float-left">
                        <h3>{{ $module->name }}</h3>
                    </div>
                </div>

                <div class="col-xs-6 col-sm-6">
                    <div class="float-right">
                        @for($i = 1; $i <= $module->vote; $i++)
                            <i class="fa fa-star fa-sm text-yellow"></i>
                        @endfor

                        @for($i = $module->vote; $i < 5; $i++)
                            <i class="fa fa-star-o fa-sm"></i>
                        @endfor
                    </div>
                </div>
            </div>

            <div class="nav-wrapper pt-0">
                <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link mb-sm-2 mb-md-0 active" href="#description" data-toggle="tab" aria-selected="false">
                            {{ trans('general.description') }}
                        </a>
                    </li>
                    @if ($module->installation)
                        <li class="nav-item">
                            <a class="nav-link mb-sm-2 mb-md-0" href="#installation" data-toggle="tab" aria-selected="false">
                                {{ trans('modules.tab.installation') }}
                            </a>
                        </li>
                    @endif
                    @if ($module->faq)
                        <li class="nav-item">
                            <a class="nav-link mb-sm-2 mb-md-0" href="#faq" data-toggle="tab" aria-selected="false">
                                {{ trans('modules.tab.faq') }}
                            </a>
                        </li>
                    @endif
                    @if ($module->changelog)
                        <li class="nav-item">
                            <a class="nav-link mb-sm-2 mb-md-0" href="#changelog" data-toggle="tab" aria-selected="false">
                                {{ trans('modules.tab.changelog') }}
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link mb-sm-2 mb-md-0" href="#review" data-toggle="tab" aria-selected="false">
                            {{ trans('modules.tab.reviews') }} @if ($module->total_review) ({{ $module->total_review }}) @endif
                        </a>
                    </li>
                </ul>
             </div>

            <div class="card">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="description">
                            {!! $module->description !!}

                            @if($module->screenshots || $module->video)
                               <akaunting-carousel :name="'{{ $module->name }}'" :height="'430px'" arrow="always"
                                    @if($module->video)
                                        @php
                                            if (strpos($module->video->link, '=') !== false) {
                                                $code = explode('=', $module->video->link);
                                                $code[1]= str_replace('&list', '', $code[1]);
                                            }
                                        @endphp
                                        :video="'{{ $code[1] }}'"
                                    @endif
                                    :screenshots="{{ json_encode($module->screenshots) }}">
                                </akaunting-carousel>
                            @endif
                        </div>

                         @if ($module->installation)
                            <div class="tab-pane fade" id="installation">
                                {!! $module->installation !!}
                            </div>
                         @endif

                         @if ($module->faq)
                            <div class="tab-pane fade" id="faq">
                                {!! $module->faq !!}
                            </div>
                         @endif

                         @if ($module->changelog)
                            <div class="tab-pane fade" id="changelog">
                                @php
                                    $releases = $module->app_releases;
                                @endphp

                                <div id="releases" class="clearfix" v-if="releases.status" v-html="releases.html"></div>

                                <div id="releases" class="clearfix" v-else>
                                    @include('partials.modules.releases')
                                </div>

                                @php
                                    $release_first_item = count($releases->data) > 0 ? ($releases->current_page - 1) * $releases->per_page + 1 : null;
                                    $release_last_item = count($releases->data) > 0 ? $release_first_item + count($releases->data) - 1 : null;
                                @endphp

                                @if (!empty($release_first_item))
                                    @stack('pagination_start')

                                    <div class="row mt-4">
                                        <div class="col-md-6">
                                            <span class="table-text d-lg-block">
                                                {{ trans('pagination.showing', ['first' => $release_first_item, 'last' => $release_last_item, 'total' => $releases->total, 'type' => strtolower(trans('modules.tab.changelog'))]) }}
                                            </span>
                                        </div>

                                        <div class="col-md-6">
                                            <ul class="pagination float-right">
                                                {{-- Previous Page Link --}}
                                                <li class="page-item disabled" v-if="releases.pagination.current_page == 1">
                                                    <span class="page-link">&laquo;</span>
                                                </li>
                                                <li class="page-item" v-else>
                                                    <button type="button" class="page-link" @click="onReleases(releases.pagination.current_page - 1)" rel="prev">&laquo;</button>
                                                </li>

                                                {{-- Pagination Elements --}}
                                                @for ($page = 1; $page <= $releases->last_page; $page++)
                                                    <li class="page-item" :class="[{'active': releases.pagination.current_page == {{ $page }}}]" v-if="releases.pagination.current_page == {{ $page }}">
                                                        <span class="page-link">{{ $page }}</span>
                                                    </li>
                                                    <li class="page-item" v-else>
                                                        <button type="button" class="page-link" @click="onReleases({{ $page }})" data-page="{{ $page }}">{{ $page }}</button>
                                                    </li>
                                                @endfor

                                                {{-- Next Page Link --}}
                                                <li class="page-item" v-if="releases.pagination.last_page != releases.pagination.current_page">
                                                    <button type="button" class="page-link" @click="onReleases(releases.pagination.current_page + 1)" rel="next">&raquo;</button>
                                                </li>
                                                <li class="page-item disabled" v-else>
                                                    <span class="page-link">&raquo;</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    @stack('pagination_end')
                                @else
                                    <div class="row">
                                        <div class="col-md-12">
                                            <small>{{ trans('general.no_records') }}</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                         @endif

                         <div class="tab-pane fade" id="review">
                            @php
                                $reviews = $module->app_reviews;
                            @endphp

                            <div id="reviews" class="clearfix" v-if="reviews.status" v-html="reviews.html"></div>

                            <div id="reviews" class="clearfix" v-else>
                                @include('partials.modules.reviews')
                            </div>

                            @php
                                $review_first_item = count($reviews->data) > 0 ? ($reviews->current_page - 1) * $reviews->per_page + 1 : null;
                                $review_last_item = count($reviews->data) > 0 ? $review_first_item + count($reviews->data) - 1 : null;
                            @endphp

                            @if (!empty($review_first_item))
                                @stack('pagination_start')

                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <span class="table-text d-lg-block">
                                            {{ trans('pagination.showing', ['first' => $review_first_item, 'last' => $review_last_item, 'total' => $reviews->total, 'type' => strtolower(trans('modules.tab.reviews'))]) }}
                                        </span>
                                    </div>

                                    <div class="col-md-6">
                                        <ul class="pagination float-right">
                                            {{-- Previous Page Link --}}
                                            <li class="page-item disabled" v-if="reviews.pagination.current_page == 1">
                                                <span class="page-link">&laquo;</span>
                                            </li>
                                            <li class="page-item" v-else>
                                                <button type="button" class="page-link" @click="onReviews(reviews.pagination.current_page - 1)" rel="prev">&laquo;</button>
                                            </li>

                                            {{-- Pagination Elements --}}
                                            @for ($page = 1; $page <= $reviews->last_page; $page++)
                                                <li class="page-item" :class="[{'active': reviews.pagination.current_page == {{ $page }}}]" v-if="reviews.pagination.current_page == {{ $page }}">
                                                    <span class="page-link">{{ $page }}</span>
                                                </li>
                                                <li class="page-item" v-else>
                                                    <button type="button" class="page-link" @click="onReviews({{ $page }})" data-page="{{ $page }}">{{ $page }}</button>
                                                </li>
                                            @endfor

                                            {{-- Next Page Link --}}
                                            <li class="page-item" v-if="reviews.pagination.last_page != reviews.pagination.current_page">
                                                <button type="button" class="page-link" @click="onReviews(reviews.pagination.current_page + 1)" rel="next">&raquo;</button>
                                            </li>
                                            <li class="page-item disabled" v-else>
                                                <span class="page-link">&raquo;</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                @stack('pagination_end')
                            @else
                                <div class="row">
                                    <div class="col-md-12">
                                        <small>{{ trans('general.no_records') }}</small>
                                    </div>
                                </div>
                            @endif

                            <div class="card-footer mx--4 mb--4">
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        @if (!empty($module->review_action))
                                            <a href="{{ $module->review_action }}" class="btn btn-success" target="_blank">
                                                {{ trans('modules.reviews.button.add') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                         </div>
                     </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <h3>{{ trans_choice('general.actions', 1) }}</h3>

            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <strong>
                            <div class="text-xl">
                                @if ($module->price == '0.0000')
                                    {{ trans('modules.free') }}
                                @else
                                    {!! $module->price_prefix !!}

                                    @if (isset($module->special_price))
                                        <del class="text-danger">{{ $module->price }}</del>
                                        {{ $module->special_price }}
                                    @else
                                        {{ $module->price }}
                                    @endif
                                    {!! $module->price_suffix !!}
                                @endif
                            </div>
                        </strong>
                    </div>
                </div>

                <div class="card-footer">
                    @if ($installed)
                        @can('delete-modules-item')
                            <a href="{{ route('apps.app.uninstall', $module->slug) }}" class="btn btn-block btn-danger">{{ trans('modules.button.uninstall') }}</a>
                        @endcan

                        @can('update-modules-item')
                            @if ($enable)
                                <a href="{{ route('apps.app.disable', $module->slug) }}" class="btn btn-block btn-warning">{{ trans('modules.button.disable') }}</a>
                            @else
                                <a href="{{ route('apps.app.enable', $module->slug) }}" class="btn btn-block btn-success">{{ trans('modules.button.enable') }}</a>
                            @endif
                        @endcan
                    @else
                        @can('create-modules-item')
                            @if ($module->install)
                                <button type="button" @click="onInstall('{{ $module->action_url }}', '{{ $module->slug }}', '{{ $module->name }}', '{{ $module->version }}')" class="btn btn-success btn-block" id="install-module">
                                    {{ trans('modules.install') }}
                                </button>
                            @else
                                <a href="{{ $module->action_url }}" class="btn btn-success btn-block" target="_blank">
                                    {{ trans('modules.buy_now') }}
                                </a>
                            @endif
                        @endcan
                    @endif

                    @if (!empty($module->purchase_desc))
                        <div class="text-center mt-3">
                            {!! $module->purchase_desc !!}
                        </div>
                    @endif
                </div>
            </div>

            <h3>{{ trans('modules.about') }}</h3>

            <div class="card">
                <table class="table">
                    <tbody>
                        @if ($module->vendor_name)
                            <tr class="row">
                                <th class="col-5">{{ trans_choice('general.developers', 1) }}</th>
                                <td class="col-7 text-right"><a href="{{ route('apps.vendors.show', $module->vendor->slug) }}">{{ $module->vendor_name }}</a></td>
                            </tr>
                        @endif
                        @if ($module->version)
                            <tr class="row">
                                <th class="col-5">{{ trans('footer.version') }}</th>
                                <td class="col-7 text-right">{{ $module->version }}</td>
                            </tr>
                        @endif
                        @if ($module->created_at)
                            <tr class="row">
                                <th class="col-5">{{ trans('modules.added') }}</th>
                                <td class="col-7 text-right long-texts">@date($module->created_at)</td>
                            </tr>
                        @endif
                        @if ($module->updated_at)
                            <tr class="row">
                                <th class="col-5">{{ trans('modules.updated') }}</th>
                                <td class="col-7 text-right">{{ Date::parse($module->updated_at)->diffForHumans() }}</td>
                            </tr>
                        @endif
                        @if ($module->categories)
                            <tr class="row">
                                <th class="col-5">{{ trans_choice('general.categories', (count($module->categories) > 1) ? 2 : 1) }}</th>
                                <td class="col-7 text-right">
                                    @foreach ($module->categories as $module_category)
                                        <a href="{{ route('apps.categories.show', $module_category->slug) }}">{{ $module_category->name }}</a> </br>
                                    @endforeach
                                </td>
                            </tr>
                        @endif
                        <tr class="row">
                            <th class="col-5">{{ trans('modules.documentation') }}</th>
                            @if ($module->documentation)
                                <td class="col-7 text-right">
                                    <a href="{{ route('apps.docs.show', $module->slug) }}">{{ trans('modules.view') }}</a>
                                </td>
                            @else
                               <th class="col-7 text-right">{{ trans('general.na') }}</th>
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if ($module->purchase_faq)
        <akaunting-modal :show="faq" modal-dialog-class="modal-md">
            <template #modal-content>
                {!! $module->purchase_faq !!}
            </template>
        </akaunting-modal>
    @endif

    @if ($module->install)
        <akaunting-modal :show="installation.show"
        title="{{ trans('modules.installation.header') }}"
        @cancel="installation.show = false">
            <template #modal-body>
                <div class="modal-body">
                    <el-progress :text-inside="true" :stroke-width="24" :percentage="installation.total" :status="installation.status"></el-progress>

                    <div id="progress-text" class="mt-3" v-html="installation.html"></div>
                </div>
            </template>
            <template #card-footer>
                <span></span>
            </template>
        </akaunting-modal>
    @endif
@endsection

@push('scripts_start')
    <script type="text/javascript">
        var app_slug = "{{ $module->slug }}";
    </script>

    <script src="{{ asset('public/js/modules/item.js?v=' . version('short')) }}"></script>
@endpush
