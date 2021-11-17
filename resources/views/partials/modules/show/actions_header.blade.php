@if ($module->price != '0.0000')
    <span style="position: absolute; right: 120px; top: -15px;">
        <img src="{{ asset('public/img/modules/save30arrow3.png') }}" alt="{{ trans('modules.yearly_pricing') }}"/>
    </span>
@endif

@if ($module->price != '0.0000')
    <div id="app-pricing" class="nav-wrapper pt-0">
        <ul class="nav nav-pills nav-fill flex-column flex-md-row" role="tablist">
            <li class="nav-item">
                <a class="nav-link mb-sm-2 mb-md-0 active" id="tab-pricing-monthly" href="#monthly" data-toggle="tab" aria-selected="false">
                    {{ trans('modules.monthly') }}
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link mb-sm-2 mb-md-0" href="#yearly" id="tab-pricing-yearly" data-toggle="tab" aria-selected="false">
                    {{ trans('modules.yearly') }}
                </a>
            </li>
        </ul>
    </div>
@endif
