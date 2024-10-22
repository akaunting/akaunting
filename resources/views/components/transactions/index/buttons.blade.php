@if ($checkPermissionCreate)
    @can($permissionCreate)
        @if (! $hideIncomeCreate)
            <x-link href="{{ route($routeIncomeCreate[0], $routeIncomeCreate[1]) }}" kind="primary" id="index-more-actions-new-income-transaction">
                {{ trans('general.title.new', ['type' => trans_choice($textIncomeCreate, 1)]) }}
            </x-link>
        @endif

        @if (! $hideExpenseCreate)
            <x-link href="{{ route($routeExpenseCreate[0], $routeExpenseCreate[1]) }}" kind="primary" id="index-more-actions-new-expense-transaction">
                {{ trans('general.title.new', ['type' => trans_choice($textExpenseCreate, 1)]) }}
            </x-link>
        @endif
    @endcan
@else
    @if (! $hideIncomeCreate)
        <x-link href="{{ route($routeIncomeCreate[0], $routeIncomeCreate[1]) }}" kind="primary" id="index-more-actions-new-income-transaction">
            {{ trans('general.title.new', ['type' => trans_choice($textIncomeCreate, 1)]) }}
        </x-link>
    @endif

    @if (! $hideExpenseCreate)
        <x-link href="{{ route($routeExpenseCreate[0], $routeExpenseCreate[1]) }}" kind="primary" id="index-more-actions-new-expense-transaction">
            {{ trans('general.title.new', ['type' => trans_choice($textExpenseCreate, 1)]) }}
        </x-link>
    @endif
@endif
