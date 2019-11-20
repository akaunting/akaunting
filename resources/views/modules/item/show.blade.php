@extends('layouts.modules')

@section('title', trans_choice('general.modules', 2))

@section('new_button')
    <span class="new-button">
        <a href="{{ route('apps.api-key.create') }}" class="btn btn-white btn-sm header-button-top">
            <span class="fa fa-key"></span> &nbsp;{{ trans('modules.api_key') }}
        </a>
    </span>
    <span class="new-button">
        <a href="{{ route('apps.my.index')  }}" class="btn btn-white btn-sm header-button-bottom">
            <span class="fa fa-user"></span> &nbsp;{{ trans('modules.my_apps') }}
        </a>
    </span>
@endsection

@section('content')
    @include('partials.modules.bar')

    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6">
                    <div class="float-left">
                        <h3>{{ $module->name }}</h3>
                    </div>
                </div>

                <div class="col-md-6">
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
                               <div class="carousel-inner">
                                   <div class="carousel-item" data-ride="carousel">
                                        @if($module->video)
                                            @php
                                                if (strpos($module->video->link, '=') !== false) {
                                                    $code = explode('=', $module->video->link);
                                                    $code[1]= str_replace('&list', '', $code[1]);

                                                    if (empty($status)) {
                                                        $status = 5;
                                                    } else {
                                                        $status = 1;
                                                    }
                                            @endphp
                                            <div class="carousel-item @if($status == 5) {{ 'active' }} @endif">
                                                <iframe width="100%" height="410px" src="https://www.youtube-nocookie.com/embed/{{ $code[1] }}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                <div class="image-description text-center">
                                                    {{ $module->name }}
                                                </div>
                                            </div>
                                            @php } @endphp
                                        @endif

                                       @foreach($module->screenshots as $screenshot)
                                           @php if (empty($status)) { $status = 5; } else { $status = 1; } @endphp
                                           <div class="carousel-item @if($status == 5) {{ 'active' }} @endif">
                                               <a href="{{ $screenshot->path_string }}" data-toggle="lightbox" data-gallery="{{ $module->slug}}">
                                                   <img class="d-block w-100" src="{{ $screenshot->path_string }}" alt="{{ $screenshot->alt_attribute }}">
                                               </a>

                                               <div class="image-description text-center">
                                                   {{ $screenshot->description }}
                                               </div>
                                           </div>
                                       @endforeach
                                   </div>
                                </div>
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
                                {!! $module->changelog !!}
                            </div>
                         @endif

                         <div class="tab-pane fade" id="review">
                            <div id="reviews" class="clearfix" v-html="reviews">
                                @if(!$module->reviews)
                                    <div class="text-center">
                                        <strong>
                                            {{ trans('modules.reviews.na') }}
                                        </strong>
                                    </div>
                                @endif
                            </div>

                            <div class="card-footer pb-0 px-0 mt-4">
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
                                        <del>{{ $module->price }}</del>
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
                        @permission('delete-modules-item')
                            <a href="{{ url('apps/' . $module->slug . '/uninstall') }}" class="btn btn-block btn-danger">{{ trans('modules.button.uninstall') }}</a>
                        @endpermission

                        @permission('update-modules-item')
                            @if ($enable)
                                <a href="{{ url('apps/' . $module->slug . '/disable') }}" class="btn btn-block btn-warning">{{ trans('modules.button.disable') }}</a>
                            @else
                                <a href="{{ url('apps/' . $module->slug . '/enable') }}" class="btn btn-block btn-success">{{ trans('modules.button.enable') }}</a>
                            @endif
                        @endpermission
                    @else
                        @permission('create-modules-item')
                            @if ($module->install)
                                <button type="button" @click="onInstall('{{ $module->action_url }}', '{{ $module->name }}', '{{ $module->version }}')" class="btn btn-success btn-block" id="install-module">
                                    {{ trans('modules.install') }}
                                </button>
                            @else
                                <a href="{{ $module->action_url }}" class="btn btn-success btn-block" target="_blank">
                                    {{ trans('modules.buy_now') }}
                                </a>
                            @endif
                        @endpermission
                    @endif  

                    @if ($module->purchase_faq)
                         <div class="text-center mt-3">
                             <a href="#" @click="onShowFaq" id="button-purchase-faq">{{ trans('modules.tab.faq')}}</a>
                         </div>
                    @endif
                </div>
            </div>

            <h3>{{ trans('modules.about') }}</h3>

            <div class="card">
                <table class="table table-fixed">
                    <tbody>
                        @if ($module->vendor_name)
                            <tr>
                                <th>{{ trans_choice('general.developers', 1) }}</th>
                                <td class="text-right"><a href="{{ url('apps/vendors/' . $module->vendor->slug) }}">{{ $module->vendor_name }}</a></td>
                            </tr>
                        @endif
                        @if ($module->version)
                            <tr>
                                <th>{{ trans('footer.version') }}</th>
                                <td class="text-right">{{ $module->version }}</td>
                            </tr>
                        @endif
                        @if ($module->created_at)
                            <tr>
                                <th>{{ trans('modules.added') }}</th>
                                <td class="text-right long-module-detail">@date($module->created_at)</td>
                            </tr>
                        @endif
                        @if ($module->updated_at)
                            <tr>
                                <th>{{ trans('modules.updated') }}</th>
                                <td class="text-right">{{ Date::parse($module->updated_at)->diffForHumans() }}</td>
                            </tr>
                        @endif
                        @if ($module->compatibility)
                            <tr>
                                <th>{{ trans('modules.compatibility') }}</th>
                                <td class="text-right">{{ $module->compatibility }}</td>
                            </tr>
                        @endif
                        @if ($module->category)
                            <tr>
                                <th>{{ trans_choice('general.categories', 1) }}</th>
                                <td class="text-right"><a href="{{ url('apps/categories/' . $module->category->slug) }}">{{ $module->category->name }}</a></td>
                            </tr>
                        @endif
                        <tr>
                            <th>{{ trans('modules.documentation') }}</th>
                            @if ($module->documentation)
                                <td class="text-right">
                                    <a class="font-weight-bold" href="{{ url('apps/docs/' . $module->slug) }}">{{ trans('modules.view') }}</a>
                                </td>
                            @else
                               <th class="text-right">{{ trans('general.na') }}</th>
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if ($module->purchase_faq)
        <akaunting-modal :show="faq">
            <template #modal-content>
                {!! $module->purchase_faq !!}
            </template>
        </akaunting-modal>
    @endif

    @if ($module->install)
        <akaunting-modal :show="installation.show"
        :title="'{{ trans('modules.installation.header') }}'"
        @cancel="installation.show = false">
            <template #modal-body>
                <div class="modal-body">
                    <el-progress :text-inside="true" :stroke-width="24" :percentage="installation.total" :status="installation.status"></el-progress>

                    <div id="progress-text" v-html="installation.html"></div>
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
